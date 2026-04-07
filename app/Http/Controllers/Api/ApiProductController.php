<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Manga;
use App\Models\History;
use App\Models\Genre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ApiProductController extends Controller
{
    public function index(Request $request)
    {
        try {
            $popularMangaIds = History::query()
                ->select('manga_id', DB::raw('COUNT(DISTINCT user_id) as unique_readers_count'))
                ->groupBy('manga_id')
                ->having('unique_readers_count', '>', 1)
                ->orderByDesc('unique_readers_count')
                ->take(12)
                ->pluck('manga_id');

            $popularMangas = collect();
            
            if ($popularMangaIds->isNotEmpty()) {
                $popularMangas = Manga::with('latestPublishedChapter')
                    ->withAvg('ratings', 'rating')
                    ->whereIn('id', $popularMangaIds)
                    ->orderByRaw(DB::raw("FIELD(id, " . $popularMangaIds->implode(',') . ")"))
                    ->get();
            }
            
            $mangas = Manga::with(['latestPublishedChapter'])
                ->withAvg('ratings', 'rating')
                ->whereHas('chapters', function ($query) {
                    $query->where('status', 'published');
                })
                ->withMax(['latestPublishedChapter as latest_chapter_date' => function ($query) {
                    $query->where('status', 'published');
                }], 'created_at')
                ->orderByDesc('latest_chapter_date')
                ->paginate(25);

            $mangas->getCollection()->transform(function ($manga) {
                $manga->ratings_avg_rating = round($manga->ratings_avg_rating, 2);
                return $manga;
            });

            $popularMangas->transform(function ($manga) {
                $manga->ratings_avg_rating = round($manga->ratings_avg_rating, 2);
                return $manga;
            });

            return response()->json([
                'mangas' => $mangas,
                'popularMangas' => $popularMangas,
            ]);

        } catch (\Throwable $th) {
            Log::error($th);
            return response()->json(['message' => 'Internal Server Error'], 500);
        }
    }
    
    public function mangaList(Request $request)
    {
        $query = Manga::query()->with(['latestPublishedChapter', 'genres'])->withAvg('ratings', 'rating');

        if ($request->filled('q')) {
            $query->where('title', 'like', '%' . $request->input('q') . '%');
        }

        if ($request->filled('genre')) {
            $genreNames = explode('%', $request->genre);
            $query->whereHas('genres', fn($q) => $q->whereIn('genres.name', $genreNames));
        }

        if ($request->filled('status') && $request->status != 'all') {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('type') && $request->type != 'all') {
            $query->where('type', 'like', $request->type);
        }

        match ($request->input('order', 'updated')) {
            'popularity' => $query->withCount('bookmarks')->orderByDesc('bookmarks_count'),
            'rating' => $query->orderByDesc('ratings_avg_rating'),
            'a-z' => $query->orderBy('title', 'asc'),
            'z-a' => $query->orderBy('title', 'desc'),
            'newest' => $query->latest('created_at'),
            default => $query->latest('updated_at'),
        };

        $mangas = $query->paginate(50)->withQueryString();
        $genres = Genre::orderBy('name')->get();

        return response()->json(['mangas' => $mangas, 'genres' => $genres]);
    }

    public function show(Request $request, Manga $manga)
    {
        $manga->load([
            'genres', 
            'user', 
            'comments' => fn($q) => $q->whereNull('parent_id')->with(['user', 'replies.user'])->latest()
        ]);
        
        $chapters = $manga->chapters()->published()->orderBy('number', 'desc')->get();
        
        $user = $request->user();
        $isBookmarked = false;
        $readChapters = [];
        $userHistories = [];

        if ($user) {
            $isBookmarked = $manga->bookmarks()->where('user_id', $user->id)->exists();
            
            $readChapters = $user->histories()->where('manga_id', $manga->id)->pluck('chapter_id')->all();

            $userHistories = $user->histories()
                ->where('manga_id', $manga->id)
                ->with('chapter:id,slug,number')
                ->latest('updated_at')
                ->take(5)
                ->get();
        }

        return response()->json([
            'manga' => $manga,
            'chapters' => $chapters,
            'isBookmarked' => $isBookmarked,
            'readChapters' => $readChapters,
            'userHistories' => $userHistories,
        ]);
    }
}