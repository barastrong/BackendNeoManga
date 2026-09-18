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

        $counts = MangaView::where('view_date', '>=', $since->toDateString())
            ->select('manga_id')
            ->selectRaw('COUNT(*) as views_count')
            ->groupBy('manga_id')
            ->pluck('views_count', 'manga_id');

        if ($counts->isEmpty()) {
            return collect();
        }

        // Urut DESC by views, ambil top N — pluck gak jamin urutan, sort manual
        $counts = $counts->sortDesc()->take($limit);
        $ids = $counts->keys();

        return Manga::with(['latestPublishedChapter', 'genres'])
            ->withAvg('ratings', 'rating')
            ->whereIn('id', $ids)
            ->get()
            ->map(function (Manga $m) use ($counts) {
                $m->views_count = (int) ($counts[$m->id] ?? 0);
                return $m;
            })
            ->sortByDesc('views_count')   // sort DESC biar urut konsisten
            ->values();
    }

    /** Total view (semua periode) — buat stat card admin. */
    public static function total(): int
    {
        return (int) MangaView::count();
    }
}
