<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\CommentReport;
use App\Models\Manga;
use App\Models\Chapter;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function getMangaComments(Manga $manga)
    {
        $comments = Comment::with(['user', 'replies.user'])
            ->where('manga_id', $manga->id)
            ->whereNull('parent_id')
            ->whereNull('chapter_id')
            ->latest()
            ->paginate(10);

        return response()->json([
            'comments' => $comments,
            'manga' => $manga,
        ]);
    }

    public function getChapterComments(Chapter $chapter)
    {
        $comments = Comment::with(['user', 'replies.user'])
            ->where('chapter_id', $chapter->id)
            ->whereNull('parent_id')
            ->latest()
            ->paginate(10);

        return response()->json([
            'comments' => $comments,
            'chapter' => $chapter,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'content' => 'required|string|max:1000',
            'manga_id' => 'required|exists:mangas,id',
            'chapter_id' => 'nullable|exists:chapters,id',
        ]);

        Comment::create([
            'user_id' => Auth::id(),
            'content' => $validated['content'],
            'manga_id' => $validated['manga_id'],
            'chapter_id' => $request->chapter_id ?? null,
            'parent_id' => null,
        ]);

       return redirect(url()->previous() . '#comments-section')->with('success', 'Komentar berhasil ditambahkan');
    }

    public function reply(Request $request, Comment $comment)
    {
        $validated = $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        Comment::create([
            'user_id' => Auth::id(),
            'content' => $validated['content'],
            'manga_id' => $comment->manga_id,
            'chapter_id' => $comment->chapter_id,
            'parent_id' => $comment->id,
        ]);

       return redirect(url()->previous() . '#comments-section')->with('success', 'Balasan berhasil ditambahkan');
    }

    /**
     * Report sebuah komentar (dari tombol 🚩 di halaman manga/chapter).
     * Satu user hanya bisa report 1x per komentar (unique comment_id + reporter_id).
     */
    public function report(Request $request, Comment $comment)
    {
        // Jangan izinkan report komentar sendiri
        if ($comment->user_id === Auth::id()) {
            return back()->with('error', 'Kamu tidak bisa melaporkan komentarmu sendiri.');
        }

        $validated = $request->validate([
            'reason' => 'required|string|in:spam,pelecehan,spoiler,offensive,lainnya|max:100',
        ]);

        $exists = CommentReport::where('comment_id', $comment->id)
            ->where('reporter_id', Auth::id())
            ->exists();

        if ($exists) {
            return back()->with('error', 'Kamu sudah melaporkan komentar ini.');
        }

        CommentReport::create([
            'comment_id'  => $comment->id,
            'reporter_id' => Auth::id(),
            'reason'      => $validated['reason'],
            'status'      => 'pending',
        ]);

        return back()->with('success', 'Laporan terkirim. Terima kasih, admin akan meninjaunya.');
    }

    public function toggleLike(Comment $comment)
    {
        $likedComments = session()->get('liked_comments', []);

        if (in_array($comment->id, $likedComments)) {
            $comment->decrement('likes_count');
            $likedComments = array_diff($likedComments, [$comment->id]);
            $isLiked = false;
        } else {
            $comment->increment('likes_count');
            $likedComments[] = $comment->id;
            $isLiked = true;
        }
        session(['liked_comments' => $likedComments]);

        return response()->json([
            'success'     => true,
            'liked'       => $isLiked,
            'likes_count' => $comment->refresh()->likes_count,
        ]);
    }
    public function destroy(Comment $comment)
    {

        $comment->replies()->delete();
        $comment->delete();

       return redirect(url()->previous() . '#comments-section')->with('success', 'Komentar dan balasan berhasil dihapus');
    }
}
