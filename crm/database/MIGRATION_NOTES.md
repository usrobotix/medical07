# Migration Notes — Schema & Reference-Data Reconciliation

## Purpose

These migrations reconcile the production database (represented by
`crm/database/cp81814_medical07.sql`) with the desired updated schema and seed
data (represented by the individual table SQL files in `crm/database/`).

---

## How to Run

```bash
cd crm

# 1. Apply schema + data migrations
php artisan migrate

# 2. (Optional) Re-seed all reference data on a fresh install
php artisan db:seed
```

### Import clinic research data (imported partner layer)

The `partners` and `partner_research_profiles` tables for imported clinics are
populated by the `research:import-partners` Artisan command, which reads YAML
files from the configured research source path:

```bash
# Against the committed sample dataset
php artisan research:import-partners --sample

# Against a custom path
php artisan research:import-partners --path=/path/to/Research/
```

The command is idempotent: it uses `firstOrCreate` for partners and
`updateOrCreate` for research profiles.

---

## What the Migrations Do

### `2026_04_29_100000_upsert_roles_reference_data`
Inserts the four canonical web-guard roles (`admin`, `coordinator`, `intake`,
`manager`) using `INSERT IGNORE` on the `(name, guard_name)` unique key.
Existing rows are never modified.

### `2026_04_29_100100_upsert_partner_layers_reference_data`
Inserts all four partner layer rows (`clinic_ipo`, `translator`, `curator`,
`imported`) using `INSERT IGNORE` on the `code` unique key. Notably adds the
`imported` layer (sort_order = 99) which was previously created only as a
side-effect of running `research:import-partners`.

### `2026_04_29_100200_upsert_verification_checklists_reference_data`
Inserts the three verification checklists and their 16 checklist items using
`INSERT IGNORE` on the respective unique keys. Existing text is never
overwritten.

### `2026_04_29_100300_backfill_partner_verification_items`
For every row in `partner_verifications`, creates a corresponding
`partner_verification_items` row (unchecked) for each item in the linked
checklist. Uses `INSERT IGNORE` on the `pvi_verif_item_unique` composite key.
This fixes a gap in the production DB where verification-item rows were not
initialised when the demo partner verifications were first seeded.

---

## Assumptions

1. **Schema is already aligned.** All existing migrations (up to batch 2,
   `2026_04_26_000600`) have been run on production. No column additions or
   type changes are required.

2. **Data conflicts are resolved by INSERT IGNORE.** If a row already exists
   (identified by the unique key stated per-migration), the new row is silently
   dropped — no error, no update.

3. **Roles are identified by `(name, guard_name)`.** The production DB assigned
   different auto-increment IDs to roles vs. the desired SQL file. This is
   harmless because `model_has_roles` and `role_has_permissions` use the stable
   role `id` from the production DB.

4. **Large partner/research datasets** (imported clinics) are managed via the
   `research:import-partners` command and `ClinicResearchSeeder`, not via these
   migrations.

---

## Rollback Limitations

All `down()` methods are intentional **no-ops** because:

* Removing reference rows (roles, partner layers, checklists) would cascade-
  delete live production data (user role assignments, partner records, partner
  verifications).
* We cannot distinguish rows inserted by a migration from rows inserted by a
  user action, so selective deletion is unsafe.

**If a rollback is truly required**, restore from a pre-migration database
backup (`php artisan backup:run --only-db` before running migrations).

---

## Seeder Changes

| Seeder | Change |
|---|---|
| `PartnerLayerSeeder` | Added `imported` layer (code=`imported`, sort_order=99) so fresh installs have all four layers without needing `research:import-partners`. |
| `DemoPartnerSeeder` | After creating each demo `PartnerVerification`, now also creates unchecked `PartnerVerificationItem` rows for every checklist item. The private `seedVerificationItems()` helper uses `firstOrCreate` so re-running the seeder is safe. |
