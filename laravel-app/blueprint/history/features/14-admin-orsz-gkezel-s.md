# Feature: Admin országkezelés

**From build-plan:** feature 14
**Build attempt:** 1
**Status:** verified
**Branch:** feature/admin-orsz-gkezel-s

## Goal

Az admin a saját felületén listázhatja, létrehozhatja, szerkesztheti és
törölheti az országokat (3 nyelvű név + egy kép), és az oldalsávból eléri ezt
a menüpontot (ajánlat 2.1). Ez az alapja a városkezelésnek (15), a program
ország-mezőjének (16) és a helyválasztónak (17).

## In scope

- `/admin/countries` erőforrás `show` nélkül: lista, létrehozás, szerkesztés, törlés.
- Név magyarul, angolul, olaszul; a meglévő témakezelés mintáját követve a
  `hu` és `en` kötelező, az `it` nem kötelező.
- Legfeljebb egy kép országonként, nem kötelező: feltöltés létrehozáskor vagy
  szerkesztéskor, csere a régi fájl törlésével, és szerkesztéskor a meglévő kép
  eltávolítása.
- Törlés csak városok nélkül: ha van város, barátságos hibaüzenet, nem
  adatbázis-kivétel.
- "Országok" menüpont az admin oldalsáv "Tartalom" csoportjában.
- A listában kép bélyegkép, a három név, a városok és programok száma.

## Out of scope

- Városkezelés (15), a program űrlap ország/város mezője (16), minden nyilvános
  oldali változás (17-19).
- Képtömörítés vagy átméretezés feltöltéskor (nyitott kérdés az áttekintésben,
  nem rendelték meg).
- Kiemelés, egyedi színek, húzható sorrend (2026-11-17 döntés szerint nem kellenek).
- Meglévő programok Egyiptomhoz rendelése (5.1, opcionális).
- Új nyilvános fordítási kulcsok: az admin felület csak magyar
  (coding-standards, Localization). A 4.1 itt a 3 nyelvű adatmezőket jelenti.
- Tesztkészlet bevezetése (nincs `Test` parancs).

## Build loop

- `workflow.stepReview: feature`: a lépések egymás után készülnek, a review a
  teljes feature végén, `/check` és `/audit current` előtt egyben történik.
- `workflow.checkpointCommits: disabled`: lépésenként nincs commit; a végső
  commitot a `/complete` készíti a `feature/admin-orsz-gkezel-s` ágon.
- Minden lépés után az admin (XAMPP alatt) működőképes marad, és
  `npm run build` hiba nélkül lefut, ha a lépés nézetet érintett.

## Build steps

- [x] **1. Útvonalak, controller váz, lista és menüpont**
  - `routes/admin/countries.php`: `Route::resource('countries', AdminCountryController::class)->except(['show']);`
    és a `require` a `routes/auth/admin.php` `auth:admin` csoportjában, a
    `topics.php` mellett.
  - `AdminCountryController::index()`: `Country::withCount(['cities', 'programs'])->orderBy('name->hu')->paginate(20)`.
  - `resources/views/admin/countries/index.blade.php` a `topics/index` mintájára:
    bélyegkép (vagy szürke helyőrző, ha nincs kép), Magyar / English / Italiano,
    Városok, Programok oszlop, Szerkesztés / Törlés. Üres állapot: "Még nincs
    egyetlen ország sem." A táblázat keskeny kijelzőn `overflow-x-auto`
    konténerben görgethető.
  - Oldalsáv: "Országok" link a Témák után, aktív állapot `admin.countries.*`-ra,
    a többivel egyező ikonnal és osztályokkal.
  - **Done when:** belépett adminként a `/admin/countries` megnyílik, az üres
    állapot látszik, a menüpont aktív; kilépve a `/admin/countries` a belépő
    oldalra irányít; `php artisan route:list --name=admin.countries` 6
    útvonalat mutat (index, create, store, edit, update, destroy).

- [x] **2. Létrehozás és szerkesztés képpel**
  - `app/Http/Requests/Admin/CountryRequest.php` (a store és update közösen
    használja): `name.hu` és `name.en` `required|string|max:100`, `name.it`
    `nullable|string|max:100`; `image` `nullable|image|mimes:jpg,jpeg,png,gif,webp|max:3072`
    (a programképekkel egyező szabály); `remove_image` `nullable|boolean`.
    Magyar mezőnevek (`attributes()`) és üzenetek (`messages()`: "Csak képfájl
    tölthető fel.", "A kép mérete legfeljebb 3 MB lehet.").
  - `store()`: `DB::transaction` alatt `Country::create(['name' => ...])`, majd
    ha van feltöltött kép, `$file->store("countries/{$country->id}", 'public')`
    és az `image` mező mentése. Ha a fájlmentés kivételt dob, az ország sem
    jön létre (a beküldött képpel együtt vagy semmi).
  - `update()`: név mentése; új kép esetén az új fájl mentése a
    `countries/{id}/` mappába, az `image` frissítése, majd a régi fájl törlése a
    `public` diszkről (csak sikeres mentés után). Ha nincs új kép és
    `remove_image` be van jelölve, a régi fájl törlődik és az `image` `null`
    lesz. Új kép és bejelölt eltávolítás együtt: az új kép nyer.
  - Nézetek: `create.blade.php`, `edit.blade.php`, `_form.blade.php` a
    `topics` mintájára, `enctype="multipart/form-data"`. A képmezőnek látható
    `<label for>` felirata van, `accept="image/*"`, a hibája a mező alatt
    (`@error('image')`), mellette "nem kötelező" címke. Szerkesztéskor a
    jelenlegi kép előnézete látszik, alatta "Új kép feltöltésével
    lecserélheted." és egy címkézett "Kép eltávolítása" jelölőnégyzet
    (`remove_image`); kép nélküli országnál egyik sem jelenik meg. Kiválasztott fájlnál Alpine
    `FileReader` előnézet, ahogy a program űrlapon.
  - Validációs hibánál a `old()` értékek visszakerülnek a névmezőkbe (a fájlt a
    böngésző nem őrzi meg; ezt a kép hibaüzenete alatt nem kell jelezni).
  - Sikeres mentés után átirányítás a listára "Ország sikeresen létrehozva." /
    "Ország sikeresen módosítva." flash üzenettel (az admin layout `success`
    blokkja).
  - **Done when:** hu vagy en név nélküli, nem képfájl és 3 MB-nál nagyobb kép
    beküldése magyar hibaüzenettel a mező alatt visszatér, a beírt nevek
    megmaradnak; kép nélküli létrehozás sikeres, a listában helyőrző látszik;
    képpel létrehozás után az ország a listában a bélyegképpel látszik, a fájl
    a `storage/app/public/countries/{id}/` alatt van, és a
    `Country::imageUrl()` URL-je betölt a böngészőben; képcsere után csak az
    új fájl marad a mappában; új kép nélküli szerkesztés a meglévő képet
    megtartja; "Kép eltávolítása" után a fájl törlődik és az `image` `null`.

- [x] **3. Törlés városvédelemmel**
  - `destroy()`: ha `$country->cities()->exists()`, átirányítás a listára
    `error` flash üzenettel: "Az ország nem törölhető, mert N város tartozik
    hozzá. Előbb töröld vagy helyezd át a városait." Egyébként `delete()`; a
    képmappát a modell meglévő `deleted` eseménye törli; "Ország sikeresen
    törölve." flash üzenet.
  - A lista törlés-megerősítése: "Biztosan törlöd?", és ha `programs_count > 0`,
    hozzáfűzve: "N program országa üres lesz." (a `programs.country_id`
    `nullOnDelete`, ezt a meglévő FK intézi).
  - A `cities.country_id` `restrict` FK marad a végső védelem; a controller
    előzetes ellenőrzése adja a felhasználóbarát üzenetet.
  - **Done when:** városok nélküli ország törlése után eltűnik a listából és a
    `countries/{id}/` mappa is törlődik; tinkerrel létrehozott városú ország
    törlésekor a fenti hibaüzenet jelenik meg, az ország és a képe megmarad;
    programhoz rendelt ország törlése után a program `country_id`-ja `NULL`.

- [x] **4. Asztali és mobil ellenőrzés, build**
  - `npm run build` hibátlan; a négy admin oldal (lista üresen és adatokkal,
    létrehozás, szerkesztés) asztali és keskeny (kb. 390 px) szélességben
    használható, a táblázat vízszintesen görgethető, nem lóg ki az oldalból.
  - **Done when:** a build lefut, és a fenti oldalak mindkét szélességben
    ellenőrizve vannak (képernyőkép, ha elérhető).

## Files / areas

- Új: `routes/admin/countries.php`
- Módosul: `routes/auth/admin.php` (`require` a `topics.php` mellé)
- Új: `app/Http/Controllers/Admin/AdminCountryController.php`
- Új: `app/Http/Requests/Admin/CountryRequest.php`
- Új: `resources/views/admin/countries/{index,create,edit,_form}.blade.php`
- Módosul: `resources/views/components/admin-sidebar.blade.php`
- Változatlan, felhasznált: `app/Models/Country.php` (`$fillable`, `name` cast,
  `deleted` esemény, `imageUrl()`, `cities()`, `programs()`),
  `resources/views/components/admin-layout.blade.php` (`success` / `error` flash)
- Nincs migráció: a séma a 13. feature-ben elkészült.

## Data / contracts

- Útvonalak (`auth:admin`, `admin.` névelőtag): `GET /admin/countries`
  (`admin.countries.index`), `GET /admin/countries/create`, `POST
  /admin/countries`, `GET /admin/countries/{country}/edit`, `PUT
  /admin/countries/{country}`, `DELETE /admin/countries/{country}`; route model
  binding `id` alapján (nincs slug).
- `countries.name`: JSON `{"hu": string, "en": string, "it": string|null}`; az
  `it` üresen `null`-ként mentődik (a `ConvertEmptyStringsToNull` miatt), az
  olvasás `getTranslation()` `hu` fallbackkel. A lista `name['it'] ?: '—'`
  formában jelenít meg.
- `countries.image`: nem kötelező (2026-11-18 döntés); `null` vagy a `public`
  diszkhez relatív út, `countries/{id}/<hash>.<ext>` (Laravel `store()`
  generált név). Mentés után a mappában legfeljebb egy fájl van. A
  helyválasztónak (17) és a lista tetejének kép nélküli helyet is kezelnie kell.
- Sorrend: `name->hu` szerint növekvő (a "név szerint" feltételezés; a
  nyilvános locale-függő sorrend a 17. feature TODO-ja).
- Megjelenítés: minden név `{{ }}` escape-pel; a kép `src` a
  `imageUrl()`-ból, `alt` a magyar név.
- Jogosultság: csak `auth:admin`; az adminok megbízhatók, nincs tulajdonosi
  vagy tenant-határ. CSRF a meglévő `web` middleware-ből.

## Testing

- Nincs deklarált `Test` vagy `Verify` parancs; a Pest készlet nincs bevezetve
  (`verification.logicTests: when-configured`), ezért ez a feature nem ír
  teszteket.
- Bizonyíték: kézi ellenőrzés a helyi XAMPP oldalon belépett adminként a
  lépések "Done when" pontjai szerint, `php artisan route:list
  --name=admin.countries`, a `storage/app/public/countries/` mappa tartalma,
  tinker a városos és programos törlési esetekhez, és `npm run build`.
- Böngésző-automatizálás nincs; képernyőkép csak akkor, ha elérhető, és nem
  állítunk olyan vizuális bizonyítékot, ami nem futott.

## Notes for the AI

- A `topics` CRUD a minta (controller, nézetek, osztályok, flash szövegek); a
  `HasCrudActions` trait nem illik, mert képkezelés és egyedi törlésvédelem
  kell.
- A `CountryRequest` store/update különbségét a `$this->isMethod('post')`
  dönti el; ne legyen két külön request osztály.
- A kép tárolásához az `id` kell, ezért létrehozáskor előbb a rekord, utána a
  fájl, egy tranzakcióban.
- A kép nem kötelező (felhasználói döntés, 2026-11-18). A 15. feature
  városképére ugyanez a döntés vonatkozik-e, azt a 15. specje rögzíti.
- A régi kép törlése a `Storage::disk('public')->delete($old)`; a mappát csak a
  modell `deleted` eseménye törli.
- Az admin szövegek, validációs üzenetek és kódkommentek magyarul.
- A webgyökér `../justlookandbook.com/`; a `storage` link ott már létezik, a
  `Storage::url()` erre mutat. Ha egy feltöltött kép mégsem tölt be, az a
  `storage:link` beállítás hibája, nem ennek a feature-nek a része: állj meg és
  jelezd.
- A `project-overview.md` a munkafában módosított (nem commitolt) állapotú;
  ne írd felül, és ne vedd fel ennek a feature-nek a diffjébe.


<!-- blueprint:completion {"schemaVersion":2,"specBytes":11656,"specSha256":"33aec0dc687de66bf9d36a750f1c0f97fdd9a9726cf159962b490cf7ef2674d9","branch":"refs/heads/feature/admin-orsz-gkezel-s","head":"053f943da2e18da8f1ccbf04888b35c2548da3b5","baseRef":"refs/heads/main","baseCommit":"053f943da2e18da8f1ccbf04888b35c2548da3b5","sourceTree":"5cbe80551c9f7066c00070ac6bd55ac4d42d107b","landing":"local-merge","absentOptional":[]} -->

## Commands

- `php -l app/Http/Controllers/Admin/AdminCountryController.php` / `php -l app/Http/Requests/Admin/CountryRequest.php`: pass
- `php artisan route:list --name=admin.countries`: pass, 6 routes (index, create, store, edit, update, destroy)
- `php artisan view:cache`: pass (all Blade templates compile)
- Temporary Pest feature test (in-memory SQLite, `Storage::fake('public')`), deleted after the run as the spec adds no tests: pass, 6 tests / 55 assertions
- `npm run build`: pass (rerun during `/complete`)
- Desktop and ~390 px manual check of list (empty and with data), create and edit pages: confirmed by the user in chat during `/complete`

## Evidence

- Guest `GET /admin/countries` redirects to `admin.login`; logged-in admin sees the empty state and the active "Országok" sidebar item.
- Validation: missing `hu`/`en` name and non-image upload return Hungarian messages (`A(z) magyar név megadása kötelező.`, `Csak képfájl tölthető fel.`), a >3 MB image returns `A kép mérete legfeljebb 3 MB lehet.`, entered names come back via `old()`, and no country is created.
- Create without image stores `{"hu","en","it":null}` and the list shows the placeholder; create with image stores `countries/{id}/<hash>.<ext>` and the list shows the thumbnail.
- Image replace leaves exactly one file in `countries/{id}/` (new image wins over a ticked "Kép eltávolítása"); edit without a new image keeps it; "Kép eltávolítása" deletes the file and sets `image` to `null`; the edit page of an image-less country shows neither preview nor remove checkbox.
- Delete of a country with a city returns the `error` flash "Az ország nem törölhető, mert 1 város tartozik hozzá. ...", keeping the country and its image; after the city is removed, delete succeeds, `countries/{id}/` is gone, and the linked program's `country_id` is `NULL`. The list confirm text includes "1 program országa üres lesz.".
- `@error` rendering was proven by rendering `_form` with an explicit `ViewErrorBag`: with `session.serialization = json`, the array session driver used in tests keeps the error bag as a plain array between test requests (same for the existing topics form); real requests re-marshal it.

## Findings

- None raised for this feature. Existing F-01 [P2] open (settings migration rollback, feature 20 deploy note) stays in the ledger.

## Remaining risk

- No automated regression test remains: no `Test` command is declared and the default suite is red.
- `vite build` writes to `laravel-app/public/build`, while the served web root `../justlookandbook.com/build` holds an older build copied outside the repo. Until assets are copied there, the file-picker button styling (`file:` variants) and the checkbox focus ring are missing from the served CSS; layout classes are present.
- `/audit`, `/check` and independent review were not run (`manual` / not sensitive: admin-only CRUD, no migration or auth change).

## Try it now

Precondition: XAMPP running, logged in as admin (copy the fresh build into the web root for full file-picker styling).

1. Sidebar → **Országok**: the empty state "Még nincs egyetlen ország sem." shows and the item is active.
2. **Új ország**, submit empty: Hungarian errors appear under the Hungarian and English name fields.
3. Create one country without an image and one with an image: the list shows a placeholder and a thumbnail.
4. Edit the country with the image, tick **Kép eltávolítása**, save: it now shows without an image.
5. Narrow the browser to about 390 px: the table scrolls horizontally and nothing overflows the page.

This proves the admin country CRUD with optional image works end to end on the local site.
