<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Bookmark;
use App\Models\Comment;
use App\Models\History;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function show(Request $request): View
    {
        $user = $request->user()->loadCount([
            'bookmarks',
            'histories',
            'comments',
        ]);

        $recentBookmarks = $user->bookmarks()
            ->with(['manga.latestPublishedChapter'])
            ->latest()
            ->limit(8)
            ->get();

        $recentHistories = History::where('user_id', $user->id)
            ->with(['manga', 'chapter'])
            ->latest()
            ->limit(8)
            ->get();

        $favoriteGenres = DB::table('histories')
            ->join('manga_genres', 'histories.manga_id', '=', 'manga_genres.manga_id')
            ->join('genres', 'manga_genres.genre_id', '=', 'genres.id')
            ->where('histories.user_id', $user->id)
            ->select('genres.name', DB::raw('COUNT(*) as total'))
            ->groupBy('genres.id', 'genres.name')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        $recentComments = $user->comments()
            ->with(['manga:id,title,slug'])
            ->latest()
            ->limit(5)
            ->get();

        return view('profile.show', compact('user', 'recentBookmarks', 'recentHistories', 'favoriteGenres', 'recentComments'));
    }

    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
