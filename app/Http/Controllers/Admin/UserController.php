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

    public function create()
    {
        return view('admin.user.form', ['user' => null, 'title' => 'Tambah User']);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|lowercase|email|max:255|unique:users,email',
            'password' => 'required|string|min:8',
            'role'     => 'required|in:user,admin',
        ]);

        $user = User::create([
            'name'              => $validated['name'],
            'email'             => $validated['email'],
            'password'          => bcrypt($validated['password']),
            'role'              => $validated['role'],
            'email_verified'    => 1,
            'email_verified_at' => now(),
        ]);

        return redirect()->route('admin.user.index')
            ->with('success', "User \"{$user->name}\" berhasil ditambahkan.");
    }

    public function edit(User $user)
    {
        return view('admin.user.form', ['user' => $user, 'title' => 'Edit User']);
    }

    public function update(Request $request, User $user)
    {
        // admin tidak bisa diedit lewat panel ini
        if ($user->role === 'admin') {
            return back()->with('error', 'Akun admin tidak bisa diedit di sini.');
        }

        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => ['required', 'string', 'lowercase', 'email', 'max:255', \Illuminate\Validation\Rule::unique('users', 'email')->ignore($user->id)],
            'password' => 'nullable|string|min:8',
            'role'     => 'required|in:user,admin',
        ]);

        $user->name  = $validated['name'];
        $user->email = $validated['email'];
        $user->role  = $validated['role'];
        if (!empty($validated['password'])) {
            $user->password = bcrypt($validated['password']);
        }
        $user->save();

        return redirect()->route('admin.user.index')
            ->with('success', "User \"{$user->name}\" berhasil diperbarui.");
    }

    public function destroy(User $user)
    {
        if ($user->role === 'admin') {
            return back()->with('error', 'Akun admin tidak bisa dihapus.');
        }

        $name = $user->name;
        $user->delete();

        return back()->with('success', "User \"$name\" berhasil dihapus.");
    }

    public function verify(User $user)
    {
        $user->email_verified = 1;
        $user->email_verified_at = now();
        $user->otp_code = null;
        $user->otp_expires_at = null;
        $user->save();

        return back()->with('success', "Email \"{$user->email}\" berhasil diverifikasi.");
    }
}
