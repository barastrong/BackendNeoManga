<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;

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
}
