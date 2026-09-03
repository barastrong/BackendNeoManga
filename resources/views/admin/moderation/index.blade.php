@extends('layouts.admin')

@section('title', 'Moderasi Komentar — Admin NeoManga')
@section('page-title', 'Moderasi')

@section('content')
<link rel="stylesheet" href="{{ asset('css/admin/moderation/index.css') }}">

{{-- ============ HEADER ============ --}}
<div class="flex flex-wrap items-end justify-between gap-4">
    <div>
        <div class="flex items-center gap-2">
            <span class="mx-eyebrow"><i class="fa-solid fa-shield-halved mr-1.5"></i>Community Engine</span>
            <span class="text-slate-600 text-xs">•</span>
            <span class="text-[11px] font-semibold tracking-wide text-emerald-400/90 uppercase">Live Moderation</span>
        </div>
        <h1 class="font-display text-[26px] lg:text-3xl font-bold text-white tracking-tight mt-1.5">Moderasi Komentar &amp; Pengguna</h1>
        <p class="text-sm text-slate-400 mt-1">Tinjau laporan pembaca, kelola komentar, dan tindak akun bermasalah.</p>
    </div>
    <div class="flex items-center gap-3 adm-chip px-4 py-2.5 rounded-xl">
        <span class="mx-live-dot"></span>
        <span class="text-xs text-slate-200 font-semibold">Automod Engine Aktif</span>
        <span class="w-px h-4 bg-white/10"></span>
        <span class="font-mono text-[11px] text-slate-400">Filter: Standar</span>
    </div>
</div>

{{-- ============ TAB NAV ============ --}}
<div class="mx-tabs mt-6">
    <button class="mx-tab active" data-panel="panel-reports">
        <i class="fa-solid fa-flag"></i>Laporan Komentar <span class="badge b-red">{{ $pendingCount }}</span>
    </button>
    <button class="mx-tab" data-panel="panel-comments">
        <i class="fa-solid fa-comments"></i>Semua Komentar
    </button>
    <button class="mx-tab" data-panel="panel-users">
        <i class="fa-solid fa-users"></i>Pengguna <span class="badge">{{ number_format($userCount) }}</span>
    </button>
    <button class="mx-tab" data-panel="panel-banned">
        <i class="fa-solid fa-ban"></i>Di-ban <span class="badge b-red">{{ $bannedUsers }}</span>
    </button>
</div>

{{-- ============ KPI ============ --}}
<div class="mt-5 grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
    <div class="mx-kpi">
        <div class="glow" style="background:#ff2e4d"></div>
        <div class="flex items-start justify-between">
            <span class="mx-kpi-label">Perlu Review</span>
            <span class="p-2 rounded-lg" style="background:rgba(255,46,77,.14);color:#ff2e4d"><i class="fa-solid fa-flag text-xs"></i></span>
        </div>
        <p class="mx-kpi-num mt-2">{{ $pendingCount }}</p>
        <p class="text-xs text-slate-500 mt-0.5">laporan menunggu tindakan</p>
        <div class="mx-progress mt-3"><i style="width:{{ min(100, $pendingCount * 14) }}%;background:#ff2e4d"></i></div>
    </div>
    <div class="mx-kpi">
        <div class="glow" style="background:#38bdf8"></div>
        <div class="flex items-start justify-between">
            <span class="mx-kpi-label">Total Komentar</span>
            <span class="p-2 rounded-lg" style="background:rgba(56,189,248,.14);color:#38bdf8"><i class="fa-solid fa-comment text-xs"></i></span>
        </div>
        <p class="mx-kpi-num mt-2">{{ number_format($commentCount) }}</p>
        <p class="text-xs text-slate-500 mt-0.5">tersebar di semua manga</p>
        <div class="mx-progress mt-3"><i style="width:100%;background:linear-gradient(90deg,#38bdf8,#818cf8)"></i></div>
    </div>
    <div class="mx-kpi">
        <div class="glow" style="background:#34d399"></div>
        <div class="flex items-start justify-between">
            <span class="mx-kpi-label">Pengguna Terdaftar</span>
            <span class="p-2 rounded-lg" style="background:rgba(52,211,153,.14);color:#34d399"><i class="fa-solid fa-user text-xs"></i></span>
        </div>
        <p class="mx-kpi-num mt-2">{{ number_format($userCount) }}</p>
        <p class="text-xs text-slate-500 mt-0.5">{{ $bannedUsers }} di antaranya di-ban</p>
        <div class="mx-progress mt-3"><i style="width:96%;background:linear-gradient(90deg,#34d399,#a3e635)"></i></div>
    </div>
    <div class="mx-kpi">
        <div class="glow" style="background:#a78bfa"></div>
        <div class="flex items-start justify-between">
            <span class="mx-kpi-label">Ditangani 24 Jam</span>
            <span class="p-2 rounded-lg" style="background:rgba(167,139,250,.14);color:#a78bfa"><i class="fa-solid fa-check text-xs"></i></span>
        </div>
        <p class="mx-kpi-num mt-2">{{ number_format($resolved24h) }}</p>
        <p class="text-xs text-slate-500 mt-0.5">laporan selesai ditindak</p>
        <div class="mx-progress mt-3"><i style="width:{{ min(100, $resolved24h * 25) }}%;background:linear-gradient(90deg,#a78bfa,#f472b6)"></i></div>
    </div>
</div>

{{-- ============ PANEL: LAPORAN ============ --}}
<div class="mx-panel active mt-6" id="panel-reports">
    @php
        $reasonMeta = [
            'phishing'   => ['Tautan Berbahaya', 'rgba(244,63,94,.16)', '#fb7185', 'fa-link-slash'],
            'pelecehan'  => ['Pelecehan & Kata Kasar', 'rgba(251,146,60,.16)', '#fb923c', 'fa-comment-slash'],
            'spam'       => ['Spam / Promosi', 'rgba(251,191,36,.15)', '#fbbf24', 'fa-bullhorn'],
            'spoiler'    => ['Spoiler Tanpa Tag', 'rgba(167,139,250,.16)', '#a78bfa', 'fa-eye-slash'],
            'lainnya'    => ['Lainnya', 'rgba(148,163,184,.14)', '#94a3b8', 'fa-ellipsis'],
        ];
    @endphp

    <div class="mx-toolbar">
        <div class="flex items-center gap-2.5">
            <h2 class="font-display text-lg font-semibold text-white flex items-center gap-2.5">
                <i class="fa-solid fa-inbox text-brand"></i>Antrean Moderasi
            </h2>
            <span class="adm-chip text-[11px] text-slate-400 px-2.5 py-1 rounded-lg font-medium">komentar yang dilaporkan</span>
        </div>
        <div class="flex items-center gap-2.5">
            <div class="mx-search">
                <i class="fa-solid fa-magnifying-glass text-slate-500 text-xs"></i>
                <input id="queue-search" type="text" placeholder="Cari isi komentar atau username..." oninput="filterQueue(this.value)">
            </div>
            <span class="text-[11px] text-slate-600 hidden sm:block" id="queue-count"></span>
        </div>
    </div>

    <div class="mt-4 space-y-4" id="queue-list">
        @forelse($reports as $r)
            @php
                $c = $u = null;
                $c = $r->comment;
                $u = $c->user ?? null;
                $m = $c->manga ?? null;
                $isBanned = $u?->isBanned();
                $isAdmin = $u?->isAdmin();
            @endphp
            <div class="mx-card hoverable p-5 queue-item" data-search="{{ strtolower(($u->name ?? '') . ' ' . ($c->content ?? '')) }}">
                <div class="flex flex-col lg:flex-row gap-4">
                    {{-- KIRI: info + konten --}}
                    <div class="flex-1 min-w-0 flex gap-3.5">
                        <div class="relative shrink-0">
                            <img class="mx-avatar" src="https://ui-avatars.com/api/?name={{ urlencode($u->name ?? 'A') }}&background=ff2e4d&color=fff&bold=true" alt="">
                            @if($isBanned)
                                <span class="absolute -bottom-0.5 -right-0.5 w-4.5 h-4.5 rounded-full flex items-center justify-center" style="width:17px;height:17px;background:#e11d48;color:#fff;font-size:8px"><i class="fa-solid fa-ban"></i></span>
                            @elseif($isAdmin)
                                <span class="absolute -bottom-0.5 -right-0.5 rounded-full flex items-center justify-center" style="width:17px;height:17px;background:#ff2e4d;color:#fff;font-size:8px"><i class="fa-solid fa-shield-halved"></i></span>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            {{-- baris identitas --}}
                            <div class="flex flex-wrap items-center gap-x-2 gap-y-1">
                                <span class="font-semibold text-white text-sm">{{ $u->name ?? 'User Terhapus' }}</span>
                                <span class="mx-uid">#{{ $u->id ?? '?' }}</span>
                                @if($isBanned)
                                    <span class="mx-chip" style="background:rgba(244,63,94,.16);color:#fb7185"><i class="fa-solid fa-ban text-[8px]"></i>BANNED</span>
                                @endif
                                <span class="text-slate-600 text-[10px]">•</span>
                                <span class="text-xs text-slate-500">{{ $r->latest->diffForHumans() }}</span>
                                @if($m)
                                    <span class="text-slate-600 text-[10px]">•</span>
                                    <span class="mx-manga text-slate-400 text-xs" title="{{ $m->title }}">
                                        <i class="fa-solid fa-book text-brand text-[10px]"></i>
                                        <span class="t">{{ $m->title }}</span>
                                    </span>
                                @endif
                            </div>
                            {{-- isi komentar --}}
                            <div class="mx-qbox mt-2.5">
                                <p>{{ $c->content }}</p>
                            </div>
                            {{-- indikator report --}}
                            <div class="flex flex-wrap items-center gap-2 mt-2.5">
                                @foreach($r->reasons as $rs)
                                    @php $meta = $reasonMeta[$rs] ?? $reasonMeta['lainnya']; @endphp
                                    <span class="mx-chip" style="background:{{ $meta[1] }};color:{{ $meta[2] }}">
                                        <i class="fa-solid {{ $meta[3] }} text-[9px]"></i>{{ $meta[0] }}
                                    </span>
                                @endforeach
                                <span class="text-[11px] text-slate-500 inline-flex items-center gap-1.5">
                                    <i class="fa-solid fa-flag text-[9px] text-slate-600"></i>dilaporkan {{ $r->reporterCount }}×
                                </span>
                                @if($c->likes_count > 0)
                                    <span class="text-[11px] text-slate-500 inline-flex items-center gap-1"><i class="fa-solid fa-heart text-[9px]" style="color:#fb7185"></i>{{ $c->likes_count }}</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- KANAN: aksi --}}
                    <div class="flex flex-wrap lg:flex-col lg:items-stretch gap-2 shrink-0 lg:w-52">
                        <form method="POST" action="{{ route('admin.moderation.action', $c->id) }}" onsubmit="return confirm('Hapus komentar ini DAN ban user-nya?')">
                            @csrf
                            <input type="hidden" name="action" value="delete_ban">
                            <button class="mx-btn mx-btn-danger w-full"><i class="fa-solid fa-user-slash text-xs"></i>Hapus &amp; Ban User</button>
                        </form>
                        <form method="POST" action="{{ route('admin.moderation.action', $c->id) }}" onsubmit="return confirm('Hapus komentar ini?')">
                            @csrf
                            <input type="hidden" name="action" value="delete">
                            <button class="mx-btn mx-btn-soft w-full"><i class="fa-solid fa-trash text-xs"></i>Hapus Komentar</button>
                        </form>
                        <form method="POST" action="{{ route('admin.moderation.action', $c->id) }}">
                            @csrf
                            <input type="hidden" name="action" value="resolve">
                            <button class="mx-btn mx-btn-ghost w-full"><i class="fa-solid fa-check text-xs"></i>Abaikan / Bukan Spam</button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="mx-card mx-empty">
                <i class="fa-regular fa-circle-check ic"></i>
                <p class="mt-3 font-semibold text-white text-base">Tidak ada laporan pending 🎉</p>
                <p class="text-sm mt-1">Semua komentar aman. Laporan baru dari pembaca akan muncul di sini.</p>
            </div>
        @endforelse
    </div>
</div>

{{-- ============ PANEL: SEMUA KOMENTAR ============ --}}
<div class="mx-panel mt-6" id="panel-comments">
    <div class="mx-toolbar">
        <h2 class="font-display text-lg font-semibold text-white flex items-center gap-2.5">
            <i class="fa-solid fa-comments text-brand"></i>Komentar Terbaru
            <span class="adm-chip text-[11px] text-slate-400 px-2.5 py-1 rounded-lg font-medium">100 terbaru</span>
        </h2>
        <div class="mx-search">
            <i class="fa-solid fa-magnifying-glass text-slate-500 text-xs"></i>
            <input type="text" placeholder="Cari komentar..." oninput="filterTable(this.value, 'comments-table')">
        </div>
    </div>

    <div class="mx-card mt-4 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="mx-table" id="comments-table">
                <thead>
                    <tr>
                        <th>Pengguna</th>
                        <th>Komentar</th>
                        <th>Pada Manga</th>
                        <th class="text-center">Laporan</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($comments as $cm)
                        <tr data-search="{{ strtolower(($cm->user->name ?? '') . ' ' . $cm->content) }}">
                            <td>
                                <div class="flex items-center gap-2.5">
                                    <img class="mx-avatar sm" src="https://ui-avatars.com/api/?name={{ urlencode($cm->user->name ?? '?') }}&background=ff2e4d&color=fff&bold=true" alt="">
                                    <div class="leading-tight">
                                        <p class="font-semibold text-white text-[13px] {{ $cm->user?->isBanned() ? 'line-through opacity-60' : '' }}">{{ $cm->user->name ?? 'User Terhapus' }}</p>
                                        <p class="text-[11px] text-slate-500">{{ $cm->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="max-w-md">
                                <p class="text-slate-300 text-[13px] leading-relaxed mx-trunc2">{{ $cm->content }}</p>
                            </td>
                            <td>
                                @if($cm->manga)
                                    <span class="mx-manga text-slate-400 text-xs" title="{{ $cm->manga->title }}">
                                        <i class="fa-solid fa-book text-brand text-[10px]"></i><span class="t" style="max-width:170px">{{ $cm->manga->title }}</span>
                                    </span>
                                @else
                                    <span class="text-slate-600 text-xs">—</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($cm->reports_count > 0)
                                    <span class="mx-chip" style="background:rgba(251,191,36,.15);color:#fbbf24"><i class="fa-solid fa-flag text-[9px]"></i>{{ $cm->reports_count }}</span>
                                @else
                                    <span class="text-slate-700 text-xs">—</span>
                                @endif
                            </td>
                            <td class="text-right">
                                <form method="POST" action="{{ route('admin.moderation.action', $cm->id) }}" class="inline" onsubmit="return confirm('Hapus komentar ini?')">
                                    @csrf
                                    <input type="hidden" name="action" value="delete">
                                    <button class="mx-ico-btn danger" title="Hapus komentar"><i class="fa-solid fa-trash-can text-[13px]"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5"><div class="mx-empty"><i class="fa-regular fa-comment-dots ic"></i><p class="mt-3 text-sm">Belum ada komentar dari pembaca.</p></div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- ============ PANEL: PENGGUNA ============ --}}
<div class="mx-panel mt-6" id="panel-users">
    <div class="mx-toolbar">
        <h2 class="font-display text-lg font-semibold text-white flex items-center gap-2.5">
            <i class="fa-solid fa-users text-brand"></i>Pengguna Terbaru
            <span class="adm-chip text-[11px] text-slate-400 px-2.5 py-1 rounded-lg font-medium">50 terbaru</span>
        </h2>
        <div class="mx-search">
            <i class="fa-solid fa-magnifying-glass text-slate-500 text-xs"></i>
            <input type="text" placeholder="Cari nama / email..." oninput="filterTable(this.value, 'users-table')">
        </div>
    </div>

    <div class="mx-card mt-4 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="mx-table" id="users-table">
                <thead>
                    <tr>
                        <th>Pengguna</th>
                        <th>Role</th>
                        <th>Gabung</th>
                        <th class="text-center">Komentar</th>
                        <th>Status</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $us)
                        <tr data-search="{{ strtolower($us->name . ' ' . $us->email) }}" style="{{ $us->isBanned() ? 'background:rgba(244,63,94,.05)' : '' }}">
                            <td>
                                <div class="flex items-center gap-2.5">
                                    <img class="mx-avatar sm" src="{{ $us->photo_profile ?? 'https://ui-avatars.com/api/?name=' . urlencode($us->name) . '&background=ff2e4d&color=fff&bold=true' }}" alt="">
                                    <div class="leading-tight">
                                        <p class="font-semibold text-white text-[13px] {{ $us->isBanned() ? 'line-through opacity-60' : '' }}">{{ $us->name }}</p>
                                        <p class="text-[11px] text-slate-500">{{ $us->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($us->isAdmin())
                                    <span class="mx-chip" style="background:rgba(255,46,77,.15);color:#ff2e4d"><i class="fa-solid fa-shield-halved text-[9px]"></i>Admin</span>
                                @else
                                    <span class="mx-chip" style="background:rgba(255,255,255,.06);color:#94a3b8"><i class="fa-solid fa-user text-[9px]"></i>Member</span>
                                @endif
                            </td>
                            <td class="text-slate-500 text-xs whitespace-nowrap">{{ $us->created_at->format('d M Y') }}</td>
                            <td class="text-center">
                                <span class="font-semibold text-[12px] text-slate-200" style="background:rgba(255,255,255,.06);padding:2px 10px;border-radius:8px">{{ number_format($us->comments_count) }}</span>
                            </td>
                            <td>
                                @if($us->isBanned())
                                    <span class="mx-pill" style="background:rgba(244,63,94,.15);color:#fb7185"><span style="width:6px;height:6px;border-radius:99px;background:#fb7185;display:inline-block"></span>Banned</span>
                                @else
                                    <span class="mx-pill" style="background:rgba(52,211,153,.12);color:#34d399"><span style="width:6px;height:6px;border-radius:99px;background:#34d399;display:inline-block"></span>Aktif</span>
                                @endif
                            </td>
                            <td class="text-right">
                                @if(!$us->isAdmin())
                                    @if($us->isBanned())
                                        <form method="POST" action="{{ route('admin.moderation.user', $us->id) }}" class="inline">
                                            @csrf
                                            <input type="hidden" name="action" value="unban">
                                            <button class="mx-btn mx-btn-soft mx-btn-sm" title="Buka ban"><i class="fa-solid fa-lock-open text-[11px]"></i>Unban</button>
                                        </form>
                                    @else
                                        <form method="POST" action="{{ route('admin.moderation.user', $us->id) }}" class="inline" onsubmit="return confirm('Ban user {{ $us->name }}?')">
                                            @csrf
                                            <input type="hidden" name="action" value="ban">
                                            <button class="mx-ico-btn danger" title="Ban user"><i class="fa-solid fa-ban text-[13px]"></i></button>
                                        </form>
                                    @endif
                                @else
                                    <span class="text-[11px] text-slate-600 px-1.5">Protected</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6"><div class="mx-empty"><i class="fa-solid fa-users ic"></i><p class="mt-3 text-sm">Belum ada pengguna terdaftar.</p></div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- ============ PANEL: DI-BAN ============ --}}
<div class="mx-panel mt-6" id="panel-banned">
    <h2 class="font-display text-lg font-semibold text-white flex items-center gap-2.5">
        <i class="fa-solid fa-ban text-brand"></i>Pengguna Di-ban
    </h2>
    <div class="mx-card mt-4 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="mx-table">
                <thead>
                    <tr>
                        <th>Pengguna</th>
                        <th>Di-ban sejak</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users->where('banned_at', '!=', null) as $us)
                        <tr style="background:rgba(244,63,94,.05)">
                            <td>
                                <div class="flex items-center gap-2.5">
                                    <div class="mx-avatar sm flex items-center justify-center" style="background:rgba(244,63,94,.14);color:#fb7185;border:none">
                                        <i class="fa-solid fa-user-slash text-sm"></i>
                                    </div>
                                    <div class="leading-tight">
                                        <p class="font-semibold text-red-400 text-[13px] line-through">{{ $us->name }}</p>
                                        <p class="text-[11px] text-slate-500">{{ $us->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="text-slate-500 text-xs whitespace-nowrap">{{ $us->banned_at?->format('d M Y H:i') }}</td>
                            <td class="text-right">
                                <form method="POST" action="{{ route('admin.moderation.user', $us->id) }}" class="inline">
                                    @csrf
                                    <input type="hidden" name="action" value="unban">
                                    <button class="mx-btn mx-btn-soft mx-btn-sm" style="color:#34d399"><i class="fa-solid fa-lock-open text-[11px]"></i>Unban</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="3"><div class="mx-empty"><i class="fa-solid fa-shield-heart ic"></i><p class="mt-3 font-semibold text-white text-sm">Tidak ada user di-ban 👍</p><p class="text-sm mt-1">Semua pengguna dalam keadaan baik.</p></div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Toast --}}
<div class="mx-toast" id="mx-toast">
    <i class="fa-solid fa-circle-check" style="color:#ff2e4d"></i>
    <span id="mx-toast-msg" data-msg="{{ session('success') ?? '' }}">Aksi berhasil diterapkan</span>
</div>

@push('scripts')
<script src="{{ asset('js/admin/moderation/index.js') }}"></script>
@endpush
@endsection
