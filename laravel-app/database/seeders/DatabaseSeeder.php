<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        Admin::firstOrCreate(
            ['email' => 'developedbybarley@gmail.com'],
            [
                'name'     => 'Admin',
                'password' => Hash::make('password123'),
            ]
        );

        $this->call([
            TopicSeeder::class,
            ProgramSeeder::class,
        ]);
    }
}
