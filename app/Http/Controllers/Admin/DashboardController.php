<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bookmark;
use App\Models\Chapter;
use App\Models\Comment;
use App\Models\Manga;
use App\Models\MangaView;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $mangaCount = Manga::count();
        $chapterCount = Chapter::count();
        $userCount = User::count();
        $latestMangas = Manga::latest()->take(5)->get();
        $viewCount = MangaView::count();
        $commentCount = Comment::count();

        // Grafik view harian — 30 hari terakhir (area chart + filter 7/14/30)
        $viewsDaily = MangaView::where('view_date', '>=', now()->subDays(29)->toDateString())
            ->selectRaw('view_date, COUNT(*) as total')
            ->groupBy('view_date')
            ->orderBy('view_date')
            ->pluck('total', 'view_date');

        $chartData = collect(range(29, 0))->map(fn ($i) => [
            'date'  => now()->subDays($i)->toDateString(),
            'label' => now()->subDays($i)->locale('id')->isoFormat('dd'),
            'total' => (int) ($viewsDaily[now()->subDays($i)->toDateString()] ?? 0),
        ]);
        $chartMax = max(1, $chartData->max('total'));

        $viewsToday = (int) ($viewsDaily[now()->toDateString()] ?? 0);
        $viewsYesterday = (int) ($viewsDaily[now()->subDay()->toDateString()] ?? 0);
        $viewsWeek  = MangaView::where('view_date', '>=', now()->startOfWeek()->toDateString())->count();
        $viewsMonth = MangaView::where('view_date', '>=', now()->startOfMonth()->toDateString())->count();
        $views30d   = $chartData->sum('total');
        $viewDelta  = $viewsToday - $viewsYesterday;

        // Rilis chapter terbaru (untuk tabel log upload)
        $recentChapters = Chapter::with('manga:id,title,slug,cover_image,type,user_id', 'manga.user:id,name')
            ->latest()
            ->take(6)
            ->get();

        // Distribusi genre (top 5 berdasar jumlah manga per genre)
        $genreDistribution = DB::table('manga_genres')
            ->join('genres', 'manga_genres.genre_id', '=', 'genres.id')
            ->select('genres.id', 'genres.name', DB::raw('COUNT(*) as total'))
            ->groupBy('genres.id', 'genres.name')
            ->orderByDesc('total')
            ->take(5)
            ->get();
        $genreMax = max(1, $genreDistribution->max('total'));

        // Komentar terbaru (feed moderasi ringan)
        $recentComments = Comment::with('user:id,name', 'manga:id,title,slug')
            ->latest()
            ->take(4)
            ->get();

        // Statistik sekunder & trend
        $bookmarkCount = Bookmark::count();
        $mangasThisMonth = Manga::where('created_at', '>=', now()->startOfMonth())->count();
        $chaptersToday   = Chapter::where('created_at', '>=', now()->startOfDay())->count();
        $usersThisMonth  = User::where('created_at', '>=', now()->startOfMonth())->count();

        return view('admin.dashboard', compact(
            'mangaCount', 'chapterCount', 'userCount', 'latestMangas', 'viewCount', 'commentCount',
            'chartData', 'chartMax', 'viewsToday', 'viewsYesterday', 'viewsWeek', 'viewsMonth', 'views30d', 'viewDelta',
            'recentChapters', 'genreDistribution', 'genreMax', 'recentComments',
            'bookmarkCount', 'mangasThisMonth', 'chaptersToday', 'usersThisMonth'
        ));
    }
}
