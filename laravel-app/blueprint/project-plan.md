# Project Plan

> Brownfield adoption (`/adopt`, 2026-11-17). The sections describe the code
> that already exists. The project will be reworked feature by feature as the
> owner directs; new direction goes into `build-plan.md`.

## 1. Problem - What problem are we solving?

A **Just Look and Book** (justlookandbook.com) egy programkatalógus-oldal: a
látogatók témák szerint böngészhetik a szervezett programokat (árak, időtartam,
elérhető napok, galéria, videó), és egy programot chaten (WhatsApp, Messenger,
Viber) vagy a kapcsolati űrlapon keresztül foglalhatnak le. Az üzemeltető egy
admin felületen kezeli a programokat, a témákat és a kapcsolati beállításokat.

## 2. Users - Who is this for?

- **Látogatók / vendégek:** turisták, akik programot keresnek és foglalnának;
  magyar, angol és olasz nyelven.
- **Adminisztrátor(ok):** az üzemeltető, aki a programokat, témákat és a
  WhatsApp-számot karbantartja.

## 3. Features - What does the MVP need?

Már kész és élesben futó funkciók:

- Nyilvános programlista témaszűréssel, lapozással, "népszerű" jelöléssel (top 3 megtekintés).
- Program adatlap: galéria, felnőtt/gyerek ár, korhatár, időtartam, elérhető napok, YouTube-videó, megtekintésszámláló.
- Háromnyelvű felület (hu/en/it), munkamenet-alapú nyelvválasztó, fordítható adatmezők magyar fallbackkel.
- Foglalás/érdeklődés chat-widgetekkel (WhatsApp, Messenger, Viber), előtte ÁSZF-elfogadó modállal.
- Kapcsolati űrlap: validáció, honeypot, rate limit, SMTP-küldés, hibaértesítő e-mail (sikertelen küldés, 419, 429, 500).
- Admin: külön `admin` guard, belépés, jelszó-visszaállítás e-mailben, jelszócsere.
- Admin dashboard statisztikákkal és belső kézikönyvvel (handbook).
- Admin témakezelés (CRUD) és programkezelés (CRUD, Tiptap szövegszerkesztő, max. 7 kép borítóképpel, drag-and-drop sorrend).
- Admin beállítások: WhatsApp-telefonszám.
- SEO és megfelelés: `sitemap.xml`, meta leírások, sütihozzájárulás, biztonsági fejlécek, statikus hibaoldalak.
- Licencrendszer (licencszerver API + `CheckLicense` kliens middleware), jelenleg kikapcsolva.

Következő átalakítás (árajánlat 2026-11-03 + redesign dokumentum 2026-10-30;
döntések és nyitott kérdések: `build-plan.md` 13-20): ország / város rendszer.
A programok (nem kötelezően) egy országhoz és egy városhoz köthetők, az admin
kezeli az országokat és a városokat (3 nyelvű név, egy kép), a látogató első
látogatáskor kihagyhatóan, felugró ablakban helyet választ, ezt a böngésző
megjegyzi, és a programlista meg a témák a kiválasztott helyre szűrődnek.

## 4. Data - What are we storing?

- `programs`: slug, név/leírás/időtartam (JSON, nyelvenként), felnőtt és gyerek ár, gyerek korhatár, elérhető napok, csoportlétszám, YouTube-link, aktív, megtekintések, sorrend.
- `program_images`: programonként képek, borítókép-jelzővel, sorrenddel (`public` disk, `programs/{id}/`).
- `topics`: slug és név (JSON, nyelvenként), `program_topic` pivot táblán keresztül kapcsolódik a programokhoz.
- `admins` és `admin_password_reset_tokens`: adminfiókok.
- Tervezett: `countries` (3 nyelvű név, egy kép) és `cities` (3 nyelvű név, egy kép, ország FK); a `programs` nem kötelező ország- és városmezőt kap.
- `settings`: kulcs-érték beállítások (jelenleg `whatsapp_phone`).
- `licenses`: licenckulcs, ügyfélnév, aktív, lejárat.
- Laravel alaptáblák: `users` (nem használt), `sessions`, `cache`, `jobs`.

Foglalási adatot az alkalmazás nem tárol; a foglalás chaten vagy e-mailben zajlik.

## 5. Tech - What stack are we using?

- PHP 8.3+ (helyben 8.4), Laravel 13, MySQL (helyben XAMPP).
- Blade nézetek és komponensek, Alpine.js, Tailwind CSS v4 (Vite 8, `laravel-vite-plugin`).
- Tiptap 3 rich text szerkesztő az admin programleírásokhoz.
- Session, cache és queue: `database` driver. Levelezés: SMTP élesben.
- Pest 4 telepítve, de a tesztelés még nincs bevezetve (lásd `coding-standards.md`).

## 6. Monetize - How will this make money?

Az oldal nem kezel fizetést; a foglalás chaten vagy e-mailben zajlik.

## 7. UI/UX - How should this look and feel?

Világos, barátságos, turisztikai hangulatú felület (emoji-akcentusok,
nagy képek, kártyás programlista). Mobilbarát, lebegő chat-widgetekkel. Az admin
felület egyszerű, magyar nyelvű, oldalsávos elrendezés.

## 8. Deployment - Where and how will this ship?

- Éles domain: `https://justlookandbook.com/`.
- Megosztott tárhely-szerű elrendezés: az alkalmazás a `laravel-app/`
  mappában van, a webgyökér a mellette lévő `justlookandbook.com/` mappa
  (`bootstrap/app.php` ezt `usePublicPath`-ként állítja be).
- Build: `npm run build` (Vite), a kimenet a webgyökérbe kerül.
- Env változók (név szerint): `APP_*`, `DB_*`, `MAIL_*`, `SESSION_*`,
  `WHATSAPP_PHONE`, `LICENSE_API_URL`, `LICENSE_KEY`.
- Health check: `/up`.

## 9. Usage model and constraints (optional)

- Internetre nyitott, nyilvános oldal; a látogatók névtelenek.
- Az adminok megbízható felhasználók: az admin által írt programleírás HTML-ként
  jelenik meg (`{!! !!}`).
- Egyetlen üzemeltető, nem multi-tenant.
