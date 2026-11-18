# Findings

> **Generated file.** The findings ledger: review findings raised by `/audit`
> against the work in progress, each with a durable ID, severity (P0-P3), and
> status. `/implement` marks repaired findings `fixed`, a later `/audit` pass
> moves them to `closed`, and `/complete` refuses to land while any P0 or P1
> finding is `open` or `fixed`, then archives resolved findings with the work
> and resets this file.

### F-01 [P2] open - Rolling back the adopted settings migration drops the pre-existing table and its data

**File:** database/migrations/2026_05_10_000001_create_settings_table.php:24
**Found:** 2026-11-18 by /audit independent (scope: current; lens: quality, security, performance, tests)
**Why it matters:** When `up()` hits the new `Schema::hasTable('settings')` guard it creates nothing, but the migration is still recorded in the current batch, and the unchanged `down()` still runs `Schema::dropIfExists('settings')`. Reproduced on a throwaway SQLite DB: with a pre-existing `settings` table holding a `whatsapp_phone` row, `php artisan migrate` recorded the settings migration in a new batch (batch 2) and kept the row. A following `php artisan migrate:rollback` then dropped the whole table (`Schema::hasTable('settings')` became false). On the live database (feature 20), a plain `php artisan migrate` run alongside feature 13's new migrations puts all of them in one batch. A later `migrate:rollback` to undo feature 13 would then also delete the live settings data, which the spec says must not be harmed ("Nem szabad elrontani: a meglévő `settings` adatokat"). The spec chose to keep `down()` unchanged, so this is a gap in a deliberate design and only an operator rollback can trigger it. It does not block this fix.
**Suggested fix:** Smallest option, no code change: when the live database is in this state, run the deploy with `php artisan migrate --step`, or run `migrate` once before feature 13's migrations exist. Either way the adopted settings migration gets its own batch and a feature rollback never reaches it. Note this in the feature 20 deploy steps. Changing `down()` (for example making it a no-op) changes shipped rollback behavior on fresh installs and needs an explicit user decision. Current requirement lost by the operational fix: None.
**Resolution:**
