<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Backfill partner_verification_items for every existing partner_verification.
 *
 * For each row in `partner_verifications`, this migration retrieves all
 * `verification_checklist_items` that belong to that verification's checklist
 * and inserts a corresponding (unchecked) row in `partner_verification_items`
 * if one does not already exist.
 *
 * Idempotency is guaranteed by the `pvi_verif_item_unique` UNIQUE KEY on
 * (partner_verification_id, checklist_item_id): INSERT IGNORE will silently
 * skip duplicates.
 *
 * Safe to run on production: only INSERT IGNORE operations — no DELETEs or
 * UPDATEs are performed.
 */
return new class extends Migration
{
    public function up(): void
    {
        $now = now();

        $verifications = DB::table('partner_verifications')->get();

        if ($verifications->isEmpty()) {
            return;
        }

        // Pre-load all relevant checklist items in one query, grouped by checklist_id.
        $checklistIds = $verifications->pluck('checklist_id')->unique()->values()->all();

        $itemsByChecklist = DB::table('verification_checklist_items')
            ->whereIn('checklist_id', $checklistIds)
            ->orderBy('sort_order')
            ->get()
            ->groupBy('checklist_id');

        foreach ($verifications as $verification) {
            $items = $itemsByChecklist->get($verification->checklist_id, collect());

            foreach ($items as $item) {
                DB::table('partner_verification_items')->insertOrIgnore([
                    'partner_verification_id' => $verification->id,
                    'checklist_item_id'       => $item->id,
                    'is_checked'              => false,
                    'checked_at'              => null,
                    'notes'                   => null,
                    'created_at'              => $now,
                    'updated_at'              => $now,
                ]);
            }
        }
    }

    /**
     * Rollback cannot safely reverse this: we don't know which rows were added
     * by this migration vs. by user action.  Intentionally a no-op.
     */
    public function down(): void {}
};
