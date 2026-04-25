<?php

namespace Database\Seeders;

use App\Models\Country;
use Illuminate\Database\Seeder;

class CountrySeeder extends Seeder
{
    public function run(): void
    {
        $countries = [
            [
                'sort_order' => 1,
                'iso2'       => 'TR',
                'name_ru'    => 'Турция',
                'name_en'    => 'Turkey',
            ],
            [
                'sort_order' => 2,
                'iso2'       => 'IL',
                'name_ru'    => 'Израиль',
                'name_en'    => 'Israel',
            ],
            [
                'sort_order' => 3,
                'iso2'       => 'DE',
                'name_ru'    => 'Германия',
                'name_en'    => 'Germany',
            ],
            [
                'sort_order' => 4,
                'iso2'       => 'ES',
                'name_ru'    => 'Испания',
                'name_en'    => 'Spain',
            ],
            [
                'sort_order' => 5,
                'iso2'       => 'AE',
                'name_ru'    => 'ОАЭ',
                'name_en'    => 'United Arab Emirates',
            ],
            [
                'sort_order' => 6,
                'iso2'       => 'FR',
                'name_ru'    => 'Франция',
                'name_en'    => 'France',
            ],
            [
                'sort_order' => 7,
                'iso2'       => 'KR',
                'name_ru'    => 'Южная Корея',
                'name_en'    => 'South Korea',
            ],
            [
                'sort_order' => 8,
                'iso2'       => 'IT',
                'name_ru'    => 'Италия',
                'name_en'    => 'Italy',
            ],
        ];

        foreach ($countries as $data) {
            Country::firstOrCreate(['iso2' => $data['iso2']], $data);
        }
    }
}
