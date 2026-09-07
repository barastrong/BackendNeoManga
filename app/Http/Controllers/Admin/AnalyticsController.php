<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bookmark;
use App\Models\Chapter;
use App\Models\Comment;
use App\Models\MangaView;
use App\Models\Rating;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    public function index()
    {
        // ===== KPI =====
        $totalViews     = MangaView::count();
        $readers30d     = MangaView::where('view_date', '>=', now()->subDays(29)->toDateString())->distinct()->count('user_id');
        $avgRating      = round((float) DB::table('ratings')->avg('rating'), 1);
        $ratingVotes    = DB::table('ratings')->count();
        $totalComments  = Comment::count();
        $commentsMonth  = Comment::where('created_at', '>=', now()->startOfMonth())->count();
        $totalBookmarks = Bookmark::count();
        $totalUsers     = User::count();
        $usersMonth     = User::where('created_at', '>=', now()->startOfMonth())->count();
        $mangaCount     = \App\Models\Manga::count();
        $chapterCount   = Chapter::count();

        // ===== Dual-line: views + pembaca unik, 30 hari =====
        $viewsDaily = MangaView::where('view_date', '>=', now()->subDays(29)->toDateString())
            ->selectRaw('view_date, COUNT(*) as total, COUNT(DISTINCT user_id) as readers')
            ->groupBy('view_date')->orderBy('view_date')->get()->keyBy('view_date');

        $chartData = collect(range(29, 0))->map(function ($i) use ($viewsDaily) {
            $date = now()->subDays($i)->toDateString();
            $row  = $viewsDaily->get($date);
            return [
                'date'    => $date,
                'label'   => now()->subDays($i)->locale('id')->isoFormat('D MMM'),
                'total'   => (int) ($row->total ?? 0),
                'readers' => (int) ($row->readers ?? 0),
            ];
        });
        $chartMax = max(1, $chartData->max('total'), $chartData->max('readers'));

        $viewsToday     = (int) ($viewsDaily->get(now()->toDateString())->total ?? 0);
        $viewsYesterday = (int) ($viewsDaily->get(now()->subDay()->toDateString())->total ?? 0);
        $viewDelta      = $viewsToday - $viewsYesterday;

        // ===== Rilis chapter per bulan (6 bulan) =====
        $chByMonth = Chapter::where('created_at', '>=', now()->startOfMonth()->subMonths(5))
            ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') ym, COUNT(*) c")
            ->groupBy('ym')->pluck('c', 'ym');
        $chapterSeries = collect(range(5, 0))->map(fn ($i) => [
            'label' => now()->locale('id')->subMonths($i)->isoFormat('MMM'),
            'total' => (int) ($chByMonth[now()->subMonths($i)->format('Y-m')] ?? 0),
        ]);
        $chapterMax = max(1, $chapterSeries->max('total'));

        // ===== Registrasi user per bulan (6 bulan) =====
        $uByMonth = User::where('created_at', '>=', now()->startOfMonth()->subMonths(5))
            ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') ym, COUNT(*) c")
            ->groupBy('ym')->pluck('c', 'ym');
        $userSeries = collect(range(5, 0))->map(fn ($i) => [
            'label' => now()->locale('id')->subMonths($i)->isoFormat('MMM'),
            'total' => (int) ($uByMonth[now()->subMonths($i)->format('Y-m')] ?? 0),
        ]);
        $userMax = max(1, $userSeries->max('total'));

        // ===== Genre terpopuler berdasar views =====
        $genreViews = DB::table('manga_views')
            ->join('manga_genres', 'manga_genres.manga_id', '=', 'manga_views.manga_id')
            ->join('genres', 'genres.id', '=', 'manga_genres.genre_id')
            ->select('genres.name', DB::raw('COUNT(*) total'))
            ->groupBy('genres.name')->orderByDesc('total')->take(5)->get();
        $genreMax = max(1, $genreViews->max('total'));

        // ===== Top manga by views =====
        $topManga = DB::table('manga_views')
            ->join('mangas', 'mangas.id', '=', 'manga_views.manga_id')
            ->select('mangas.id', 'mangas.title', 'mangas.slug', 'mangas.cover_image',
                DB::raw('COUNT(*) total'), DB::raw('COUNT(DISTINCT manga_views.user_id) readers'))
            ->groupBy('mangas.id', 'mangas.title', 'mangas.slug', 'mangas.cover_image')
            ->orderByDesc('total')->take(5)->get();

        // ===== Top rating =====
        $topRated = DB::table('ratings')
            ->join('mangas', 'mangas.id', '=', 'ratings.manga_id')
            ->select('mangas.id', 'mangas.title', 'mangas.slug', 'mangas.cover_image',
                DB::raw('AVG(ratings.rating) r'), DB::raw('COUNT(ratings.id) votes'))
            ->groupBy('mangas.id', 'mangas.title', 'mangas.slug', 'mangas.cover_image')
            ->orderByDesc('r')->orderByDesc('votes')->take(5)->get();

        return view('admin.analytics', compact(
            'totalViews', 'readers30d', 'avgRating', 'ratingVotes', 'totalComments', 'commentsMonth',
            'totalBookmarks', 'totalUsers', 'usersMonth', 'mangaCount', 'chapterCount',
            'chartData', 'chartMax', 'viewsToday', 'viewDelta',
            'chapterSeries', 'chapterMax', 'userSeries', 'userMax',
            'genreViews', 'genreMax', 'topManga', 'topRated'
        ));
    }
}
