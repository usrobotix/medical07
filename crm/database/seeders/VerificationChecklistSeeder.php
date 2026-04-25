<?php

namespace Database\Seeders;

use App\Models\VerificationChecklist;
use App\Models\VerificationChecklistItem;
use Illuminate\Database\Seeder;

class VerificationChecklistSeeder extends Seeder
{
    public function run(): void
    {
        $checklists = [
            [
                'code'         => 'clinic_verification',
                'name'         => 'Верификация клиники / IPO',
                'partner_type' => 'clinic',
                'description'  => 'Чек-лист для проверки клиники или International Patient Office '
                    . 'перед началом сотрудничества.',
                'sort_order'   => 1,
                'items'        => [
                    [
                        'code'       => 'license_checked',
                        'text'       => 'Лицензия учреждения проверена на сайте регулятора страны '
                            . '(или реестр аккредитации JCI/ISO).',
                        'sort_order' => 1,
                    ],
                    [
                        'code'       => 'ipo_is_staff',
                        'text'       => 'IPO-контакт является сотрудником клиники (не посредником).',
                        'sort_order' => 2,
                    ],
                    [
                        'code'       => 'official_invoices',
                        'text'       => 'Счета выставляются официально от юрлица клиники '
                            . '(не наличными и не «на карту»).',
                        'sort_order' => 3,
                    ],
                    [
                        'code'       => 'test_case_passed',
                        'text'       => 'Пройден тестовый кейс: ответ получен в согласованный срок, '
                            . 'заключение содержательное.',
                        'sort_order' => 4,
                    ],
                    [
                        'code'       => 'sla_agreed',
                        'text'       => 'Согласован SLA: первичный ответ ≤ 48 ч, '
                            . 'заключение ≤ 10 рабочих дней.',
                        'sort_order' => 5,
                    ],
                    [
                        'code'       => 'coordinator_terms',
                        'text'       => 'Получены условия работы с координаторами '
                            . '(комиссионная политика или её отсутствие).',
                        'sort_order' => 6,
                    ],
                ],
            ],
            [
                'code'         => 'translator_verification',
                'name'         => 'Верификация переводчика',
                'partner_type' => 'translator',
                'description'  => 'Чек-лист для проверки переводчика с медицинской специализацией.',
                'sort_order'   => 2,
                'items'        => [
                    [
                        'code'       => 'medical_samples',
                        'text'       => 'Есть примеры медицинских переводов (аналогичная ниша).',
                        'sort_order' => 1,
                    ],
                    [
                        'code'       => 'test_translation',
                        'text'       => 'Тестовый фрагмент (1–2 страницы) переведён без грубых '
                            . 'ошибок в терминологии.',
                        'sort_order' => 2,
                    ],
                    [
                        'code'       => 'sla_in_writing',
                        'text'       => 'SLA зафиксирован письменно (мессенджер/email).',
                        'sort_order' => 3,
                    ],
                    [
                        'code'       => 'nda_signed',
                        'text'       => 'Подписано или согласовано NDA '
                            . '(или согласие на конфиденциальность в простой форме).',
                        'sort_order' => 4,
                    ],
                    [
                        'code'       => 'pricing_formula',
                        'text'       => 'Стоимость зафиксирована с формулой (страница/символ/час).',
                        'sort_order' => 5,
                    ],
                ],
            ],
            [
                'code'         => 'curator_verification',
                'name'         => 'Верификация медицинского редактора / куратора',
                'partner_type' => 'curator',
                'description'  => 'Чек-лист для проверки врача-куратора кейса.',
                'sort_order'   => 3,
                'items'        => [
                    [
                        'code'       => 'specialization_match',
                        'text'       => 'Специализация соответствует нише.',
                        'sort_order' => 1,
                    ],
                    [
                        'code'       => 'no_conflict_of_interest',
                        'text'       => 'Нет конфликта интересов (не аффилирован с клиниками '
                            . 'из вашей сети без раскрытия).',
                        'sort_order' => 2,
                    ],
                    [
                        'code'       => 'role_understood',
                        'text'       => 'Чётко понимает свою роль: структурирование, '
                            . 'а не постановка диагноза пациенту напрямую.',
                        'sort_order' => 3,
                    ],
                    [
                        'code'       => 'sla_agreed',
                        'text'       => 'Готов работать в согласованном SLA (≤ 24–48 ч на review кейса).',
                        'sort_order' => 4,
                    ],
                    [
                        'code'       => 'nda_signed',
                        'text'       => 'Подписано NDA.',
                        'sort_order' => 5,
                    ],
                ],
            ],
        ];

        foreach ($checklists as $checklistData) {
            $items = $checklistData['items'];
            unset($checklistData['items']);

            $checklist = VerificationChecklist::firstOrCreate(
                ['code' => $checklistData['code']],
                $checklistData
            );

            foreach ($items as $itemData) {
                VerificationChecklistItem::firstOrCreate(
                    ['checklist_id' => $checklist->id, 'code' => $itemData['code']],
                    array_merge($itemData, ['checklist_id' => $checklist->id])
                );
            }
        }
    }
}
