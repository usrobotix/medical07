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

## Site Sections

### Dashboard `/dashboard`

- **Purpose:** landing page after login — overview of CRM sections and quick links.
- **Route:** `routes/web.php` → `Route::get('/dashboard', fn () => view('dashboard'))` middleware `auth`, `verified`
- **View:** `resources/views/dashboard.blade.php`
- **Edit UI:** edit `dashboard.blade.php` and its inner cards.

### Profile `/profile`

- **Purpose:** edit name, email, password; delete account.
- **Routes:** `routes/web.php` → `GET/PATCH/DELETE /profile`
- **Controller:** `app/Http/Controllers/ProfileController.php` (methods `edit`, `update`, `destroy`)
- **Views:** `resources/views/profile/`
- **Middleware:** `auth`

### Cases `/cases`

- **Purpose:** create and manage medical cases for patients.
- **List/create:** `app/Http/Controllers/MedicalCaseController.php` → resource (index, create, store)
  | Views: `resources/views/cases/`
- **Board:** `GET /cases/board` → `app/Http/Controllers/CaseBoardController.php@index`
- **Pipeline status:** `PATCH /cases/{case}/pipeline-status` → `CaseStatusController@updatePipeline`
- **Service status:** `PATCH /cases/{case}/service-status` → `CaseStatusController@updateService`
- **Status controller:** `app/Http/Controllers/CaseStatusController.php`
- **Middleware:** `auth`

### Patients `/patients`

- **Purpose:** patient directory — list and create.
- **Routes:** resource (index, create, store) in `routes/web.php`
- **Controller:** `app/Http/Controllers/PatientController.php`
- **Views:** `resources/views/patients/`
- **Middleware:** `auth`

### KB — Read area `/kb/*`

- **Purpose:** browse reference data (partners, countries, niches, directions, checklists, message templates, verifications).
- **Routes:** `routes/web.php` → group `Route::prefix('kb')->name('kb.')` — only `index` and `show` for each resource.
- **Controllers:** `app/Http/Controllers/Kb/`
- **Views:** `resources/views/kb/` (subdirectories per section)
- **Middleware:** `auth` — accessible to all authenticated users.

### KB — Write / Admin area

Same `/kb` prefix but protected by `role:admin|manager`.

| Section | Views | Controller |
|---------|-------|------------|
| Partners | `resources/views/kb/partners/` | `App\Http\Controllers\Kb\PartnerController` |
| Countries | `resources/views/kb/countries/` | `App\Http\Controllers\Kb\CountryController` |
| Niches | `resources/views/kb/niches/` | `App\Http\Controllers\Kb\NicheController` |
| Country Directions | `resources/views/kb/country-directions/` | `App\Http\Controllers\Kb\CountryDirectionController` |
| Verification Checklists | `resources/views/kb/verification-checklists/` | `App\Http\Controllers\Kb\VerificationChecklistController` |
| Checklist Items | *(inline in checklist view)* | `App\Http\Controllers\Kb\VerificationChecklistItemController` |
| Message Templates | `resources/views/kb/message-templates/` | `App\Http\Controllers\Kb\MessageTemplateController` |
| Partner Verifications | `resources/views/kb/partner-verifications/` | `App\Http\Controllers\Kb\PartnerVerificationController` |
| **Tech page** | `resources/views/kb/tech.blade.php` | `App\Http\Controllers\Kb\TechPageController` |

Special routes:
- `POST /kb/partners/{partner}/start-verification` → `PartnerController@startVerification`
- `POST /kb/partner-verifications/{id}/items/update-bulk` → `PartnerVerificationController@updateItems`

**Middleware:** `auth` + `role:admin|manager`

### Auth `routes/auth.php`

- **Purpose:** login, registration, password reset, email verification.
- **Routes:** `routes/auth.php` (required at the end of `routes/web.php`)
- **Controllers:** `app/Http/Controllers/Auth/`
- **Views:** `resources/views/auth/` (Breeze stack)

---

## Project Structure

All CRM code lives inside the `crm/` directory:

```
crm/
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       ├── Kb/                    ← all KB controllers
│   │       ├── CaseBoardController.php
│   │       ├── CaseStatusController.php
│   │       ├── MedicalCaseController.php
│   │       ├── PatientController.php
│   │       └── ProfileController.php
│   ├── Models/                        ← Eloquent models
│   ├── Policies/                      ← authorization policies
│   └── Providers/                     ← service providers
├── routes/
│   ├── web.php                        ← all web routes
│   └── auth.php                       ← Breeze auth routes
├── resources/
│   ├── views/
│   │   ├── dashboard.blade.php
│   │   ├── cases/
│   │   ├── patients/
│   │   ├── profile/
│   │   ├── auth/
│   │   ├── kb/                        ← KB Blade templates
│   │   ├── layouts/                   ← app layout
│   │   └── components/                ← Blade components (dc/, etc.)
│   ├── css/app.css                    ← CSS variables & base styles
│   └── js/app.js                      ← JS entry (Alpine.js, axios)
├── database/
│   ├── migrations/                    ← DB migrations
│   └── seeders/                       ← seeders
├── config/                            ← Laravel & package configs (incl. permission.php)
├── public/                            ← webroot: index.php, compiled assets (build/)
├── storage/
│   └── logs/laravel.log               ← application log
├── vite.config.js                     ← Vite build config
├── tailwind.config.js                 ← Tailwind config
├── composer.json                      ← PHP dependencies
└── package.json                       ← JS dependencies
```

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
| DC design-system components | `resources/views/components/dc/` — `<x-dc.card>`, `<x-dc.button>`, `<x-dc.table>` |
| Other Blade components | `resources/views/components/` |

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

The `/kb/tech` page is also protected: `auth` + `role:admin|manager`.

---

## Maintenance Commands (Git Bash)

### Git

```bash
git status
git branch --show-current
git fetch --all --prune
git pull origin main
git log -n 20 --oneline --decorate
git diff
git diff main..feature/my-branch

# Temporarily stash uncommitted changes
git stash -u
git stash pop

# Restore a single file
git restore path/to/file

# ⚠️ Caution: discard ALL uncommitted changes
git reset --hard
git clean -fd
```

### Laravel (run from `crm/` directory)

```bash
php artisan optimize:clear          # clear all caches at once
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

php artisan route:list              # list all routes
php artisan route:list --path=kb   # filter to KB routes

php artisan migrate:status          # check migration status
php artisan migrate                 # run pending migrations

php artisan storage:link            # create public/storage symlink

php artisan tinker                  # interactive REPL
```

### Composer

```bash
composer install          # install dependencies from composer.lock
composer dump-autoload    # regenerate autoload maps
```

### Node / Vite (`package.json` is present)

```bash
npm ci          # clean install from package-lock.json (CI/prod)
npm install     # install/update dependencies
npm run dev     # dev server with hot-reload
npm run build   # build production assets to public/build/
```

### Log tailing

```bash
# Git Bash / Linux / macOS:
tail -f storage/logs/laravel.log

# Windows PowerShell (if tail is unavailable):
Get-Content storage\logs\laravel.log -Tail 100 -Wait
```

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

## Route List (KB section)

Run `php artisan route:list --path=kb` to see all KB routes including:

- `GET /kb/tech` — this technical documentation page (admin|manager only)
- `GET /kb/partners` — partners index (all authenticated)
- … and other KB CRUD routes
