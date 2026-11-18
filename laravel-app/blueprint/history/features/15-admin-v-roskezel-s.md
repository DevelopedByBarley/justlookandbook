# Feature: Admin városkezelés

**From build-plan:** feature 15
**Build attempt:** 1
**Status:** verified
**Branch:** feature/admin-v-roskezel-s

## Goal

Az admin a saját felületén listázhatja, ország szerint szűrheti, létrehozhatja,
szerkesztheti és törölheti a városokat (3 nyelvű név, egy kép, pontosan egy
országhoz rendelve), és az oldalsávból eléri ezt a menüpontot (ajánlat 2.2).
Ez az alapja a program városmezőjének (16) és a helyválasztó városlépésének (17).

## In scope

- `/admin/cities` erőforrás `show` nélkül: lista, létrehozás, szerkesztés, törlés.
- Ország szerinti szűrés a listán (`?country={id}`), lapozáskor megmaradó szűrővel.
- Név magyarul, angolul, olaszul: a `hu` és `en` kötelező, az `it` nem
  (azonos a 14. feature-rel).
- Ország kiválasztása kötelező (a `cities.country_id` nem null).
- Legfeljebb egy kép városonként, **nem kötelező**, a 14. feature képkezelésével
  azonos módon (feltöltés, csere a régi fájl törlésével, eltávolítás).
- Törlés: a város mindig törölhető; a hozzá rendelt programok `city_id`-ja
  `NULL` lesz (meglévő FK), ezt a megerősítő szöveg jelzi.
- "Városok" menüpont az admin oldalsávban az "Országok" után.
- A listában bélyegkép, a három név, az ország magyar neve és a programok száma.

## Out of scope

- A program űrlap ország/város mezője és a program-város konzisztencia
  validálása (16), minden nyilvános oldali változás (17-19).
- Képtömörítés vagy átméretezés feltöltéskor (nyitott kérdés, nem rendelték meg).
- Kiemelés, egyedi színek, húzható sorrend (2026-11-17 döntés).
- Városnév-egyediség országon belül (nincs rá követelmény).
- Az országlista "Városok" számának linkké alakítása (nem kért kényelmi funkció).
- Új nyilvános fordítási kulcsok: az admin felület csak magyar.
- Tesztkészlet bevezetése (nincs `Test` parancs).

## Build loop

- `workflow.stepReview: feature`: a lépések egymás után készülnek, a review a
  teljes feature végén, `/check` és `/audit current` előtt egyben történik.
- `workflow.checkpointCommits: disabled`: lépésenként nincs commit; a végső
  commitot a `/complete` készíti a `feature/admin-v-roskezel-s` ágon.
- Minden lépés után az admin (XAMPP alatt) működőképes marad, és
  `npm run build` hiba nélkül lefut, ha a lépés nézetet érintett.

## Build steps

- [x] **1. Útvonalak, controller váz, szűrhető lista és menüpont**
  - `routes/admin/cities.php`: `Route::resource('cities', AdminCityController::class)->except(['show']);`
    és a `require` a `routes/auth/admin.php` `auth:admin` csoportjában a
    `countries.php` után.
  - `AdminCityController::index(Request $request)`:
    - `$countries = Country::orderBy('name->hu')->get()` a szűrőhöz.
    - A szűrő: a `country` query paraméter csak akkor érvényes, ha egész szám és
      szerepel a `$countries` között; különben figyelmen kívül marad (minden
      város látszik, a választó "Összes ország" állásban). Nem 404, nem
      validációs hiba.
    - Lekérdezés: `City::with('country')->withCount('programs')`, szűrés
      `country_id`-ra, ha van érvényes szűrő; sorrend az ország magyar neve, majd
      a város magyar neve (`name->hu`) szerint. Az ország szerinti rendezéshez
      allekérdezés (`orderBy(Country::select('name->hu')->whereColumn('countries.id', 'cities.country_id'))`);
      ha ez a MySQL-en nem a várt sorrendet adja, `join` a `countries`-ra
      `select('cities.*')`-gal. `paginate(20)->withQueryString()`.
  - `resources/views/admin/cities/index.blade.php` a `countries/index` mintájára:
    - Fejléc "Városok" / "Úti célok városai", "Új város" gomb; ha van érvényes
      szűrő, a gomb a `admin.cities.create` útvonalra `?country={id}`-vel mutat.
    - Szűrő: GET űrlap a táblázat fölött, címkézett `<select id="country"
      name="country">` ("Ország" felirat, `<label for>`), első opció "Összes
      ország" (üres érték), utána az országok magyar névvel; a kiválasztott
      ország `selected`. Változáskor beküldődik (`onchange="this.form.submit()"`),
      és egy látható "Szűrés" gomb is van, hogy JS nélkül és billentyűzettel is
      működjön. A szűrő csak akkor jelenik meg, ha van legalább egy ország.
    - Oszlopok: Kép (bélyegkép vagy szürke helyőrző), Magyar, English, Italiano,
      Ország (az ország magyar neve), Programok, Szerkesztés / Törlés.
      `overflow-x-auto` konténer.
    - Üres állapotok: ha nincs egyetlen ország sem: "Előbb hozz létre egy
      országot." linkkel az `admin.countries.create`-re (és az "Új város" gomb
      helyett is ez a link/hint); ha van ország, de nincs város: "Még nincs
      egyetlen város sem."; ha a szűrt országban nincs város: "Ebben az
      országban még nincs város."
  - Oldalsáv: "Városok" link az "Országok" után, aktív állapot
    `admin.cities.*`-ra, a többivel egyező osztályokkal, saját (pl. térképtű)
    ikonnal.
  - **Done when:** belépett adminként a `/admin/cities` megnyílik, országok
    nélkül az "Előbb hozz létre egy országot." állapot látszik, a menüpont
    aktív; kilépve a `/admin/cities` a belépő oldalra irányít;
    `php artisan route:list --name=admin.cities` 6 útvonalat mutat; tinkerrel
    létrehozott két ország városaival a szűrő csak a kiválasztott ország
    városait mutatja, lapozáskor a `country` paraméter megmarad, és
    `?country=abc` vagy nem létező id esetén minden város látszik.

- [x] **2. Létrehozás és szerkesztés országgal és képpel**
  - `app/Http/Requests/Admin/CityRequest.php` (store és update közös, a
    `CountryRequest` mintájára): `country_id` `required|integer|exists:countries,id`;
    `name.hu`, `name.en` `required|string|max:100`, `name.it`
    `nullable|string|max:100`; `image` `nullable|image|mimes:jpg,jpeg,png,gif,webp|max:3072`;
    `remove_image` `nullable|boolean` csak nem-POST kérésnél. Magyar
    `attributes()` (`country_id` => "ország") és `messages()` a
    `CountryRequest` szövegeivel, plusz "Válassz országot." a
    `country_id.required` / `exists` / `integer` esetre.
  - Országváltás védelme (update): ha a beküldött `country_id` eltér a
    jelenlegitől, és a városhoz program tartozik, validációs hiba a
    `country_id` mezőn: "Az ország nem módosítható, mert N program tartozik a
    városhoz. Előbb vedd ki a városból a programokat." (A `CityRequest`
    `after()` / `withValidator()` hookjában, a route-ból kapott `city`
    alapján.) Ez megelőzi, hogy egy program városa más országé legyen, mint a
    program országa.
  - `store()`: `DB::transaction` alatt `City::create(['country_id' => ..., 'name' => ...])`,
    majd kép esetén `cities/{id}/` alá mentés és az `image` frissítése (a
    `AdminCountryController::store()` mintája; fájlmentési hiba esetén a város
    sem jön létre).
  - `update()`: `country_id` és név mentése; a képcsere / eltávolítás pontosan a
    `AdminCountryController::update()` logikája (új kép nyer, a régi fájl csak
    sikeres mentés után törlődik).
  - Nézetek: `create.blade.php`, `edit.blade.php`, `_form.blade.php` a
    `countries` mintájára, `enctype="multipart/form-data"`. A `_form` első
    blokkja az "Ország" (`kötelező` címke): címkézett `<select id="country_id"
    name="country_id">` "Válassz országot…" üres első opcióval, az országok
    magyar névvel `name->hu` szerint; kiválasztott érték
    `old('country_id', $city->country_id ?? request('country'))`; hiba a mező
    alatt (`@error('country_id')`), piros kerettel. Utána a három név és a kép
    blokk a `countries/_form` szerint (placeholder pl. "Hurghada").
  - A `create()` és `edit()` átadja a `$countries` listát. Ha nincs egyetlen
    ország sem, a `create` oldal űrlap helyett az "Előbb hozz létre egy
    országot." üzenetet mutatja linkkel.
  - Validációs hibánál az `old()` értékek (ország, nevek) visszakerülnek.
  - Flash: "Város sikeresen létrehozva." / "Város sikeresen módosítva.";
    átirányítás a listára, és ha a város országa ismert, `?country={id}`
    szűrővel, hogy az admin lássa az elmentett várost.
  - **Done when:** ország, hu vagy en név nélküli, nem képfájl és 3 MB-nál
    nagyobb kép beküldése magyar hibaüzenettel a mező alatt visszatér, a
    beírt értékek megmaradnak; nem létező `country_id` (devtoolsszal
    átírva) "Válassz országot." hibát ad; kép nélküli és képes létrehozás
    sikeres, a fájl a `storage/app/public/cities/{id}/` alatt van, és az
    `imageUrl()` betölt; képcsere után csak az új fájl marad; "Kép
    eltávolítása" után a fájl törlődik és az `image` `null`; programmal
    rendelkező város (tinkerrel `city_id` beállítva) országának cseréje a
    fenti hibát adja, program nélküli városé sikeres; `?country={id}`-vel
    nyitott létrehozó oldalon az ország előre ki van választva.

- [x] **3. Törlés**
  - `destroy()`: `$city->delete()`; a képmappát a modell `deleted` eseménye
    törli; a programok `city_id`-ja a meglévő `nullOnDelete` FK miatt `NULL`
    lesz (a `country_id` megmarad). "Város sikeresen törölve." flash,
    átirányítás a listára a város országának szűrőjével.
  - A lista törlés-megerősítése: "Biztosan törlöd?", és ha `programs_count > 0`,
    hozzáfűzve: "N program városa üres lesz." (`Js::from`, mint a 14-ben).
  - **Done when:** a város törlése után eltűnik a listából, a `cities/{id}/`
    mappa törlődik; programhoz rendelt város törlése után a program
    `city_id`-ja `NULL`, `country_id`-ja változatlan; a megerősítő szöveg
    programos városnál tartalmazza a program-számot; a városok nélküli ország
    ezután az országkezelésben törölhető.

- [x] **4. Asztali és mobil ellenőrzés, build**
  - `npm run build` hibátlan; a lista (országok nélkül, üresen, adatokkal,
    szűrve), a létrehozó és a szerkesztő oldal asztali és kb. 390 px
    szélességben használható, a szűrő nem lóg ki, a táblázat vízszintesen
    görgethető.
  - **Done when:** a build lefut, és a fenti oldalak mindkét szélességben
    ellenőrizve vannak (képernyőkép, ha elérhető).
  - Állapot: `npm run build` hibátlan; a vizuális ellenőrzést (asztali és ~390 px) a
    felhasználó végezte, a lépés lezárását ő hagyta jóvá (képernyőkép nem készült).

## Files / areas

- Új: `routes/admin/cities.php`
- Módosul: `routes/auth/admin.php` (`require` a `countries.php` után)
- Új: `app/Http/Controllers/Admin/AdminCityController.php`
- Új: `app/Http/Requests/Admin/CityRequest.php`
- Új: `resources/views/admin/cities/{index,create,edit,_form}.blade.php`
- Módosul: `resources/views/components/admin-sidebar.blade.php`
- Változatlan, felhasznált: `app/Models/City.php` (`$fillable` tartalmazza a
  `country_id`-t, `name` cast, `deleted` esemény, `imageUrl()`, `country()`,
  `programs()`), `app/Models/Country.php`, a `countries` nézetek és controller
  mintaként, `resources/views/components/admin-layout.blade.php` (flash).
- Nincs migráció: a séma a 13. feature-ben elkészült.

## Data / contracts

- Útvonalak (`auth:admin`, `admin.` névelőtag): `GET /admin/cities`
  (`admin.cities.index`, opcionális `?country={id}`), `GET /admin/cities/create`
  (opcionális `?country={id}` előválasztás), `POST /admin/cities`,
  `GET /admin/cities/{city}/edit`, `PUT /admin/cities/{city}`,
  `DELETE /admin/cities/{city}`; route model binding `id` alapján.
- `cities.country_id`: kötelező, létező ország id-ja. Programmal rendelkező
  városnál nem módosítható (ez tartja fenn a "program városa a program
  országáé" szabályt, amit a DB nem kényszerít ki).
- `cities.name`: JSON `{"hu": string, "en": string, "it": string|null}`, mint a
  `countries.name`.
- `cities.image`: nem kötelező; `null` vagy `cities/{id}/<hash>.<ext>` a `public`
  diszken; a mappában legfeljebb egy fájl.
- Sorrend: ország `name->hu`, majd város `name->hu` szerint növekvő.
- Megjelenítés: minden név `{{ }}` escape-pel; a kép `alt` a magyar név; a
  megerősítő szöveg `Js::from`-mal.
- Jogosultság: csak `auth:admin`; az adminok megbízhatók, nincs tenant-határ.
  CSRF a `web` middleware-ből. A `country` query paraméter nem megbízható
  bemenet: csak a meglévő ország-id-k közül fogadható el.

## Testing

- Nincs deklarált `Test` vagy `Verify` parancs; a Pest készlet nincs bevezetve
  (`verification.logicTests: when-configured`), ezért a feature nem ír
  maradandó teszteket.
- Bizonyíték: kézi ellenőrzés a helyi XAMPP oldalon belépett adminként a
  "Done when" pontok szerint, `php artisan route:list --name=admin.cities`,
  `php artisan view:cache`, a `storage/app/public/cities/` tartalma, tinker a
  programos törlési és országváltási esetekhez, `npm run build`.
- Böngésző-automatizálás nincs; képernyőkép csak ha elérhető, és nem állítunk
  le nem futtatott vizuális bizonyítékot.

## Notes for the AI

- A `countries` CRUD (14) a közvetlen minta; a `storeImage()` privát metódust
  másold a controllerbe (két előfordulás miatt nem kell közös trait/service).
- Döntés ebben a specben: a városkép **nem kötelező**, a 2026-11-18-i
  országkép-döntéssel összhangban (a 14. feature a 15-re hagyta). A
  helyválasztónak (17) kép nélküli várost is kezelnie kell.
- Döntés ebben a specben: programos város országa nem cserélhető (hiba), a
  programok csendes átírása helyett. Jelenleg még egyetlen programnak sincs
  városa (16 előtt), ezért a tinkeres eset szükséges az ellenőrzéshez.
- A `CountryRequest` store/update különbsége `$this->isMethod('post')`; a
  `CityRequest` ugyanígy, egy osztály.
- A régi kép törlése `Storage::disk('public')->delete($old)`; a mappát csak a
  modell `deleted` eseménye törli.
- Az admin szövegek, validációs üzenetek és kódkommentek magyarul.
- A `vite build` a `laravel-app/public/build`-be ír, a kiszolgált webgyökér
  (`../justlookandbook.com/build`) régebbi buildet tartalmaz; ha egy új
  Tailwind osztály nem látszik, ez az oka, nem ennek a feature-nek a hibája.
- A `project-overview.md` a munkafában módosított (nem commitolt); ne írd
  felül, és ne vedd fel ennek a feature-nek a diffjébe.


<!-- blueprint:completion {"schemaVersion":2,"specBytes":14606,"specSha256":"8e6a7465d43cfc04b2a3e091e27d696e1bafba5bdd4a696ef724b9c34e2d9ef0","branch":"refs/heads/feature/admin-v-roskezel-s","head":"87c2b2fb3fd0d92f93b0b8b2b17c2ad11331b3fb","baseRef":"refs/heads/main","baseCommit":"87c2b2fb3fd0d92f93b0b8b2b17c2ad11331b3fb","sourceTree":"db73bb686cdfa1c3a884fc8dabf70329d87e9234","landing":"local-merge","absentOptional":[]} -->

## Commands

- `php -l` on `AdminCityController.php`, `CityRequest.php`, `routes/admin/cities.php`: pass
- `php artisan route:list --name=admin.cities`: pass, 6 routes (index, create, store, edit, update, destroy)
- `php artisan view:cache`: pass (all Blade templates compile), followed by `view:clear`
- Temporary Pest feature test (in-memory SQLite, `Storage::fake('public')`), deleted after the run because the spec adds no tests: pass, 5 tests / 46 assertions (rerun during `/complete`)
- `npm run build`: pass (rerun during `/complete`)
- Desktop and ~390 px manual check of the list, create and edit pages: done by the user during `/implement` step 4 (no screenshot taken)

## Evidence

- Guest `GET /admin/cities` redirects to `admin.login`. A logged-in admin with no countries sees "Előbb hozz létre egy országot.".
- Filter: `?country={id}` shows only that country's cities. The unfiltered list orders by country, then city Hungarian name (Hurghada, Kairó, Róma). `?country=abc` and `?country=999` show every city.
- Validation: an empty submit returns "Válassz országot." plus the `hu`/`en` name errors. A non-existent `country_id` returns "Válassz országot.", a PDF returns "Csak képfájl tölthető fel.", and a 4 MB image returns "A kép mérete legfeljebb 3 MB lehet.". No city is created.
- `create?country={id}` preselects the country. Create with an image stores `cities/{id}/<hash>.<ext>` and redirects to the list filtered to that country with "Város sikeresen létrehozva.".
- Replacing the image leaves exactly one file in `cities/{id}/` and deletes the old one. "Kép eltávolítása" deletes the file and sets `image` to `null`.
- With a program linked, changing the city's country returns "Az ország nem módosítható, mert 1 program tartozik a városhoz. ...". A save with the same country passes, and a city without programs can move to another country.
- The list's delete confirmation includes "1 program városa üres lesz.". After delete, the city and its `cities/{id}/` folder are gone, and the program's `city_id` is `NULL` while its `country_id` stays.

## Findings

- None raised for this feature. Existing F-01 [P2] open (settings migration rollback, feature 20 deploy note) stays in the ledger.

## Remaining risk

- No automated regression test remains: no `Test` command is declared and the default suite is red.
- The temporary test ran on SQLite. The MySQL behavior of the country-name subquery ordering was only checked manually on the local XAMPP site during `/implement`.
- `vite build` writes to `laravel-app/public/build`, while the served web root `../justlookandbook.com/build` holds an older build copied outside the repo.
- `/audit`, `/check` and independent review were not run (`manual` / not sensitive: admin-only CRUD, no migration or auth change, same as feature 14).

## Try it now

Precondition: XAMPP running, logged in as admin, at least two countries exist (feature 14).

1. Sidebar → **Városok**: the menu item is active and the list with the country filter appears.
2. **Új város**, submit empty: "Válassz országot." and the Hungarian/English name errors appear under the fields.
3. Create a city with an image in one country and one without an image in the other. The list shows a thumbnail and a placeholder.
4. In the **Ország** filter pick one country: only its cities remain, and **Új város** opens with that country preselected.
5. Edit the city with the image, tick **Kép eltávolítása**, save: it now shows the placeholder.
6. Delete a city: the confirmation asks "Biztosan törlöd?" and the city disappears from the list.
7. Narrow the browser to about 390 px: the filter fits and the table scrolls horizontally.

This proves the admin city CRUD with country filter and optional image works end to end on the local site.
