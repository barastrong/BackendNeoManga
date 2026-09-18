<?php

namespace App\Services;

use App\Models\MangaView;
use App\Models\ReadingStreak;
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
}