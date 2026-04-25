<?php

namespace Database\Seeders;

use App\Models\PartnerLayer;
use Illuminate\Database\Seeder;

class PartnerLayerSeeder extends Seeder
{
    public function run(): void
    {
        $layers = [
            [
                'sort_order'  => 1,
                'code'        => 'clinic_ipo',
                'name'        => 'Клиники / International Patient Office (IPO)',
                'description' => 'Принимают пациента, ставят диагноз, оказывают лечение. '
                    . 'Предпочтительный контакт — международный отдел (IPO) клиники.',
            ],
            [
                'sort_order'  => 2,
                'code'        => 'translator',
                'name'        => 'Переводчики с медицинской специализацией',
                'description' => 'Письменный перевод медицинских документов; устный перевод '
                    . 'на онлайн-консультациях. SLA: 24–72 ч, понятное ценообразование.',
            ],
            [
                'sort_order'  => 3,
                'code'        => 'curator',
                'name'        => 'Медицинский редактор / куратор кейса',
                'description' => 'Врач-консультант, который помогает структурировать вопросы '
                    . 'и проверить правильность сборки кейса (без постановки диагноза пациенту напрямую).',
            ],
        ];

        foreach ($layers as $data) {
            PartnerLayer::firstOrCreate(['code' => $data['code']], $data);
        }
    }
}
