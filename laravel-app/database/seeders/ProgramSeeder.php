<?php

namespace Database\Seeders;

use App\Models\Program;
use App\Models\Topic;
use Illuminate\Database\Seeder;

class ProgramSeeder extends Seeder
{
    public function run(): void
    {
        $topics = Topic::pluck('id', 'slug');

        $programs = [
            // Vízi sportok
            [
                'topic'       => 'vizi-sportok',
                'slug'        => 'kajak-kenu-tura',
                'name'        => ['hu' => 'Kajak-kenu túra', 'en' => 'Kayak & Canoe Tour', 'it' => 'Tour in kayak e canoa'],
                'description' => ['hu' => '<p>Fedezd fel a folyók és tavak világát kajakkal és kenuval, profi oktatók segítségével.</p>', 'en' => '<p>Explore rivers and lakes by kayak and canoe with professional instructors.</p>', 'it' => '<p>Esplora fiumi e laghi in kayak e canoa con istruttori professionisti.</p>'],
                'price_adult' => 19900, 'price_child' => 12900, 'child_age_min' => 8, 'child_age_max' => 14,
                'duration'    => ['hu' => '4–5 óra', 'en' => '4–5 hours', 'it' => '4–5 ore'],
                'group_size' => 12,
                'available_days' => ['mon', 'wed', 'fri', 'sat'],
            ],
            [
                'topic'       => 'vizi-sportok',
                'slug'        => 'buvarkodas-kezdoknek',
                'name'        => ['hu' => 'Búvárkodás kezdőknek', 'en' => 'Diving for Beginners', 'it' => 'Immersioni per principianti'],
                'description' => ['hu' => '<p>Merülj el a víz alatti világ titkaiban biztonságos körülmények között, tapasztalt búvároktatókkal.</p>', 'en' => '<p>Dive into the secrets of the underwater world in safe conditions with experienced instructors.</p>', 'it' => '<p>Immergiti nei segreti del mondo sottomarino in condizioni sicure con istruttori esperti.</p>'],
                'price_adult' => 34900, 'price_child' => 24900, 'child_age_min' => 12, 'child_age_max' => 16,
                'duration'    => ['hu' => '1 nap', 'en' => '1 day', 'it' => '1 giorno'],
                'group_size' => 8,
                'available_days' => ['tue', 'thu', 'sat', 'sun'],
            ],
            [
                'topic'       => 'vizi-sportok',
                'slug'        => 'sup-szorfozes',
                'name'        => ['hu' => 'SUP szörfözés', 'en' => 'Stand-Up Paddleboarding', 'it' => 'Stand-Up Paddleboarding'],
                'description' => ['hu' => '<p>Próbáld ki Európa egyik legdivatosabb vízisportját állva evezős deszkán.</p>', 'en' => '<p>Try one of Europe\'s trendiest water sports — stand-up paddleboarding.</p>', 'it' => '<p>Prova uno degli sport acquatici più trendy d\'Europa.</p>'],
                'price_adult' => 14900, 'price_child' => 9900, 'child_age_min' => 10, 'child_age_max' => 16,
                'duration'    => ['hu' => '3 óra', 'en' => '3 hours', 'it' => '3 ore'],
                'group_size' => 10,
                'available_days' => ['wed', 'fri', 'sat', 'sun'],
            ],
            [
                'topic'       => 'vizi-sportok',
                'slug'        => 'dunai-hajokirandulas',
                'name'        => ['hu' => 'Dunai hajókirándulás', 'en' => 'Danube Boat Trip', 'it' => 'Gita in barca sul Danubio'],
                'description' => ['hu' => '<p>Fedezd fel Budapest látképét a Duna vizéről, kényelmes hajón.</p>', 'en' => '<p>Discover Budapest\'s skyline from the Danube on a comfortable boat.</p>', 'it' => '<p>Scopri lo skyline di Budapest dal Danubio su una comoda barca.</p>'],
                'price_adult' => 13500, 'price_child' => 7500, 'child_age_min' => 4, 'child_age_max' => 12,
                'duration'    => ['hu' => '2 óra', 'en' => '2 hours', 'it' => '2 ore'],
                'group_size' => 30,
                'available_days' => ['mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun'],
            ],
            // Természet
            [
                'topic'       => 'termeszet',
                'slug'        => 'hegyi-turataboroz',
                'name'        => ['hu' => 'Hegyi túratábor', 'en' => 'Mountain Hiking Camp', 'it' => 'Campo trekking in montagna'],
                'description' => ['hu' => '<p>Fedezd fel a Mátra rejtett ösvényeit tapasztalt túravezetőkkel, lenyűgöző panorámával.</p>', 'en' => '<p>Discover the hidden trails of the Mátra with experienced guides and stunning views.</p>', 'it' => '<p>Scopri i sentieri nascosti del Mátra con guide esperte e viste mozzafiato.</p>'],
                'price_adult' => 18500, 'price_child' => 11500, 'child_age_min' => 10, 'child_age_max' => 16,
                'duration'    => ['hu' => '2 nap / 1 éjszaka', 'en' => '2 days / 1 night', 'it' => '2 giorni / 1 notte'],
                'group_size' => 12,
                'available_days' => ['fri', 'sat'],
            ],
            [
                'topic'       => 'termeszet',
                'slug'        => 'kerekparos-kaland',
                'name'        => ['hu' => 'Kerékpáros kaland', 'en' => 'Cycling Adventure', 'it' => 'Avventura in bicicletta'],
                'description' => ['hu' => '<p>Tekerj végig festői tájakon, erdei utakon és folyópartok mentén felejthetetlen élményt szerezve.</p>', 'en' => '<p>Cycle through picturesque landscapes, forest roads and river banks for an unforgettable experience.</p>', 'it' => '<p>Pedalate attraverso paesaggi pittoreschi per un\'esperienza indimenticabile.</p>'],
                'price_adult' => 14900, 'price_child' => 8900, 'child_age_min' => 8, 'child_age_max' => 14,
                'duration'    => ['hu' => '5–6 óra', 'en' => '5–6 hours', 'it' => '5–6 ore'],
                'group_size' => 16,
                'available_days' => ['tue', 'thu', 'sat', 'sun'],
            ],
            [
                'topic'       => 'termeszet',
                'slug'        => 'naplemente-tura',
                'name'        => ['hu' => 'Naplemente túra', 'en' => 'Sunset Hike', 'it' => 'Escursione al tramonto'],
                'description' => ['hu' => '<p>Romantikus esti séta, ahol a naplemente fényében csodálhatjuk meg a természet szépségét.</p>', 'en' => '<p>A romantic evening walk where we admire nature\'s beauty in the light of the sunset.</p>', 'it' => '<p>Una romantica passeggiata serale al tramonto.</p>'],
                'price_adult' => 9900, 'price_child' => null, 'child_age_min' => null, 'child_age_max' => null,
                'duration'    => ['hu' => '2–3 óra', 'en' => '2–3 hours', 'it' => '2–3 ore'],
                'group_size' => 20,
                'available_days' => ['fri', 'sat', 'sun'],
            ],
            [
                'topic'       => 'termeszet',
                'slug'        => 'sziklamaszas',
                'name'        => ['hu' => 'Sziklamászás', 'en' => 'Rock Climbing', 'it' => 'Arrampicata su roccia'],
                'description' => ['hu' => '<p>Kihívásra vágysz? Profi instruktorok segítségével hódítsd meg a sziklafalakat!</p>', 'en' => '<p>Looking for a challenge? Conquer the rock faces with professional instructors!</p>', 'it' => '<p>Stai cercando una sfida? Conquista le pareti rocciose con istruttori professionisti!</p>'],
                'price_adult' => 17900, 'price_child' => 12900, 'child_age_min' => 12, 'child_age_max' => 16,
                'duration'    => ['hu' => '4–5 óra', 'en' => '4–5 hours', 'it' => '4–5 ore'],
                'group_size' => 8,
                'available_days' => ['sat', 'sun'],
            ],
            // Város
            [
                'topic'       => 'varos',
                'slug'        => 'varoismereti-seta',
                'name'        => ['hu' => 'Városismereti séta', 'en' => 'City Walking Tour', 'it' => 'Tour a piedi della città'],
                'description' => ['hu' => '<p>Fedezd fel a város rejtett zugait és érdekes történeteit helyi idegenvezetőkkel.</p>', 'en' => '<p>Discover the city\'s hidden corners and interesting stories with local guides.</p>', 'it' => '<p>Scopri gli angoli nascosti della città con guide locali.</p>'],
                'price_adult' => 8900, 'price_child' => 4900, 'child_age_min' => 6, 'child_age_max' => 14,
                'duration'    => ['hu' => '3 óra', 'en' => '3 hours', 'it' => '3 ore'],
                'group_size' => 20,
                'available_days' => ['mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun'],
            ],
            [
                'topic'       => 'varos',
                'slug'        => 'budapest-ejjel',
                'name'        => ['hu' => 'Budapest éjjel', 'en' => 'Budapest by Night', 'it' => 'Budapest di notte'],
                'description' => ['hu' => '<p>Tapasztald meg Budapest varázsát az éjszakai fények között — egy különleges esti séta.</p>', 'en' => '<p>Experience Budapest\'s magic among the night lights — a special evening walk.</p>', 'it' => '<p>Vivi la magia di Budapest tra le luci notturne.</p>'],
                'price_adult' => 11500, 'price_child' => null, 'child_age_min' => null, 'child_age_max' => null,
                'duration'    => ['hu' => '2–3 óra', 'en' => '2–3 hours', 'it' => '2–3 ore'],
                'group_size' => 15,
                'available_days' => ['fri', 'sat'],
            ],
            [
                'topic'       => 'varos',
                'slug'        => 'muzeumok-ejszakaja',
                'name'        => ['hu' => 'Múzeumok éjszakája', 'en' => 'Night of Museums', 'it' => 'Notte dei Musei'],
                'description' => ['hu' => '<p>Különleges éjszakai múzeumlátogatás, ahol a kiállítások más megvilágításban tárulnak elénk.</p>', 'en' => '<p>A special nighttime museum visit where exhibitions are presented in a different light.</p>', 'it' => '<p>Una speciale visita notturna ai musei.</p>'],
                'price_adult' => 5900, 'price_child' => 2900, 'child_age_min' => 6, 'child_age_max' => 14,
                'duration'    => ['hu' => '3–4 óra', 'en' => '3–4 hours', 'it' => '3–4 ore'],
                'group_size' => 25,
                'available_days' => ['fri', 'sat'],
            ],
            [
                'topic'       => 'varos',
                'slug'        => 'street-art-tura',
                'name'        => ['hu' => 'Street art túra', 'en' => 'Street Art Tour', 'it' => 'Tour di street art'],
                'description' => ['hu' => '<p>Fedezd fel Budapest street art jelenetét helyi művészek és graffitirajzolók nyomában.</p>', 'en' => '<p>Discover Budapest\'s street art scene following local artists and graffiti painters.</p>', 'it' => '<p>Scopri la scena street art di Budapest seguendo artisti locali.</p>'],
                'price_adult' => 7900, 'price_child' => null, 'child_age_min' => null, 'child_age_max' => null,
                'duration'    => ['hu' => '2–3 óra', 'en' => '2–3 hours', 'it' => '2–3 ore'],
                'group_size' => 15,
                'available_days' => ['wed', 'sat', 'sun'],
            ],
            // Gasztronómia
            [
                'topic'       => 'gasztronimia',
                'slug'        => 'gasztronomiai-est',
                'name'        => ['hu' => 'Gasztronómiai est', 'en' => 'Gastronomic Evening', 'it' => 'Serata gastronomica'],
                'description' => ['hu' => '<p>Kóstold meg Magyarország legjobb ízeit egy exkluzív gasztronómiai esten, séfek kíséretében.</p>', 'en' => '<p>Taste the best flavors of Hungary at an exclusive gastronomic evening with chefs.</p>', 'it' => '<p>Assapora i migliori sapori dell\'Ungheria in una serata gastronomica esclusiva.</p>'],
                'price_adult' => 22000, 'price_child' => null, 'child_age_min' => null, 'child_age_max' => null,
                'duration'    => ['hu' => '3–4 óra', 'en' => '3–4 hours', 'it' => '3–4 ore'],
                'group_size' => 16,
                'available_days' => ['thu', 'fri', 'sat'],
            ],
            [
                'topic'       => 'gasztronimia',
                'slug'        => 'bortura-tokajban',
                'name'        => ['hu' => 'Bortúra Tokajban', 'en' => 'Wine Tour in Tokaj', 'it' => 'Tour del vino a Tokaj'],
                'description' => ['hu' => '<p>Látogass el Tokajba, a világ egyik leghíresebb borvidékére, és kóstold meg a helyi nedűket!</p>', 'en' => '<p>Visit Tokaj, one of the world\'s most famous wine regions, and taste the local wines!</p>', 'it' => '<p>Visita Tokaj, una delle regioni vinicole più famose del mondo!</p>'],
                'price_adult' => 28000, 'price_child' => null, 'child_age_min' => null, 'child_age_max' => null,
                'duration'    => ['hu' => '1 nap', 'en' => '1 day', 'it' => '1 giorno'],
                'group_size' => 20,
                'available_days' => ['sat', 'sun'],
            ],
            [
                'topic'       => 'gasztronimia',
                'slug'        => 'grillmester-workshop',
                'name'        => ['hu' => 'Grillmester workshop', 'en' => 'Grill Master Workshop', 'it' => 'Workshop Grill Master'],
                'description' => ['hu' => '<p>Sajátítsd el a tökéletes grillezés titkait profi séfektől egy izgalmas és ízletes workshopon.</p>', 'en' => '<p>Learn the secrets of perfect grilling from professional chefs at an exciting and tasty workshop.</p>', 'it' => '<p>Impara i segreti della grigliata perfetta da chef professionisti.</p>'],
                'price_adult' => 12900, 'price_child' => null, 'child_age_min' => null, 'child_age_max' => null,
                'duration'    => ['hu' => '3–4 óra', 'en' => '3–4 hours', 'it' => '3–4 ore'],
                'group_size' => 12,
                'available_days' => ['sat', 'sun'],
            ],
            [
                'topic'       => 'gasztronimia',
                'slug'        => 'street-food-fesztival',
                'name'        => ['hu' => 'Street food fesztivál', 'en' => 'Street Food Festival', 'it' => 'Festival dello street food'],
                'description' => ['hu' => '<p>Kóstold meg a világ street food kultúráját egy helyszínen — sokszínű, ízletes, felejthetetlen.</p>', 'en' => '<p>Taste street food cultures from around the world in one place — colorful, tasty, unforgettable.</p>', 'it' => '<p>Assapora le culture street food del mondo in un unico posto.</p>'],
                'price_adult' => 6900, 'price_child' => 3900, 'child_age_min' => 4, 'child_age_max' => 14,
                'duration'    => ['hu' => '4–5 óra', 'en' => '4–5 hours', 'it' => '4–5 ore'],
                'group_size' => 50,
                'available_days' => ['sat', 'sun'],
            ],
            // Wellness
            [
                'topic'       => 'wellness',
                'slug'        => 'wellness-hetvege',
                'name'        => ['hu' => 'Wellness hétvége', 'en' => 'Wellness Weekend', 'it' => 'Weekend benessere'],
                'description' => ['hu' => '<p>Töltődj fel teljesen egy exkluzív wellness hétvégén — masszázs, fürdő és relaxáció vár.</p>', 'en' => '<p>Fully recharge on an exclusive wellness weekend — massage, spa and relaxation await.</p>', 'it' => '<p>Ricaricati completamente in un weekend benessere esclusivo.</p>'],
                'price_adult' => 39900, 'price_child' => null, 'child_age_min' => null, 'child_age_max' => null,
                'duration'    => ['hu' => '2 nap / 2 éjszaka', 'en' => '2 days / 2 nights', 'it' => '2 giorni / 2 notti'],
                'group_size' => 10,
                'available_days' => ['fri', 'sat', 'sun'],
            ],
            [
                'topic'       => 'wellness',
                'slug'        => 'erdei-yoga-tabor',
                'name'        => ['hu' => 'Erdei yoga tábor', 'en' => 'Forest Yoga Camp', 'it' => 'Campo yoga nella foresta'],
                'description' => ['hu' => '<p>Találj belső békét az erdő csendjében — reggeli és esti yoga, meditáció, erdei séták.</p>', 'en' => '<p>Find inner peace in the silence of the forest — morning and evening yoga, meditation, forest walks.</p>', 'it' => '<p>Trova la pace interiore nel silenzio della foresta.</p>'],
                'price_adult' => 16500, 'price_child' => null, 'child_age_min' => null, 'child_age_max' => null,
                'duration'    => ['hu' => '2 nap / 1 éjszaka', 'en' => '2 days / 1 night', 'it' => '2 giorni / 1 notte'],
                'group_size' => 12,
                'available_days' => ['fri', 'sat'],
            ],
            [
                'topic'       => 'wellness',
                'slug'        => 'termalfurdo-csomag',
                'name'        => ['hu' => 'Termálfürdő csomag', 'en' => 'Thermal Spa Package', 'it' => 'Pacchetto terme'],
                'description' => ['hu' => '<p>Élvezd Magyarország természetes termálvizét egy teljes relaxációs napon — fürdőzés, masszázs, pihenés.</p>', 'en' => '<p>Enjoy Hungary\'s natural thermal waters on a full relaxation day — bathing, massage, rest.</p>', 'it' => '<p>Goditi le acque termali naturali dell\'Ungheria in una giornata di relax.</p>'],
                'price_adult' => 29900, 'price_child' => 14900, 'child_age_min' => 6, 'child_age_max' => 14,
                'duration'    => ['hu' => '1 nap', 'en' => '1 day', 'it' => '1 giorno'],
                'group_size' => 20,
                'available_days' => ['mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun'],
            ],
            [
                'topic'       => 'wellness',
                'slug'        => 'meditacios-napkezdo',
                'name'        => ['hu' => 'Meditációs napkezdő', 'en' => 'Morning Meditation', 'it' => 'Meditazione mattutina'],
                'description' => ['hu' => '<p>Kezdd a napot tudatosan — guidált meditáció, légzőgyakorlatok és mentális frissítő reggeli rutinok.</p>', 'en' => '<p>Start the day mindfully — guided meditation, breathing exercises and mental morning routines.</p>', 'it' => '<p>Inizia la giornata consapevolmente con meditazione guidata.</p>'],
                'price_adult' => 8900, 'price_child' => null, 'child_age_min' => null, 'child_age_max' => null,
                'duration'    => ['hu' => '2 óra', 'en' => '2 hours', 'it' => '2 ore'],
                'group_size' => 15,
                'available_days' => ['mon', 'wed', 'fri'],
            ],
        ];

        foreach ($programs as $i => $data) {
            Program::firstOrCreate(
                ['slug' => $data['slug']],
                [
                    'topic_id'       => $topics[$data['topic']] ?? null,
                    'name'           => $data['name'],
                    'description'    => $data['description'],
                    'price_adult'    => $data['price_adult'],
                    'price_child'    => $data['price_child'],
                    'child_age_min'  => $data['child_age_min'],
                    'child_age_max'  => $data['child_age_max'],
                    'duration'       => $data['duration'],
                    'group_size'     => $data['group_size'],
                    'available_days' => $data['available_days'],
                    'active'         => true,

                    'sort_order'     => $i + 1,
                ]
            );
        }
    }
}
