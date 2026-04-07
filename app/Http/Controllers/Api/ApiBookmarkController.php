<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Bookmark;
use App\Models\Manga;
use Illuminate\Http\Request;

class ApiBookmarkController extends Controller
{
    public function index(Request $request)
    {
        $bookmarks = $request->user()->bookmarks()
            ->with([
                'manga' => function ($query) {
                    $query->withAvg('ratings', 'rating');
                }, 
                'manga.latestPublishedChapter'
            ])
            ->latest()
            ->paginate(24);

        return response()->json($bookmarks);
    }

    public function toggle(Request $request, Manga $manga)
    {
        $user = $request->user();

        $bookmark = $user->bookmarks()->where('manga_id', $manga->id)->first();

        if ($bookmark) {
            $bookmark->delete();
            $isBookmarked = false;
            $message = 'Bookmark berhasil dihapus';
        } else {
            $user->bookmarks()->create(['manga_id' => $manga->id]);
            $isBookmarked = true;
            $message = 'Bookmark berhasil ditambahkan';
        }

        $followersCount = $manga->bookmarks()->count();

        return response()->json([
            'message' => $message,
            'is_bookmarked' => $isBookmarked,
            'followers_count' => $followersCount
        ]);
    }

    public function destroy(Request $request, Bookmark $bookmark)
    {
        if ($bookmark->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        
        $bookmark->delete();

        return response()->json(['message' => 'Bookmark berhasil dihapus.']);
    }
}