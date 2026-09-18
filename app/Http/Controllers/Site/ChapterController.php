<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Chapter;
use App\Models\History;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;

class ChapterController extends Controller
{
    public function show($slug)
    {
        $chapter = Chapter::where('slug', $slug)
                          ->with('manga')
                          ->published()
                          ->firstOrFail();

        // Tracking view manga (chapter dibaca = manga dilihat)
        \App\Services\ViewTrackingService::record($chapter->manga_id);

        if (Auth::check()) {
            // Streak baca harian (1x per hari per user)
            \App\Services\EngagementService::recordRead();

            $history = History::updateOrCreate(
                [
                    'user_id' => Auth::id(),
                    'manga_id' => $chapter->manga_id,
                    'chapter_id' => $chapter->id,
                ],
                [
                    'updated_at' => now()
                ]
            );

            // XP cuma buat chapter yang BARU pertama kali dibaca (anti-farm baca ulang)
            if ($history->wasRecentlyCreated) {
                \App\Services\EngagementService::addXp(Auth::id());
            }
        }

        $comments = Comment::with(['user', 'replies.user', 'replies.parent'])
            ->where('chapter_id', $chapter->id)
            ->whereNull('parent_id')
            ->latest()
            ->get();
        
        $totalCommentsCount = Comment::where('chapter_id', $chapter->id)->count();

         $allChapters = $chapter->manga->chapters()->latest()->get();
        
        $nextChapter = Chapter::where('manga_id', $chapter->manga_id)
                             ->where('number', '>', $chapter->number)
                             ->published()
                             ->orderBy('number', 'asc')
                             ->first();
        
        $prevChapter = Chapter::where('manga_id', $chapter->manga_id)
                             ->where('number', '<', $chapter->number)
                             ->published()
                             ->orderBy('number', 'desc')
                             ->first();
        
        return view('chapter.show', [
            'chapter' => $chapter,
            'prevChapter' => $prevChapter,
            'nextChapter' => $nextChapter,
            'comments' => $comments,
            'totalCommentsCount' => $totalCommentsCount,
            'allChapters' => $allChapters,
        ]);
    }
}