<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::withCount('comments')->latest()->paginate(15);
        $stats = [
            'total'     => User::count(),
            'admins'    => User::where('role', 'admin')->count(),
            'banned'    => User::banned()->count(),
            'thisMonth' => User::where('created_at', '>=', now()->startOfMonth())->count(),
        ];
        return view('admin.user.index', compact('users', 'stats'));
    }

    /**
     * Aksi massal user: ban / unban / delete (non-admin saja).
     */
    public function bulk(Request $request)
    {
        $validated = $request->validate([
            'ids'    => 'required|array',
            'ids.*'  => 'exists:users,id',
            'action' => 'required|in:ban,unban,delete',
        ]);

        $users = User::whereIn('id', $validated['ids'])->where('role', '!=', 'admin')->get();
        $count = $users->count();

        if ($validated['action'] === 'delete') {
            foreach ($users as $user) {
                $user->delete();
            }
            $msg = "$count user berhasil dihapus.";
        } else {
            $users->each->update(['banned_at' => $validated['action'] === 'ban' ? now() : null]);
            $msg = $validated['action'] === 'ban'
                ? "$count user berhasil di-ban."
                : "$count user berhasil di-unban.";
        }

        return back()->with('success', $msg);
    }
}
