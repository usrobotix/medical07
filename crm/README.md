<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

# Medical07 CRM

Laravel-based CRM for medical case coordination with a Kanban pipeline board, RBAC, and status history.

## Quick Local Setup

```bash
cd crm

# 1. Install PHP dependencies
composer install

# 2. Copy and configure environment
cp .env.example .env
php artisan key:generate

# 3. Configure your database in .env (DB_DATABASE, DB_USERNAME, DB_PASSWORD)

# 4. Run migrations in order:
php artisan migrate

# 5. Seed roles, case statuses, and admin user
php artisan db:seed

# 6. Install JS dependencies and build assets
npm install
npm run build

# 7. Start development server
php artisan serve
```

Open http://localhost:8000 and log in with `admin@medical07.local` / `ChangeMe123!`.

## Migrations (in order)

1. `2014_10_12_000000_create_users_table`
2. `2014_10_12_100000_create_password_reset_tokens_table`
3. `2019_08_19_000000_create_failed_jobs_table`
4. `2019_12_14_000001_create_personal_access_tokens_table`
5. `2026_04_24_194129_create_permission_tables` — spatie/permission
6. `2026_04_24_194942_create_case_statuses_table` — pipeline + service statuses
7. `2026_04_24_195236_create_patients_table`
8. `2026_04_24_195336_create_medical_cases_table` — original cases table
9. `2026_04_24_200000_update_cases_pipeline_service_status` — renames `case_status_id` → `pipeline_status_id`, adds `service_status_id`
10. `2026_04_24_200100_create_case_status_histories_table` — audit log for status transitions
11. `2026_04_25_100000_create_partner_layers_table` — partner network layer reference
12. `2026_04_25_100100_create_niches_table` — medical niche reference
13. `2026_04_25_100200_create_countries_table` — country directory
14. `2026_04_25_100300_create_country_directions_table` — per-country guidance
15. `2026_04_25_100400_create_partners_table` — partner records
16. `2026_04_25_100500_create_niche_partner_table` — partner ↔ niche pivot
17. `2026_04_25_100600_create_country_partner_table` — partner ↔ country pivot
18. `2026_04_25_100700_create_case_partner_table` — case ↔ partner pivot
19. `2026_04_25_100800_create_verification_checklists_table` — verification templates
20. `2026_04_25_100900_create_verification_checklist_items_table` — checklist items
21. `2026_04_25_101000_create_partner_verifications_table` — per-partner verification instances
22. `2026_04_25_101100_create_partner_verification_items_table` — per-item tracking
23. `2026_04_25_101200_create_message_templates_table` — message templates

## RBAC Roles

| Role | Permissions |
|------|-------------|
| `admin` | Full access |
| `coordinator` | Move cases across all pipeline statuses; set/clear service statuses |
| `intake` | Create patients/cases; move cases within pipeline stages 1–4 only |

## Key Routes

| Method | URL | Purpose |
|--------|-----|---------|
| GET | `/cases/board` | Kanban board |
| GET | `/cases` | Case list |
| GET/POST | `/cases/create` | Create case |
| PATCH | `/cases/{id}/pipeline-status` | Update pipeline status (JSON, drag&drop) |
| PATCH | `/cases/{id}/service-status` | Set/clear service pause overlay (JSON) |

## Pipeline vs Service Statuses

- **Pipeline statuses** (is_service=false, sort_order 1–15): represent the main workflow stages shown as Kanban columns.
- **Service/pause statuses** (is_service=true, sort_order 101+): overlay badges shown on cards without changing the pipeline stage (e.g. "Ожидаю клиента", "Стоп").

## Partner Knowledge Base

The CRM includes a hybrid partner knowledge base + partner records system based on `set.md`.

### Running migrations and seeding demo data

```bash
cd crm

# Run all migrations (includes partner KB tables)
php artisan migrate

# Seed all data including partner knowledge base
php artisan db:seed
```

The `db:seed` command will populate:
- **3 partner layers** — Clinic/IPO, Translator, Medical Curator
- **5 niches** — Oncology, Neurosurgery/Orthopedics, Cardiology, Rare/Genetics, Reproductive/IVF
- **8 countries** — Turkey, Israel, Germany, Spain, UAE, France, South Korea, Italy
- **Country directions** — per-country "what to look for" and search query hints
- **Verification checklists** — clinic, translator, curator templates from `set.md`
- **Message templates** — email to clinic (EN), WhatsApp (RU), Telegram (RU), letter to translator (RU), letter to curator (RU)
- **Demo partners** — 1 clinic (Turkey, status: new), 1 translator, 1 curator with pending verification instances

### Partner tables

| Table | Description |
|-------|-------------|
| `partner_layers` | 3-layer network structure |
| `niches` | Medical niche reference |
| `countries` | Country directory |
| `country_directions` | Per-country guidance and search tips |
| `partners` | Actual partner records (clinic/translator/curator) |
| `niche_partner` | Partner ↔ niche pivot |
| `country_partner` | Partner ↔ country pivot (multi-country) |
| `case_partner` | Case ↔ partner pivot |
| `verification_checklists` | Verification templates |
| `verification_checklist_items` | Checklist item templates |
| `partner_verifications` | Per-partner verification instances |
| `partner_verification_items` | Per-item completion tracking |
| `message_templates` | Email/WhatsApp/Telegram message templates |



Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com/)**
- **[Tighten Co.](https://tighten.co)**
- **[WebReinvent](https://webreinvent.com/)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel/)**
- **[Cyber-Duck](https://cyber-duck.co.uk)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Jump24](https://jump24.co.uk)**
- **[Redberry](https://redberry.international/laravel/)**
- **[Active Logic](https://activelogic.com)**
- **[byte5](https://byte5.de)**
- **[OP.GG](https://op.gg)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
