<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Chapter;
use App\Models\History;
use App\Models\Comment;
use Illuminate\Http\Request;

class ApiChapterController extends Controller
{
    public function showApi(Request $request, Chapter $chapter)
    {
        $chapter->load('manga');

        // Only save history if user is authenticated
        if ($request->user()) {
            History::updateOrCreate(
                [
                    'user_id' => $request->user()->id,
                    'manga_id' => $chapter->manga_id,
                    'chapter_id' => $chapter->id,
                ],
                ['updated_at' => now()]
            );
        }
        
        $mangaChapters = $chapter->manga->chapters()
                              ->published()
                              ->orderBy('created_at', 'asc')
                              ->get(['id', 'slug', 'number', 'created_at']);

        $currentCreatedAt = $chapter->created_at;
        
        $prevChapter = $chapter->manga->chapters()
                              ->published()
                              ->where('created_at', '<', $currentCreatedAt)
                              ->orderBy('created_at', 'desc')
                              ->first(['id', 'slug', 'number']);
                              
        $nextChapter = $chapter->manga->chapters()
                              ->published()
                              ->where('created_at', '>', $currentCreatedAt)
                              ->orderBy('created_at', 'asc')
                              ->first(['id', 'slug', 'number']);
        
        return response()->json([
            'chapter' => $chapter,
            'prev_chapter' => $prevChapter,
            'next_chapter' => $nextChapter,
            'all_chapters' => $mangaChapters,
        ]);
    }
    public function getComments(Request $request, Chapter $chapter)
    {
        $user = $request->user();

        $comments = Comment::where('chapter_id', $chapter->id)
            ->whereNull('parent_id')
            ->with(['user', 'replies.user', 'likes'])
            ->latest()
            ->paginate(15);

        $comments->getCollection()->transform(function ($comment) use ($user) {
            if ($user) {
                $comment->is_liked_by_user = $comment->likes()->where('user_id', $user->id)->exists();
            } else {
                $comment->is_liked_by_user = false;
            }

            $comment->replies->transform(function ($reply) use ($user) {
                if ($user) {
                    $reply->is_liked_by_user = $reply->likes()->where('user_id', $user->id)->exists();
                } else {
                    $reply->is_liked_by_user = false;
                }
                return $reply;
            });
            
            return $comment;
        });

        return response()->json($comments);
    }
}