<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\CommentReport;
use App\Models\User;
use Illuminate\Http\Request;

class ModerationController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->get('tab', 'reports');

        // KPI
        $pendingCount = CommentReport::where('status', 'pending')->count();
        $bannedUsers  = User::banned()->count();
        $userCount    = User::count();
        $commentCount = Comment::count();
        $resolved24h  = CommentReport::where('status', 'resolved')
            ->where('handled_at', '>=', now()->subDay())->count();

        // Antrean report (komentar kena report, dedupe, status pending)
        $reports = CommentReport::with(['comment.user', 'comment.manga:id,title,slug', 'reporter:id,name'])
            ->where('status', 'pending')
            ->latest()
            ->get()
            ->groupBy('comment_id')
            ->map(function ($group) {
                $first = $group->first();
                return (object) [
                    'comment'       => $first->comment,
                    'reasons'       => $group->pluck('reason')->unique()->values(),
                    'reporterCount' => $group->count(),
                    'latest'        => $first->created_at,
                ];
            })
            ->values();

        // Semua komentar terbaru (tab Komentar) — termasuk yang ga kena report
        $comments = Comment::with(['user:id,name,photo_profile,role,banned_at', 'manga:id,title,slug'])
            ->withCount('reports')
            ->latest()
            ->take(100)
            ->get();

        // User terbaru (tab Pengguna)
        $users = User::withCount('comments')->latest()->take(50)->get();

        return view('admin.moderation.index', compact(
            'tab', 'pendingCount', 'bannedUsers', 'userCount', 'commentCount', 'resolved24h',
            'reports', 'comments', 'users'
        ));
    }

    public function action(Request $request, Comment $comment)
    {
        $validated = $request->validate([
            'action'    => 'required|in:delete,delete_ban,ban,resolve',
            'comment_id' => 'sometimes',
        ]);

        $reports = CommentReport::where('comment_id', $comment->id)->where('status', 'pending')->get();

        switch ($validated['action']) {
            case 'delete':
                // Hapus komentar + balasannya
                $comment->replies()->delete();
                $comment->delete();
                break;

            case 'delete_ban':
                $comment->user?->update(['banned_at' => now()]);
                $comment->replies()->delete();
                $comment->delete();
                break;

            case 'ban':
                $comment->user?->update(['banned_at' => now()]);
                break;

            case 'resolve':
                // Abaikan report, komentar tetap
                break;
        }

        // Tutup semua report pending untuk komentar ini
        CommentReport::where('comment_id', $comment->id)->where('status', 'pending')
            ->update([
                'status'     => $validated['action'] === 'resolve' ? 'dismissed' : 'resolved',
                'handled_by' => auth()->id(),
                'handled_at' => now(),
            ]);

        return back()->with('success', 'Aksi moderasi "' . $validated['action'] . '" diterapkan.');
    }

    public function userAction(Request $request, User $user)
    {
        $validated = $request->validate([
            'action' => 'required|in:ban,unban',
        ]);

        $user->update(['banned_at' => $validated['action'] === 'ban' ? now() : null]);

        return back()->with('success', 'Pengguna ' . ($validated['action'] === 'ban' ? 'di-ban' : 'di-unban') . '.');
    }

    /**
     * Aksi massal moderasi komentar: delete (dari tabel Semua Komentar).
     * ids = comment id. Bisa juga sekalian ban user via action delete_ban.
     */
    public function bulk(Request $request)
    {
        $validated = $request->validate([
            'ids'    => 'required|array',
            'ids.*'  => 'exists:comments,id',
            'action' => 'required|in:delete,delete_ban',
        ]);

        $comments = Comment::whereIn('id', $validated['ids'])->get();

        if ($validated['action'] === 'delete_ban') {
            $userIds = $comments->pluck('user_id')->filter()->unique();
            User::whereIn('id', $userIds)->update(['banned_at' => now()]);
        }

        foreach ($comments as $comment) {
            $comment->replies()->delete();
            $comment->delete();
        }

        // Tutup semua report pending terkait
        CommentReport::whereIn('comment_id', $comments->pluck('id'))
            ->where('status', 'pending')
            ->update(['status' => 'resolved', 'handled_by' => auth()->id(), 'handled_at' => now()]);

        return back()->with('success', count($comments) . ' komentar berhasil dihapus.');
    }
}
