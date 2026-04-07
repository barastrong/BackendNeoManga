<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            GenreSeeder::class,
        ]);

        User::factory()->create([
            'name' => 'NeoManga - Admin',
            'email' => 'Test@gmail.com',
            'password' => Hash::make('HelloWorld'),
        ]); 
    }
}
