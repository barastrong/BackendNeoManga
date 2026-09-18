@extends('layouts.admin')

@section('title', $title . ' — Admin NeoManga')
@section('page-title', $title)

@section('content')
<link rel="stylesheet" href="{{ asset('css/admin/user/index.css') }}">
<div class="max-w-2xl">
    {{-- HEADER --}}
    <div class="flex flex-wrap items-end justify-between gap-4">
        <div>
            <div class="flex items-center gap-2">
                <span class="us-eyebrow"><i class="fa-solid fa-user-pen mr-1.5"></i>{{ $user ? 'Edit' : 'Baru' }}</span>
            </div>
            <h1 class="font-display text-[26px] lg:text-3xl font-bold text-white tracking-tight mt-1.5">{{ $title }}</h1>
            <p class="text-sm text-slate-400 mt-1">Kelola data akun pengguna NeoManga.</p>
        </div>
        <a href="{{ route('admin.user.index') }}" class="us-ico-btn">
            <i class="fa-solid fa-arrow-left"></i>Kembali
        </a>
    </div>

    {{-- FORM --}}
    <div class="us-card mt-6">
        <div class="px-6 py-5" style="border-bottom:1px solid rgba(255,255,255,.05)">
            <h2 class="font-display text-[15px] font-semibold text-white flex items-center gap-2">
                <i class="fa-solid fa-address-card text-brand"></i>Data User
            </h2>
        </div>
        <div class="p-6">
            <form method="POST" action="{{ $user ? route('admin.user.update', $user) : route('admin.user.store') }}" class="space-y-5">
                @csrf
                @if($user) @method('PUT') @endif

                @if($errors->any())
                    <div class="rounded-xl bg-red-500/10 border border-red-500/30 px-4 py-3 text-sm text-red-300">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wide text-slate-400 mb-1.5">Nama</label>
                    <input type="text" name="name" value="{{ old('name', $user->name ?? '') }}" required
                           class="w-full px-4 py-2.5 rounded-xl bg-white/5 border border-white/10 text-white text-sm focus:ring-2 focus:ring-[#ff2e4d]/60 focus:border-[#ff2e4d] transition outline-none"
                           placeholder="Nama lengkap user">
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wide text-slate-400 mb-1.5">Email</label>
                    <input type="email" name="email" value="{{ old('email', $user->email ?? '') }}" required
                           class="w-full px-4 py-2.5 rounded-xl bg-white/5 border border-white/10 text-white text-sm focus:ring-2 focus:ring-[#ff2e4d]/60 focus:border-[#ff2e4d] transition outline-none"
                           placeholder="user@example.com">
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wide text-slate-400 mb-1.5">
                        Password {{ $user ? '(kosongkan jika tidak diganti)' : '' }}
                    </label>
                    <input type="password" name="password" {{ $user ? '' : 'required' }}
                           class="w-full px-4 py-2.5 rounded-xl bg-white/5 border border-white/10 text-white text-sm focus:ring-2 focus:ring-[#ff2e4d]/60 focus:border-[#ff2e4d] transition outline-none"
                           placeholder="{{ $user ? '••••••••' : 'Minimal 8 karakter' }}">
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wide text-slate-400 mb-1.5">Role</label>
                    <select name="role" {{ $user && $user->role === 'admin' ? 'disabled' : '' }}
                            class="w-full px-4 py-2.5 rounded-xl bg-white/5 border border-white/10 text-white text-sm focus:ring-2 focus:ring-[#ff2e4d]/60 focus:border-[#ff2e4d] transition outline-none appearance-none">
                        <option value="user" class="bg-[#131a2c]" {{ old('role', $user->role ?? 'user') === 'user' ? 'selected' : '' }}>Member</option>
                        <option value="admin" class="bg-[#131a2c]" {{ old('role', $user->role ?? '') === 'admin' ? 'selected' : '' }}>Admin</option>
                    </select>
                    @if($user && $user->role === 'admin')
                        <input type="hidden" name="role" value="admin">
                        <p class="text-[11px] text-slate-500 mt-1.5">Role admin tidak bisa diubah dari panel ini.</p>
                    @endif
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-[#ff2e4d] hover:bg-[#e62242] text-white text-sm font-bold px-5 py-2.5 transition">
                        <i class="fa-solid fa-floppy-disk"></i>{{ $user ? 'Simpan Perubahan' : 'Tambah User' }}
                    </button>
                    <a href="{{ route('admin.user.index') }}" class="us-ico-btn h-10 px-4">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection