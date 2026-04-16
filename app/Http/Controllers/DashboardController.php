<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Manga;
use App\Models\History;
use App\Models\Chapter;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    public function index() 
    {
        $popularMangas = Cache::remember('popular_mangas', 300, function () {
            $popularMangaIds = History::select('manga_id')
                ->selectRaw('COUNT(DISTINCT user_id) as unique_readers_count')
                ->groupBy('manga_id')
                ->havingRaw('COUNT(DISTINCT user_id) > 1')
                ->orderByRaw('COUNT(DISTINCT user_id) DESC')
                ->take(12)
                ->pluck('manga_id');

            if ($popularMangaIds->isEmpty()) return collect();

            return Manga::with('latestPublishedChapter')
                ->withAvg('ratings', 'rating')
                ->whereIn('id', $popularMangaIds)
                ->orderByRaw("CASE id " . $popularMangaIds->values()->map(fn($id, $i) => "WHEN {$id} THEN {$i}")->implode(' ') . " END")
                ->get();
        });

        $mangas = Manga::with(['latestPublishedChapter'])
            ->withAvg('ratings', 'rating')
            ->whereHas('chapters', fn($q) => $q->where('status', 'published'))
            ->orderByDesc(
                Chapter::select('created_at')
                    ->whereColumn('manga_id', 'mangas.id')
                    ->where('status', 'published')
                    ->latest()
                    ->limit(1)
            )
            ->paginate(25);

        return view('dashboard', compact('mangas', 'popularMangas'));
    }
}