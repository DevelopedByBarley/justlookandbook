# Feature: Ország és város adatmodell

**From build-plan:** feature 13
**Build attempt:** 1
**Status:** verified
**Branch:** feature/orsz-g-s-v-ros-adatmodell

## Goal

Létrejön az ország/város rendszer adatrétege: `countries` és `cities` tábla
(háromnyelvű név, egy kép, a város pontosan egy országhoz kötve), valamint a
programok nem kötelező `country_id` és `city_id` mezője, a hozzájuk tartozó
Eloquent modellekkel és kapcsolatokkal (ajánlat 1.1). Felület nem készül; erre
épül a 14-19. feature.

## In scope

- Három új migráció: `countries` tábla, `cities` tábla, `programs.country_id` és
  `programs.city_id` oszlop. Mindhárom visszagörgethető (`down()`).
- Új `App\Models\Country` és `App\Models\City` modell: `$fillable`, `$casts`,
  kapcsolatok, `getTranslation()` magyar fallbackkel, `imageUrl()`, képmappa
  törlése a rekord törlése után.
- `App\Models\Program`: `country()` és `city()` `BelongsTo` kapcsolat, a két mező
  a `$fillable`-be.
- A meglévő (kb. 80, élesben 19+ sor a dumpban) program változatlan marad,
  ország és város nélkül (`NULL`).

## Out of scope

- Admin ország- és városkezelés, képfeltöltés, validáció (14, 15).
- Ország/város mező a program űrlapon, a "város az ország alá tartozik" szabály
  validációja (16).
- Nyilvános helyválasztó, szűrés, témaszűkítés (17-19), fordítási kulcsok (nincs
  új felirat).
- A meglévő programok Egyiptomhoz rendelése (ajánlat 5.1) és képtömörítés (5.2):
  az ajánlat opcionális tételei, a build-plan szerint nem készülnek.
- Seeder, factory, `slug` mező, sorrend-oszlop (a sorrend fix, név szerint; nem
  húzható), kiemelés, egyedi színek.
- Éles adatbázis-frissítés (20).

## Build loop

- `workflow.stepReview: feature`: a lépések egymás után készülnek, felülvizsgálat
  a teljes feature végén, `/check` és `/audit current` előtt.
- `workflow.checkpointCommits: disabled`: lépésenként nincs commit; a végső
  feature-commitot a `/complete` készíti.
- Nincs `Test` / `Verify` parancs, a tesztelés nem kapu: a lépéseket migrációs
  futtatással és `tinker`-ellenőrzéssel kell igazolni.

## Build steps

- [x] **1. Migrációk.** Három új fájl a `database/migrations/`-ben (dátum
  `2026_11_18_...`, ebben a sorrendben):
  - `create_countries_table`: `id`, `name` (json), `image` (string, nullable),
    `timestamps`.
  - `create_cities_table`: `id`, `country_id` (`foreignId()->constrained()
    ->restrictOnDelete()`), `name` (json), `image` (string, nullable),
    `timestamps`.
  - `add_country_and_city_to_programs_table`: `country_id` és `city_id`
    (`foreignId()->nullable()->after('slug')->constrained()->nullOnDelete()`);
    `down()` előbb a foreign key-t, majd az oszlopot dobja (a
    `create_program_topic_pivot_table` mintájára).

  Meglévő migrációt nem szabad módosítani.
  **Done when:** a helyi MySQL-en (XAMPP, `justlookabook` adatbázis) a
  `php artisan migrate` lefuttatja a hármat; `php artisan migrate:rollback
  --step=3`, majd újra `migrate` hiba nélkül fut; a `programs` sorai megmaradnak,
  `country_id`/`city_id` értékük `NULL`. Ugyanez lefut egy átmeneti SQLite-fájlon
  is (`DB_CONNECTION=sqlite DB_DATABASE=<scratchpad>/check.sqlite php artisan
  migrate`), a projekt `.env`-jének módosítása nélkül.

- [x] **2. Modellek és kapcsolatok.**
  - `Country`: `$fillable = ['name', 'image']`, `name` → `array` cast;
    `cities(): HasMany` (név szerint rendezés nélkül, azt a lekérdező végzi),
    `programs(): HasMany`; `getTranslation(?string $locale = null): string` a
    `Topic` mintájára (`hu` fallback); `imageUrl(): ?string` (`null`, ha nincs
    kép, különben `Storage::url($this->image)`, mint a `ProgramImage`-nél);
    `booted()`-ban **`deleted`** eseményre `Storage::disk('public')
    ->deleteDirectory("countries/{$id}")`.
  - `City`: ugyanez `cities/{$id}` mappával, plusz `country(): BelongsTo` és
    `country_id` a `$fillable`-ben, `programs(): HasMany`.
  - `Program`: `country(): BelongsTo`, `city(): BelongsTo`; `country_id`,
    `city_id` a `$fillable`-be.
  - Ellenőrizd az `AdminProgramController` `store`/`update` útját: a
    `Program::create($data)` / `update` csak validált mezőket kaphat, hogy a két
    új `$fillable` mező ne legyen kéréssel írható a 16. feature előtt.

  **Done when:** `php artisan tinker`-ben (helyi adatbázison, utána a próbasorok
  törölve): létrehozható egy ország és alá egy város; `$city->country`,
  `$country->cities`, `$program->country`/`city` és `$country->programs` a
  várt rekordot adja; `getTranslation('it')` üres `it` esetén a magyar nevet
  adja; `imageUrl()` kép nélkül `null`; városos ország törlése
  `QueryException`-t dob, és a képmappa megmarad; város törlése után a hozzá
  rendelt program `city_id`-ja `NULL`, `country_id`-ja változatlan.

## Files / areas

- `database/migrations/2026_11_18_*_create_countries_table.php` (új)
- `database/migrations/2026_11_18_*_create_cities_table.php` (új)
- `database/migrations/2026_11_18_*_add_country_and_city_to_programs_table.php` (új)
- `app/Models/Country.php`, `app/Models/City.php` (új)
- `app/Models/Program.php`
- `app/Http/Controllers/Admin/AdminProgramController.php` (csak ellenőrzés)

## Data / contracts

- **`countries`**: `id` (bigint unsigned PK), `name` (json, nem null, kulcsok
  `hu`/`en`/`it`, a `topics.name` alakjával azonos), `image` (string, nullable,
  `public` disk relatív útvonal, `countries/{id}/...`), `created_at`,
  `updated_at`.
- **`cities`**: `id`, `country_id` (FK `countries.id`, nem null, **törlés
  korlátozva**: országot csak városok nélkül lehet törölni), `name` (json, mint
  fent), `image` (string, nullable, `cities/{id}/...`), timestampek.
- **`programs`**: + `country_id` (nullable FK, ország törlésekor `NULL`),
  + `city_id` (nullable FK, város törlésekor `NULL`).
- Konzisztencia: a "program városa a program országához tartozik" és a "város
  csak országgal együtt" szabályt az adatbázis nem kényszeríti ki; a 16. feature
  validálja (az adminok megbízhatók). A törlési szabályok ezt nem rontják el:
  város törlése csak a `city_id`-t nullázza, ország pedig csak városok nélkül
  törölhető.
- Az `image` a DB-ben nullable; hogy az admin űrlapon kötelező-e, a 14/15.
  feature dönti el.
- A képmappa törlése a `deleted` eseményen történik (nem `deleting`), hogy egy
  FK miatt meghiúsult törlés ne vigye el a fájlokat.

## Testing

Nincs deklarált `Test` parancs (`AGENTS.md`), így automatizált teszt nem kapu és
nem készül. Bizonyíték: a migrációs körök (migrate / rollback / migrate) a helyi
MySQL-en és SQLite-on, valamint a 2. lépés `tinker`-ellenőrzése. Böngészős
vagy vizuális bizonyíték nem releváns (nincs UI).

## Notes for the AI

- A helyi MySQL a feature tervezésekor nem futott (`migrate:status`: kapcsolat
  visszautasítva). Az 1. lépés előtt a felhasználónak el kell indítania a XAMPP
  MySQL-t; ha nem fut, állj meg és kérd, ne csak SQLite-on igazolj.
- A helyi `justlookabook` adatbázis az éles adatok másolata; ne futtass
  `migrate:fresh`-t vagy `db:wipe`-ot rajta.
- Az éles séma (`../justlookabook_2026_11_17.sql`) `programs` táblája egyezik a
  migrációkkal; `countries`/`cities` tábla még nincs.
- Stílus: igazított `=>` oszlopok, magyar kódkommentek, Pint ne fusson egész
  fájlokra. A migrációk és modellek a meglévők (`Topic`, `ProgramImage`,
  pivot-migráció) mintáját kövessék.
- A `project-overview.md` adatmodell-szakasza generált; a változást `/overview`
  vezeti át, kézzel ne szerkeszd.


<!-- blueprint:completion {"schemaVersion":2,"specBytes":7751,"specSha256":"6d0dfa3b38ea6e2dc389499865d8c88bbbff3f39012699445695d3eb0dfb54d1","branch":"refs/heads/feature/orsz-g-s-v-ros-adatmodell","head":"208a3d8b2b15bcff9f01616c45266316ec938b76","baseRef":"refs/heads/main","baseCommit":"ecda7681d7e40a27b347788ee232d0cc284f60ae","sourceTree":"56563f6a2b4e2186043eb6587ca09e260360dd42","landing":"local-merge","absentOptional":[]} -->

## Independent review

**Status:** passed
**Target commit:** 208a3d8b2b15bcff9f01616c45266316ec938b76
**Base commit:** ecda7681d7e40a27b347788ee232d0cc284f60ae
**Base ref:** main
**Spec hash:** 6d0dfa3b38ea6e2dc389499865d8c88bbbff3f39012699445695d3eb0dfb54d1
**Prepared by:** claude
**Builder model:** claude-opus-5-5
**Requested reviewer:** claude
**Requested model:** claude-opus-5-5
**Requested execution:** automatic
**Requested at:** 2026-11-18T17:18:27+01:00
**Workflow:** regular
**Check required:** no
**Reviewer adapter:** claude
**Reviewer model:** claude-opus-5-5
**Reviewer context:** fresh subagent
**Actual execution:** automatic
**Reviewed at:** 2026-11-18T17:21:14+01:00
**Scope:** current
**Lenses:** quality, security, performance, tests
**Verdict:** passed
**Check result:** not-required

## Commands

- `git rev-parse HEAD` / `git merge-base main HEAD` / `git status --porcelain --untracked-files=all` / `sha256sum blueprint/context/current-feature.md`: pass (HEAD = target, merge base = base, only review.md dirty, spec hash matches)
- `php -l` on `app/Models/Country.php`, `City.php`, `Program.php`: pass
- `php artisan migrate --force` (throwaway SQLite in scratchpad via `DB_CONNECTION`/`DB_DATABASE` env, `.env` untouched): pass
- `php artisan migrate:rollback --step=3 --force`, then `php artisan migrate --force` (same throwaway SQLite): pass
- `php artisan migrate:rollback --step=1 --force` + `migrate --force` with seeded `programs`, `program_topic`, `program_images` rows (throwaway SQLite): pass, row counts unchanged (1/1/1)
- Scratchpad PHP probe of relations, `getTranslation()`, `imageUrl()`, FK delete rules and `deleted` image-folder cleanup (throwaway SQLite, public disk redirected to scratchpad): pass, 16/16 assertions
- `php artisan migrate:status` and `php artisan db:table programs` (local MySQL, read-only): pass, three new migrations in batch 4, FKs `set null` on delete with btree indexes
- `php artisan test`: fail (pre-existing default `ExampleTest` feature test, "no such table: programs"; no `Test` command declared, not a gate)

## Evidence

- Code delta reviewed: `app/Models/Country.php`, `app/Models/City.php`, `app/Models/Program.php`, `database/migrations/2026_11_18_100000_create_countries_table.php`, `..._100001_create_cities_table.php`, `..._100002_add_country_and_city_to_programs_table.php`; docs delta: `AGENTS.md`, `blueprint/build-plan.md`, `blueprint/project-plan.md`, `blueprint/context/coding-standards.md`, `blueprint/context/project-overview.md`, `blueprint/context/current-feature.md`.
- Mass assignment: `AdminProgramController::store`/`update` (app/Http/Controllers/Admin/AdminProgramController.php:92, :147) pass only `$request->validate()` output to `Program::create`/`update`; neither validates `country_id`/`city_id`, so the new `$fillable` fields are not request-writable before feature 16. No other `Program` fill path (`HasCrudActions` is not used for programs).
- Delete rules: deleting a country with cities throws `QueryException` and keeps its image folder (event is `deleted`, not `deleting`); deleting a city nulls `programs.city_id` and keeps `country_id`; deleting a country nulls `programs.country_id`; image folders are removed only after a successful delete.
- Translation fallback: empty `it` returns the `hu` name; `imageUrl()` returns `null` without an image and `Storage::url()` otherwise, matching `Topic`/`ProgramImage` patterns.
- Performance: no new queries on public or admin hot paths; MySQL FK columns are indexed. Tests: no `Test` command is declared, the spec declares migration runs and tinker as evidence, no skipped/focused tests in `tests/`.
- No em dashes in the new code or spec; Hungarian comments and aligned style match the coding standards.

## Findings

- None (no new findings in this delta; ledger unchanged. Existing F-01 [P2] open concerns the settings migration in the base commit, outside this delta, and was not re-examined)

## Remaining risk

- `php artisan test` is not a usable signal: no `Test` command declared and the default suite is red; model and migration behavior has no automated regression test.
- MySQL migrate/rollback cycle was not re-run by the reviewer (local DB is a live-data copy); MySQL evidence is the read-only `migrate:status`/`db:table` output only.
- `/check` was not required and was not run; no Lint command is declared.
- City-belongs-to-program's-country consistency is not enforced in the DB by design; feature 16 must validate it.
- Live deploy batching of the adopted settings migration (F-01) still applies to feature 20.

## Try it now

Run or do:

    php artisan migrate:status
    php artisan db:table cities

Expected:

The three new migrations end in `Ran`:

    2026_11_18_100000_create_countries_table .. [4] Ran
    2026_11_18_100001_create_cities_table .. [4] Ran
    2026_11_18_100002_add_country_and_city_to_programs_table .. [4] Ran

`db:table cities` lists `id`, `country_id`, `name`, `image` (nullable), `created_at`, `updated_at`, and the foreign key `cities_country_id_foreign country_id references id on countries .. restrict / restrict`.

This proves:

The country/city data layer exists on the local database (a copy of live data), and a country with cities cannot be deleted. Both commands are read-only.
