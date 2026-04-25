<?php

namespace App\Console\Commands;

use App\Models\Country;
use App\Models\Niche;
use App\Models\Partner;
use App\Models\PartnerLayer;
use App\Models\PartnerResearchProfile;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ImportResearchPartners extends Command
{
    protected $signature = 'research:import-partners
                            {--path= : Override the source path (default: config research.source_path)}
                            {--dry-run : Parse files and report without writing to the database}';

    protected $description = 'Import clinic research data from Research/ YAML files into CRM partners';

    /**
     * Direction name (Russian) → niche code mapping.
     */
    private const DIRECTION_TO_NICHE = [
        'Онкология'                  => 'oncology',
        'Нейрохирургия / Ортопедия'  => 'neurosurgery_orthopedics',
        'Нейрохирургия'              => 'neurosurgery_orthopedics',
        'Ортопедия'                  => 'neurosurgery_orthopedics',
        'Кардиология'                => 'cardiology',
        'Редкие диагнозы / Генетика' => 'rare_genetics',
        'Редкие диагнозы'            => 'rare_genetics',
        'Генетика'                   => 'rare_genetics',
        'Репродуктология / ЭКО'      => 'reproductive_ivf',
        'Репродуктология'            => 'reproductive_ivf',
        'ЭКО'                        => 'reproductive_ivf',
    ];

    /**
     * Country name_ru → iso2 mapping for countries missing from the DB.
     */
    private const COUNTRY_ISO2 = [
        'Германия'    => 'DE',
        'Испания'     => 'ES',
        'Италия'      => 'IT',
        'Франция'     => 'FR',
        'ОАЭ'         => 'AE',
        'Турция'      => 'TR',
        'Южная Корея' => 'KR',
    ];

    public function handle(): int
    {
        $sourcePath = $this->option('path') ?? config('research.source_path');
        $dryRun     = (bool) $this->option('dry-run');

        if (! is_dir($sourcePath)) {
            $this->error("Source path not found: {$sourcePath}");
            $this->line('Run: mkdir -p ' . $sourcePath . ' and copy Research/ folder there.');
            return Command::FAILURE;
        }

        // Find the Research/ subdirectory if source_path points to a parent
        $researchRoot = is_dir($sourcePath . '/Research') ? $sourcePath . '/Research' : $sourcePath;

        $this->info("Scanning: {$researchRoot}");
        if ($dryRun) {
            $this->warn('[DRY-RUN] No changes will be written to the database.');
        }

        $files = $this->findClinicYamlFiles($researchRoot);

        if (empty($files)) {
            $this->warn('No clinic.yaml files found. Check that Research/ folder is populated.');
            return Command::SUCCESS;
        }

        $this->info('Found ' . count($files) . ' clinic.yaml files.');

        // Ensure a default partner layer exists for imported clinics
        $defaultLayer = PartnerLayer::firstOrCreate(
            ['code' => 'imported'],
            ['name' => 'Импорт (Research)', 'sort_order' => 99]
        );

        $stats = ['processed' => 0, 'created' => 0, 'updated' => 0, 'errors' => 0];

        foreach ($files as $yamlPath) {
            try {
                $this->processFile($yamlPath, $researchRoot, $defaultLayer, $dryRun, $stats);
            } catch (\Throwable $e) {
                $stats['errors']++;
                $this->error("  Error processing {$yamlPath}: " . $e->getMessage());
            }
        }

        $this->newLine();
        $this->info(sprintf(
            'Done. Processed: %d | Created: %d | Updated: %d | Errors: %d',
            $stats['processed'],
            $stats['created'],
            $stats['updated'],
            $stats['errors']
        ));

        return $stats['errors'] > 0 ? Command::FAILURE : Command::SUCCESS;
    }

    // -------------------------------------------------------------------------

    private function findClinicYamlFiles(string $root): array
    {
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($root, \FilesystemIterator::SKIP_DOTS)
        );

        $files = [];
        foreach ($iterator as $file) {
            if ($file->isFile() && $file->getFilename() === 'clinic.yaml') {
                $files[] = $file->getPathname();
            }
        }

        sort($files);
        return $files;
    }

    private function processFile(
        string $yamlPath,
        string $researchRoot,
        PartnerLayer $defaultLayer,
        bool $dryRun,
        array &$stats
    ): void {
        $stats['processed']++;

        $rawYaml = file_get_contents($yamlPath);
        $data    = $this->parseYaml($rawYaml);

        if (! is_array($data)) {
            throw new \RuntimeException('YAML parse returned non-array');
        }

        // --- Extract fields from YAML ---
        $clinicName  = $this->str($data['название'] ?? null);
        $countryName = $this->str($data['страна'] ?? null);
        $city        = $this->str($data['город'] ?? null);
        $direction   = $this->str($data['направление'] ?? null);

        if (! $clinicName || ! $countryName || ! $direction) {
            // Try to derive from path: Research/<Country>/<Direction>/<Name — City>/
            $rel   = ltrim(str_replace($researchRoot, '', $yamlPath), '/\\');
            $parts = explode(DIRECTORY_SEPARATOR, $rel);
            if (count($parts) >= 4) {
                $countryName = $countryName ?: $parts[0];
                $direction   = $direction   ?: trim($parts[1]);
                $folderName  = $parts[2]; // "Clinic name — City"
                if (! $clinicName && str_contains($folderName, '—')) {
                    [$clinicName, $city] = array_map('trim', explode('—', $folderName, 2));
                } elseif (! $clinicName) {
                    $clinicName = trim($folderName);
                }
            }
        }

        if (! $clinicName) {
            throw new \RuntimeException('Cannot determine clinic name');
        }

        $contacts = $data['контакты'] ?? [];
        $website  = $this->filterUrl($this->str($contacts['сайт'] ?? null));
        $phone    = $this->filterSpecial($this->str($contacts['телефон'] ?? null));
        $email    = $this->filterSpecial($this->str($contacts['email'] ?? null));
        $intlPage = $this->filterUrl($this->str($contacts['страница_для_иностранных_пациентов'] ?? null));

        $acceptForeigners = $data['приём_иностранцев'] ?? [];
        $acceptRu         = $data['приём_пациентов_из_РФ'] ?? [];
        $workingHoursData = $data['режим_работы'] ?? [];
        $workingHours     = $this->str($workingHoursData['клиника'] ?? null);
        $lastChecked      = $this->str($data['дата_последней_проверки'] ?? null) ?: null;
        $keyServices      = $this->asList($data['ключевые_услуги'] ?? []);
        $doctors          = $data['врачи'] ?? [];
        $prices           = $data['цены'] ?? null;
        $reviews          = $data['отзывы'] ?? [];
        $sources          = $data['источники'] ?? [];

        $sourcePath = ltrim(str_replace($researchRoot, '', $yamlPath), '/\\');

        // Review markdown
        $reviewPath = dirname($yamlPath) . '/review.md';
        $reviewMd   = file_exists($reviewPath) ? file_get_contents($reviewPath) : null;

        if ($dryRun) {
            $this->line("  [dry] {$countryName} / {$direction} / {$clinicName} ({$city})");
            return;
        }

        DB::transaction(function () use (
            $data, $clinicName, $countryName, $city, $direction,
            $website, $phone, $email, $intlPage, $acceptForeigners, $acceptRu,
            $workingHours, $lastChecked, $keyServices, $doctors, $prices, $reviews,
            $sources, $sourcePath, $rawYaml, $reviewMd, $defaultLayer, &$stats
        ) {
            // 1) Resolve country
            $country = $this->resolveCountry($countryName);

            // 2) Resolve niche
            $nicheCode = $this->resolveNicheCode($direction);
            $niche     = $nicheCode ? Niche::where('code', $nicheCode)->first() : null;

            // 3) Upsert partner (unique key: type=clinic + name + country_id)
            $partner = Partner::firstOrCreate(
                [
                    'type'       => 'clinic',
                    'name'       => $clinicName,
                    'country_id' => $country?->id,
                ],
                [
                    'partner_layer_id' => $defaultLayer->id,
                    'city'             => $city ?: null,
                    'website_url'      => $website,
                    'contact_phone'    => $phone,
                    'contact_email'    => $email,
                    'status'           => 'new',
                ]
            );

            if (! $partner->wasRecentlyCreated) {
                $stats['updated']++;
                // Keep existing values, only fill nulls
                if (! $partner->city && $city) {
                    $partner->city = $city;
                }
                if (! $partner->website_url && $website) {
                    $partner->website_url = $website;
                }
                if (! $partner->contact_phone && $phone) {
                    $partner->contact_phone = $phone;
                }
                if (! $partner->contact_email && $email) {
                    $partner->contact_email = $email;
                }
                $partner->save();
            } else {
                $stats['created']++;
            }

            // 4) Attach niche
            if ($niche) {
                $partner->niches()->syncWithoutDetaching([$niche->id]);
            }

            // 5) Upsert research profile
            PartnerResearchProfile::updateOrCreate(
                ['partner_id' => $partner->id],
                [
                    'address'                       => $this->filterSpecial($this->str($data['адрес'] ?? null)),
                    'direction'                     => $direction,
                    'key_services'                  => $keyServices,
                    'international_page_url'        => $intlPage,
                    'accepts_foreigners_status'     => $this->filterSpecial($this->str($acceptForeigners['статус'] ?? null)),
                    'accepts_foreigners_source_url' => $this->filterUrl($this->str($acceptForeigners['источник_url'] ?? null)),
                    'accepts_ru_status'             => $this->filterSpecial($this->str($acceptRu['статус'] ?? null)),
                    'accepts_ru_source_url'         => $this->filterUrl($this->str($acceptRu['источник_url'] ?? null)),
                    'working_hours'                 => $this->filterSpecial($workingHours),
                    'doctors'                       => $doctors ?: null,
                    'prices'                        => $prices,
                    'reviews'                       => $reviews ?: null,
                    'sources'                       => $sources ?: null,
                    'last_checked_at'               => $lastChecked,
                    'source_path'                   => $sourcePath,
                    'raw_clinic_yaml'               => $rawYaml,
                    'review_markdown'               => $reviewMd,
                    'imported_at'                   => now(),
                ]
            );

            $this->line("  ✓ {$countryName} / {$direction} / {$clinicName}");
        });
    }

    // -------------------------------------------------------------------------

    private function resolveCountry(string $nameRu): ?Country
    {
        if (! $nameRu) {
            return null;
        }

        return Country::firstOrCreate(
            ['name_ru' => $nameRu],
            [
                'iso2'       => self::COUNTRY_ISO2[$nameRu] ?? null,
                'name_en'    => null,
                'sort_order' => 99,
            ]
        );
    }

    private function resolveNicheCode(string $direction): ?string
    {
        $direction = trim($direction);

        if (isset(self::DIRECTION_TO_NICHE[$direction])) {
            return self::DIRECTION_TO_NICHE[$direction];
        }

        // Fuzzy: check if direction starts with a known key
        foreach (self::DIRECTION_TO_NICHE as $key => $code) {
            if (str_starts_with($direction, $key) || str_starts_with($key, $direction)) {
                return $code;
            }
        }

        return null;
    }

    private function parseYaml(string $content): mixed
    {
        // Prefer symfony/yaml if available
        if (class_exists(\Symfony\Component\Yaml\Yaml::class)) {
            return \Symfony\Component\Yaml\Yaml::parse($content);
        }

        // Fall back to PHP native yaml extension
        if (function_exists('yaml_parse')) {
            return yaml_parse($content);
        }

        throw new \RuntimeException('No YAML parser available. Run: composer require symfony/yaml');
    }

    private function str(mixed $value): string
    {
        if ($value === null) {
            return '';
        }
        return trim((string) $value);
    }

    private function filterSpecial(string $value): ?string
    {
        if (in_array($value, ['не опубликовано', 'неизвестно', 'требует уточнения', ''], true)) {
            return null;
        }
        return $value ?: null;
    }

    private function filterUrl(string $value): ?string
    {
        $filtered = $this->filterSpecial($value);
        if (! $filtered) {
            return null;
        }
        // Only store if it looks like a URL
        if (str_starts_with($filtered, 'http://') || str_starts_with($filtered, 'https://')) {
            return $filtered;
        }
        return null;
    }

    private function asList(mixed $value): array
    {
        if (! is_array($value)) {
            return [];
        }
        return array_values(array_filter(array_map('strval', $value)));
    }
}
