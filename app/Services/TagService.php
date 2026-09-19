<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserTag;
use App\Models\History;
use App\Models\Comment;
use App\Models\Bookmark;
use App\Models\ReadingStreak;
use Illuminate\Support\Facades\DB;

/**
 * Gelar User (achievement tags) — evaluasi syarat per user.
 * requirement_type: always|admin|reads|manga_read|xp|level|streak|comments|bookmarks|days
 */
class TagService
{
    /**
     * Gelar yang sudah terbuka untuk user.
     * @return \Illuminate\Support\Collection<UserTag>
     */
    public static function unlockedFor(User $user)
    {
        $stats = self::statsFor($user);
        $level = EngagementService::levelFor((int) $user->xp)['level'];

        return UserTag::where('is_active', true)
            ->orderBy('sort_order')
            ->get()
            ->filter(function ($tag) use ($user, $stats, $level) {
                return match ($tag->requirement_type) {
                    'always'    => true,
                    'admin'     => $user->role === 'admin',
                    'reads'     => $stats['histories'] >= $tag->requirement_value,
                    'manga_read'=> $stats['manga_read'] >= $tag->requirement_value,
                    'xp'        => (int) $user->xp >= $tag->requirement_value,
                    'level'     => $level >= $tag->requirement_value,
                    'streak'    => $stats['streak'] >= $tag->requirement_value,
                    'comments'  => $stats['comments'] >= $tag->requirement_value,
                    'bookmarks' => $stats['bookmarks'] >= $tag->requirement_value,
                    'days'      => $stats['days'] >= $tag->requirement_value,
                    default     => false,
                };
            })
            ->values();
    }

    /** Statistik ringkas user buat evaluasi semua gelar (1x query per metrik). */
    public static function statsFor(User $user): array
    {
        $streak = ReadingStreak::where('user_id', $user->id)->first();

        return [
            'histories'  => History::where('user_id', $user->id)->count(),
            'manga_read' => History::where('user_id', $user->id)->distinct('manga_id')->count('manga_id'),
            'comments'   => Comment::where('user_id', $user->id)->count(),
            'bookmarks'  => Bookmark::where('user_id', $user->id)->count(),
            'streak'     => (int) ($streak->current_streak ?? 0),
            'days'       => (int) $user->created_at?->diffInDays(now()) ?? 0,
        ];
    }

    /** Tipe syarat yang didukung (buat dropdown CMS). */
    public static function types(): array
    {
        return [
            'always'    => 'Semua user (otomatis)',
            'admin'     => 'Role Admin',
            'reads'     => 'Total Chapter Dibaca',
            'manga_read'=> 'Jumlah Manga Berbeda',
            'xp'        => 'Total XP',
            'level'     => 'Level User',
            'streak'    => 'Streak Harian',
            'comments'  => 'Jumlah Komentar',
            'bookmarks' => 'Jumlah Bookmark',
            'days'      => 'Lama Jadi Member (hari)',
        ];
    }
}