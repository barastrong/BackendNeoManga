@extends('layouts.admin')

@section('title', 'Dashboard — Admin NeoManga')
@section('page-title', 'Dashboard')

@section('content')
    <div class="flex flex-wrap items-end justify-between gap-4">
        <div>
            <h1 class="font-display text-2xl font-bold text-white">Ringkasan Operasional</h1>
            <p class="mt-1 text-sm text-slate-400">Selamat datang kembali, Admin! 👋</p>
        </div>
        <a href="{{ route('admin.manga.create') }}" class="inline-flex items-center gap-2 adm-primary-bg hover:bg-brand-dark text-white text-sm font-semibold px-5 py-2.5 rounded-xl transition-all hover:shadow-lg hover:shadow-brand/25">
            <i class="fa-solid fa-plus"></i>Tambah Manga
        </a>
    </div>

    {{-- 1. Metric cards (bento grid + sparkline) --}}
    <div class="mt-8 grid grid-cols-1 gap-5 sm:grid-cols-2 xl:grid-cols-4">
        {{-- Total Manga --}}
        <div class="adm-card adm-card-hover relative overflow-hidden flex flex-col justify-between p-5 rounded-2xl shadow-sm transition-all duration-300 group">
            <div class="absolute -right-6 -bottom-6 w-24 h-24 rounded-full bg-brand/5 blur-2xl group-hover:bg-brand/10 transition-colors"></div>
            <div>
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-medium text-slate-500 uppercase tracking-wider">Total Katalog Manga</span>
                    <span class="p-1.5 rounded-lg adm-chip text-brand"><i class="fa-solid fa-book text-sm"></i></span>
                </div>
                <div class="flex items-baseline gap-2 mt-3">
                    <span class="font-display text-3xl font-bold text-white">{{ number_format($mangaCount) }}</span>
                    <span class="text-xs text-slate-500">judul</span>
                </div>
            </div>
            <div class="mt-4 pt-3 flex items-center justify-between border-t border-white/5">
                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md adm-chip text-emerald-400 text-[11px] font-semibold">
                    <i class="fa-solid fa-arrow-trend-up text-[10px]"></i>+{{ $mangasThisMonth }} bulan ini
                </span>
                <svg class="w-20 h-6 overflow-visible text-brand" fill="none" viewBox="0 0 80 24">
                    <path d="M0 18 L15 14 L30 16 L45 8 L60 11 L80 3" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                </svg>
            </div>
        </div>

        {{-- Total Chapter --}}
        <div class="adm-card adm-card-hover relative overflow-hidden flex flex-col justify-between p-5 rounded-2xl shadow-sm transition-all duration-300 group">
            <div class="absolute -right-6 -bottom-6 w-24 h-24 rounded-full bg-indigo-500/5 blur-2xl group-hover:bg-indigo-500/10 transition-colors"></div>
            <div>
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-medium text-slate-500 uppercase tracking-wider">Total Chapter Terbit</span>
                    <span class="p-1.5 rounded-lg adm-chip text-indigo-400"><i class="fa-solid fa-layer-group text-sm"></i></span>
                </div>
                <div class="flex items-baseline gap-2 mt-3">
                    <span class="font-display text-3xl font-bold text-white">{{ number_format($chapterCount) }}</span>
                    <span class="text-xs text-slate-500">bab</span>
                </div>
            </div>
            <div class="mt-4 pt-3 flex items-center justify-between border-t border-white/5">
                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md adm-chip text-brand text-[11px] font-semibold">
                    <i class="fa-solid fa-bolt text-[10px]"></i>+{{ $chaptersToday }} hari ini
                </span>
                <svg class="w-20 h-6 overflow-visible text-indigo-400" fill="none" viewBox="0 0 80 24">
                    <path d="M0 20 L20 17 L40 10 L55 12 L68 6 L80 2" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                </svg>
            </div>
        </div>

        {{-- Total Pengguna --}}
        <div class="adm-card adm-card-hover relative overflow-hidden flex flex-col justify-between p-5 rounded-2xl shadow-sm transition-all duration-300 group">
            <div class="absolute -right-6 -bottom-6 w-24 h-24 rounded-full bg-emerald-500/5 blur-2xl group-hover:bg-emerald-500/10 transition-colors"></div>
            <div>
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-medium text-slate-500 uppercase tracking-wider">Total Pengguna</span>
                    <span class="p-1.5 rounded-lg adm-chip text-emerald-400"><i class="fa-solid fa-users text-sm"></i></span>
                </div>
                <div class="flex items-baseline gap-2 mt-3">
                    <span class="font-display text-3xl font-bold text-white">{{ number_format($userCount) }}</span>
                    <span class="text-xs text-slate-500">akun</span>
                </div>
            </div>
            <div class="mt-4 pt-3 flex items-center justify-between border-t border-white/5">
                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md adm-chip text-emerald-400 text-[11px] font-semibold">
                    <i class="fa-solid fa-arrow-trend-up text-[10px]"></i>+{{ $usersThisMonth }} bulan ini
                </span>
                <svg class="w-20 h-6 overflow-visible text-emerald-400" fill="none" viewBox="0 0 80 24">
                    <path d="M0 22 L18 19 L32 15 L50 16 L65 9 L80 4" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                </svg>
            </div>
        </div>

        {{-- Views 24 jam --}}
        <div class="adm-card adm-card-hover relative overflow-hidden flex flex-col justify-between p-5 rounded-2xl shadow-sm transition-all duration-300 group">
            <div class="absolute -right-6 -bottom-6 w-24 h-24 rounded-full bg-amber-500/5 blur-2xl group-hover:bg-amber-500/10 transition-colors"></div>
            <div>
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-medium text-slate-500 uppercase tracking-wider">Views 24 Jam</span>
                    <span class="p-1.5 rounded-lg adm-chip text-amber-400"><i class="fa-solid fa-fire text-sm"></i></span>
                </div>
                <div class="flex items-baseline gap-2 mt-3">
                    <span class="font-display text-3xl font-bold text-white">{{ number_format($viewsToday) }}</span>
                    <span class="text-xs text-slate-500">hits</span>
                </div>
            </div>
            <div class="mt-4 pt-3 flex items-center justify-between border-t border-white/5">
                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md {{ $viewDelta >= 0 ? 'adm-chip text-emerald-400' : 'adm-chip text-red-400' }} text-[11px] font-semibold">
                    <i class="fa-solid {{ $viewDelta >= 0 ? 'fa-arrow-trend-up' : 'fa-arrow-trend-down' }} text-[10px]"></i>{{ $viewDelta >= 0 ? '+' : '' }}{{ $viewDelta }} vs kemarin
                </span>
                <svg class="w-20 h-6 overflow-visible text-amber-400" fill="none" viewBox="0 0 80 24">
                    <path d="M0 21 L16 18 L34 20 L48 11 L64 7 L80 1" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                </svg>
            </div>
        </div>
    </div>

    {{-- 2. Area chart + distribusi genre --}}
    <div class="mt-8 grid grid-cols-1 xl:grid-cols-12 gap-5">

        {{-- Area chart 30 hari --}}
        <div class="xl:col-span-8 adm-card flex flex-col justify-between p-6 rounded-2xl shadow-sm">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-5">
                <div>
                    <h2 class="font-display text-lg font-semibold text-white">Tren Pembaca &amp; Kunjungan Harian</h2>
                    <p class="mt-0.5 text-sm text-slate-500">Volume akses pembaca dari seluruh manga.</p>
                </div>
                <div class="flex items-center p-1 rounded-xl adm-chip" id="time-filter-group">
                    @foreach([7, 14, 30] as $n)
                        <button data-days="{{ $n }}" onclick="setChartFilter(this)"
                                class="chart-filter px-3 py-1 rounded-lg text-xs font-semibold transition-all
                                       {{ $n === 7 ? 'bg-brand text-white shadow' : 'text-slate-400 hover:text-white' }}">
                            {{ $n }} Hari
                        </button>
                    @endforeach
                </div>
            </div>

            @foreach([7, 14, 30] as $n)
                @php
                    $slice = $chartData->slice(30 - $n)->values();
                    $mx = max(1, $slice->max('total'));
                    $pts = $slice->map(function ($d, $i) use ($n, $mx) {
                        $x = round($i * (700 / max(1, $n - 1)));
                        $y = round(220 - ($d['total'] / $mx) * 190);
                        return [$x, $y];
                    });
                    $line = $pts->map(fn($p) => "{$p[0]},{$p[1]}")->implode(' ');
                    $area = "0,220 {$line} 700,220";
                @endphp
                <div class="chart-panel relative w-full h-64 mt-2" data-days-panel="{{ $n }}" style="{{ $n === 7 ? '' : 'display:none' }}">
                    <svg class="w-full h-full" fill="none" preserveAspectRatio="none" viewBox="0 0 700 240">
                        <defs>
                            <linearGradient id="areaGrad{{ $n }}" x1="0" x2="0" y1="0" y2="1">
                                <stop offset="0%" stop-color="#ff2e4d" stop-opacity="0.32"></stop>
                                <stop offset="60%" stop-color="#ff2e4d" stop-opacity="0.06"></stop>
                                <stop offset="100%" stop-color="#ff2e4d" stop-opacity="0"></stop>
                            </linearGradient>
                            <linearGradient id="lineGrad{{ $n }}" x1="0" x2="1" y1="0" y2="0">
                                <stop offset="0%" stop-color="#ff2e4d"></stop>
                                <stop offset="100%" stop-color="#ff8a5c"></stop>
                            </linearGradient>
                        </defs>
                        @foreach([40, 90, 140, 190] as $gy)
                            <line stroke="rgba(255,255,255,.07)" stroke-dasharray="4 4" x1="0" x2="700" y1="{{ $gy }}" y2="{{ $gy }}"></line>
                        @endforeach
                        <polygon points="{{ $area }}" fill="url(#areaGrad{{ $n }})"></polygon>
                        <polyline points="{{ $line }}" stroke="url(#lineGrad{{ $n }})" stroke-linecap="round" stroke-width="2.5"></polyline>
                        @foreach($pts as $pi => $p)
                            @if($n <= 14 || $pi % 2 === 0 || $pi === count($pts) - 1)
                                <circle cx="{{ $p[0] }}" cy="{{ $p[1] }}" fill="#0d1220" stroke="#ff2e4d" stroke-width="2" r="3"></circle>
                            @endif
                        @endforeach
                    </svg>
                    <div class="flex items-center justify-between text-[11px] text-slate-500 pt-2">
                        <span>{{ $slice->first()['date'] }}</span>
                        <span class="inline-flex items-center gap-1.5 text-slate-400">
                            <span class="w-2 h-2 rounded-full bg-brand inline-block"></span> Total 30 hari: {{ number_format($views30d) }}
                        </span>
                        <span>{{ $slice->last()['date'] }}</span>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Distribusi genre --}}
        <div class="xl:col-span-4 adm-card flex flex-col justify-between p-6 rounded-2xl shadow-sm">
            <div>
                <div class="flex items-center justify-between pb-2">
                    <h3 class="font-display text-lg font-semibold text-white">Distribusi Genre</h3>
                    <span class="text-[11px] font-semibold text-brand adm-chip px-2 py-0.5 rounded-lg">Top 5</span>
                </div>
                <p class="text-sm text-slate-500">Proporsi katalog berdasar genre terpopuler.</p>

                <div class="space-y-5 mt-6">
                    @forelse($genreDistribution as $gi => $g)
                        @php
                            $pct = round($g->total / $genreMax * 100);
                            $colors = ['#ff2e4d', '#ff8a5c', '#f59e0b', '#38bdf8', '#a78bfa'];
                            $c = $colors[$gi % count($colors)];
                        @endphp
                        <div>
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-slate-200 flex items-center gap-2">
                                    <span class="w-2 h-2 rounded-full" style="background-color:{{ $c }}"></span>
                                    {{ $g->name }}
                                </span>
                                <span class="text-xs text-slate-500">{{ $pct }}% ({{ number_format($g->total) }} judul)</span>
                            </div>
                            <div class="w-full h-2 rounded-full bg-white/5 overflow-hidden mt-1.5">
                                <div class="h-full rounded-full transition-all duration-500" style="width:{{ $pct }}%;background-color:{{ $c }}"></div>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-slate-500 italic">Belum ada genre terdaftar.</p>
                    @endforelse
                </div>
            </div>
            <div class="pt-5 mt-5 border-t border-white/5 flex items-center justify-between">
                <span class="text-sm text-slate-500">Total {{ \App\Models\Genre::count() }} kategori</span>
                <a href="{{ route('admin.manga.index') }}" class="text-xs font-semibold text-brand hover:text-white transition-colors flex items-center gap-1">
                    Kelola Katalog <i class="fa-solid fa-arrow-right text-[10px]"></i>
                </a>
            </div>
        </div>
    </div>

    {{-- 3. Rilis chapter + feed komentar --}}
    <div class="mt-8 grid grid-cols-1 xl:grid-cols-12 gap-5 items-start">

        {{-- Tabel rilis chapter (8 col) --}}
        <div class="xl:col-span-8 adm-card rounded-2xl shadow-sm overflow-hidden">
            <div class="px-6 py-5 border-b border-white/5 flex items-center justify-between">
                <div>
                    <h2 class="font-display text-lg font-semibold text-white">Rilis Chapter Terbaru</h2>
                    <p class="mt-0.5 text-sm text-slate-500">Log upload chapter terbaru dari admin.</p>
                </div>
                <span class="px-2 py-0.5 rounded-full adm-chip text-slate-400 text-[11px] font-code">24 jam terakhir</span>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="adm-th uppercase tracking-wider">
                            <th class="py-3 px-6 text-left font-semibold text-[11px] text-slate-500 uppercase tracking-wider">Manga</th>
                            <th class="py-3 px-3 text-left font-semibold text-[11px] text-slate-500 uppercase tracking-wider">Chapter</th>
                            <th class="py-3 px-3 text-left font-semibold text-[11px] text-slate-500 uppercase tracking-wider">Status</th>
                            <th class="py-3 px-3 text-left font-semibold text-[11px] text-slate-500 uppercase tracking-wider">Tanggal</th>
                            <th class="py-3 px-6 text-right font-semibold text-[11px] text-slate-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @forelse($recentChapters as $ch)
                            <tr class="adm-tr-hover transition-colors">
                                <td class="py-3.5 px-6">
                                    <div class="flex items-center gap-3">
                                        <img src="{{ $ch->manga?->cover_url ?? asset('images/no-image.png') }}" alt="" class="h-11 w-8 object-cover rounded-md adm-chip">
                                        <div class="min-w-0">
                                            <span class="font-medium text-slate-200 block truncate max-w-[180px]">{{ $ch->manga?->title ?? '—' }}</span>
                                            <span class="text-[11px] text-slate-500">{{ ucfirst($ch->manga?->type ?? '') }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3.5 px-3 font-mono text-brand font-semibold text-xs">Ch. {{ $ch->number }}</td>
                                <td class="py-3.5 px-3">
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md adm-chip text-[11px] font-semibold
                                        {{ $ch->status === 'published' ? 'text-emerald-400' : ($ch->status === 'draft' ? 'text-amber-400' : 'text-slate-400') }}">
                                        <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                                        {{ $ch->status_label }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-3 whitespace-nowrap text-slate-500">{{ $ch->created_at->diffForHumans(['short' => true, 'parts' => 1]) }}</td>
                                <td class="py-3.5 px-6 text-right">
                                    <a href="{{ route('admin.manga.chapters.edit', [$ch->manga_id, $ch->id]) }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-brand hover:text-white transition-colors">
                                        <i class="fa-solid fa-pen"></i>Edit
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-14 text-center text-slate-500">
                                    <div class="flex flex-col items-center">
                                        <i class="fa-solid fa-box-open text-4xl text-slate-600 mb-4"></i>
                                        <p class="font-semibold text-slate-400">Belum ada chapter</p>
                                        <p class="text-sm text-slate-500">Silakan tambahkan chapter pertamamu.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Feed komentar terbaru (4 col) --}}
        <div class="xl:col-span-4 space-y-5">
            <div class="adm-card rounded-2xl shadow-sm p-6">
                <div class="flex items-center justify-between pb-2">
                    <div class="flex items-center gap-2.5">
                        <i class="fa-solid fa-comment text-amber-400 text-sm"></i>
                        <h3 class="font-display text-lg font-semibold text-white">Komentar Terbaru</h3>
                    </div>
                    <span class="px-2 py-0.5 rounded-full adm-chip text-amber-400 text-[11px] font-semibold">{{ $commentCount }}</span>
                </div>
                <p class="text-sm text-slate-500">Aktivitas komentar dari pembaca.</p>

                <div class="space-y-3 mt-5">
                    @forelse($recentComments as $comment)
                        <div class="p-3.5 rounded-xl bg-[#0d1220] border border-white/5 transition-all hover:border-brand/30">
                            <div class="flex items-center justify-between text-xs">
                                <span class="text-slate-200 font-semibold flex items-center gap-1.5">
                                    <span class="text-brand">•</span>{{ $comment->user?->name ?? 'Guest' }}
                                </span>
                                <span class="text-slate-500 font-mono text-[10px]">
                                    @if($comment->manga){{ Str::limit($comment->manga->title, 22) }}@endif
                                </span>
                            </div>
                            <p class="text-sm text-slate-400 italic mt-1.5" style="display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden">{{ Str::limit($comment->content, 90) }}</p>
                            <div class="flex items-center justify-between pt-2.5 mt-1">
                                <span class="text-[11px] text-slate-500">{{ $comment->created_at->diffForHumans(['short' => true, 'parts' => 1]) }}</span>
                                <a href="{{ $comment->manga ? route('manga.show', $comment->manga->slug) : '#' }}" class="text-[11px] font-semibold text-brand hover:text-white transition-colors">
                                    Lihat <i class="fa-solid fa-arrow-right text-[9px] ml-0.5"></i>
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="p-6 text-center text-slate-500">
                            <i class="fa-regular fa-comment text-3xl text-slate-600 mb-3"></i>
                            <p class="text-sm">Belum ada komentar.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Mini status sistem --}}
            <div class="adm-card rounded-2xl shadow-sm p-6">
                <div class="flex items-center justify-between pb-3">
                    <div class="flex items-center gap-2.5">
                        <i class="fa-solid fa-server text-emerald-400 text-sm"></i>
                        <h3 class="font-display text-lg font-semibold text-white">Status Sistem</h3>
                    </div>
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-pulse"></span>
                </div>
                <div class="space-y-2.5 text-sm">
                    <div class="flex items-center justify-between rounded-xl bg-[#0d1220] border border-white/5 px-3.5 py-2.5">
                        <span class="flex items-center gap-2.5 text-slate-300"><i class="fa-solid fa-database text-slate-500 w-4"></i>Database</span>
                        <span class="text-emerald-400 text-xs font-semibold">Operasional</span>
                    </div>
                    <div class="flex items-center justify-between rounded-xl bg-[#0d1220] border border-white/5 px-3.5 py-2.5">
                        <span class="flex items-center gap-2.5 text-slate-300"><i class="fa-solid fa-cloud text-slate-500 w-4"></i>Penyimpanan Gambar</span>
                        <span class="text-emerald-400 text-xs font-semibold">Operasional</span>
                    </div>
                    <div class="flex items-center justify-between rounded-xl bg-[#0d1220] border border-white/5 px-3.5 py-2.5">
                        <span class="flex items-center gap-2.5 text-slate-300"><i class="fa-solid fa-bookmark text-slate-500 w-4"></i>Total Bookmark</span>
                        <span class="text-slate-300 text-xs font-semibold">{{ number_format($bookmarkCount) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/admin/dashboard.js') }}"></script>
@endsection
