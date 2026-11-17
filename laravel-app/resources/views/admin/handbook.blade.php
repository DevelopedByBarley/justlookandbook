<x-admin-layout title="Kézikönyv">

    <div class="mb-6">
        <h1 class="text-xl font-bold text-gray-900">Kézikönyv</h1>
        <p class="text-sm text-gray-500 mt-0.5">Az admin felület használati útmutatója</p>
    </div>

    <div class="space-y-6 max-w-3xl">

        {{-- Áttekintés --}}
        <div class="bg-white rounded-2xl border border-gray-200 divide-y divide-gray-100">
            <div class="px-6 py-4">
                <h2 class="text-base font-bold text-gray-900 flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0h6"/>
                    </svg>
                    Dashboard
                </h2>
            </div>
            <div class="px-6 py-4 text-sm text-gray-600 space-y-2">
                <p>A dashboard az admin felület kezdőlapja. Belépés után ide kerülsz.</p>
                <ul class="list-disc pl-5 space-y-1">
                    <li><strong>Összes program</strong> – a rendszerben tárolt programok száma (aktív + inaktív)</li>
                    <li><strong>Aktív program</strong> – a weboldalon jelenleg látható programok száma</li>
                    <li><strong>Témák</strong> – a programok csoportosítására használt témák száma</li>
                    <li><strong>Összes megtekintés</strong> – a programoldalak összesített látogatásszáma</li>
                    <li><strong>Legutóbb hozzáadott programok</strong> – az 5 legfrissebb program gyors eléréshez</li>
                </ul>
            </div>
        </div>

        {{-- Témák --}}
        <div class="bg-white rounded-2xl border border-gray-200 divide-y divide-gray-100">
            <div class="px-6 py-4">
                <h2 class="text-base font-bold text-gray-900 flex items-center gap-2">
                    <svg class="w-5 h-5 text-amber-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                    </svg>
                    Témák kezelése
                </h2>
            </div>
            <div class="px-6 py-4 text-sm text-gray-600 space-y-2">
                <p>A témák segítségével csoportosíthatók a programok (pl. "Vízisportok", "Kalandtúrák").</p>
                <ul class="list-disc pl-5 space-y-1">
                    <li>Magyar és angol név megadása <strong>kötelező</strong>, az olasz opcionális</li>
                    <li>Egy programhoz csak egy téma rendelhető</li>
                    <li>Téma csak akkor törölhető, ha nincs hozzárendelt program</li>
                    <li>A témák a programok szűrőjében jelennek meg a nyilvános oldalon</li>
                </ul>
            </div>
        </div>

        {{-- Programok --}}
        <div class="bg-white rounded-2xl border border-gray-200 divide-y divide-gray-100">
            <div class="px-6 py-4">
                <h2 class="text-base font-bold text-gray-900 flex items-center gap-2">
                    <svg class="w-5 h-5 shrink-0" style="color:#037F8C;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    Programok kezelése
                </h2>
            </div>

            <div class="px-6 py-4 text-sm text-gray-600 space-y-3">
                <p class="font-semibold text-gray-800">Új program létrehozása</p>
                <ul class="list-disc pl-5 space-y-1">
                    <li>Magyar és angol <strong>név megadása kötelező</strong> — az angol névből generálódik az URL (slug)</li>
                    <li>Az angol nevet mentés után ne változtasd meg, mert az URL megváltozik</li>
                    <li>A leírás mezőkben rich text szerkesztő érhető el (félkövér, lista, link, stb.)</li>
                </ul>
            </div>

            <div class="px-6 py-4 text-sm text-gray-600 space-y-3">
                <p class="font-semibold text-gray-800">Képek</p>
                <ul class="list-disc pl-5 space-y-1">
                    <li>Legfeljebb <strong>7 kép</strong> tölthető fel programonként</li>
                    <li>Egy kép maximális mérete <strong>3 MB</strong> (JPG, PNG, WebP)</li>
                    <li>Az <strong>első feltöltött kép</strong> lesz automatikusan a borítókép</li>
                    <li>Szerkesztéskor a képre húzva megjelenik a borítókép és törlés gomb</li>
                    <li>A törlésre jelölt képek csak mentés után törlődnek véglegesen</li>
                </ul>
            </div>

            <div class="px-6 py-4 text-sm text-gray-600 space-y-3">
                <p class="font-semibold text-gray-800">Árak és részletek</p>
                <ul class="list-disc pl-5 space-y-1">
                    <li>Az árakat <strong>euróban (€)</strong> add meg egész számként</li>
                    <li>Ha nincs gyerekár, hagyd üresen az egész "Gyerek ár" részt</li>
                    <li>Az időtartamot szabad szövegként add meg (pl. "4–5 óra", "Egész napos")</li>
                </ul>
            </div>

            <div class="px-6 py-4 text-sm text-gray-600 space-y-3">
                <p class="font-semibold text-gray-800">YouTube videó</p>
                <ul class="list-disc pl-5 space-y-1">
                    <li>Megadható teljes YouTube URL vagy csak a videó ID (11 karakter)</li>
                    <li>A rendszer automatikusan kinyeri az embed URL-t</li>
                    <li>A beágyazott videó előnézete azonnal megjelenik a mezőbe gépelés közben</li>
                </ul>
            </div>

            <div class="px-6 py-4 text-sm text-gray-600 space-y-3">
                <p class="font-semibold text-gray-800">Státusz és sorrend</p>
                <ul class="list-disc pl-5 space-y-1">
                    <li><strong>Aktív</strong> program megjelenik a nyilvános oldalon, <strong>Inaktív</strong> nem</li>
                    <li>A programok sorrendje a listában drag & drop-pal módosítható</li>
                </ul>
            </div>

            <div class="px-6 py-4 text-sm text-gray-600 space-y-3">
                <p class="font-semibold text-gray-800">Program törlése</p>
                <ul class="list-disc pl-5 space-y-1">
                    <li>A törlés <strong>visszavonhatatlan</strong> — a program és az összes képe véglegesen törlődik</li>
                    <li>Törlés előtt érdemes inkább Inaktívra állítani</li>
                </ul>
            </div>
        </div>

        {{-- Jelszó --}}
        <div class="bg-white rounded-2xl border border-gray-200 divide-y divide-gray-100">
            <div class="px-6 py-4">
                <h2 class="text-base font-bold text-gray-900 flex items-center gap-2">
                    <svg class="w-5 h-5 text-gray-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                    </svg>
                    Jelszó változtatás
                </h2>
            </div>
            <div class="px-6 py-4 text-sm text-gray-600 space-y-2">
                <p>A jelszót a <strong>Beállítások → Jelszó változtatás</strong> menüpontban módosíthatod.</p>
                <ul class="list-disc pl-5 space-y-1">
                    <li>A jelenlegi jelszó megadása szükséges</li>
                    <li>Az új jelszónak legalább <strong>8 karakter</strong> hosszúnak kell lennie</li>
                    <li>Elfelejtett jelszó esetén a bejelentkező oldalon igényelhető visszaállítási e-mail</li>
                </ul>
            </div>
        </div>

    </div>

</x-admin-layout>
