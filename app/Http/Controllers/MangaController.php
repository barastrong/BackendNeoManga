<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Manga;
use App\Models\Chapter;
use App\Models\History;
use Illuminate\Support\Facades\Auth;
use App\Models\Genre;

class MangaController extends Controller
{
    public function mangaList(Request $request)
    {
        $query = Manga::query();

        if ($request->filled('genre') && is_array($request->genre)) {
            $query->whereHas('genres', function ($q) use ($request) {
                $q->whereIn('genres.id', $request->genre);
            });
        }

        if ($request->filled('status') && $request->status != 'all') {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('type') && $request->type != 'all') {
            $query->where('type', $request->type);
        }

        $order = $request->input('order', 'default');

        switch ($order) {
            case 'updated':
                $query->whereHas('chapters', function ($q) {
                        $q->where('status', 'published');
                    })
                    ->withMax(['latestPublishedChapter as latest_chapter_date' => function ($q) {
                        $q->where('status', 'published');
                    }], 'created_at')
                    ->orderByDesc('latest_chapter_date');
                break;
            case 'popularity':
                $query->withCount('bookmarks')->orderByDesc('bookmarks_count');
                break;
            case 'rating':
                $query->withAvg('ratings', 'rating')->orderByDesc('ratings_avg_rating');
                break;
            case 'a-z':
                $query->orderBy('title', 'asc');
                break;
            case 'z-a':
                $query->orderBy('title', 'desc');
                break;
            case 'newest':
                $query->latest('created_at');
                break;
            default:
                $query->latest('created_at');
                break;
        }

        $mangas = $query->with('latestPublishedChapter')
                        ->withAvg('ratings', 'rating')
                        ->paginate(50)
                        ->withQueryString();

        $genres = Genre::orderBy('name')->get();

        return view('manga.list', compact('mangas', 'genres'));
    }

    public function show($slug)
    {
        $manga = Manga::where('slug', $slug)
                      ->with(['genres', 'user', 'bookmarks', 'comments.user', 'comments.replies.user'])
                      ->firstOrFail();
        
        $chapters = Chapter::where('manga_id', $manga->id)
                          ->published()
                          ->orderBy('number', 'asc')
                          ->get();
        
        $isBookmarked = false;
        $readChapters = [];
        $userHistories = collect();

        if (Auth::check()) {
            $user = Auth::user();
            $isBookmarked = $manga->isBookmarkedBy($user->id);
            
            $readChapters = History::where('user_id', $user->id)
                                  ->where('manga_id', $manga->id)
                                  ->pluck('chapter_id')
                                  ->toArray();

            $userHistories = History::with('chapter')
                ->where('user_id', $user->id)
                ->where('manga_id', $manga->id)
                ->where('updated_at', '>=', now()->subDays(5))
                ->latest('updated_at')
                ->take(10) 
                ->get();
            
        }
        
        return view('manga.show', compact('manga', 'chapters', 'isBookmarked', 'readChapters', 'userHistories'));
    }

        public function search(Request $request)
    {
        $searchTerm = $request->input('q');

        if (empty($searchTerm)) {
            return redirect()->route('manga.list');
        }

        $query = Manga::query()->where('title', 'like', '%' . $searchTerm . '%');

        $mangas = $query->with('latestPublishedChapter')
                        ->withAvg('ratings', 'rating')
                        ->paginate(50)
                        ->withQueryString();

        $genres = Genre::orderBy('name')->get();

        return view('manga.list', compact('mangas', 'genres', 'searchTerm'));
    }
}