# Coding Standards

> Adopted from the existing codebase on 2026-11-17 (`/adopt`). These rules
> describe what the code already does. Follow them for new work; do not
> restyle existing code unless a feature asks for it.

## PHP and Laravel

- PHP 8.3+, Laravel 13 with the slim `bootstrap/app.php` configuration
  (middleware, exception handling, and routing are configured there, no Kernel).
- Typed method signatures and return types (`View`, `RedirectResponse`,
  `JsonResponse`, relation types).
- Eloquent models with `$fillable` and `$casts`; model events in `booted()`
  (for example cleaning up images on program delete, auto-slug on topic save).
- Small static helpers on models are accepted (`Setting::get()` /
  `Setting::set()`, `License::generateKey()`).
- Plain controllers per resource. Simple CRUD can reuse
  `App\Http\Controllers\Admin\Concerns\HasCrudActions`.
- Validation inline with `$request->validate()` or `Validator::make()`, or a
  Form Request (`app/Http/Requests/Admin/`) when it is reused.
- Rate limiters are defined in `AppServiceProvider` and applied with
  `throttle:<name>` on the route.
- Shared non-HTTP logic goes in `app/Support/`.

## Routing

- `routes/web.php` holds the public home, sitemap, and language switch, and
  `require`s the feature route files: `routes/programs.php`,
  `routes/contact.php`, `routes/auth/*.php`, and `routes/admin/*.php`.
- Admin routes live under the `admin` prefix and `admin.` name prefix, behind
  `auth:admin` (guest pages behind `guest:admin`).
- Every route is named. Admin resources use `Route::resource(...)->except(['show'])`.

## Auth

- Admins use a separate session guard `admin` with the `Admin` model and
  `admins` provider. The default `web` guard and `users` table are unused.
- Admin login and password-reset endpoints are throttled.

## Localization

- Supported locales: `hu`, `en`, `it` (`App\Http\Middleware\SetLocale`); the
  chosen locale is stored in the session.
- UI strings live in `lang/{hu,en,it}/<area>.php` and are used via `__()`. Add
  every new key to all three files.
- Translatable model fields are JSON columns cast to `array`, keyed by locale,
  read through `getTranslation()` with Hungarian as the fallback.
- The admin UI and admin validation messages are in Hungarian only.

## Views and Frontend

- Blade views in `resources/views/<area>/`, reusable pieces as anonymous Blade
  components in `resources/views/components/` (`<x-layout>`,
  `<x-admin-layout>`, etc.).
- Interactivity with Alpine.js (`Alpine.data(...)` in `resources/js/app.js`)
  and small vanilla scripts; no SPA framework.
- Styling with Tailwind CSS v4, CSS-first config in `resources/css/app.css`. A
  few inline `style=""` attributes exist; prefer Tailwind classes for new code.
- Rich text (program descriptions) is edited with Tiptap via the
  `rich-editor` component and rendered with `{!! !!}`. This is acceptable only
  because admins are trusted; never render visitor input unescaped.

## Database

- MySQL. Schema changes only through new migrations in `database/migrations/`
  (never edit a migration that has already run in production).
- Many-to-many via pivot tables (`program_topic`).
- Uploaded files go to the `public` disk under a per-record folder
  (`programs/{id}/`).

## Error Handling

- User-facing forms redirect `back()` with errors and old input, and jump to
  the form with `withFragment()` when it sits lower on the page.
- Failures that would lose a visitor's message are logged and reported through
  `ContactFailureNotifier`, which never throws.
- Special-case HTTP errors (419, 429) for a route in `bootstrap/app.php`
  `withExceptions()`, not in the controller.

## Naming

- Classes PascalCase; admin controllers prefixed `Admin` (`AdminProgramController`).
- Methods and variables camelCase; constants SCREAMING_SNAKE_CASE.
- Views, components, and route files kebab-case (`admin-password.php`,
  `language-switcher.blade.php`); partials prefixed with `_` (`_form.blade.php`).
- Route names dotted (`admin.programs.index`, `contact.store`).

## Formatting

- Laravel Pint is installed but not applied: the codebase uses aligned `=>`
  and `=` columns that Pint's default preset would rewrite. Do not run Pint on
  whole files; match the surrounding style.
- Code comments are in Hungarian, matching the existing code.

## Testing

The blueprint installs no test runner; testing is opt-in at the project level.

Current state: **testing is not adopted yet.** Pest 4 (`pestphp/pest`,
`pest-plugin-laravel`) is installed and `php artisan test` runs, but the suite
holds only the two default example tests, and the feature example fails (no
`RefreshDatabase`, so the in-memory SQLite has no tables). No `Test` command is
declared in `AGENTS.md`, so tests are not a gate yet. Run `/tests` to adopt it.

**The opt-in switch is one signal: a `test` command in the Commands section of
`AGENTS.md`.** Declare one and **tests become a gate for logic-bearing steps**,
not an optional extra; leave it out and the loop verifies logic with the evidence
it already uses (run it, a screenshot, the build). Adding the runner is itself a
deliberate step, never a silent mid-step install. This is the single definition
of the switch; the skills and `ai-interaction.md` only point back here.

When `AGENTS.md` declares a `Verify` command, treat it as the umbrella automated
gate. It combines only the checks this project actually has. `/ci` owns Verify
and CI setup.

- **What to test (the scope rule):** logic where a wrong answer is possible -
  validation rules, slug builders, translation fallbacks, YouTube URL parsing,
  ordering, throttled or failure-handling flows. Pest feature tests over HTTP
  routes are the stack-native way to cover controllers.
- **What not to test:** pure visual layout and third-party widgets. Verify those
  with a screenshot and the build.
- **The gate (once a test command is declared):** a step that adds in-scope
  logic ships a passing test in the same diff, and the test command must be green
  before approval, checkpoint commits, and `/complete`.
- An empty suite should fail, not pass, so "no tests ran" never looks like "passed".
- Tests live in `tests/Feature` and `tests/Unit` (Pest), run via the project's
  test command, not a hardcoded tool name.

## Browser Verification

For UI and integration behavior, prefer real browser evidence over reading the
code and assuming it works.

- Browser automation is separately opt-in through `/tests browser`. None is set
  up yet.
- Without a `Browser tests` command, use the local site (XAMPP or
  `php artisan serve`), screenshots, and `npm run build` output as evidence. Do
  not add a runner silently in the middle of an unrelated feature.
- Check all three locales when a change touches visible text.

## Code Quality

- No new commented-out code. Existing disabled code (for example in
  `CheckLicense` and `bootstrap/app.php`) is left as is unless a feature
  removes it.
- No unused imports or variables in new code.
- Keep functions under 50 lines when possible.

## Comments

Write code that explains itself; comment only what the code cannot say.
Over-commenting is a common AI tell, so resist it.

- Comment the **why**, not the **what**. Delete any comment that restates the code.
- No banner/header blocks, section dividers, or step-by-step narration of obvious
  code. A file does not need a comment announcing each region.
- A comment earns its place only when it captures something the code can't: a
  non-obvious decision, a gotcha or workaround, why a value is what it is, or a
  link to a spec or issue.
- Prefer self-documenting names and small functions over explanatory comments.
- Keep doc comments minimal: a one-line purpose on an exported type or function is
  plenty; don't write PHPDoc that just repeats the signature.
- When in doubt, leave the comment out.

## Writing

- No em dashes (U+2014) in generated content: docs, comments, commit messages,
  READMEs, specs. They read as AI-generated.
- Use a hyphen for `term - description` separators; rephrase prose with commas,
  parentheses, or a colon. Avoid en dashes and the ellipsis character too.
