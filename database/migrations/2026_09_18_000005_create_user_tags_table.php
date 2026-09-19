<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Gelar User (tags) dengan syarat achievement — dikelola via CMS admin.
     * requirement_type: always|admin|reads|manga_read|xp|level|streak|comments|bookmarks|days
     */
    public function up(): void
    {
        Schema::create('user_tags', function (Blueprint $table) {
            $table->id();
            $table->string('name', 60);
            $table->string('icon', 60)->default('fa-tag');
            $table->string('color', 20)->default('#38bdf8');
            $table->string('requirement_type', 30)->default('always');
            $table->unsignedInteger('requirement_value')->default(0);
            $table->string('description', 255)->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        $tags = [
            // Admin & member
            ['Admin', 'fa-user-shield', '#ff2e4d', 'admin', 0, 'Staf / pengelola NeoManga', 1],
            ['Member', 'fa-circle-check', '#34d399', 'always', 0, 'Bagian dari keluarga NeoManga', 2],

            // Jumlah baca (reads)
            ['Pembaca Pemula', 'fa-book-open', '#38bdf8', 'reads', 1, 'Baca minimal 1 chapter', 10],
            ['Bookworm', 'fa-book', '#38bdf8', 'reads', 50, 'Baca 50 chapter', 11],
            ['Kutu Buku', 'fa-book-bookmark', '#38bdf8', 'reads', 100, 'Baca 100 chapter', 12],
            ['Rak Buku Berjalan', 'fa-shelves', '#38bdf8', 'reads', 250, 'Baca 250 chapter', 13],
            ['Chapter Hunter', 'fa-crosshairs', '#38bdf8', 'reads', 750, 'Baca 750 chapter', 14],
            ['Perpustakaan Berjalan', 'fa-building-columns', '#38bdf8', 'reads', 500, 'Baca 500 chapter', 15],
            ['Mesin Baca', 'fa-robot', '#38bdf8', 'reads', 1000, 'Baca 1.000 chapter', 16],

            // Manga unik (manga_read)
            ['Kolektor Manga', 'fa-boxes-stacked', '#f97316', 'manga_read', 10, 'Baca 10 manga berbeda', 20],
            ['Penjelajah Genre', 'fa-compass', '#f97316', 'manga_read', 25, 'Baca 25 manga berbeda', 21],
            ['Kaisar Genre', 'fa-chess-king', '#f97316', 'manga_read', 50, 'Baca 50 manga berbeda', 22],

            // Komentar
            ['Komentator', 'fa-comment', '#eab308', 'comments', 1, 'Tulis 1 komentar', 30],
            ['Kritikus', 'fa-comment-dots', '#eab308', 'comments', 10, 'Tulis 10 komentar', 31],
            ['Kritikus Ulung', 'fa-comments', '#eab308', 'comments', 50, 'Tulis 50 komentar', 32],
            ['Sang Spoiler', 'fa-bullhorn', '#eab308', 'comments', 100, 'Tulis 100 komentar', 33],

            // Bookmark
            ['Penyimpan', 'fa-bookmark', '#3b82f6', 'bookmarks', 1, 'Simpan 1 bookmark', 40],
            ['Kolektor Bookmark', 'fa-star', '#3b82f6', 'bookmarks', 20, 'Simpan 20 bookmark', 41],
            ['Perpustakaan Pribadi', 'fa-house-lock', '#3b82f6', 'bookmarks', 50, 'Simpan 50 bookmark', 42],

            // Streak
            ['Pembakar Streak', 'fa-fire', '#f97316', 'streak', 3, 'Streak 3 hari', 50],
            ['Rajin Mingguan', 'fa-bolt', '#f97316', 'streak', 7, 'Streak 7 hari', 51],
            ['Sebulan Penuh', 'fa-calendar-check', '#f97316', 'streak', 30, 'Streak 30 hari', 52],
            ['Tanpa Henti', 'fa-infinity', '#f97316', 'streak', 100, 'Streak 100 hari', 53],

            // Level
            ['Perambah Level', 'fa-signal', '#a855f7', 'level', 5, 'Capai level 5', 60],
            ['Bintang Sepuluh', 'fa-star', '#a855f7', 'level', 10, 'Capai level 10', 61],
            ['Elite Dua Puluh', 'fa-shield-halved', '#a855f7', 'level', 20, 'Capai level 20', 62],
            ['Legenda Lima Puluh', 'fa-crown', '#a855f7', 'level', 50, 'Capai level 50', 63],
            ['Supreme Seratus', 'fa-chess-queen', '#ff2e4d', 'level', 100, 'Capai level 100', 64],

            // XP
            ['Kolektor XP', 'fa-coins', '#34d399', 'xp', 2500, 'Kumpulkan 2.500 XP', 70],
            ['Sultan XP', 'fa-gem', '#34d399', 'xp', 7500, 'Kumpulkan 7.500 XP', 71],
            ['Jutawan XP', 'fa-money-bill-wave', '#34d399', 'xp', 14000, 'Kumpulkan 14.000 XP', 72],

            // Lama gabung
            ['Veteran 30 Hari', 'fa-clock', '#94a3b8', 'days', 30, 'Anggota selama 30 hari', 80],
            ['Nostalgia', 'fa-hourglass-half', '#94a3b8', 'days', 90, 'Anggota selama 90 hari', 81],
            ['Kakek Baca', 'fa-clock-rotate-left', '#94a3b8', 'days', 365, 'Anggota selama 1 tahun', 82],
        ];

        foreach ($tags as $i => [$name, $icon, $color, $type, $value, $desc, $sort]) {
            DB::table('user_tags')->insert([
                'name' => $name, 'icon' => $icon, 'color' => $color,
                'requirement_type' => $type, 'requirement_value' => $value,
                'description' => $desc, 'is_active' => true, 'sort_order' => $sort,
                'created_at' => now(), 'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('user_tags');
    }
};