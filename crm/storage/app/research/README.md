# Research Dataset — External Data Folder

## ⚠️ Windows Compatibility Notice

The Research dataset contains directories whose names include a literal slash character
(e.g. `Нейрохирургия / Ортопедия`, `Репродуктология / ЭКО`). Git on Windows treats a
slash inside a path component as a directory separator, which causes:

```
error: invalid path 'crm/storage/app/research/Research/Германия/Нейрохирургия / Ортопедия/...'
```

**The dataset is therefore NOT stored in this repository.** Place the files manually
after cloning — see the instructions below.

---

## Where to place the dataset

Copy the `Research/` folder from the source repository (`usrobotix/yandex-direct`) into
this directory so the final layout is:

```
crm/storage/app/research/
  Research/
    Германия/
      Онкология/
        Charité Comprehensive Cancer Center — Берлин/
          clinic.yaml
          review.md
      Нейрохирургия / Ортопедия/
        ...
    Испания/
      ...
```

### Option A — copy from a local clone of yandex-direct

```bash
cp -r /path/to/yandex-direct/Research crm/storage/app/research/
```

### Option B — clone yandex-direct into a temporary location and copy

```bash
git clone git@github.com:usrobotix/yandex-direct.git /tmp/yd
cp -r /tmp/yd/Research crm/storage/app/research/
rm -rf /tmp/yd
```

### Option C — override path via environment variable

If you keep the dataset in a different folder, set `RESEARCH_SOURCE_PATH` in `.env`:

```env
RESEARCH_SOURCE_PATH=/absolute/path/to/research-parent
```

The import command will look for a `Research/` subdirectory inside that path.

---

## Running the import

```bash
cd crm
php artisan migrate            # only needed once (creates partner_research_profiles)
php artisan research:import-partners
# or with a custom path:
php artisan research:import-partners --path=/absolute/path/to/Research
```

See `docs/RESEARCH_IMPORT.md` for full documentation.
