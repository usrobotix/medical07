# Research Import

This document describes how to populate the CRM partner knowledge base with clinic research data.

## Dataset location

Place the research dataset under:

```
crm/storage/app/research/Research/
```

The expected structure is:

```
Research/
  <Country>/           # e.g. Турция, Германия
    <Direction>/       # e.g. Онкология, Кардиология
      <Clinic — City>/
        clinic.yaml    # structured data
        review.md      # extended review (optional)
```

## Running the import

```bash
cd crm

# Run migrations first (if not done)
php artisan migrate

# Import clinic research data
php artisan research:import-partners

# Dry run (no DB writes)
php artisan research:import-partners --dry-run

# Use custom source path
php artisan research:import-partners --source=/path/to/Research
```

## Idempotency

The command is idempotent — running it multiple times is safe:
- Partners are matched by `research_slug` (a SHA-256 hash of country + direction + name + city).
- Existing partners and research profiles are updated in place.
- Niche and country pivots use `syncWithoutDetaching` so existing links are preserved.

## Updating data from the dataset repository

If the dataset lives in `usrobotix/yandex-direct`:

```bash
# Clone or pull the source repo
git clone https://github.com/usrobotix/yandex-direct /path/to/yandex-direct
# or
cd /path/to/yandex-direct && git pull

# Copy Research folder
cp -r /path/to/yandex-direct/Research crm/storage/app/research/

# Run import
cd crm && php artisan research:import-partners
```

## Field mapping

| clinic.yaml field | DB location |
|---|---|
| `название` | `partners.name` |
| `город` | `partners.city` |
| `адрес` | `partners.address` |
| `контакты.сайт` | `partners.website_url` |
| `контакты.страница_для_иностранных_пациентов` | `partners.international_page_url` |
| `контакты.email` | `partners.contact_email` |
| `контакты.телефон` | `partners.contact_phone` |
| `направление` | niche via `niche_partner` pivot |
| `ключевые_услуги` | `partner_research_profiles.key_services` |
| `приём_иностранцев` | `partner_research_profiles.accepts_foreigners` |
| `приём_пациентов_из_РФ` | `partner_research_profiles.accepts_russians` |
| `режим_работы` | `partner_research_profiles.working_hours` |
| `врачи` | `partner_research_profiles.doctors` |
| `цены` | `partner_research_profiles.prices` |
| `отзывы` | `partner_research_profiles.reviews` |
| `источники` | `partner_research_profiles.sources` |
| `дата_последней_проверки` | `partners.last_checked_date` |
| review.md content | `partner_research_profiles.review_md` |
