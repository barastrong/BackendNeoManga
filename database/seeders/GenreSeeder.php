<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GenreSeeder extends Seeder
{
    public function run(): void
    {
        $genres = ['4-Koma', 'Action', 'Adult', 'Adventure', 'Cooking', 'Comedy', 'Crime', 'Drama', 'Ecchi', 'Fantasy', 'Game', 'Gender Bender', 'Ghost', 'Gyaru', 'Harem', 'Historical', 'Horor', 'Isekai', 'Josei', 'Magic', 'Martial Arts', 'Mature', 'Mecha', 'Medical', 'Military', 'Monters', 'Music', 'Mystery', 'Oneshot', 'Police', 'Project', ' Psychological', 'Reincarnation', 'Reverse Harem', 'Romance', 'School','School Life', 'Seinen', 'Shoujo', 'Shoujo Ai', 'Shounen', 'Sci-fi', 'Slice of Life', 'Smut', 'Sports', 'Super Power', 'Supernatural', 'Survival', 'Thriller', 'Time Travel', 'Tragedy', 'Vampire', 'Vilainess', 'Webtoon', 'Yuri', 'Zombies'];

        foreach ($genres as $genre) {
            DB::table('genres')->insert([
                'name' => $genre,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
