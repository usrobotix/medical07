<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PartnerKnowledgeBaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            PartnerLayerSeeder::class,
            NicheSeeder::class,
            CountrySeeder::class,
            CountryDirectionSeeder::class,
            VerificationChecklistSeeder::class,
            MessageTemplateSeeder::class,
            DemoPartnerSeeder::class,
        ]);
    }
}
