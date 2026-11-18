# AGENTS.md

Instructions for AI coding agents working in this project. This is the cross-tool
entry point. Codex, Google Antigravity, and OpenCode read `AGENTS.md`. Other
compatible tools do too. Claude Code reads `CLAUDE.md`, which imports this file,
so there is a single source of truth.

Claude Code, Codex, and every other AI tool must not add AI attribution to
commits or pull requests, including AI `Co-Authored-By` trailers or generated-by
signatures. Preserve genuine human attribution. See
[Commit and PR attribution](blueprint/context/ai-interaction.md#commit-and-pr-attribution)
for optional tool settings.

## What this is

**Just Look and Book** (justlookandbook.com): a multilingual (hu/en/it) program
catalog built with Laravel 13, Blade, Alpine.js, and Tailwind CSS v4. Visitors
browse programs by topic and book via WhatsApp, Messenger, Viber, or the contact
form; admins manage programs, topics, and settings in a separate admin area.

This project is built with the **AI Blueprint**, a workflow layer, not an
app skeleton. To start a new project, scaffold the app first in an empty folder
(create-next-app, Vite, etc.), then overlay these files on top. Never run a
framework scaffolder inside a directory that already holds the blueprint files
(`AGENTS.md`, `CLAUDE.md`, `.agents/`, `.claude/`, `blueprint/`); it fails
because the directory isn't empty.

The workflow is defined by the local skills and context files below.

## Proportional engineering

Build for established requirements, not hypothetical scale, threats, or future
flexibility. Reuse existing code, the standard library, native platform features,
and installed dependencies before adding machinery.

- Unknown scale or extensibility defaults to the smaller reversible design. Do
  not infer enterprise, multi-tenant, hostile-user, or compliance requirements.
- Derive trust and data-integrity boundaries from actual reachability: untrusted
  input, auth/session/ownership, shared persisted data, destructive operations,
  payments, secrets, and sensitive data.
- Ask only when an unknown materially changes behavior, architecture, persisted
  data, interoperability, a real security boundary, or cost. Otherwise choose the
  simplest repository-native implementation.
- Add an abstraction, dependency, service, configuration surface, compatibility
  layer, or security mechanism only for a current requirement.
- Simplicity never removes real trust-boundary validation, data-loss prevention,
  accessibility, explicit security requirements, configured tests, or project rules.
- Stack-specific template standards apply only when the project uses that stack.

## Read these when relevant

- `blueprint/config.json` - deterministic project workflow settings
- `blueprint/workflow.md` - workflow reference for configuration, skills, dashboard activity, and checks
- `blueprint/context/project-overview.md` - the project's source of truth
- `blueprint/context/coding-standards.md` - read before changing code
- `blueprint/context/ai-interaction.md` - read when running the Blueprint workflow
- `blueprint/context/current-feature.md` - the one feature or fix being built in this checkout

Reuse relevant context already loaded in the session. Claude Code imports only
this file; its Blueprint skills load the other files on demand.

## Workflow

Build one feature or fix at a time, behind review gates. The feature
loop is `/feature` -> `/implement` -> `/check` -> `/audit current` ->
`/complete`; approve the Feature spec before Implement. Each command is a local
skill at `.agents/skills/<skill>/SKILL.md` or `.claude/skills/<skill>/SKILL.md`
that carries its own instructions. Claude Code and Google Antigravity use slash
commands such as `/feature`, Codex uses `$feature`, and other tools can follow
the matching `SKILL.md` when asked.

Skills: `adopt`, `audit`, `blueprint-doctor`, `blueprint-status`, `brief`,
`check`, `ci`, `complete`, `debug`, `discovery`, `explore`, `feature`, `fix`,
`implement`, `onboard`, `overview`, `prototype`, and `tests`, plus the
explicit-only `autopilot` and `continuous`.

- Keep one active work item per checkout. Parallel work needs its own clone or
  Git worktree and branch; never replace another worker's active spec.
- `blueprint/config.json` can make review or verification stricter and tune
  local workflow settings. It never grants permission to commit, merge, push,
  deploy, publish, send, delete data, waive a failing check, or accept a finding.
- Never create or edit `blueprint/.state/run.json` directly. Tracked skills
  record dashboard activity through the managed helper.

## Commands

<!-- blueprint:onboarding-required -->
Laravel 13 (PHP 8.3+, Composer) with Vite 8 (npm). Run from `laravel-app/`.

- Setup: `composer setup` (install, `.env`, key, migrate, npm install, build)
- Dev: `composer dev` (`php artisan serve` + queue listener + Pail logs + Vite);
  locally the site also runs under XAMPP, then `npm run dev` is enough for assets
- Build assets: `npm run build`
- Migrations: `php artisan migrate`

The web root is `../justlookandbook.com/` (set via `usePublicPath` in
`bootstrap/app.php`), not `laravel-app/public/`.

No `Test` command is declared yet. Pest 4 is installed (`php artisan test`), but
the suite only has the default example tests and is currently red, so testing
is not a gate. Run `/tests` or `$tests` to adopt it and add the real `Test`
command here.

No `Lint` command is declared: Laravel Pint is installed, but the codebase does
not follow its default preset (see `blueprint/context/coding-standards.md`).

Browser testing is also opt-in. Run `/tests browser` or `$tests browser` to add
or normalize a browser harness and document its exact command as `Browser
tests`. Check and Continuous Mode can then reuse it without installing tooling
mid-feature.
