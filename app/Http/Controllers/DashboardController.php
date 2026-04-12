<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Manga;
use App\Models\History;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index() 
    {
        $popularMangaIds = History::query()
            ->select('manga_id')
            ->selectRaw('COUNT(DISTINCT user_id) as unique_readers_count')
            ->groupBy('manga_id')
            ->havingRaw('COUNT(DISTINCT user_id) > 1')
            ->orderByRaw('COUNT(DISTINCT user_id) DESC')
            ->take(12)
            ->pluck('manga_id');

        $popularMangas = collect();
        
        if ($popularMangaIds->isNotEmpty()) {
            $popularMangas = Manga::with('latestPublishedChapter')
                ->withAvg('ratings', 'rating')
                ->withCount('histories')
                ->whereIn('id', $popularMangaIds)
                ->orderByRaw("CASE id " . $popularMangaIds->values()->map(fn($id, $i) => "WHEN {$id} THEN {$i}")->implode(' ') . " END")
                ->get();
        }
        
        $mangas = Manga::with(['genres', 'latestPublishedChapter'])
            ->withAvg('ratings', 'rating')
            ->whereHas('chapters', function ($query) {
                $query->where('status', 'published');
            })
            ->withMax(['latestPublishedChapter as latest_chapter_date' => function ($query) {
                $query->where('status', 'published');
            }], 'created_at')
            ->orderByDesc('latest_chapter_date')
            ->paginate(25);

        return view('dashboard', compact('mangas', 'popularMangas'));
    }
}