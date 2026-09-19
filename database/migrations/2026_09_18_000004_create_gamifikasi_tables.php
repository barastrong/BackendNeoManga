<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /** Default 100 title level (sama dengan LEVEL_TITLES di EngagementService). */
    protected const TITLES = [
        1 => 'Pembaca Baru', 2 => 'Penasaran', 3 => 'Perambah Halaman', 4 => 'Kolektor Chapter', 5 => 'Penjelajah',
        6 => 'Pengumpul Spoiler', 7 => 'Pecandu Baca', 8 => 'Ahli Ngarang Teori', 9 => 'Petualang', 10 => 'Bintang Baru',
        11 => 'Pendekar Halaman', 12 => 'Pemburu Chapter', 13 => 'Samurai Baca', 14 => 'Ksatria Komik', 15 => 'Master Spoiler',
        16 => 'Penguasa Panel', 17 => 'Ninja Baca', 18 => 'Strategis', 19 => 'Veteran Komik', 20 => 'Pahlawan Halaman',
        21 => 'Mentor Komik', 22 => 'Grandmaster Halaman', 23 => 'Penakluk Arc', 24 => 'Pembasmi Plot Twist', 25 => 'Sang Pencerita',
        26 => 'Gladiator Manga', 27 => 'Perisai Rak', 28 => 'Paladin Komik', 29 => 'Ahli Strategi', 30 => 'Raja Baca',
        31 => 'Kaisar Chapter', 32 => 'Penguasa Rak', 33 => 'Badai Panel', 34 => 'Sang Penyimpan Arc', 35 => 'Overlord Chapter',
        36 => 'Pemburu Arc', 37 => 'Ksatria Naga', 38 => 'Pendekar Legendaris', 39 => 'Sang Arsitek Plot', 40 => 'Naga Pembaca',
        41 => 'Maharaja Halaman', 42 => 'Penjaga Perpustakaan', 43 => 'Wali Chapter', 44 => 'Penyair Spoiler', 45 => 'Archon Komik',
        46 => 'Maharaja Baca', 47 => 'Dewa Rak', 48 => 'Manusia Perpustakaan', 49 => 'Penakluk Dunia', 50 => 'Legenda Baca',
        51 => 'Jenderal Halaman', 52 => 'Kaisar Legenda', 53 => 'Legenda Hidup', 54 => 'Sang Penimbun Chapter', 55 => 'Emperor Panel',
        56 => 'Penjaga Arc', 57 => 'Naga Emas', 58 => 'Arsitek Spoiler', 59 => 'Sang Pemikir', 60 => 'Iblis Halaman',
        61 => 'Iblis Chapter', 62 => 'Penunggu Rak', 63 => 'Raja Iblis', 64 => 'Sang Fenomenal', 65 => 'Master Segala Arc',
        66 => 'Pemburu Legenda', 67 => 'Vampir Halaman', 68 => 'Dewa Rak Buku', 69 => 'Naga Iblis', 70 => 'Dewa Spoiler',
        71 => 'Kolonel Spoiler', 72 => 'Archon Legenda', 73 => 'Sang Abadi Arc', 74 => 'Penjaga Semesta', 75 => 'Kaisar Komik',
        76 => 'Titan Halaman', 77 => 'Raja Segala Raja', 78 => 'Sang Penyatu Arc', 79 => 'Naga Abadi', 80 => 'Immortal Reader',
        81 => 'Dewa Halaman', 82 => 'Sang Tak Terbaca', 83 => 'Monster Chapter', 84 => 'Semesta Manga', 85 => 'Pencipta Arc',
        86 => 'Sang Omnipotent', 87 => 'Penguasa Spoiler', 88 => 'Raja Legendaris', 89 => 'Dewa Naga', 90 => 'Titan Manga',
        91 => 'Titan Komik', 92 => 'Sang Mahakuasa', 93 => 'Kaisar Semesta', 94 => 'Penakluk Takdir', 95 => 'Naga Maharaja',
        96 => 'Sang Supreme', 97 => 'Penguasa Halaman', 98 => 'Dewa Tertinggi', 99 => 'Sang Legenda', 100 => 'Supreme Overlord',
    ];

    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->string('key')->primary();
            $table->string('value')->nullable();
            $table->timestamps();
        });

        Schema::create('level_titles', function (Blueprint $table) {
            $table->unsignedTinyInteger('level')->primary();
            $table->string('title', 60);
            $table->timestamps();
        });

        DB::table('settings')->insert([
            ['key' => 'xp_per_read', 'value' => '5', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'daily_xp_cap', 'value' => '100', 'created_at' => now(), 'updated_at' => now()],
        ]);

        foreach (self::TITLES as $level => $title) {
            DB::table('level_titles')->insert([
                'level' => $level, 'title' => $title, 'created_at' => now(), 'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
        Schema::dropIfExists('level_titles');
    }
};