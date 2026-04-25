# CRM Technical Documentation

> Live version available at `/kb/tech` (requires `admin` or `manager` role).

---

## Project Info Panel

The page at `/kb/tech` automatically displays:

| Field | Source |
|-------|--------|
| Server date/time | `now()` (PHP server time) |
| Application version | `APP_VERSION` env → `config('app.version')` → `—` |
| Git commit hash | `.git/HEAD` (short 7-char hash, safe fallback if absent) |
| Git commit date | `.git/logs/HEAD` (last entry timestamp, safe fallback) |
| PHP version | `PHP_VERSION` constant |
| Laravel version | `app()->version()` |
| spatie/laravel-permission | `vendor/composer/installed.php` |
| Environment | `APP_ENV` (`config('app.env')`) |

No sensitive data (passwords, keys) is exposed on this page.

---

## Styles & Design Tokens

| Resource | Path |
|----------|------|
| CSS variables & base styles | `resources/css/app.css` |
| Tailwind configuration | `tailwind.config.js` |
| Vite build config | `vite.config.js` |

Rebuild frontend assets: `npm run build`

---

## Layouts & Blade Components

| Resource | Path / Tag |
|----------|-----------|
| Main app layout | `resources/views/layouts/app.blade.php` — `<x-app-layout>` |
| Guest layout (auth pages) | `resources/views/layouts/guest.blade.php` |
| Navigation | `resources/views/layouts/navigation.blade.php` |
| DC design-system components | `resources/views/components/dc/` — `<x-dc.card>`, `<x-dc.button>`, `<x-dc.table>`, `<x-dc.input>`, `<x-dc.select>`, `<x-dc.tabs>`, `<x-dc.badge>`, `<x-dc.modal>` |
| Other Blade components | `resources/views/components/` |

---

## CRM Sections — Where to Find What

### Dashboard (Главная)
- **Purpose:** Starting page after login; summary widgets.
- **Route:** `GET /dashboard` (closure in `routes/web.php`) → view `dashboard`
- **View:** `resources/views/dashboard.blade.php`
- **Middleware:** `auth`, `verified`
- **Edit UI:** `resources/views/dashboard.blade.php`; use `x-dc.*` components.

### Profile (Профиль)
- **Purpose:** Edit name, email, password; delete account.
- **Routes:** `GET /profile`, `PATCH /profile`, `DELETE /profile`
- **Controller:** `app/Http/Controllers/ProfileController.php`
- **Views:** `resources/views/profile/`
- **Middleware:** `auth`

### Cases (Кейсы)
- **Purpose:** Medical cases — list, create, kanban board, pipeline/service status updates.
- **Routes:**
  - `GET /cases`, `GET /cases/create`, `POST /cases` → `MedicalCaseController`
  - `GET /cases/board` → `CaseBoardController`
  - `PATCH /cases/{case}/pipeline-status` → `CaseStatusController@updatePipeline`
  - `PATCH /cases/{case}/service-status` → `CaseStatusController@updateService`
- **Controllers:** `app/Http/Controllers/MedicalCaseController.php`, `CaseBoardController.php`, `CaseStatusController.php`
- **Views:** `resources/views/cases/`
- **Models:** `app/Models/MedicalCase.php`, `CaseStatus.php`, `CaseStatusHistory.php`
- **Middleware:** `auth`

### Patients (Пациенты)
- **Purpose:** List and create patients.
- **Routes:** `GET /patients`, `GET /patients/create`, `POST /patients`
- **Controller:** `app/Http/Controllers/PatientController.php`
- **Views:** `resources/views/patients/`
- **Model:** `app/Models/Patient.php`
- **Middleware:** `auth`

### KB Read — Knowledge Base (Справочники, просмотр)
- **Purpose:** Read-only access to knowledge base — partners, countries, niches, country-directions, verification-checklists, message-templates, partner-verifications.
- **Routes (index/show only):** `GET /kb/partners`, `/kb/countries`, `/kb/niches`, `/kb/country-directions`, `/kb/verification-checklists`, `/kb/message-templates`, `/kb/partner-verifications`
- **Controllers:** `app/Http/Controllers/Kb/` (PartnerController, CountryController, NicheController, CountryDirectionController, VerificationChecklistController, MessageTemplateController, PartnerVerificationController)
- **Views:** `resources/views/kb/{section}/index.blade.php` and `show.blade.php`
- **Middleware:** `auth`

### KB Admin/Write (Справочники, редактирование)
- **Purpose:** Create / edit / delete KB records; start partner verification; manage checklist items; message templates.
- **Middleware:** `role:admin|manager` — only `admin` or `manager` roles.
- **Notable routes:**
  - `POST /kb/partners/{partner}/start-verification`
  - `POST|PATCH|DELETE /kb/verification-checklists/{id}/items`
  - `POST /kb/partner-verifications/{id}/items/update-bulk`
- **Views (create/edit):** `resources/views/kb/{section}/create.blade.php`, `edit.blade.php`
- **Edit UI:** forms in `resources/views/kb/{section}/`; use `x-dc.input`, `x-dc.select`, `x-dc.button`.

### Auth (Авторизация и регистрация)
- **Purpose:** Login, registration, password reset, email verification, password confirmation.
- **Routes:** `routes/auth.php` (required at end of `web.php`)
- **Controllers:** `app/Http/Controllers/Auth/` — AuthenticatedSessionController, RegisteredUserController, PasswordResetLinkController, NewPasswordController, EmailVerificationController, ConfirmablePasswordController
- **Views:** `resources/views/auth/` — login, register, forgot-password, reset-password, verify-email, confirm-password
- **Layout:** `resources/views/layouts/guest.blade.php`
- **Middleware:** guest pages use `guest`; email verification uses `verified`

---

## KB Admin Pages (Reference table)

| Section | Views path | Controller |
|---------|-----------|------------|
| Partners | `resources/views/kb/partners/` | `App\Http\Controllers\Kb\PartnerController` |
| Countries | `resources/views/kb/countries/` | `App\Http\Controllers\Kb\CountryController` |
| Niches | `resources/views/kb/niches/` | `App\Http\Controllers\Kb\NicheController` |
| Country Directions | `resources/views/kb/country-directions/` | `App\Http\Controllers\Kb\CountryDirectionController` |
| Verification Checklists | `resources/views/kb/verification-checklists/` | `App\Http\Controllers\Kb\VerificationChecklistController` |
| Message Templates | `resources/views/kb/message-templates/` | `App\Http\Controllers\Kb\MessageTemplateController` |
| Partner Verifications | `resources/views/kb/partner-verifications/` | `App\Http\Controllers\Kb\PartnerVerificationController` |
| **Tech page** | `resources/views/kb/tech.blade.php` | `App\Http\Controllers\Kb\TechPageController` |

---

## Role Middleware (Spatie)

> ⚠️ **Important:** Always use the pipe `|` delimiter, **not** a comma `,`.

| Usage | Result |
|-------|--------|
| `role:admin,manager` | ❌ Comma is interpreted as a guard parameter → `Auth guard [manager] is not defined` |
| `role:admin\|manager` | ✅ Correct — allows users with either `admin` or `manager` role |

KB write routes are protected via:
```php
Route::prefix('kb')->name('kb.')->middleware('role:admin|manager')->group(function () { ... });
```

---

## Project Structure

All Laravel application code lives in the `crm/` directory of the repository.

| Path | Purpose |
|------|---------|
| `crm/app/Http/Controllers/` | Controllers (main + `Kb/` sub-namespace) |
| `crm/app/Http/Controllers/Auth/` | Breeze authentication controllers |
| `crm/app/Models/` | Eloquent models (MedicalCase, Patient, Partner, Country, Niche…) |
| `crm/app/Policies/` | Authorization policies |
| `crm/app/Providers/` | Service providers (AppServiceProvider, etc.) |
| `crm/config/` | Laravel configuration — `app.php`, `auth.php`, `database.php`, etc. |
| `crm/database/migrations/` | Database migrations |
| `crm/database/factories/` | Model factories for tests/seeding |
| `crm/database/seeders/` | Seeders (initial data: PartnerLayerSeeder, etc.) |
| `crm/resources/views/` | Blade templates (layouts/, auth/, kb/, cases/, patients/, profile…) |
| `crm/resources/views/components/dc/` | DC design system: card, button, input, select, table, tabs, badge, modal |
| `crm/resources/views/layouts/` | `app.blade.php`, `guest.blade.php`, `navigation.blade.php` |
| `crm/resources/css/app.css` | CSS variables, base styles, Tailwind directives |
| `crm/resources/js/app.js` | JS entry point; Alpine.js and other libraries |
| `crm/routes/web.php` | Web routes |
| `crm/routes/auth.php` | Breeze auth routes |
| `crm/tailwind.config.js` | Tailwind CSS configuration (tokens, safelist) |
| `crm/vite.config.js` | Vite bundler configuration |
| `crm/public/` | Public directory (index.php, compiled assets in `build/`) |
| `crm/storage/logs/` | Laravel log files (`laravel.log`) |
| `crm/tests/` | Tests (Feature/, Unit/) — run with `php artisan test` |
| `crm/.env` | Environment variables (not in git; template: `.env.example`) |
| `docs/` | Repository technical documentation |

---

## Useful Cache Commands

```bash
# Clear all caches at once
php artisan optimize:clear

# Individual caches
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

# Rebuild frontend assets
npm run build

# Verify routes
php artisan route:list --path=kb
```

---

## Maintenance Commands (Git Bash)

All Laravel / Composer / Node commands should be run from inside the `crm/` directory.

### Git

```bash
git status                      # Working directory state
git branch -a                   # All branches (local + remote)
git fetch origin                # Fetch updates without applying
git pull origin main            # Update current branch from remote
git checkout -b feature/name    # Create and switch to new branch
git stash -u                    # Stash all changes (including untracked)
git stash pop                   # Restore stashed changes
git diff                        # Show unstaged changes
git log --oneline -10           # Last 10 commits
git reset --soft HEAD~1         # Undo last commit (keep changes staged)
git reset --hard HEAD           # ⚠ Reset working changes to HEAD — irreversible
git clean -fd                   # ⚠ Delete untracked files/folders — irreversible
```

### Laravel

```bash
php artisan optimize:clear      # Clear all caches (config, route, view, cache)
php artisan config:cache        # Cache configuration (production)
php artisan route:cache         # Cache routes (production)
php artisan view:cache          # Pre-compile Blade templates
php artisan migrate             # Run pending migrations
php artisan migrate:status      # Show migration status
php artisan migrate:rollback    # ⚠ Roll back last batch of migrations
php artisan db:seed             # Run seeders (initial data)
php artisan tinker              # Interactive Laravel REPL
php artisan route:list --path=kb  # List KB routes
php artisan storage:link        # Create public/storage symlink
php artisan test                # Run PHPUnit tests
```

### Composer

```bash
composer install                # Install dependencies from composer.lock
composer install --no-dev       # Install production dependencies only
composer update                 # Update dependencies and composer.lock
composer dump-autoload          # Regenerate class autoload map
```

### Node / Vite

```bash
npm install                     # Install npm dependencies
npm run dev                     # Start Vite dev server (HMR) for local development
npm run build                   # Build production assets to public/build/
```

### Log Tailing (Windows / Git Bash)

Laravel log file: `crm/storage/logs/laravel.log`

```bash
# Git Bash / Linux (if tail is available)
tail -f storage/logs/laravel.log

# PowerShell alternative
Get-Content storage/logs/laravel.log -Wait -Tail 50

# One-time dump (if tail is unavailable in Git Bash)
cat storage/logs/laravel.log
```

> **Windows / OSPanel note:** Ensure the `storage/` directory is writable by PHP.
> On OSPanel permissions are set automatically, but after manual file transfers you may need:
> `chmod -R 775 storage bootstrap/cache` (Linux/WSL environments only).

---

## Route List (KB section)

Run `php artisan route:list --path=kb` to see all KB routes including:

- `GET /kb/tech` — this technical documentation page (admin|manager only)
- `GET /kb/partners` — partners index (all authenticated)
- … and other KB CRUD routes

