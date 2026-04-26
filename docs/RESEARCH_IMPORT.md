# Research Import — документация

## Обзор

Модуль импорта исследовательских данных загружает информацию о клиниках (партнёрах типа `clinic`) из YAML-файлов датасета Research в CRM.

**Источник датасета:** репозиторий `usrobotix/yandex-direct`, папка `Research/`  
**Покрытие:** 7 стран × 5 направлений × 10 клиник = 350 клиник  
**Страны:** Германия, Испания, Италия, Франция, ОАЭ, Турция, Южная Корея  
**Направления:** Онкология, Нейрохирургия / Ортопедия, Кардиология, Редкие диагнозы / Генетика, Репродуктология / ЭКО

---

## ⚠️ Ограничение Windows — датасет не включён в репозиторий

Некоторые директории в датасете содержат **символ `/` в имени папки** (например
`Нейрохирургия / Ортопедия`, `Репродуктология / ЭКО`). Git на Windows интерпретирует
слеш как разделитель пути, что приводит к ошибке при `git pull`:

```
error: invalid path 'crm/storage/app/research/Research/Германия/Нейрохирургия / Ортопедия/...'
```

**Решение:** датасет исключён из репозитория и должен быть скопирован вручную в
`crm/storage/app/research/` (или указан через переменную окружения). Сам механизм
импорта (`php artisan research:import-partners`) при этом **полностью сохраняется**.

---

## Структура данных

Каждая клиника в датасете хранится в отдельной папке:

```
Research/<Страна>/<Направление>/<Название клиники> — <Город>/
  clinic.yaml    — структурированные данные (контакты, услуги, статусы)
  review.md      — развёрнутый обзор клиники
```

Поля `clinic.yaml` описаны в `Research/schema.md` (в репозитории yandex-direct).

---

## Образец датасета (Windows-safe, в репозитории)

Репозиторий содержит **один пример клиники** с безопасными путями (без `/` в именах папок):

```
crm/storage/app/research/sample/
  charite/
    clinic.yaml    ← структурированные данные
    review.md      ← Markdown-обзор клиники
```

Этот образец можно использовать сразу после клонирования без дополнительных действий:

```bash
cd crm
php artisan research:import-partners --sample --dry-run
php artisan research:import-partners --sample
```

Флаг `--sample` автоматически указывает команде на папку `storage/app/research/sample/`.

---

## Где хранятся данные (внешняя папка)

Датасет **не входит в репозиторий** (см. раздел ⚠️ выше). После клонирования/pull
нужно скопировать папку `Research/` вручную:

```bash
# Вариант 1: копировать из локального клона yandex-direct
cp -r /path/to/yandex-direct/Research crm/storage/app/research/

# Вариант 2: клонировать yandex-direct во временную папку и скопировать
git clone git@github.com:usrobotix/yandex-direct.git /tmp/yd
cp -r /tmp/yd/Research crm/storage/app/research/
rm -rf /tmp/yd
```

После копирования структура должна выглядеть:

```
crm/storage/app/research/Research/
  Германия/
    Онкология/
      Charité Comprehensive Cancer Center — Берлин/
        clinic.yaml
        review.md
      ...
    Нейрохирургия / Ортопедия/   ← имя папки содержит '/' — нормально на Linux/macOS
      ...
    Кардиология/
      ...
  Испания/
    Репродуктология / ЭКО/       ← аналогично
      ...
```

> **Linux / macOS**: слеш в именах папок разрешён — всё работает штатно.  
> **Windows**: такие имена недопустимы в NTFS/FAT, поэтому датасет хранится вне репо.

---

## Схема базы данных

Для хранения исследовательских данных добавлена таблица `partner_research_profiles` (1:1 к `partners`):

| Поле | Тип | Описание |
|------|-----|----------|
| `id` | bigint | PK |
| `partner_id` | bigint (unique FK) | Ссылка на partners |
| `address` | text | Адрес клиники |
| `direction` | string | Направление (на русском) |
| `key_services` | json | Список ключевых услуг |
| `international_page_url` | text | URL страницы для иностранных пациентов |
| `accepts_foreigners_status` | string | Статус приёма иностранцев |
| `accepts_foreigners_source_url` | text | URL источника |
| `accepts_ru_status` | string | Статус приёма пациентов из РФ |
| `accepts_ru_source_url` | text | URL источника |
| `working_hours` | text | Режим работы |
| `doctors` | json | Список врачей |
| `prices` | json | Данные о ценах |
| `reviews` | json | Список отзывов |
| `sources` | json | Список источников |
| `last_checked_at` | date | Дата последней проверки данных |
| `source_path` | string | Относительный путь к clinic.yaml |
| `raw_clinic_yaml` | longText | Исходный YAML (полный текст) |
| `review_markdown` | longText | Текст review.md |
| `imported_at` | timestamp | Дата последнего импорта |
| `timestamps` | — | created_at / updated_at |

---

## Маппинг направлений → ниши CRM

| Направление | Код ниши в CRM |
|-------------|----------------|
| Онкология | `oncology` |
| Нейрохирургия / Ортопедия | `neurosurgery_orthopedics` |
| Кардиология | `cardiology` |
| Редкие диагнозы / Генетика | `rare_genetics` |
| Репродуктология / ЭКО | `reproductive_ivf` |

---

## Как запустить миграции

```bash
cd crm
composer install       # устанавливает зависимости, включая league/commonmark и symfony/yaml
php artisan migrate
```

Миграция создаст таблицу `partner_research_profiles`.

---

## Как запустить импорт

### На Windows / OpenServer — полный датасет (Вариант B1)

Датасет содержит папки с `/` в именах (`Нейрохирургия / Ортопедия`), которые **нельзя создать в NTFS**.
Для Windows используется специальная команда санитизации, которая копирует датасет с безопасными именами.

#### Шаг 1: Получить датасет из `usrobotix/yandex-direct`

**Вариант А (рекомендуется):** скачать ZIP-архив репозитория (не `git clone` — это важно!)

```
https://github.com/usrobotix/yandex-direct/archive/refs/heads/main.zip
```

1. Скачать и распаковать архив (например, в `C:\tmp\yandex-direct-main\`).
2. Внутри архива найти папку `Research/`.

**Вариант Б:** клонировать репозиторий **на Linux/macOS** (или WSL), а потом скопировать только содержимое `Research/`:

```bash
git clone https://github.com/usrobotix/yandex-direct.git /tmp/yd
# Только потом копировать на Windows через сетевую папку или USB
```

> ⚠️ **Никогда не клонируйте `yandex-direct` напрямую на Windows через `git clone`** — папки
> с `/` в именах вызовут ошибку `invalid path`.

#### Шаг 2: Скопировать папку `Research/` в проект

Скопируйте папку `Research/` (из архива или с Linux-машины) в:

```
C:\OSPanel\domains\medical07\crm\storage\app\research\Research\
```

После копирования структура должна выглядеть:

```
crm\storage\app\research\Research\
  Германия\
    Онкология\
      Charité Comprehensive Cancer Center — Берлин\
        clinic.yaml
        review.md
    Нейрохирургия / Ортопедия\   ← такое имя допустимо при копировании через Explorer
      ...
```

> На Windows через Explorer (не через `git`) папки с `/` в именах создаются без проблем,
> поскольку Explorer передаёт имена через Win32 API, а NTFS их поддерживает.
> Проблема только при `git checkout` / `git clone`.

#### Шаг 3: Создать Windows-безопасную копию (команда `research:sanitize-dataset`)

```bat
cd C:\OSPanel\domains\medical07\crm

REM Посмотреть, что будет скопировано (без записи файлов)
php artisan research:sanitize-dataset --dry-run

REM Создать Windows-safe копию в storage/app/research/Research_win/
php artisan research:sanitize-dataset
```

Команда заменяет `/` в именах папок на ` - `, удаляет другие Windows-небезопасные символы
(`< > : " | ? *`) и копирует только `clinic.yaml` + `review.md`.

Дополнительные опции:

```bat
REM Указать другой источник и/или назначение
php artisan research:sanitize-dataset --source=C:\path\to\Research --dest=C:\path\to\Research_win
```

#### Шаг 4: Запустить импорт

```bat
cd C:\OSPanel\domains\medical07\crm

REM 1. Установить зависимости
composer install

REM 2. Выполнить миграции (один раз)
php artisan migrate

REM 3. Проверка без записи в БД на образце (работает сразу из коробки)
php artisan research:import-partners --sample --dry-run

REM 4. Импорт образца
php artisan research:import-partners --sample

REM 5a. Импорт Windows-safe датасета (после шагов 1-3 выше)
php artisan research:import-partners --path=storage\app\research\Research_win

REM 5b. Или с полным путём
php artisan research:import-partners --path=C:\OSPanel\domains\medical07\crm\storage\app\research\Research_win
```

### На Linux / macOS

```bash
cd crm

# Проверка без записи в БД (рекомендуется перед первым запуском)
php artisan research:import-partners --dry-run

# Образец (всегда работает без дополнительных файлов)
php artisan research:import-partners --sample --dry-run
php artisan research:import-partners --sample

# Стандартный запуск (данные читаются из storage/app/research/)
php artisan research:import-partners

# С другим путём к данным
php artisan research:import-partners --path=/path/to/Research
```

**Команда идемпотентна**: повторный запуск обновит существующие записи (upsert), не создавая дубликатов. Партнёры идентифицируются по ключу `(type='clinic', name, country_id)`.

### Вывод команды

```
Scanning: /path/to/storage/app/research/Research
Found 350 clinic.yaml files.
  ✓ Германия / Онкология / Charité Comprehensive Cancer Center
  ✓ Германия / Онкология / Helios Klinikum Berlin-Buch — Onkologie
  ...
Done. Processed: 350 | Created: 350 | Updated: 0 | Errors: 0
```

---

## Запуск через seeder

```bash
cd crm
php artisan db:seed --class=ClinicResearchSeeder
```

Или как часть полного сидирования:

```bash
php artisan db:seed --class=PartnerKnowledgeBaseSeeder
```

---

## Как работает идемпотентность

1. **Партнёр** (`partners`): ищется по `(type='clinic', name, country_id)`. Если найден — обновляются только пустые поля. Если нет — создаётся новый.
2. **Профиль** (`partner_research_profiles`): `updateOrCreate` по `partner_id`. При повторном запуске данные полностью обновляются.
3. **Ниша**: `syncWithoutDetaching` — добавляет нишу к партнёру, не удаляя существующие связи.
4. **Страна**: `firstOrCreate` по `name_ru` — создаёт страну, если её нет в БД.

---

## Конфигурация

Путь к источнику данных задаётся в `config/research.php`:

```php
'source_path' => env('RESEARCH_SOURCE_PATH', storage_path('app/research')),
```

Можно переопределить через `.env`:

```env
RESEARCH_SOURCE_PATH=/var/www/medical07/crm/storage/app/research
```

---

## Отображение в UI

На странице партнёра (`/kb/partners/{id}`) автоматически отображается раздел **"Research — данные исследования"**, если у партнёра есть связанный профиль (`researchProfile`).

Раздел включает:
- Направление и дата последней проверки
- Адрес клиники
- Ссылка на страницу для иностранных пациентов
- Статус приёма иностранцев и пациентов из РФ (со ссылками на источники)
- Режим работы
- Ключевые услуги
- Список источников
- Текст review.md — отображается как **отформатированный HTML** (Markdown-рендеринг через `league/commonmark`)

---

## Обслуживание на боевом сервере

```bash
# После git pull (обновились код или конфигурация)
cd crm
git pull origin main
php artisan optimize:clear
php artisan migrate        # только если появились новые миграции
```

> **Датасет не обновляется через `git pull`** — он хранится вне репозитория.
> Чтобы обновить данные клиник, скопируйте свежий `Research/` и запустите импорт:

```bash
# Обновить датасет (пример — вручную скопировать из yandex-direct)
cp -r /path/to/yandex-direct/Research crm/storage/app/research/

# Запустить импорт (идемпотентно, можно гонять сколько угодно раз)
php artisan research:import-partners
```

---

## Требования

- PHP >= 8.1 с расширением `yaml` (либо установленный `symfony/yaml`)
- Laravel 10+
- `league/commonmark` ^2.6 (для рендеринга Markdown в UI — добавлен в `composer.json`)
- Заполненная папка `storage/app/research/Research/` с YAML-файлами клиник (или `--sample` для образца)

### Безопасный рендеринг Markdown

`review_markdown` рендерится через `league/commonmark` с параметрами:

```php
new \League\CommonMark\CommonMarkConverter([
    'html_input'         => 'escape',    // экранирует сырой HTML → защита от XSS
    'allow_unsafe_links' => false,       // блокирует javascript: / vbscript: ссылки
    'max_nesting_level'  => 10,          // ограничивает глубину вложенности
]);
```

Это гарантирует, что даже если данные YAML-файла содержат HTML-теги или вредоносные ссылки, они будут безопасно экранированы.
