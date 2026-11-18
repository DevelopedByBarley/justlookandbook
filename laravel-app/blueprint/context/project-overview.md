# Just Look and Book - Project Overview

<!-- blueprint:source-hash 186baa49eb00bd9677fb77639f2e1dc35aeafdf2d67a44199c0f2a707e76b5d5 -->

> Háromnyelvű (hu/en/it) programkatalógus chat- és űrlapalapú foglalással, külön admin felülettel. Élesben fut; a tulajdonos utasításai szerint funkcióról funkcióra alakul át.

## Problem

A látogatók témák szerint böngészhetik a szervezett programokat (árak, időtartam, elérhető napok, galéria, videó), és chaten (WhatsApp, Messenger, Viber) vagy kapcsolati űrlapon foglalhatnak. Az üzemeltető egy admin felületen kezeli a programokat, témákat és a kapcsolati beállításokat.

## Users

- **Látogatók** (névtelenek): programot keresnek és foglalnak; hu/en/it felület.
- **Adminok** (`admin` guard, megbízható felhasználók): programok, témák, WhatsApp-szám karbantartása.

## Usage model

- Nyilvános, internetre nyitott oldal; a látogatói bemenet nem megbízható.
- Az adminok megbízhatók: az admin által írt programleírás HTML-ként (`{!! !!}`) jelenik meg.
- Egyetlen üzemeltető, nem multi-tenant.
- Fizetést és foglalási adatot az alkalmazás nem kezel.

## Features

Mind a 12 kész és élesben fut (brownfield adoption, 2026-11-17). Új tételek 13-tól kerülnek a `build-plan.md`-be.

1. **Nyilvános programlista** - témaszűrés, lapozás, "népszerű" jelölés (top 3 megtekintés). *Fő funkció.*
2. **Program adatlap** - galéria, felnőtt/gyerek ár, korhatár, időtartam, napok, YouTube, megtekintésszámláló.
3. **Többnyelvűség** - hu/en/it, munkamenet-alapú nyelvválasztó, JSON-mezők magyar fallbackkel.
4. **Chat-alapú foglalás** - WhatsApp, Messenger, Viber widgetek ÁSZF-elfogadó modállal.
5. **Kapcsolati űrlap** - validáció, honeypot, rate limit, SMTP-küldés, hibaértesítő (küldési hiba, 419, 429, 500).
6. **Admin hitelesítés** - belépés, jelszó-visszaállítás e-mailben, jelszócsere; throttle-olt végpontok.
7. **Admin dashboard** - statisztikák, legutóbbi programok, belső kézikönyv.
8. **Admin témakezelés** - CRUD.
9. **Admin programkezelés** - CRUD, Tiptap szerkesztő, max. 7 kép borítóképpel, drag-and-drop sorrend.
10. **Admin beállítások** - WhatsApp-telefonszám.
11. **SEO és megfelelés** - `sitemap.xml`, meta leírások, sütihozzájárulás, biztonsági fejlécek, hibaoldalak.
12. **Licencrendszer** - licencszerver API + `CheckLicense` kliens middleware (kikapcsolva).

## Data model

Fordítható mezők: JSON oszlop, `array` cast, kulcs a locale (`hu`, `en`, `it`), olvasás `getTranslation()`-nel, `hu` fallbackkel. Ezt az alakot a nyilvános oldal és az admin űrlapok is használják; ne változtasd meg migráció nélkül.

### Program (`programs`)

- `slug` (string, unique)
- `name` (json, fordítható), `description` (json, fordítható HTML, nullable)
- `price_adult`, `price_child` (unsigned int, nullable)
- `child_age_min`, `child_age_max` (unsigned tinyint, nullable)
- `duration` (json, fordítható, nullable)
- `available_days` (json tömb, nullable)
- `group_size` (unsigned smallint, nullable)
- `youtube_embed` (string, nullable)
- `active` (bool, default true), `views` (unsigned bigint, default 0), `sort_order` (unsigned smallint)
- hasMany `ProgramImage` (`sort_order` szerint), belongsToMany `Topic` (`program_topic`)

### ProgramImage (`program_images`)

- `program_id` (FK, cascade delete), `path` (string, `public` disk, `programs/{id}/`)
- `is_cover` (bool), `sort_order` (unsigned smallint)
- Program törlésekor a fájlok is törlődnek (`booted()`).

### Topic (`topics`)

- `slug` (string, unique, mentéskor automatikus), `name` (json, fordítható)
- belongsToMany `Program`

### program_topic (pivot)

- `program_id`, `topic_id` (FK, cascade delete), összetett elsődleges kulcs

### Admin (`admins`) és `admin_password_reset_tokens`

- `name`, `email` (unique), `password`, `remember_token`

### Setting (`settings`)

- `key` (string, PK), `value` (text, nullable); `Setting::get()` / `Setting::set()`. Jelenleg: `whatsapp_phone`.

### License (`licenses`)

- `key` (string, unique), `client_name`, `active` (bool), `expires_at` (datetime, nullable)

Laravel alaptáblák: `sessions`, `cache`, `jobs`; a `users` tábla nem használt.

## Tech stack

- **PHP 8.3+ / Laravel 13** - slim `bootstrap/app.php` konfiguráció.
- **MySQL** - helyben XAMPP.
- **Blade + Alpine.js** - nézetek, anonim komponensek, interaktivitás.
- **Tailwind CSS v4 + Vite 8** - CSS-first konfiguráció.
- **Tiptap 3** - rich text az admin programleírásokhoz.
- **database** driver - session, cache, queue. **SMTP** - levelezés.
- **Pest 4** - telepítve, a tesztelés még nincs bevezetve.

## Monetization

Az oldal nem kezel fizetést; a foglalás chaten vagy e-mailben zajlik.

## UI/UX

Világos, barátságos, turisztikai hangulat (emoji-akcentusok, nagy képek, kártyás lista), mobilbarát, lebegő chat-widgetek. Az admin egyszerű, magyar nyelvű, oldalsávos.

- `/` - programlista témaszűréssel
- `/programs/{slug}` - program adatlap
- `POST /contact` - kapcsolati űrlap
- `/lang/{locale}` - nyelvváltás
- `/sitemap.xml`
- `/admin/login`, `/admin/password/*` - admin belépés és jelszó-visszaállítás
- `/admin/dashboard`, `/admin/handbook`
- `/admin/programs` (+ `/order`, `/reorder`), `/admin/topics`, `/admin/settings`, `/admin/profile/password`
- `GET /api/license/{key}` - licencszerver API

## Deployment

- Domain: `https://justlookandbook.com/`.
- Az app a `laravel-app/`-ban, a webgyökér a szomszédos `justlookandbook.com/` (`usePublicPath`).
- Build: `npm run build` (a kimenet a webgyökérbe kerül); migráció: `php artisan migrate`.
- Env: `APP_*`, `DB_*`, `MAIL_*`, `SESSION_*`, `WHATSAPP_PHONE`, `LICENSE_API_URL`, `LICENSE_KEY`.
- Health check: `/up`.
