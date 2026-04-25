<?php

namespace App\Console\Commands;

use App\Models\Country;
use App\Models\CountryDirection;
use App\Models\Niche;
use App\Models\Partner;
use App\Models\PartnerLayer;
use App\Models\PartnerResearchProfile;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Symfony\Component\Yaml\Yaml;

class ImportClinicResearch extends Command
{
    protected $signature = 'research:import-partners
                            {--source= : Path to research directory (default: storage/app/research)}
                            {--dry-run : Show what would be imported without saving}';

    protected $aliases = ['research:import-clinics'];

    protected $description = 'Import clinic research data from Research/ folder into CRM Partners';

    private const DIRECTION_TO_NICHE = [
        'Онкология'                    => 'oncology',
        'Нейрохирургия / Ортопедия'    => 'neurosurgery_orthopedics',
        'Кардиология'                  => 'cardiology',
        'Редкие диагнозы / Генетика'   => 'rare_genetics',
        'Репродуктология / ЭКО'        => 'reproductive_ivf',
    ];

    private const COUNTRY_ISO = [
        'Германия'      => 'DE',
        'Испания'       => 'ES',
        'Италия'        => 'IT',
        'Франция'       => 'FR',
        'ОАЭ'           => 'AE',
        'Турция'        => 'TR',
        'Южная Корея'   => 'KR',
    ];

    private int $created = 0;
    private int $updated = 0;
    private int $skipped = 0;
    private int $errors  = 0;

    public function handle(): int
    {
        $sourcePath = $this->option('source')
            ?? storage_path('app/research');

        $dryRun = (bool) $this->option('dry-run');

        if (! is_dir($sourcePath)) {
            $this->error("Research directory not found: {$sourcePath}");
            $this->line('Run with --source=/path/to/Research or place data in storage/app/research/');
            return self::FAILURE;
        }

        $this->info("Importing from: {$sourcePath}" . ($dryRun ? ' [DRY RUN]' : ''));

        $layer = $this->ensureClinicLayer();

        $countryDirs = glob($sourcePath . '/*', GLOB_ONLYDIR);

        foreach ($countryDirs as $countryDir) {
            $countryName = basename($countryDir);

            if (! isset(self::COUNTRY_ISO[$countryName])) {
                $this->line("  Skipping unknown country dir: {$countryName}");
                continue;
            }

            $country = $this->ensureCountry($countryName);

            $directionDirs = glob($countryDir . '/*', GLOB_ONLYDIR);

            foreach ($directionDirs as $directionDir) {
                $directionName = trim(basename($directionDir));

                $nicheCode = $this->resolveNicheCode($directionName);
                if ($nicheCode === null) {
                    $this->line("    Skipping unknown direction: {$directionName}");
                    continue;
                }

                $niche = $this->ensureNiche($nicheCode, $directionName);
                $this->ensureCountryDirection($country, $niche, $directionName);

                $clinicDirs = glob($directionDir . '/*', GLOB_ONLYDIR);

                foreach ($clinicDirs as $clinicDir) {
                    $this->importClinic(
                        $clinicDir,
                        $country,
                        $niche,
                        $layer,
                        $sourcePath,
                        $dryRun
                    );
                }
            }
        }

        $this->newLine();
        $this->info('Import complete.');
        $this->table(
            ['Created', 'Updated', 'Skipped', 'Errors'],
            [[$this->created, $this->updated, $this->skipped, $this->errors]]
        );

        return self::SUCCESS;
    }

    private function importClinic(
        string $clinicDir,
        Country $country,
        Niche $niche,
        PartnerLayer $layer,
        string $sourcePath,
        bool $dryRun
    ): void {
        $yamlFile   = $clinicDir . '/clinic.yaml';
        $reviewFile = $clinicDir . '/review.md';

        if (! file_exists($yamlFile)) {
            $this->line("      Missing clinic.yaml in: " . basename($clinicDir));
            $this->skipped++;
            return;
        }

        try {
            $data = Yaml::parseFile($yamlFile);
        } catch (\Throwable $e) {
            $this->error("      YAML parse error in {$yamlFile}: " . $e->getMessage());
            $this->errors++;
            return;
        }

        $name       = trim($data['название'] ?? basename($clinicDir));
        $city       = trim($data['город'] ?? '');
        $direction  = trim($data['направление'] ?? '');
        $slug       = $this->buildSlug($country->name_ru, $direction, $name, $city);
        $sourcePath = str_replace(storage_path('app/research') . '/', '', $clinicDir);

        $reviewMd   = file_exists($reviewFile) ? file_get_contents($reviewFile) : null;

        if ($dryRun) {
            $this->line("      [DRY] Would upsert: {$name} / {$city} [{$slug}]");
            $this->created++;
            return;
        }

        try {
            DB::transaction(function () use (
                $data, $name, $city, $slug, $country, $niche, $layer,
                $sourcePath, $reviewMd, $clinicDir
            ) {
                $contacts = $data['контакты'] ?? [];

                $partnerData = [
                    'partner_layer_id'       => $layer->id,
                    'type'                   => 'clinic',
                    'name'                   => $name,
                    'country_id'             => $country->id,
                    'city'                   => $city ?: null,
                    'address'                => $this->nullIfUnknown($data['адрес'] ?? null),
                    'contact_phone'          => $this->nullIfUnknown($contacts['телефон'] ?? null),
                    'contact_email'          => $this->nullIfUnknown($contacts['email'] ?? null),
                    'website_url'            => $this->nullIfUnknown($contacts['сайт'] ?? null),
                    'international_page_url' => $this->nullIfUnknown(
                        $contacts['страница_для_иностранных_пациентов'] ?? null
                    ),
                    'status'                 => 'new',
                    'research_source_path'   => $sourcePath,
                    'research_imported_at'   => now(),
                    'last_checked_date'      => $this->parseDate($data['дата_последней_проверки'] ?? null),
                    'notes'                  => $this->nullIfUnknown($data['примечания'] ?? null),
                ];

                $existing = Partner::where('research_slug', $slug)->first();

                if ($existing) {
                    $existing->update($partnerData);
                    $partner = $existing;
                    $this->updated++;
                } else {
                    $partnerData['research_slug'] = $slug;
                    $partner = Partner::create($partnerData);
                    $this->created++;
                }

                // Sync niche & country
                $partner->niches()->syncWithoutDetaching([$niche->id]);
                $partner->countries()->syncWithoutDetaching([$country->id]);

                // Upsert research profile
                $profile = $partner->researchProfile ?? new PartnerResearchProfile();
                $profile->partner_id       = $partner->id;
                $profile->key_services     = $this->parseList($data['ключевые_услуги'] ?? []);
                $profile->accepts_foreigners = $this->parseAcceptance($data['приём_иностранцев'] ?? []);
                $profile->accepts_russians = $this->parseAcceptance($data['приём_пациентов_из_РФ'] ?? []);
                $profile->working_hours    = $this->parseWorkingHours($data['режим_работы'] ?? []);
                $profile->doctors          = $data['врачи'] ?? [];
                $profile->prices           = $data['цены'] ?? [];
                $profile->payment_info     = $data['платёжная_информация'] ?? [];
                $profile->reviews          = $data['отзывы'] ?? [];
                $profile->accreditations   = $data['аккредитации_сертификаты'] ?? [];
                $profile->sources          = $this->parseSources($data['источники'] ?? []);
                $profile->review_md        = $reviewMd;
                $profile->save();
            });
        } catch (\Throwable $e) {
            $this->error("      Error importing {$name}: " . $e->getMessage());
            Log::error("research:import-clinics error for {$clinicDir}", ['error' => $e->getMessage()]);
            $this->errors++;
        }
    }

    private function ensureClinicLayer(): PartnerLayer
    {
        return PartnerLayer::firstOrCreate(
            ['code' => 'clinic_research'],
            [
                'name'        => 'Клиники (Research)',
                'description' => 'Клиники, импортированные из Research-датасета',
                'sort_order'  => 10,
            ]
        );
    }

    private function ensureCountry(string $nameRu): Country
    {
        $iso2 = self::COUNTRY_ISO[$nameRu];

        $countryNames = [
            'DE' => ['name_en' => 'Germany',              'sort_order' => 3],
            'ES' => ['name_en' => 'Spain',                'sort_order' => 4],
            'IT' => ['name_en' => 'Italy',                'sort_order' => 8],
            'FR' => ['name_en' => 'France',               'sort_order' => 6],
            'AE' => ['name_en' => 'United Arab Emirates', 'sort_order' => 5],
            'TR' => ['name_en' => 'Turkey',               'sort_order' => 1],
            'KR' => ['name_en' => 'South Korea',          'sort_order' => 7],
        ];

        return Country::firstOrCreate(
            ['iso2' => $iso2],
            array_merge(['iso2' => $iso2, 'name_ru' => $nameRu], $countryNames[$iso2])
        );
    }

    private function ensureNiche(string $code, string $directionName): Niche
    {
        $descriptions = [
            'oncology'                 => 'Онкология — лечение онкологических заболеваний за рубежом.',
            'neurosurgery_orthopedics' => 'Нейрохирургия и ортопедия.',
            'cardiology'               => 'Кардиология.',
            'rare_genetics'            => 'Редкие диагнозы и генетика.',
            'reproductive_ivf'         => 'Репродуктология и ЭКО.',
        ];

        $nicheNames = [
            'oncology'                 => 'Онкология',
            'neurosurgery_orthopedics' => 'Нейрохирургия / Ортопедия',
            'cardiology'               => 'Кардиология',
            'rare_genetics'            => 'Редкие диагнозы / Генетика',
            'reproductive_ivf'         => 'Репродуктология / ЭКО',
        ];

        return Niche::firstOrCreate(
            ['code' => $code],
            [
                'name'        => $nicheNames[$code],
                'description' => $descriptions[$code] ?? '',
                'sort_order'  => array_search($code, array_keys(self::DIRECTION_TO_NICHE)) + 1,
            ]
        );
    }

    private function ensureCountryDirection(Country $country, Niche $niche, string $directionName): void
    {
        CountryDirection::firstOrCreate(
            ['country_id' => $country->id, 'niche_id' => $niche->id],
            [
                'title'      => $directionName . ' — ' . $country->name_ru,
                'sort_order' => 0,
            ]
        );
    }

    private function resolveNicheCode(string $directionName): ?string
    {
        $trimmed = trim($directionName);

        if (isset(self::DIRECTION_TO_NICHE[$trimmed])) {
            return self::DIRECTION_TO_NICHE[$trimmed];
        }

        // Fuzzy match: check if any known direction is a prefix of the folder name
        foreach (self::DIRECTION_TO_NICHE as $direction => $code) {
            if (str_starts_with($trimmed, rtrim($direction, ' /')) ||
                str_starts_with($direction, $trimmed)) {
                return $code;
            }
        }

        return null;
    }

    private function buildSlug(string $country, string $direction, string $name, string $city): string
    {
        $raw = implode('|', [
            mb_strtolower(trim($country)),
            mb_strtolower(trim($direction)),
            mb_strtolower(trim($name)),
            mb_strtolower(trim($city)),
        ]);

        return hash('sha256', $raw);
    }

    private function nullIfUnknown(?string $value): ?string
    {
        if ($value === null) {
            return null;
        }

        $lower = mb_strtolower(trim($value));

        if (in_array($lower, ['не опубликовано', 'неизвестно', 'требует уточнения', ''], true)) {
            return null;
        }

        return $value;
    }

    private function parseDate(?string $value): ?\Illuminate\Support\Carbon
    {
        if (! $value || trim($value) === '') {
            return null;
        }

        try {
            return \Illuminate\Support\Carbon::parse($value);
        } catch (\Throwable) {
            return null;
        }
    }

    private function parseList($value): array
    {
        if (! is_array($value)) {
            return $value ? [$value] : [];
        }

        return array_filter(
            array_map('trim', $value),
            fn($v) => $v !== '' && mb_strtolower($v) !== 'требует уточнения'
        );
    }

    private function parseAcceptance(array $data): array
    {
        return [
            'status'     => $data['статус'] ?? null,
            'source_url' => $this->nullIfUnknown($data['источник_url'] ?? null),
            'note'       => $this->nullIfUnknown($data['примечание'] ?? null),
        ];
    }

    private function parseWorkingHours(array $data): array
    {
        return [
            'clinic'             => $this->nullIfUnknown($data['клиника'] ?? null),
            'international_dept' => $this->nullIfUnknown($data['отдел_международных_пациентов'] ?? null),
            'source_url'         => $this->nullIfUnknown($data['источник_url'] ?? null),
        ];
    }

    private function parseSources($sources): array
    {
        if (! is_array($sources)) {
            return [];
        }

        return array_values(array_filter(
            array_map(function ($s) {
                if (! is_array($s)) {
                    return null;
                }

                return [
                    'url'         => $this->nullIfUnknown($s['url'] ?? null),
                    'description' => $s['описание'] ?? null,
                    'checked_at'  => $s['дата_проверки'] ?? null,
                ];
            }, $sources),
            fn($s) => $s !== null && $s['url'] !== null
        ));
    }
}
