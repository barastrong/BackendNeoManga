<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;
use App\Models\User;

class ApiCommentController extends Controller
{
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'content' => 'required|string|max:1000',
                'manga_id' => 'required|exists:mangas,id',
                'chapter_id' => 'required|exists:chapters,id',
                'parent_id' => 'nullable|exists:comments,id',
            ]);

            $user = $request->user();
            
            if (!$user) {
                return response()->json([
                    'message' => 'Authentication required',
                    'error' => 'User must be logged in to comment'
                ], 401);
            }

            $comment = $user->comments()->create($validated);
            $comment->load(['user', 'replies.user', 'manga', 'chapter']);
            
            \Log::info('Comment created successfully', [
                'comment_id' => $comment->id,
                'manga_title' => $comment->manga->title ?? 'Unknown',
                'chapter_number' => $comment->chapter->number ?? 'Unknown',
                'manga_id' => $validated['manga_id'],
                'chapter_id' => $validated['chapter_id'],
                'user_id' => $user->id,
                'location' => "Comment posted in manga '{$comment->manga->title}' chapter {$comment->chapter->number}"
            ]);
            
            return response()->json($comment->load('user', 'replies.user'), 201);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Comment validation failed', [
                'errors' => $e->errors(),
                'request_data' => $request->all()
            ]);
            
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
            
        } catch (\Exception $e) {
            \Log::error('Comment creation failed', [
                'error' => $e->getMessage(),
                'request_data' => $request->all()
            ]);
            
            return response()->json([
                'message' => 'Error creating comment',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function toggleLike(Request $request, Comment $comment)
    {
        $user = $request->user();

        $result = $user->likedComments()->toggle($comment->id);

        $isLikedNow = count($result['attached']) > 0;

        $newLikesCount = $comment->likes()->count();

        $comment->likes_count = $newLikesCount;
        $comment->save();

        return response()->json([
            'liked' => $isLikedNow,
            'likes_count' => $newLikesCount,
        ]);
    }
    public function destroy(Request $request, Comment $comment)
    {
        if (!$request->user()->isAdmin()) {
            return response()->json(['message' => 'Hanya admin yang dapat menghapus komentar.'], 403);
        }
        $comment->delete();
        return response()->json(null, 204);
    }
}