<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Idempotent upsert of verification_checklists and verification_checklist_items.
 *
 * Uses INSERT IGNORE keyed on:
 *   - verification_checklists: `code` (unique)
 *   - verification_checklist_items: `(checklist_id, code)` composite unique
 *
 * Existing rows are never overwritten, so user-edited text is preserved.
 */
return new class extends Migration
{
    public function up(): void
    {
        $now = now();

        $checklists = [
            [
                'code'         => 'clinic_verification',
                'name'         => 'Верификация клиники / IPO',
                'partner_type' => 'clinic',
                'description'  => 'Чек-лист для проверки клиники или International Patient Office '
                    . 'перед началом сотрудничества.',
                'sort_order'   => 1,
                'items'        => [
                    ['code' => 'license_checked',    'sort_order' => 1,
                     'text' => 'Лицензия учреждения проверена на сайте регулятора страны (или реестр аккредитации JCI/ISO).'],
                    ['code' => 'ipo_is_staff',        'sort_order' => 2,
                     'text' => 'IPO-контакт является сотрудником клиники (не посредником).'],
                    ['code' => 'official_invoices',   'sort_order' => 3,
                     'text' => 'Счета выставляются официально от юрлица клиники (не наличными и не «на карту»).'],
                    ['code' => 'test_case_passed',    'sort_order' => 4,
                     'text' => 'Пройден тестовый кейс: ответ получен в согласованный срок, заключение содержательное.'],
                    ['code' => 'sla_agreed',          'sort_order' => 5,
                     'text' => 'Согласован SLA: первичный ответ ≤ 48 ч, заключение ≤ 10 рабочих дней.'],
                    ['code' => 'coordinator_terms',   'sort_order' => 6,
                     'text' => 'Получены условия работы с координаторами (комиссионная политика или её отсутствие).'],
                ],
            ],
            [
                'code'         => 'translator_verification',
                'name'         => 'Верификация переводчика',
                'partner_type' => 'translator',
                'description'  => 'Чек-лист для проверки переводчика с медицинской специализацией.',
                'sort_order'   => 2,
                'items'        => [
                    ['code' => 'medical_samples',   'sort_order' => 1,
                     'text' => 'Есть примеры медицинских переводов (аналогичная ниша).'],
                    ['code' => 'test_translation',  'sort_order' => 2,
                     'text' => 'Тестовый фрагмент (1–2 страницы) переведён без грубых ошибок в терминологии.'],
                    ['code' => 'sla_in_writing',    'sort_order' => 3,
                     'text' => 'SLA зафиксирован письменно (мессенджер/email).'],
                    ['code' => 'nda_signed',        'sort_order' => 4,
                     'text' => 'Подписано или согласовано NDA (или согласие на конфиденциальность в простой форме).'],
                    ['code' => 'pricing_formula',   'sort_order' => 5,
                     'text' => 'Стоимость зафиксирована с формулой (страница/символ/час).'],
                ],
            ],
            [
                'code'         => 'curator_verification',
                'name'         => 'Верификация медицинского редактора / куратора',
                'partner_type' => 'curator',
                'description'  => 'Чек-лист для проверки врача-куратора кейса.',
                'sort_order'   => 3,
                'items'        => [
                    ['code' => 'specialization_match',      'sort_order' => 1,
                     'text' => 'Специализация соответствует нише.'],
                    ['code' => 'no_conflict_of_interest',   'sort_order' => 2,
                     'text' => 'Нет конфликта интересов (не аффилирован с клиниками из вашей сети без раскрытия).'],
                    ['code' => 'role_understood',           'sort_order' => 3,
                     'text' => 'Чётко понимает свою роль: структурирование, а не постановка диагноза пациенту напрямую.'],
                    ['code' => 'sla_agreed',                'sort_order' => 4,
                     'text' => 'Готов работать в согласованном SLA (≤ 24–48 ч на review кейса).'],
                    ['code' => 'nda_signed',                'sort_order' => 5,
                     'text' => 'Подписано NDA.'],
                ],
            ],
        ];

        foreach ($checklists as $clData) {
            $items = $clData['items'];
            unset($clData['items']);

            DB::table('verification_checklists')->insertOrIgnore(array_merge($clData, [
                'created_at' => $now,
                'updated_at' => $now,
            ]));

            $checklist = DB::table('verification_checklists')
                ->where('code', $clData['code'])
                ->first();

            if (! $checklist) {
                continue;
            }

            foreach ($items as $item) {
                DB::table('verification_checklist_items')->insertOrIgnore(array_merge($item, [
                    'checklist_id' => $checklist->id,
                    'created_at'   => $now,
                    'updated_at'   => $now,
                ]));
            }
        }
    }

    /**
     * Rollback is intentionally a no-op: removing checklists would cascade-delete
     * partner verification records and their items.
     */
    public function down(): void {}
};
