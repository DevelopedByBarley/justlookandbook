# Fix: Settings migration fails on existing table

**Type:** Fix
**Status:** verified
**Branch:** fix/settings-migration-fails-on-existing-table

## The problem

A `2026_05_10_000001_create_settings_table` migráció a helyi adatbázisban
**Pending** állapotú (`php artisan migrate:status`), pedig a `settings` tábla
már létezik, és a sémája pontosan egyezik a migrációval (`key` varchar PK,
`value` text nullable, `created_at`/`updated_at`). A `migrations` táblából csak
ez a sor hiányzik; a tábla valószínűleg kézzel vagy adatbázis-importtal jött
létre.

Következmény: a `php artisan migrate` ezzel a migrációval kezd, `Table
'settings' already exists` hibával leáll, így egyetlen új migráció sem fut le.
Ez blokkolja a 13. feature-t (ország és város adatmodell), és az élesítést
(20.) is, ha az éles adatbázis ugyanilyen állapotban van (nem ellenőrizhető
innen; a helyi adatbázis valószínűleg az éles másolata).

## The fix

A migráció `up()` metódusa ne próbálja újra létrehozni a már meglévő táblát:
az elején `Schema::hasTable('settings')` esetén térjen vissza. A `down()`
változatlan.

Miért ez, és nem csak egy helyi `migrations`-sor beszúrása:

- Ugyanaz a kód javítja a helyi és az esetleg ugyanígy álló éles adatbázist
  is, kézi adatbázis-művelet nélkül; az élesítés sima `php artisan migrate`
  maradhat.
- A séma nem változik. A "lefutott migrációt ne szerkessz" szabály azért van,
  mert a módosítás nem jutna el oda, ahol már lefutott; itt ott valóban nincs
  hatása (nem fut újra), ahol pedig függőben van, ott épp ez a cél. Friss
  telepítésen a tábla nem létezik, így a migráció ugyanúgy létrehozza.

Nem szabad elrontani: a meglévő `settings` adatokat (`whatsapp_phone`) és a
többi migráció sorrendjét.

## Build steps

- [x] 1. A guard hozzáadása a `database/migrations/2026_05_10_000001_create_settings_table.php` `up()` elejére, egy rövid magyar megjegyzéssel a miértről.
  **Done when:** `php artisan migrate` hibátlanul lefut, a `migrate:status` a
  settings migrációt `Ran` állapotban mutatja, és a `settings` tábla sorai
  (köztük `whatsapp_phone`) változatlanok.

## Verify

1. Előtte: `php artisan tinker --execute="dump(DB::table('settings')->get()->toArray());"` - jegyezd fel a sorokat.
2. `php artisan migrate` - nincs hiba.
3. `php artisan migrate:status` - minden migráció `Ran`, nincs `Pending`.
4. Az 1. pont parancsa újra ugyanazokat a sorokat adja.
5. Az admin `/admin/settings` oldalon a WhatsApp-szám ugyanaz, mint előtte.
6. Friss telepítés: `php artisan migrate:fresh --database=sqlite` egy eldobható SQLite fájlon (vagy `DB_CONNECTION=sqlite DB_DATABASE=:memory:`) - a `settings` tábla létrejön. A helyi MySQL-en `migrate:fresh`-t ne futtass.

Tesztparancs nincs deklarálva (`AGENTS.md`), ezért a fenti kézi ellenőrzés a bizonyíték.

Élesítéskor (20. feature): előbb `php artisan migrate:status` az élesen; ha ott is `Pending` a settings migráció, ez a javítás kezeli.


<!-- blueprint:completion {"schemaVersion":2,"specBytes":3077,"specSha256":"6c9540719c7cf095b73dab98392c4033e3b6dcc1107ebfbcdb529c6e723181cb","branch":"refs/heads/fix/settings-migration-fails-on-existing-table","head":"a324ba1aab113e0a5382cb0266d951b194609c4a","baseRef":"refs/heads/main","baseCommit":"d3191d998e9ffefea612ac0a45477157ca7ebaf8","sourceTree":"575c33175761a23da41dff38ac16c075e0b55cb8","landing":"local-merge","absentOptional":[]} -->

## Independent review

**Status:** passed
**Target commit:** a324ba1aab113e0a5382cb0266d951b194609c4a
**Base commit:** d3191d998e9ffefea612ac0a45477157ca7ebaf8
**Base ref:** main
**Spec hash:** 6c9540719c7cf095b73dab98392c4033e3b6dcc1107ebfbcdb529c6e723181cb
**Prepared by:** claude
**Builder model:** claude-opus-5-5
**Requested reviewer:** claude
**Requested model:** claude-opus-5-5
**Requested execution:** automatic
**Requested at:** 2026-11-18T12:20:47Z
**Workflow:** regular
**Check required:** no
**Reviewer adapter:** claude
**Reviewer model:** claude-opus-5-5
**Reviewer context:** fresh subagent
**Actual execution:** automatic
**Reviewed at:** 2026-11-18T12:23:56Z
**Scope:** current
**Lenses:** quality, security, performance, tests
**Verdict:** passed
**Check result:** not-required

## Commands

- `git rev-parse HEAD` / `git merge-base main <target>` / `sha256sum blueprint/context/current-feature.md` / `git status --porcelain=v1 -uall`: pass (HEAD, merge base, and spec hash match the request; only `blueprint/context/review.md` was dirty)
- `DB_CONNECTION=sqlite DB_DATABASE=<scratchpad file> php artisan migrate --force` (fresh, throwaway SQLite): pass (all 12 migrations ran; `settings` created with `key`, `value`, `created_at`, `updated_at`)
- Same SQLite DB with the settings `migrations` row deleted and a `whatsapp_phone` row seeded, then `php artisan migrate --force` + `migrate:status`: pass (no error, settings migration `Ran` in batch 2, row unchanged)
- `php artisan migrate:rollback --force` on the same SQLite DB: ran; confirmed that the adopted table is dropped on rollback (F-01)
- Test command: unavailable (none declared in `AGENTS.md`)
- Lint command: unavailable (none declared in `AGENTS.md`)

## Evidence

- Delta `d3191d9..a324ba1`: `database/migrations/2026_05_10_000001_create_settings_table.php` (+5 lines, `Schema::hasTable('settings')` early return with a Hungarian comment) and the tracked spec `blueprint/context/current-feature.md`; matches the spec's single build step and leaves `down()` unchanged as specified
- No `bootstrap/cache/config.php`, and `.env` has no `DB_URL`, so the env overrides reached the SQLite connection only; the MySQL database was not touched
- Security: no new input, route, or auth surface. Performance: one `hasTable` query per migrate run. Tests: no test command declared, migration change verified by hand on SQLite
- `blueprint/context/coding-standards.md` is still the untuned Next.js/Prisma template, so no Laravel standard applied; the change follows the existing migration style

## Findings

- F-01 [P2] open - Rolling back the adopted settings migration drops the pre-existing table and its data (non-blocking)

## Remaining risk

- Test command unavailable: no `Test` command is declared in `AGENTS.md` (Pest is installed but the suite is red), so there is no automated regression coverage
- Lint command unavailable: no `Lint` command is declared in `AGENTS.md`
- Check was not required and was not run. The spec's MySQL verification steps (local `migrate`, `migrate:status`, settings rows, `/admin/settings` page) were not repeated by the reviewer, which ran nothing against MySQL
- The guard checks only that the table exists, not its schema. A live database whose `settings` table has a different schema would pass silently. The live database state is unverified (spec: run `migrate:status` there first at feature 20)
- F-01 rollback data-loss path on the live database if the settings migration is batched together with later migrations

## Try it now

Run or do:

    php artisan migrate:status

Expected:

Every row ends in `Ran`, none in `Pending`, including:

    2026_05_10_000001_create_settings_table .. [3] Ran

This proves:

The settings migration is recorded as run on the local database whose `settings` table already existed, so `php artisan migrate` no longer stops on it.
