<?php

namespace App\Services;

use App\Models\MangaView;
use App\Models\ReadingStreak;
use App\Models\User;
use App\Models\LevelTitle;
use App\Models\Setting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EngagementService
{
    /**
     * Catat aktivitas baca harian user + update streak.
     * Dipanggil dari ChapterController::show — cukup sekali per request.
     */
    public static function recordRead(): void
    {
        $userId = Auth::id();
        if (!$userId) {
            return;
        }

        $today = now()->toDateString();

        $streak = ReadingStreak::firstOrCreate(
            ['user_id' => $userId],
            ['current_streak' => 0, 'longest_streak' => 0, 'last_active_date' => null]
        );

        // Streak cuma dihitung 1x per hari — kalau udah aktif hari ini, lewati (hemat query)
        if ($streak->last_active_date?->toDateString() === $today) {
            return;
        }

        $yesterday = now()->subDay()->toDateString();
        $isYesterday = $streak->last_active_date?->toDateString() === $yesterday;

        $newCurrent = $isYesterday ? $streak->current_streak + 1 : 1;

        $streak->update([
            'current_streak' => $newCurrent,
            'longest_streak' => max($streak->longest_streak, $newCurrent),
            'last_active_date' => $today,
        ]);
    }

    /**
     * Baca state streak user (tanpa trigger). Untuk navbar & profil.
     */
    public static function streakFor(?int $userId): array
    {
        if (!$userId) {
            return ['current' => 0, 'longest' => 0, 'active_today' => false];
        }

        $streak = ReadingStreak::where('user_id', $userId)->first();

        if (!$streak) {
            return ['current' => 0, 'longest' => 0, 'active_today' => false];
        }

        return [
            'current' => (int) $streak->current_streak,
            'longest' => (int) $streak->longest_streak,
            'active_today' => $streak->last_active_date?->toDateString() === now()->toDateString(),
        ];
    }

    /** Badge pertama yang dicapai user (untuk navbar chip). */
    public static function earnedBadge(int $currentStreak): ?string
    {
        return match (true) {
            $currentStreak >= 30 => '🔥 Legenda (30 hari)',
            $currentStreak >= 14 => '🏆 Setia (14 hari)',
            $currentStreak >= 7  => '⚡ Rajin (7 hari)',
            $currentStreak >= 3  => '🔥 Semangat (3 hari)',
            default => null,
        };
    }

    /**
     * Daftar semua badge + status unlocked/locked + progress ke badge berikutnya.
     * Basis: JUMLAH STREAK (current_streak) — makin tinggi streak, makin banyak badge nyala.
     * Buat tampilan grid "Badge Saya" di profil.
     */
    public static function badges(int $currentStreak): array
    {
        $defs = [
            ['threshold' => 3,  'name' => 'Semangat', 'icon' => 'fa-solid fa-fire',   'color' => '#f97316'],
            ['threshold' => 7,  'name' => 'Rajin',     'icon' => 'fa-solid fa-bolt',   'color' => '#eab308'],
            ['threshold' => 14, 'name' => 'Setia',     'icon' => 'fa-solid fa-trophy', 'color' => '#3b82f6'],
            ['threshold' => 30, 'name' => 'Legenda',   'icon' => 'fa-solid fa-crown',  'color' => '#a855f7'],
        ];

        $badges = array_map(fn ($d) => $d + ['unlocked' => $currentStreak >= $d['threshold']], $defs);

        $next = null;
        foreach ($defs as $d) {
            if ($currentStreak < $d['threshold']) {
                $next = $d;
                break;
            }
        }

        return [
            'badges' => $badges,
            'next' => $next,
            'days_left' => $next ? $next['threshold'] - $currentStreak : 0,
        ];
    }

    /* ================= LEVEL SYSTEM (berbasis XP) ================= */

    /** XP per chapter baru dibaca — bisa diubah admin via CMS (default 5). */
    public static function xpPerRead(): int
    {
        return (int) (Setting::where('key', 'xp_per_read')->value('value') ?: 5);
    }

    /** Batas XP harian per user — bisa diubah admin via CMS (default 100). */
    public static function dailyXpCap(): int
    {
        return (int) (Setting::where('key', 'daily_xp_cap')->value('value') ?: 100);
    }

    /** Tambah XP user (dipanggil saat chapter pertama kali dibaca). Ada cap harian. */
    public static function addXp(int $userId, ?int $amount = null): void
    {
        $user = User::find($userId);
        if (!$user) {
            return;
        }

        $amount ??= self::xpPerRead();
        $cap = self::dailyXpCap();

        $today = now()->toDateString();
        $user->daily_xp = $user->daily_xp_date === $today ? $user->daily_xp : 0;
        $user->daily_xp_date = $today;

        if ($user->daily_xp >= $cap) {
            $user->save();
            return;
        }

        $gained = min($amount, $cap - $user->daily_xp);
        $user->xp += $gained;
        $user->daily_xp += $gained;
        $user->save();
    }

    /** Title per level 1-100. */
    protected const LEVEL_TITLES = [
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

    /** Warna + emoji per tier (chip level & progress bar). */
    protected const LEVEL_TIERS = [
        ['max' => 10,  'color' => '#38bdf8', 'emoji' => '📖'],
        ['max' => 25,  'color' => '#34d399', 'emoji' => '⚔️'],
        ['max' => 50,  'color' => '#f97316', 'emoji' => '🐉'],
        ['max' => 75,  'color' => '#a855f7', 'emoji' => '💎'],
        ['max' => 100, 'color' => '#ff2e4d', 'emoji' => '👑'],
    ];

    /**
     * Title per level — dari DB (level_titles), fallback ke konstanta bawaan.
     */
    public static function titleFor(int $level): string
    {
        return LevelTitle::where('level', $level)->value('title') ?: (self::LEVEL_TITLES[$level] ?? "Lv $level");
    }

    /** Total XP kumulatif untuk capai level L. Naik ke L butuh 2L+46 XP (start 50, +2/level). */
    protected static function levelThreshold(int $level): int
    {
        if ($level <= 1) return 0;
        // Σ(2k+46) untuk k=2..L → L² + 47L − 48
        return $level * $level + 47 * $level - 48;
    }

    /**
     * Level + title user berbasis XP.
     * @return array{level:int,title:string,emoji:string,color:string,next:?array,xp:int,progress:int,progress_total:int}
     */
    public static function levelFor(int $xp): array
    {
        $level = 1;
        for ($l = 2; $l <= 100; $l++) {
            if ($xp < self::levelThreshold($l)) break;
            $level = $l;
        }

        $tier = collect(self::LEVEL_TIERS)->first(fn ($t) => $level <= $t['max']);

        $next = null;
        if ($level < 100) {
            $next = [
                'level' => $level + 1,
                'title' => self::titleFor($level + 1),
                'xp' => self::levelThreshold($level + 1),
            ];
        }

        $currentThreshold = self::levelThreshold($level);
        $progressTotal = $next ? $next['xp'] - $currentThreshold : $currentThreshold;

        return [
            'level' => $level,
            'title' => self::titleFor($level),
            'emoji' => $tier['emoji'],
            'color' => $tier['color'],
            'next' => $next,
            'xp' => $xp,
            'current_threshold' => $currentThreshold,
            'progress' => $xp - $currentThreshold,
            'progress_total' => $progressTotal,
        ];
    }
}