<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Idempotent upsert of roles reference data.
 *
 * Uses INSERT IGNORE (insertOrIgnore) keyed on (name, guard_name) so existing
 * production rows — including any user-created roles — are never overwritten.
 * Running this migration on a DB that already has all rows is a no-op.
 */
return new class extends Migration
{
    public function up(): void
    {
        $now = now();

        $roles = [
            ['name' => 'admin',       'guard_name' => 'web'],
            ['name' => 'coordinator', 'guard_name' => 'web'],
            ['name' => 'intake',      'guard_name' => 'web'],
            ['name' => 'manager',     'guard_name' => 'web'],
        ];

        foreach ($roles as $role) {
            DB::table('roles')->insertOrIgnore(array_merge($role, [
                'created_at' => $now,
                'updated_at' => $now,
            ]));
        }
    }

    /**
     * Rollback is intentionally a no-op: removing reference roles could
     * break existing RBAC assignments on production.
     */
    public function down(): void {}
};
