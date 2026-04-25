<?php

namespace Database\Seeders;

use App\Models\Country;
use App\Models\CountryDirection;
use Illuminate\Database\Seeder;

class CountryDirectionSeeder extends Seeder
{
    public function run(): void
    {
        $directions = [
            'TR' => [
                'title'           => 'Турция — общее направление',
                'what_to_look_for' => 'Онкология, ортопедия, кардиология. '
                    . 'Много клиник с русскоязычными IPO. '
                    . 'Конкурентный чек. Приоритетная страна для старта. '
                    . 'Предпочтительно начинать не с топ-клиник, а с нормальных сильных центров, '
                    . 'где реально отвечают быстро.',
                'search_queries'  => "international patient coordinator + clinic name\n"
                    . 'JCI accredited hospitals Turkey oncology',
                'notes'           => 'Приоритет для старта: быстрый ответ IPO, опыт работы с '
                    . 'русскоязычными пациентами, понятное ценообразование.',
                'sort_order'      => 1,
            ],
            'IL' => [
                'title'           => 'Израиль — общее направление',
                'what_to_look_for' => 'Онкология (топ-уровень), генетика, репродуктология. '
                    . 'Дорого, но высокое качество. Sheba, Hadassah, Ichilov — ключевые клиники.',
                'search_queries'  => "Sheba / Hadassah / Ichilov international patients\n"
                    . 'Israel oncology second opinion coordinator',
                'notes'           => 'Приоритет для старта: опыт работы с русскоязычными пациентами.',
                'sort_order'      => 2,
            ],
            'DE' => [
                'title'           => 'Германия — общее направление',
                'what_to_look_for' => 'Нейрохирургия, онкология, кардиология. '
                    . 'Высокая стоимость, строгая документация. '
                    . 'Charité, Heidelberg, MRI München — ведущие клиники.',
                'search_queries'  => "Charité / Heidelberg / MRI München Patientenmanagement\n"
                    . 'Germany hospital international office second opinion',
                'notes'           => 'Языковой барьер меньше, чем во Франции. '
                    . 'Строгие требования к оформлению документов.',
                'sort_order'      => 3,
            ],
            'ES' => [
                'title'           => 'Испания — общее направление',
                'what_to_look_for' => 'Онкология, ЭКО (лидер репродуктологии), ортопедия. '
                    . 'IVI Valencia/Barcelona — лидеры репродуктологии.',
                'search_queries'  => "IVI Valencia / Barcelona international patients\n"
                    . 'Spain IVF clinic coordinator',
                'notes'           => null,
                'sort_order'      => 4,
            ],
            'AE' => [
                'title'           => 'ОАЭ — общее направление',
                'what_to_look_for' => 'Кардиология, ортопедия, косметика. '
                    . 'Хаб для пациентов из СНГ/Ближнего Востока. '
                    . 'Cleveland Clinic Abu Dhabi — ключевая клиника.',
                'search_queries'  => "Cleveland Clinic Abu Dhabi international\n"
                    . 'Dubai hospital international patient office',
                'notes'           => null,
                'sort_order'      => 5,
            ],
            'FR' => [
                'title'           => 'Франция — общее направление',
                'what_to_look_for' => 'Онкология, нейрохирургия. '
                    . 'Языковой барьер сильнее, нужен франкоязычный куратор. '
                    . 'IGR Gustave Roussy, AP-HP — ведущие клиники.',
                'search_queries'  => "IGR Gustave Roussy international patients\n"
                    . 'AP-HP international office second opinion',
                'notes'           => 'Необходим переводчик с фр. языком.',
                'sort_order'      => 6,
            ],
            'KR' => [
                'title'           => 'Южная Корея — общее направление',
                'what_to_look_for' => 'Онкология (особенно желудок/щитовидка), '
                    . 'репродуктология, ортопедия. '
                    . 'Samsung Medical Center, Asan Medical Center.',
                'search_queries'  => "Samsung Medical Center international\n"
                    . 'Asan Medical Center Korea second opinion',
                'notes'           => null,
                'sort_order'      => 7,
            ],
            'IT' => [
                'title'           => 'Италия — общее направление',
                'what_to_look_for' => 'Онкология, ортопедия. '
                    . 'Несколько центров с сильными международными отделами. '
                    . 'IEO Milano, Humanitas.',
                'search_queries'  => "IEO Milano international patients\n"
                    . 'Humanitas international office',
                'notes'           => null,
                'sort_order'      => 8,
            ],
        ];

        foreach ($directions as $iso2 => $data) {
            $country = Country::where('iso2', $iso2)->first();
            if (! $country) {
                continue;
            }

            CountryDirection::firstOrCreate(
                [
                    'country_id' => $country->id,
                    'niche_id'   => null,
                    'title'      => $data['title'],
                ],
                array_merge($data, ['country_id' => $country->id, 'niche_id' => null])
            );
        }
    }
}
