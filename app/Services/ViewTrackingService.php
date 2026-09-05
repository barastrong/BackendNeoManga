<?php

namespace App\Services;

use App\Models\Manga;
use App\Models\MangaView;
use Illuminate\Support\Facades\DB;

/**
 * Tracking & agregasi view manga.
 * Dipakai oleh MangaController::show, ChapterController::show, dan DashboardController.
 */
class ViewTrackingService
{
    /**
     * Catat 1 view untuk sebuah manga (1x per user per hari).
     * Guest dicatat dgn user_id null — tetap dihitung sbg view, cuma gak unik per user.
     */
    public static function record(int $mangaId): void
    {
        MangaView::updateOrCreate(
            [
                'manga_id'  => $mangaId,
                'user_id'   => auth()->id(),
                'view_date' => now()->toDateString(),
            ],
            ['period' => 'daily']
        );
    }

    /**
     * Manga paling banyak di-view dalam periode.
     *
     * @param string $period today|week|month|all
     * @param int    $limit
     * @return \Illuminate\Support\Collection Manga dgn atribut views_count
     */
    public static function popular(string $period = 'week', int $limit = 12)
    {
        $since = match ($period) {
            'today'  => now()->startOfDay(),
            'week'   => now()->startOfWeek(),
            'month'  => now()->startOfMonth(),
            default  => now()->subDays(30), // all ≈ 30 hari terakhir
        };

        $ids = MangaView::where('view_date', '>=', $since->toDateString())
            ->select('manga_id')
            ->selectRaw('COUNT(*) as views_count')
            ->groupBy('manga_id')
            ->orderByDesc('views_count')
            ->limit($limit)
            ->pluck('manga_id');

        if ($ids->isEmpty()) {
            return collect();
        }

        return Manga::with(['latestPublishedChapter', 'genres'])
            ->withAvg('ratings', 'rating')
            ->whereIn('id', $ids)
            ->get()
            ->map(function (Manga $m) use ($ids) {
                // count dari pluck asli (query terpisah biar tetap urut)
                $m->views_count = MangaView::where('manga_id', $m->id)
                    ->where('view_date', '>=', now()->subDays(30)->toDateString())
                    ->count();
                return $m;
            });
    }

    /** Total view (semua periode) — buat stat card admin. */
    public static function total(): int
    {
        return (int) MangaView::count();
    }
}
