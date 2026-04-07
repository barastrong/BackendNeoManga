<?php

namespace App\Http\Controllers;

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

        if (Auth::check()) {
            History::updateOrCreate(
                [
                    'user_id' => Auth::id(),
                    'manga_id' => $chapter->manga_id,
                    'chapter_id' => $chapter->id,
                ],
                [
                    'updated_at' => now()
                ]
            );
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