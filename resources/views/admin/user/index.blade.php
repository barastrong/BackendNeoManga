@extends('layouts.admin')

@section('title', 'Users — Admin NeoManga')
@section('page-title', 'Users')

@section('content')
<link rel="stylesheet" href="{{ asset('css/admin/user/index.css') }}">
<div>
    {{-- HEADER --}}
    <div class="flex flex-wrap items-end justify-between gap-4">
        <div>
            <div class="flex items-center gap-2">
                <span class="us-eyebrow"><i class="fa-solid fa-users mr-1.5"></i>Pengguna</span>
                <span class="text-slate-600 text-xs">•</span>
                <span class="text-[11px] font-semibold tracking-wide text-brand/90 uppercase">Akses &amp; Peran</span>
            </div>
            <h1 class="font-display text-[26px] lg:text-3xl font-bold text-white tracking-tight mt-1.5">Daftar Pengguna</h1>
            <p class="text-sm text-slate-400 mt-1">Kelola semua akun yang terdaftar di NeoManga.</p>
        </div>
    </div>

    {{-- STATS --}}
    <div class="mt-6 grid grid-cols-2 xl:grid-cols-4 gap-3.5">
        <div class="us-stat">
            <span class="ic" style="background:rgba(255,46,77,.13);color:#ff2e4d"><i class="fa-solid fa-users"></i></span>
            <div><p class="lbl">Total Pengguna</p><p class="val">{{ number_format($stats['total']) }}</p></div>
        </div>
        <div class="us-stat">
            <span class="ic" style="background:rgba(56,189,248,.13);color:#38bdf8"><i class="fa-solid fa-user-shield"></i></span>
            <div><p class="lbl">Admin</p><p class="val">{{ number_format($stats['admins']) }}</p></div>
        </div>
        <div class="us-stat">
            <span class="ic" style="background:rgba(251,191,36,.13);color:#fbbf24"><i class="fa-solid fa-user-plus"></i></span>
            <div><p class="lbl">Baru Bulan Ini</p><p class="val">{{ number_format($stats['thisMonth']) }}</p></div>
        </div>
        <div class="us-stat">
            <span class="ic" style="background:rgba(244,63,94,.13);color:#fb7185"><i class="fa-solid fa-user-slash"></i></span>
            <div><p class="lbl">Banned</p><p class="val">{{ number_format($stats['banned']) }}</p></div>
        </div>
    </div>

    {{-- TABLE --}}
    <div class="us-card mt-6">
        <div class="us-toolbar px-5 py-4" style="border-bottom:1px solid rgba(255,255,255,.05)">
            <div class="flex items-center gap-2.5">
                <h2 class="font-display text-[15px] font-semibold text-white flex items-center gap-2">
                    <i class="fa-solid fa-address-book text-brand"></i>Semua Akun
                </h2>
                <span class="us-pill" style="background:rgba(255,46,77,.12);color:#ff8a9c">{{ $users->total() }} total</span>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full" style="border-collapse:collapse;font-size:13.5px;min-width:680px"
                   data-bulk-form="{{ route('admin.user.bulk') }}"
                   data-bulk-actions='{{ json_encode([["value"=>"ban","label"=>"🚫 Ban"],["value"=>"unban","label"=>"🔓 Unban"],["value"=>"delete","label"=>"🗑 Hapus"]]) }}'>
                <thead>
                    <tr>
                        <th class="us-th">Pengguna</th>
                        <th class="us-th">Role</th>
                        <th class="us-th text-center">Komentar</th>
                        <th class="us-th">Status</th>
                        <th class="us-th">Bergabung</th>
                        <th class="us-th text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                        <tr class="us-tr" data-id="{{ $user->id }}">
                            <td class="us-td">
                                <div class="flex items-center gap-3">
                                    <img class="h-9 w-9 rounded-full object-cover ring-2 ring-white/10"
                                         src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=ff2e4d&color=fff"
                                         alt="{{ $user->name }}">
                                    <div class="min-w-0">
                                        <p class="font-semibold text-white truncate max-w-[180px]">{{ $user->name }}</p>
                                        <p class="text-[11px] text-slate-500 truncate max-w-[200px]">{{ $user->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="us-td">
                                @if($user->role === 'admin')
                                    <span class="us-pill" style="background:rgba(255,46,77,.12);color:#ff8a9c"><i class="fa-solid fa-shield-halved text-[9px] mr-1"></i>Admin</span>
                                @else
                                    <span class="us-pill" style="background:rgba(255,255,255,.05);color:#94a3b8"><i class="fa-regular fa-user text-[9px] mr-1"></i>Member</span>
                                @endif
                            </td>
                            <td class="us-td text-center">
                                <span class="us-pill" style="background:rgba(56,189,248,.1);color:#38bdf8">
                                    <i class="fa-regular fa-comment text-[9px] mr-1"></i>{{ $user->comments_count ?? 0 }}
                                </span>
                            </td>
                            <td class="us-td">
                                @if($user->isBanned())
                                    <span class="us-pill" style="background:rgba(244,63,94,.12);color:#fb7185"><i class="fa-solid fa-circle text-[6px] mr-1"></i>Banned</span>
                                @elseif($user->email_verified_at)
                                    <span class="us-pill" style="background:rgba(52,211,153,.12);color:#34d399"><i class="fa-solid fa-circle text-[6px] mr-1"></i>Verified</span>
                                @else
                                    <span class="us-pill" style="background:rgba(251,191,36,.12);color:#fbbf24"><i class="fa-solid fa-circle text-[6px] mr-1"></i>Unverified</span>
                                @endif
                            </td>
                            <td class="us-td text-slate-500 text-xs whitespace-nowrap">{{ $user->created_at->format('d M Y') }}</td>
                            <td class="us-td text-right whitespace-nowrap">
                                <a href="#" class="us-ico-btn edit" title="Edit"><i class="fa-solid fa-pen"></i></a>
                                <a href="#" class="us-ico-btn danger" title="Hapus"><i class="fa-solid fa-trash-can"></i></a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                <div class="us-empty">
                                    <i class="fa-solid fa-users ic"></i>
                                    <p class="mt-3 font-semibold text-white text-sm">Tidak Ada User Ditemukan</p>
                                    <p class="text-sm mt-1 text-slate-500">Data pengguna masih kosong.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- PAGINATION --}}
    @if ($users->hasPages())
        <div class="mt-5 flex items-center justify-between flex-wrap gap-3">
            <p class="text-xs" style="color:#64748b">
                Menampilkan <span class="text-slate-300 font-semibold">{{ $users->firstItem() }}</span>–
                <span class="text-slate-300 font-semibold">{{ $users->lastItem() }}</span> dari
                <span class="text-slate-300 font-semibold">{{ $users->total() }}</span>
            </p>
            <div class="flex gap-1.5">
                @if($users->onFirstPage())
                    <span class="us-pag dis"><i class="fa-solid fa-chevron-left text-[10px]"></i></span>
                @else
                    <a href="{{ $users->previousPageUrl() }}" class="us-pag"><i class="fa-solid fa-chevron-left text-[10px]"></i></a>
                @endif
                @for($i = max(1, $users->currentPage() - 2); $i <= min($users->lastPage(), $users->currentPage() + 2); $i++)
                    @if($i == $users->currentPage())
                        <span class="us-pag cur">{{ $i }}</span>
                    @else
                        <a href="{{ $users->url($i) }}" class="us-pag">{{ $i }}</a>
                    @endif
                @endfor
                @if($users->hasMorePages())
                    <a href="{{ $users->nextPageUrl() }}" class="us-pag"><i class="fa-solid fa-chevron-right text-[10px]"></i></a>
                @else
                    <span class="us-pag dis"><i class="fa-solid fa-chevron-right text-[10px]"></i></span>
                @endif
            </div>
        </div>
    @endif
</div>
@endsection