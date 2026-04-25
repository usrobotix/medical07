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
| DC design-system components | `resources/views/components/dc/` — `<x-dc.card>`, `<x-dc.button>`, `<x-dc.table>` |
| Other Blade components | `resources/views/components/` |

---

## KB Admin Pages

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
