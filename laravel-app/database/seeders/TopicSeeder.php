<?php

namespace Database\Seeders;

use App\Models\Topic;
use Illuminate\Database\Seeder;

class TopicSeeder extends Seeder
{
    public function run(): void
    {
        $topics = [
            ['slug' => 'vizi-sportok',  'name' => ['hu' => 'Vízi sportok',  'en' => 'Water Sports',  'it' => 'Sport acquatici']],
            ['slug' => 'termeszet',     'name' => ['hu' => 'Természet',      'en' => 'Nature',         'it' => 'Natura']],
            ['slug' => 'varos',         'name' => ['hu' => 'Város',          'en' => 'City',           'it' => 'Città']],
            ['slug' => 'gasztronimia',  'name' => ['hu' => 'Gasztronómia',   'en' => 'Gastronomy',     'it' => 'Gastronomia']],
            ['slug' => 'wellness',      'name' => ['hu' => 'Wellness',       'en' => 'Wellness',       'it' => 'Benessere']],
        ];

        foreach ($topics as $topic) {
            Topic::firstOrCreate(['slug' => $topic['slug']], ['name' => $topic['name']]);
        }
    }
}
