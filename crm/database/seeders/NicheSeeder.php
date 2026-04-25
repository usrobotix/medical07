<?php

namespace Database\Seeders;

use App\Models\Niche;
use Illuminate\Database\Seeder;

class NicheSeeder extends Seeder
{
    public function run(): void
    {
        $niches = [
            [
                'sort_order'  => 1,
                'code'        => 'oncology',
                'name'        => 'Онкология',
                'description' => 'Второе мнение по онкологии, пересмотр гистологии/стадирования. '
                    . 'Высокий средний чек, требует аккуратных процессов и сильных партнёров.',
            ],
            [
                'sort_order'  => 2,
                'code'        => 'neurosurgery_orthopedics',
                'name'        => 'Нейрохирургия / Ортопедия',
                'description' => 'Второе мнение по плановым операциям на позвоночнике, суставах, '
                    . 'нейрохирургическим вмешательствам.',
            ],
            [
                'sort_order'  => 3,
                'code'        => 'cardiology',
                'name'        => 'Кардиология',
                'description' => 'Второе мнение по выбору тактики лечения, интервенционные '
                    . 'вмешательства, оценка рисков.',
            ],
            [
                'sort_order'  => 4,
                'code'        => 'rare_genetics',
                'name'        => 'Редкие диагнозы / Генетика',
                'description' => 'Генетическое тестирование, орфанные заболевания, сложные '
                    . 'дифференциальные диагнозы.',
            ],
            [
                'sort_order'  => 5,
                'code'        => 'reproductive_ivf',
                'name'        => 'Репродуктология / ЭКО',
                'description' => 'Второе мнение по стратегии ЭКО, выбор клиники, '
                    . 'оценка протокола лечения.',
            ],
        ];

        foreach ($niches as $data) {
            Niche::firstOrCreate(['code' => $data['code']], $data);
        }
    }
}
