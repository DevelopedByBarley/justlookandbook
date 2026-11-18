# Build Plan

List the features that make up your project, high level and in rough build order.
Keep each item to one line; the details come later in `/feature`.

Run `/feature` to spec the next unchecked item, or `/feature 2` to pick one.
Keep completed items checked and append new features as the project grows.
Do not renumber completed features; their archived specs refer to those IDs.

## Your features

Brownfield adoption (2026-11-17): items 1-12 already exist in the code and are
checked. New items (`- [ ] 13. ...`) are added as the owner directs the rework.

- [x] 1. **Nyilvános programlista** - témaszűrés, lapozás, népszerű programok jelölése
- [x] 2. **Program adatlap** - galéria, árak, korhatár, időtartam, napok, YouTube-videó, megtekintésszámláló
- [x] 3. **Többnyelvűség** - hu/en/it felület, nyelvválasztó, fordítható adatmezők magyar fallbackkel
- [x] 4. **Chat-alapú foglalás** - WhatsApp, Messenger, Viber widgetek ÁSZF-elfogadó modállal
- [x] 5. **Kapcsolati űrlap** - validáció, honeypot, rate limit, e-mail küldés és hibaértesítő
- [x] 6. **Admin hitelesítés** - külön admin guard, belépés, jelszó-visszaállítás, jelszócsere
- [x] 7. **Admin dashboard** - statisztikák, legutóbbi programok, belső kézikönyv
- [x] 8. **Admin témakezelés** - témák CRUD
- [x] 9. **Admin programkezelés** - CRUD, Tiptap szerkesztő, képfeltöltés borítóképpel, drag-and-drop sorrend
- [x] 10. **Admin beállítások** - WhatsApp-telefonszám
- [x] 11. **SEO és megfelelés** - sitemap.xml, meta leírások, sütihozzájárulás, biztonsági fejlécek, hibaoldalak
- [x] 12. **Licencrendszer** - licencszerver API és kliens middleware (jelenleg kikapcsolva)

### Ország / város rendszer

Források: `arajanlat.pdf` (2026-11-03, a megrendelt alapár) és
`Website Redesign - Client Summary and Questions.docx` (2026-10-30, a terv és
az ügyfél válaszai). Az ajánlat 5. pontjának opcionális tételei nem készülnek.
Minden tételhez hozzátartozik az új feliratok hu/en/it fordítása (ajánlat 4.1)
és az asztali + mobil ellenőrzés (4.2). A zárójeles számok az ajánlat tételei.

Döntések (2026-11-17):

- Ország: több város tartozhat hozzá. Város: mindig pontosan egy országhoz tartozik.
- Program: legfeljebb egy ország és egy város; egyik sem kötelező, országhoz város nélkül is köthető. A város csak a program országához tartozó lehet. A témák változatlanok (közösek, több téma/program).
- A program űrlapon a "kötelező mezők ellenőrzése" (2.3) a ténylegesen kötelező mezőkre (pl. név) vonatkozik, nem az országra/városra.
- Országonként és városonként egy fix kép; megjelenik a választóban és a kiválasztott hely programlistájának tetején.
- Az országok és városok sorrendje nem húzható (a választóban fix sorrend, pl. név szerint - feltételezés).
- Kiemelt országok/városok és egyedi színek: most nem kellenek.
- A programsorrend marad egy globális sorrend, a mostani drag-and-drop a teljes listában; nincs átalakítás.
- A helyválasztó felugró ablak; egyszerre egy ország vagy egy város választható, vagy minden program.
- Kihagyható; aki kihagyja, minden programot lát. Aki közvetlenül program adatlapra érkezik, annak nem ugrik fel.
- Ország kiválasztásakor csak az adott ország programjai látszanak (az ország nélküliek nem); város kiválasztásakor csak az adott város programjai.
- Várható méret az első évben: 3-4 ország, országonként 3-5 város.

Nyitott kérdések (?):

- ? A meglévő (kb. 80) program Egyiptomhoz rendelése: a dokumentum szerint mind Egyiptomhoz kerül, az ajánlatban ez opcionális (5.1). Ha nem készül el automatikusan, az adminban kézzel kell hozzárendelni, különben helyválasztás után nem látszanak.
- ? A meglévő programképek tömörítése: az ügyfél a dokumentumban igent mondott, az ajánlatban opcionális (5.2), nem rendelték meg.
- ? Az új ország- és városképek automatikus tömörítése feltöltéskor: a dokumentum kéri, az ajánlat nem tartalmazza külön.

Feature-ök:

- [x] 13. **Ország és város adatmodell** - `countries` és `cities` tábla (3 nyelvű név, egy kép, a város országhoz kötve), a programok nem kötelező ország- és városmezője, migrációk (1.1)
- [x] 14. **Admin országkezelés** - lista, létrehozás, szerkesztés, törlés, 3 nyelvű név és kép, menüpont az admin navigációban (2.1)
- [ ] 15. **Admin városkezelés** - ország szerint szűrhető lista, CRUD, 3 nyelvű név, kép, országhoz rendelés (2.2)
- [ ] 16. **Program ország- és városmezője** - nem kötelező ország- és városválasztó a program űrlapon, a városlista az országtól függ, a város csak a kiválasztott országhoz tartozhat (2.3)
- [ ] 17. **Helyválasztó első látogatáskor** - felugró ablak, előbb ország, majd város képekkel, kihagyható (ekkor minden program látszik), program adatlapon nem ugrik fel, mobilra optimalizált; a választást a böngésző tárolja, visszatéréskor a lista azonnal arra szűrve jelenik meg, tetején a hely képével (3.1, 3.3)
- [ ] 18. **Helyválasztó a navigációban** - mindig látszik, mi van kiválasztva; ország- és városváltó, illetve "összes program" a felső menüben, külön felugró ablakkal, asztali és mobil nézetben (3.2)
- [ ] 19. **Témák hely szerint** - csak azok a témagombok látszanak, amelyekhez a látott programok között van program (3.4)
- [ ] 20. **Élesítés** - teljes átnézés asztalin és mobilon, biztonsági mentés az élesről, éles adatbázis-frissítés, ellenőrzés élesítés után (4.2, 4.3)
