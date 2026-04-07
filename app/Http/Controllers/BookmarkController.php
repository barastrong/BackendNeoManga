<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bookmark;
use App\Models\Manga;
use Illuminate\Support\Facades\Auth;

class BookmarkController extends Controller
{
    public function toggle(Request $request, $mangaId)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'You must be logged in to bookmark manga'
            ], 401);
        }

        $manga = Manga::findOrFail($mangaId);
        $userId = Auth::id();

        $existingBookmark = Bookmark::where('user_id', $userId)
                                   ->where('manga_id', $mangaId)
                                   ->first();

        if ($existingBookmark) {
            $existingBookmark->delete();
            $isBookmarked = false;
            $message = 'Bookmark removed successfully';
        } else {
            Bookmark::create([
                'user_id' => $userId,
                'manga_id' => $mangaId,
                'bookmark' => true
            ]);
            $isBookmarked = true;
            $message = 'Bookmark added successfully';
        }

        $followersCount = $manga->bookmarks()->count();

        return response()->json([
            'success' => true,
            'message' => $message,
            'is_bookmarked' => $isBookmarked,
            'followers_count' => $followersCount
        ]);
    }

    public function index()
    {
        $bookmarks = Bookmark::where('user_id', Auth::id())
                            ->with(['manga.latestPublishedChapter'])
                            ->orderBy('created_at', 'desc')
                            ->paginate(12);

        return view('manga.bookmark', compact('bookmarks'));
    }

    public function destroy(Bookmark $bookmark)
    {
        if ($bookmark->user_id !== Auth::id()) {
            abort(403);
        }
        $bookmark->delete();
        return redirect()->back()->with('success', 'Bookmark removed successfully.');
    }
}