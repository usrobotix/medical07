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
php artisan migrate
```

Миграция создаст таблицу `partner_research_profiles`.

---

## Как запустить импорт

```bash
cd crm

# Стандартный запуск (данные читаются из storage/app/research/)
php artisan research:import-partners

# Проверка без записи в БД (dry-run)
php artisan research:import-partners --dry-run

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
- Текст review.md (отображается как preformatted text)

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
- Заполненная папка `storage/app/research/Research/` с YAML-файлами клиник
