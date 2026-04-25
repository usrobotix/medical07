<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class ClinicResearchSeeder extends Seeder
{
    /**
     * Import clinic research data from the Research/ YAML files
     * into partners and partner_research_profiles tables.
     *
     * Uses the research:import-partners Artisan command so that
     * all parsing/mapping logic lives in one place.
     */
    public function run(): void
    {
        $this->command->info('Importing clinic research data…');

        $exitCode = Artisan::call('research:import-partners', [], $this->command->getOutput());

        if ($exitCode !== 0) {
            $this->command->warn(
                'research:import-partners finished with non-zero exit code. ' .
                'Check output above for details.'
            );
        }
    }
}
