<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Idempotent upsert of partner_layers reference data.
 *
 * Inserts the four canonical layer rows (clinic_ipo, translator, curator, imported)
 * using INSERT IGNORE keyed on the `code` unique column.
 * Pre-existing rows — including any description text edited by users — are left
 * untouched.
 */
return new class extends Migration
{
    public function up(): void
    {
        $now = now();

        $layers = [
            [
                'code'        => 'clinic_ipo',
                'name'        => 'Клиники / International Patient Office (IPO)',
                'description' => 'Принимают пациента, ставят диагноз, оказывают лечение. '
                    . 'Предпочтительный контакт — международный отдел (IPO) клиники.',
                'sort_order'  => 1,
            ],
            [
                'code'        => 'translator',
                'name'        => 'Переводчики с медицинской специализацией',
                'description' => 'Письменный перевод медицинских документов; устный перевод '
                    . 'на онлайн-консультациях. SLA: 24–72 ч, понятное ценообразование.',
                'sort_order'  => 2,
            ],
            [
                'code'        => 'curator',
                'name'        => 'Медицинский редактор / куратор кейса',
                'description' => 'Врач-консультант, который помогает структурировать вопросы '
                    . 'и проверить правильность сборки кейса (без постановки диагноза пациенту напрямую).',
                'sort_order'  => 3,
            ],
            [
                'code'        => 'imported',
                'name'        => 'Импорт (Research)',
                'description' => null,
                'sort_order'  => 99,
            ],
        ];

        foreach ($layers as $layer) {
            DB::table('partner_layers')->insertOrIgnore(array_merge($layer, [
                'created_at' => $now,
                'updated_at' => $now,
            ]));
        }
    }

    /**
     * Rollback is intentionally a no-op: removing partner layers would
     * cascade-delete partners referencing them.
     */
    public function down(): void {}
};
