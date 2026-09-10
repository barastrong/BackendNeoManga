@extends('layouts.admin')

@section('title', 'Analisis & Statistik — Admin NeoManga')
@section('page-title', 'Analisis & Statistik')

@section('content')
    <link rel="stylesheet" href="/css/admin/analytics/index.css">

    {{-- Header --}}
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <span class="flex-shrink-0" style="width:46px;height:46px;border-radius:14px;display:inline-flex;align-items:center;justify-content:center;background:linear-gradient(135deg,rgba(255,46,77,.16),rgba(56,189,248,.10));border:1px solid rgba(255,46,77,.25);color:#ff2e4d"><i class="fa-solid fa-chart-line"></i></span>
            <div>
                <h1 class="font-display text-2xl font-bold text-white">Analisis &amp; Statistik</h1>
                <p class="mt-1 text-sm text-slate-400">Pusat data lengkap NeoManga — performa katalog, pembaca, dan interaksi.</p>
            </div>
        </div>
        <div class="flex items-center gap-2 text-xs text-slate-500 adm-chip px-3 py-2 rounded-xl">
            <i class="fa-solid fa-calendar-days"></i> Data 30 hari terakhir
        </div>
    </div>

    {{-- 1. KPI strip (6 kartu) --}}
    <div class="mt-6 ana-cards">
        <div class="adm-card p-5 rounded-2xl shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-medium text-slate-500 uppercase tracking-wider">Total Views</span>
                <span class="p-2 rounded-lg adm-chip text-brand"><i class="fa-solid fa-eye text-sm"></i></span>
            </div>
            <div class="flex items-baseline gap-1.5 mt-3">
                <span class="font-display text-3xl font-bold text-white">{{ number_format($totalViews) }}</span>
                <span class="text-xs text-slate-500">sepanjang masa</span>
            </div>
            <div class="mt-3 inline-flex items-center gap-1 px-2 py-0.5 rounded-md adm-chip {{ $viewDelta >= 0 ? 'text-emerald-400' : 'text-red-400' }} text-[11px] font-semibold">
                <i class="fa-solid {{ $viewDelta >= 0 ? 'fa-arrow-trend-up' : 'fa-arrow-trend-down' }} text-[10px]"></i>
                {{ $viewDelta >= 0 ? '+' : '' }}{{ number_format($viewDelta) }} vs kemarin
            </div>
        </div>

        <div class="adm-card p-5 rounded-2xl shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-medium text-slate-500 uppercase tracking-wider">Pembaca Aktif</span>
                <span class="p-2 rounded-lg adm-chip text-indigo-400"><i class="fa-solid fa-users text-sm"></i></span>
            </div>
            <div class="flex items-baseline gap-1.5 mt-3">
                <span class="font-display text-3xl font-bold text-white">{{ number_format($readers30d) }}</span>
                <span class="text-xs text-slate-500">unik / 30 hari</span>
            </div>
            <div class="mt-3 inline-flex items-center gap-1 px-2 py-0.5 rounded-md adm-chip text-slate-400 text-[11px]">
                <i class="fa-solid fa-user-group text-[10px]"></i> dari {{ number_format($totalUsers) }} user
            </div>
        </div>

        <div class="adm-card p-5 rounded-2xl shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-medium text-slate-500 uppercase tracking-wider">Rating Rata-rata</span>
                <span class="p-2 rounded-lg adm-chip text-amber-400"><i class="fa-solid fa-star text-sm"></i></span>
            </div>
            <div class="flex items-baseline gap-1.5 mt-3">
                <span class="font-display text-3xl font-bold text-white">{{ number_format($avgRating, 1) }}</span>
                <span class="text-xs text-slate-500">/ 5 · {{ number_format($ratingVotes) }} vote</span>
            </div>
            <div class="mt-3 inline-flex items-center gap-1 px-2 py-0.5 rounded-md adm-chip text-slate-400 text-[11px]">
                <i class="fa-solid fa-comment-dots text-[10px]"></i> {{ number_format($totalComments) }} komentar
            </div>
        </div>

        <div class="adm-card p-5 rounded-2xl shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-medium text-slate-500 uppercase tracking-wider">Total User</span>
                <span class="p-2 rounded-lg adm-chip text-emerald-400"><i class="fa-solid fa-user-plus text-sm"></i></span>
            </div>
            <div class="flex items-baseline gap-1.5 mt-3">
                <span class="font-display text-3xl font-bold text-white">{{ number_format($totalUsers) }}</span>
                <span class="text-xs text-slate-500">akun</span>
            </div>
            <div class="mt-3 inline-flex items-center gap-1 px-2 py-0.5 rounded-md adm-chip text-emerald-400 text-[11px] font-semibold">
                <i class="fa-solid fa-user-plus text-[10px]"></i> +{{ number_format($usersMonth) }} bulan ini
            </div>
        </div>

        <div class="adm-card p-5 rounded-2xl shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-medium text-slate-500 uppercase tracking-wider">Komentar Bulan Ini</span>
                <span class="p-2 rounded-lg adm-chip text-sky-400"><i class="fa-solid fa-message text-sm"></i></span>
            </div>
            <div class="flex items-baseline gap-1.5 mt-3">
                <span class="font-display text-3xl font-bold text-white">{{ number_format($commentsMonth) }}</span>
                <span class="text-xs text-slate-500">/ {{ number_format($totalComments) }}</span>
            </div>
            <div class="mt-3 inline-flex items-center gap-1 px-2 py-0.5 rounded-md adm-chip text-slate-400 text-[11px]">
                <i class="fa-solid fa-bookmark text-[10px]"></i> {{ number_format($totalBookmarks) }} bookmark
            </div>
        </div>

        <div class="adm-card p-5 rounded-2xl shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-medium text-slate-500 uppercase tracking-wider">Katalog</span>
                <span class="p-2 rounded-lg adm-chip text-fuchsia-400"><img src="/images/neomanga-logo.png" alt="NeoManga" style="width:16px;height:16px;object-fit:contain;border-radius:4px;vertical-align:-2px"></span>
            </div>
            <div class="flex items-baseline gap-1.5 mt-3">
                <span class="font-display text-3xl font-bold text-white">{{ number_format($mangaCount) }}</span>
                <span class="text-xs text-slate-500">manga</span>
            </div>
            <div class="mt-3 inline-flex items-center gap-1 px-2 py-0.5 rounded-md adm-chip text-slate-400 text-[11px]">
                <i class="fa-solid fa-layer-group text-[10px]"></i> {{ number_format($chapterCount) }} chapter
            </div>
        </div>
    </div>

{{-- 1b. Baris cepat: tren upload 7/30 hari + rekap cepat --}}
    <div class="mt-4 ana-quick">
        <div class="adm-card rounded-2xl shadow-sm px-5 py-4">
            <div class="flex items-center justify-between gap-3">
                <div>
                    <h3 class="text-[11px] font-medium text-slate-500 uppercase tracking-wider">Upload Chapter — 7 Hari Terakhir</h3>
                    <p class="mt-1 text-xs text-slate-500">Total {{ number_format($releaseSeries->take(7)->sum('total')) }} chapter · {{ $chapterToday }} hari ini</p>
                </div>
                <span class="p-2 rounded-lg adm-chip text-emerald-400"><i class="fa-solid fa-layer-group text-sm"></i></span>
            </div>
            <div class="flex items-end gap-1.5 mt-4 h-16">
                @foreach($releaseSeries->slice(-7) as $b)
                    <div class="flex-1 flex flex-col items-center gap-1 min-w-0" title="{{ $b['date'] }} · {{ $b['total'] }} chapter">
                        <span class="text-[10px] font-semibold {{ $b['total'] > 0 ? 'text-slate-300' : 'text-slate-600' }}">{{ $b['total'] }}</span>
                        <div class="w-full max-w-[30px] rounded-t-md transition-all duration-500" style="height:{{ max(2, round($b['total'] / $releaseMax * 46)) }}px;background:linear-gradient(to top,rgba(52,211,153,.35),#34d399)"></div>
                        <span class="text-[9px] text-slate-500">{{ \Carbon\Carbon::parse($b['date'])->locale('id')->isoFormat('dd') }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="adm-card rounded-2xl shadow-sm px-5 py-4">
            <div class="flex items-center justify-between gap-3">
                <div>
                    <h3 class="text-[11px] font-medium text-slate-500 uppercase tracking-wider">Judul Baru — 30 Hari Terakhir</h3>
                    <p class="mt-1 text-xs text-slate-500">{{ number_format($mangaThisMonth) }} bulan ini · {{ number_format($mangaThisWeek) }} minggu ini</p>
                </div>
                <span class="p-2 rounded-lg adm-chip text-sky-400"><i class="fa-solid fa-book-medical text-sm"></i></span>
            </div>
            <div class="flex items-end gap-1.5 mt-4 h-16">
                @foreach($newMangaSeries->filter(fn ($b) => $b['total'] > 0)->take(7) as $b)
                    <div class="flex-1 flex flex-col items-center gap-1 min-w-0" title="{{ $b['date'] }} · {{ $b['total'] }} judul baru">
                        <span class="text-[10px] font-semibold text-slate-300">{{ $b['total'] }}</span>
                        <div class="w-full max-w-[30px] rounded-t-md transition-all duration-500" style="height:{{ max(2, round($b['total'] / $newMangaMax * 46)) }}px;background:linear-gradient(to top,rgba(56,189,248,.35),#38bdf8)"></div>
                        <span class="text-[9px] text-slate-500">{{ \Carbon\Carbon::parse($b['date'])->locale('id')->isoFormat('dd') }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="adm-card rounded-2xl shadow-sm px-5 py-4">
            <h3 class="text-[11px] font-medium text-slate-500 uppercase tracking-wider">Rekap Cepat</h3>
            @php
                $views30dQ = (int) $chartData->sum('total');
                $views7dQ  = (int) $chartData->slice(-7)->sum('total');
            @endphp
            <div class="grid grid-cols-3 gap-3 mt-4">
                <div class="ana-mini">
                    <span class="p-2 rounded-lg adm-chip text-brand"><i class="fa-solid fa-eye text-[11px]"></i></span>
                    <div class="min-w-0">
                        <div class="ana-mini-label">Views 7 hari</div>
                        <div class="ana-mini-value">{{ number_format($views7dQ) }}</div>
                    </div>
                </div>
                <div class="ana-mini">
                    <span class="p-2 rounded-lg adm-chip text-indigo-400"><i class="fa-solid fa-fire text-[11px]"></i></span>
                    <div class="min-w-0">
                        <div class="ana-mini-label">Views 30 hari</div>
                        <div class="ana-mini-value">{{ number_format($views30dQ) }}</div>
                    </div>
                </div>
                <div class="ana-mini">
                    <span class="p-2 rounded-lg adm-chip text-emerald-400"><i class="fa-solid fa-user-plus text-[11px]"></i></span>
                    <div class="min-w-0">
                        <div class="ana-mini-label">user baru / pekan</div>
                        <div class="ana-mini-value">{{ number_format($newUsersWeek) }}</div>
                    </div>
                </div>
            </div>
            <p class="mt-4 text-[11px] text-slate-500 leading-snug"><i class="fa-solid fa-circle-info text-sky-400 mr-1"></i>Peak upload &amp; judul baru = momen terbaik untuk push promosi.</p>
        </div>
    </div>

    {{-- 2. Baris utama: dual line chart (7) + kolom kanan (5: aktivitas + komentar) --}}
    <div class="mt-6 ana-grid">
        <div class="ana-span-7 adm-card p-6 rounded-2xl shadow-sm">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div>
                    <h2 class="font-display text-lg font-semibold text-white">Trafik Pembaca — {{ $chartSpanDays }} Hari</h2>
                    <p class="mt-0.5 text-sm text-slate-500">Total views vs pembaca unik per hari.</p>
                </div>
                <div class="flex items-center gap-3 text-xs">
                    <span class="inline-flex items-center gap-1.5 font-medium text-slate-200"><span style="width:9px;height:9px;border-radius:9999px;background:#ff2e4d;display:inline-block"></span> Views</span>
                    <span class="inline-flex items-center gap-1.5 font-medium text-slate-200"><span style="width:9px;height:9px;border-radius:9999px;background:#38bdf8;display:inline-block"></span> Pembaca Unik</span>
                </div>
            </div>
            @if(!$chartHasData)
                <div class="flex flex-col items-center justify-center py-14 text-center">
                    <i class="fa-solid fa-chart-line text-3xl" style="color:rgba(148,163,184,.3)"></i>
                    <p class="mt-3 text-sm text-slate-400">Belum ada data trafik pembaca.</p>
                    <p class="mt-1 text-xs text-slate-600">Data akan muncul otomatis setelah ada kunjungan ke halaman manga.</p>
                </div>
            @else
            @php
                $W = 760; $H = 280; $L = 44; $R = 20; $T = 18; $B = 32;
                $pw = $W - $L - $R; $ph = $H - $T - $B;
                $n  = $chartData->count();
                $step = $n > 1 ? $pw / ($n - 1) : 0;
                $px = fn($i) => round($L + $i * $step, 1);
                // Sumbu Y "nice": kelipatan 1/2/5 × 10^k, dengan ruang napas ~15% di atas nilai maks.
                $rawMax = max(1, $chartMax);
                $target = $rawMax * 1.15;
                $mag = pow(10, floor(log10($target)));
                $niceMax = $target <= $mag
                    ? $mag
                    : ($target <= 2 * $mag ? 2 * $mag : ($target <= 5 * $mag ? 5 * $mag : 10 * $mag));
                $gridCount = 5;
                $py = fn($v) => round($T + $ph - ($v / $niceMax) * $ph, 1);
                $vals = $chartData->values();
                $ptsV = $vals->map(fn($d, $i) => [$px($i), $py($d['total'])])->all();
                $ptsR = $vals->map(fn($d, $i) => [$px($i), $py($d['readers'])])->all();
                $smooth = function ($pts) {
                    $d = 'M ' . $pts[0][0] . ',' . $pts[0][1];
                    for ($i = 0; $i < count($pts) - 1; $i++) {
                        $p0 = $pts[max(0, $i - 1)]; $p1 = $pts[$i]; $p2 = $pts[$i + 1];
                        $p3 = $pts[min(count($pts) - 1, $i + 2)];
                        $c1 = [$p1[0] + ($p2[0] - $p0[0]) / 6, $p1[1] + ($p2[1] - $p0[1]) / 6];
                        $c2 = [$p2[0] - ($p3[0] - $p1[0]) / 6, $p2[1] - ($p3[1] - $p1[1]) / 6];
                        $d .= ' C' . $c1[0] . ',' . $c1[1] . ' ' . $c2[0] . ',' . $c2[1] . ' ' . $p2[0] . ',' . $p2[1];
                    }
                    return $d;
                };
                $pathV = $smooth($ptsV); $pathR = $smooth($ptsR);
                $lastV = end($ptsV); $lastR = end($ptsR);
                $baseY = $T + $ph;
                $areaV = $pathV . ' L' . $lastV[0] . ',' . $baseY . ' L' . $ptsV[0][0] . ',' . $baseY . ' Z';
                $areaR = $pathR . ' L' . $lastR[0] . ',' . $baseY . ' L' . $ptsR[0][0] . ',' . $baseY . ' Z';
                $xTicks = $n > 1 ? [0, (int)round(($n - 1) / 4), (int)round(($n - 1) / 2), (int)round(3 * ($n - 1) / 4), $n - 1] : [0];
            @endphp
            <div class="relative mt-3" style="height:300px">
                <svg class="w-full h-full" fill="none" preserveAspectRatio="none" viewBox="0 0 {{ $W }} {{ $H }}">
                    <defs>
                        <linearGradient id="anaAreaV" x1="0" x2="0" y1="0" y2="1">
                            <stop offset="0%" stop-color="#ff2e4d" stop-opacity="0.14"></stop>
                            <stop offset="100%" stop-color="#ff2e4d" stop-opacity="0"></stop>
                        </linearGradient>
                        <linearGradient id="anaAreaR" x1="0" x2="0" y1="0" y2="1">
                            <stop offset="0%" stop-color="#38bdf8" stop-opacity="0.10"></stop>
                            <stop offset="100%" stop-color="#38bdf8" stop-opacity="0"></stop>
                        </linearGradient>
                    </defs>
                    @for($gi = 0; $gi <= $gridCount; $gi++)
                        @php
                            $gv = $niceMax * $gi / $gridCount;
                            $gy = $py($gv);
                            $isBase = $gi === 0;
                        @endphp
                        <line stroke="{{ $isBase ? 'rgba(255,255,255,.14)' : 'rgba(255,255,255,.07)' }}" stroke-dasharray="{{ $isBase ? 'none' : '3 5' }}" x1="{{ $L }}" x2="{{ $W - $R }}" y1="{{ $gy }}" y2="{{ $gy }}"></line>
                        <text x="{{ $L - 9 }}" y="{{ $gy + 3.5 }}" text-anchor="end" font-size="10.5" font-weight="600" fill="{{ $isBase ? 'rgba(148,163,184,.95)' : 'rgba(148,163,184,.8)' }}" font-family="inherit">{{ (int) $gv }}</text>
                    @endfor
                    <path d="{{ $areaR }}" fill="url(#anaAreaR)"></path>
                    <path d="{{ $areaV }}" fill="url(#anaAreaV)"></path>
                    <path d="{{ $pathR }}" stroke="#38bdf8" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" stroke-opacity="0.95"></path>
                    <path d="{{ $pathV }}" stroke="#ff2e4d" stroke-width="2.8" stroke-linecap="round" stroke-linejoin="round"></path>
                    <circle cx="{{ $lastR[0] }}" cy="{{ $lastR[1] }}" r="4" fill="#0d1220" stroke="#38bdf8" stroke-width="2.2"></circle>
                    <circle cx="{{ $lastV[0] }}" cy="{{ $lastV[1] }}" r="4.5" fill="#0d1220" stroke="#ff2e4d" stroke-width="2.6"></circle>
                    @php
                        // Label nilai akhir: di kanan titik, kecuali titik di ujung kanan → geser ke kiri (anchor end) agar tidak kepotong.
                        $endRight = $lastV[0] > $W - $R - ($W - $L - $R) * 0.25;
                        $endAnchor = $endRight ? 'end' : 'start';
                        $endDx = $endRight ? -9 : 9;
                        $endDy = $endRight ? 0 : 0;
                    @endphp
                    @if($lastR[1] > $T + 16)
                        <text x="{{ $lastR[0] + $endDx }}" y="{{ $lastR[1] + 3.5 }}" text-anchor="{{ $endAnchor }}" font-size="10" font-weight="700" fill="#7dd3fc" font-family="inherit">{{ $vals[$n - 1]['readers'] }} unik</text>
                    @endif
                    @if($lastV[1] > $T + 16)
                        <text x="{{ $lastV[0] + $endDx }}" y="{{ $lastV[1] - 6 }}" text-anchor="{{ $endAnchor }}" font-size="10" font-weight="700" fill="#fda4af" font-family="inherit">{{ $vals[$n - 1]['total'] }} views</text>
                    @endif
                    @foreach($xTicks as $ti)
                        @php $txi = $vals[$ti]; @endphp
                        <text x="{{ $px($ti) }}" y="{{ $H - 9 }}" text-anchor="{{ $ti === 0 ? 'start' : ($ti === $n - 1 ? 'end' : 'middle') }}" font-size="10" fill="rgba(148,163,184,.85)" font-family="inherit">{{ $txi['label'] }}</text>
                    @endforeach
                </svg>
            </div>
            @endif
        </div>

        <div class="ana-span-5 flex flex-col gap-6">
            {{-- Aktivitas hari ini --}}
            <div class="adm-card rounded-2xl shadow-sm p-6">
                <div class="flex items-center justify-between pb-4">
                    <div>
                        <h3 class="font-display text-lg font-semibold text-white">Aktivitas Hari Ini</h3>
                        <p class="text-sm text-slate-500">{{ now()->locale('id')->translatedFormat('l, d F Y') }}</p>
                    </div>
                    <span class="p-2 rounded-lg adm-chip text-emerald-400"><i class="fa-solid fa-bolt text-sm"></i></span>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    @foreach([
                        ['views', $todayStats['views'], 'text-brand', 'fa-eye'],
                        ['komentar', $todayStats['comments'], 'text-sky-400', 'fa-message'],
                        ['user baru', $todayStats['new_users'], 'text-emerald-400', 'fa-user-plus'],
                        ['chapter', $todayStats['chapters'], 'text-fuchsia-400', 'fa-layer-group'],
                    ] as $t)
                        <div class="rounded-xl adm-chip p-3.5">
                            <div class="flex items-center justify-between">
                                <span class="text-[10px] font-medium text-slate-500 uppercase tracking-wider">{{ $t[0] }}</span>
                                <i class="fa-solid {{ $t[3] }} text-[11px] {{ $t[2] }}"></i>
                            </div>
                            <div class="mt-1.5 font-display text-xl font-bold text-white">{{ number_format($t[1]) }}</div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Komentar terbaru --}}
            <div class="adm-card rounded-2xl shadow-sm flex-1 overflow-hidden">
                <div class="px-5 py-4 border-b border-white/5 flex items-center justify-between">
                    <h3 class="font-display text-lg font-semibold text-white">Komentar Terbaru</h3>
                    <span class="px-2 py-0.5 rounded-full adm-chip text-slate-400 text-[11px]">{{ count($recentComments) }}</span>
                </div>
                <div class="divide-y divide-white/5 max-h-[290px] overflow-y-auto">
                    @forelse($recentComments as $c)
                        <div class="px-5 py-3">
                            <div class="flex items-center gap-2 text-xs">
                                <span class="font-semibold text-slate-200 truncate">{{ $c['user'] }}</span>
                                <span class="text-slate-600">·</span>
                                <span class="text-slate-500 truncate">{{ $c['time'] }}</span>
                            </div>
                            <p class="text-[13px] text-slate-400 mt-1 leading-snug">{{ $c['body'] }}</p>
                            @if($c['slug'])
                                <a href="{{ route('manga.show', $c['slug']) }}" class="text-[11px] text-brand hover:text-brand-dark mt-0.5 inline-block">di {{ $c['manga'] }}</a>
                            @endif
                        </div>
                    @empty
                        <div class="px-5 py-10 text-center">
                            <i class="fa-solid fa-comment-slash text-2xl" style="color:rgba(148,163,184,.35)"></i>
                            <p class="mt-2 text-sm text-slate-500">Belum ada komentar.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    {{-- 3. Baris: chapter (4) + user growth (4) + genre (4) --}}
    <div class="mt-6 ana-grid">
        <div class="ana-span-4 adm-card p-6 rounded-2xl shadow-sm">
            <div class="flex items-start justify-between gap-3 pb-5">
                <div>
                    <h2 class="font-display text-lg font-semibold text-white">Rilis Chapter per Bulan</h2>
                    <p class="mt-0.5 text-sm text-slate-500">Produktivitas upload 6 bulan terakhir.</p>
                </div>
                <span class="px-2.5 py-1 rounded-full text-[11px] font-bold whitespace-nowrap" style="color:#34d399;background:rgba(52,211,153,.1);border:1px solid rgba(52,211,153,.25)"><i class="fa-solid fa-circle-check mr-1"></i>{{ number_format($chapterSeries->sum('total')) }} total</span>
            </div>
            @php $cm = $chapterMax; @endphp
            <div class="flex items-end justify-between gap-2 h-44 px-1">
                @foreach($chapterSeries as $b)
                    <div class="flex-1 flex flex-col items-center gap-2 min-w-0">
                        <span class="text-[11px] font-semibold {{ $b['total'] > 0 ? 'text-slate-300' : 'text-slate-600' }}">{{ $b['total'] }}</span>
                        <div class="w-full max-w-[38px] rounded-t-lg bg-gradient-to-t from-brand/80 to-brand transition-all duration-500"
                             style="height: {{ max(4, round($b['total'] / $cm * 130)) }}px"></div>
                        <span class="text-[10px] text-slate-500">{{ $b['label'] }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="ana-span-4 adm-card p-6 rounded-2xl shadow-sm">
            <div class="pb-5">
                <h2 class="font-display text-lg font-semibold text-white">Pertumbuhan User</h2>
                <p class="mt-0.5 text-sm text-slate-500">Registrasi akun baru 6 bulan terakhir.</p>
            </div>
            @php
                $um = $userMax;
                $ugMax = max(1, $um);
                // skala Y: 0 .. max (label di kiri, garis grid)
                $us = count($userSeries) > 1 ? (280 / (count($userSeries) - 1)) : 0;
                $up = collect(range(0, count($userSeries) - 1))->map(fn($i) => [round($i * $us), round(100 - ($userSeries[$i]['total'] / $ugMax) * 80)]);
                $ul = $up->map(fn($p) => "{$p[0]},{$p[1]}")->implode(' ');
            @endphp
            <div class="relative w-full mt-2">
                <svg class="w-full" fill="none" preserveAspectRatio="none" viewBox="0 0 280 120" style="height:150px">
                    @foreach([0, 0.5, 1] as $frac)
                        @php
                            $gy = round(100 - $frac * 80);
                            $gv = $ugMax * $frac;
                        @endphp
                        <line stroke="rgba(255,255,255,.06)" stroke-dasharray="3 3" x1="30" x2="280" y1="{{ $gy }}" y2="{{ $gy }}"></line>
                        <text x="24" y="{{ $gy + 3 }}" text-anchor="end" font-size="8.5" fill="rgba(148,163,184,.7)" font-family="inherit">{{ (int) round($gv) }}</text>
                    @endforeach
                    <polyline points="{{ $ul }}" stroke="#34d399" stroke-width="2.5" stroke-linecap="round" fill="none"></polyline>
                    @foreach($up as $i => $p)
                        <circle cx="{{ $p[0] }}" cy="{{ $p[1] }}" r="3" fill="#0d1220" stroke="#34d399" stroke-width="2"></circle>
                    @endforeach
                </svg>
                <div class="flex items-center justify-between text-[11px] text-slate-500 pt-2 pl-7">
                    @foreach($userSeries as $u)
                        <span>{{ $u['label'] }}</span>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="ana-span-4 adm-card p-6 rounded-2xl shadow-sm">
            <div class="flex items-center justify-between pb-2">
                <h3 class="font-display text-lg font-semibold text-white">Genre Terpopuler</h3>
                <span class="text-[11px] font-semibold text-brand adm-chip px-2 py-0.5 rounded-lg">Top 5</span>
            </div>
            <p class="text-sm text-slate-500">Berdasarkan total views 30 hari.</p>
            <div class="space-y-4 mt-5">
                @forelse($genreViews as $gi => $g)
                    @php
                        $pct = $genreMax > 0 ? round($g->total / $genreMax * 100) : 0;
                        $cols = ['#ff2e4d', '#38bdf8', '#a78bfa', '#f59e0b', '#34d399'];
                        $c = $cols[$gi % count($cols)];
                    @endphp
                    <div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-slate-200 flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full" style="background-color:{{ $c }}"></span>{{ $g->name }}
                            </span>
                            <span class="text-xs text-slate-500">{{ $pct }}% · {{ number_format($g->total) }}</span>
                        </div>
                        <div class="w-full h-2 rounded-full bg-white/5 overflow-hidden mt-1.5">
                            <div class="h-full rounded-full transition-all duration-500" style="width:{{ $pct }}%;background-color:{{ $c }}"></div>
                        </div>
                    </div>
                @empty
                    <div class="py-6 text-center">
                        <i class="fa-solid fa-chart-pie text-2xl" style="color:rgba(148,163,184,.35)"></i>
                        <p class="mt-2 text-sm text-slate-500">Belum ada data views.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- 4. Baris: rating (6) + top manga (6) --}}
    <div class="mt-6 ana-grid">
        <div class="ana-span-6 adm-card rounded-2xl shadow-sm overflow-hidden">
            <div class="px-6 py-5 border-b border-white/5 flex items-center justify-between">
                <div>
                    <h2 class="font-display text-lg font-semibold text-white">Rating Tertinggi</h2>
                    <p class="mt-0.5 text-sm text-slate-500">Manga favorit pembaca berdasar skor & jumlah vote.</p>
                </div>
                <i class="fa-solid fa-star text-amber-400"></i>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr>
                            <th class="py-3 px-5 text-left font-semibold text-[11px] text-slate-500 uppercase tracking-wider">Manga</th>
                            <th class="py-3 px-3 text-left font-semibold text-[11px] text-slate-500 uppercase tracking-wider">Rating</th>
                            <th class="py-3 px-5 text-right font-semibold text-[11px] text-slate-500 uppercase tracking-wider">Vote</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @forelse($topRated as $i => $m)
                            <tr class="adm-tr-hover transition-colors">
                                <td class="py-3.5 px-5">
                                    <div class="flex items-center gap-3">
                                        <span class="w-6 text-center font-mono text-xs {{ $i === 0 ? 'text-amber-400' : 'text-slate-600' }}">{{ $i + 1 }}</span>
                                        <img src="{{ $m->cover_image ?: asset('images/no-image.png') }}" alt="" class="h-12 w-9 object-cover rounded-md adm-chip">
                                        <div class="min-w-0">
                                            <a href="{{ route('manga.show', $m->slug) }}" class="font-medium text-slate-200 block truncate max-w-[200px] hover:text-brand">{{ $m->title }}</a>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3.5 px-3">
                                    <span class="inline-flex items-center gap-1.5 px-2 py-1 rounded-lg adm-chip text-[12px] font-bold {{ $m->r >= 4.5 ? 'text-amber-400' : ($m->r >= 3.5 ? 'text-emerald-400' : 'text-slate-400') }}">
                                        <i class="fa-solid fa-star text-[10px]"></i>{{ number_format($m->r, 1) }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-5 text-right text-xs text-slate-500 font-mono">{{ number_format($m->votes) }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="py-12 text-center">
                                <div class="inline-flex items-center justify-center w-12 h-12 rounded-2xl adm-chip mb-3">
                                    <i class="fa-solid fa-star-half-stroke text-xl" style="color:rgba(148,163,184,.5)"></i>
                                </div>
                                <p class="text-sm font-medium text-slate-300">Belum ada rating</p>
                                <p class="mt-1 text-xs text-slate-500 max-w-[260px] mx-auto leading-relaxed">Fitur rating belum dipakai pembaca. Begitu ada yang memberi skor, peringkatnya muncul di sini.</p>
                            </td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="ana-span-6 adm-card rounded-2xl shadow-sm overflow-hidden">
            <div class="px-6 py-5 border-b border-white/5 flex items-center justify-between">
                <div>
                    <h2 class="font-display text-lg font-semibold text-white">Manga Terpopuler</h2>
                    <p class="mt-0.5 text-sm text-slate-500">Peringkat berdasar views & pembaca unik 30 hari terakhir.</p>
                </div>
                <span class="px-2 py-0.5 rounded-full adm-chip text-slate-400 text-[11px]">Top 5</span>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr>
                            <th class="py-3 px-5 text-left font-semibold text-[11px] text-slate-500 uppercase tracking-wider">#</th>
                            <th class="py-3 px-3 text-left font-semibold text-[11px] text-slate-500 uppercase tracking-wider">Manga</th>
                            <th class="py-3 px-3 text-left font-semibold text-[11px] text-slate-500 uppercase tracking-wider">Views</th>
                            <th class="py-3 px-5 text-right font-semibold text-[11px] text-slate-500 uppercase tracking-wider">Pembaca Unik</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @forelse($topManga as $i => $m)
                            <tr class="adm-tr-hover transition-colors">
                                <td class="py-3.5 px-5 font-mono text-xs {{ $i === 0 ? 'text-brand' : 'text-slate-600' }}">{{ $i + 1 }}</td>
                                <td class="py-3.5 px-3">
                                    <div class="flex items-center gap-3">
                                        <img src="{{ $m->cover_image ?: asset('images/no-image.png') }}" alt="" class="h-12 w-9 object-cover rounded-md adm-chip">
                                        <div class="min-w-0">
                                            <a href="{{ route('manga.show', $m->slug) }}" class="font-medium text-slate-200 block truncate max-w-[240px] hover:text-brand">{{ $m->title }}</a>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3.5 px-3">
                                    <div class="flex items-center gap-2">
                                        <span class="text-sm font-semibold text-slate-200">{{ number_format($m->total) }}</span>
                                        <div class="w-16 h-1.5 rounded-full bg-white/5 overflow-hidden">
                                            <div class="h-full rounded-full bg-brand" style="width: {{ $topManga->max('total') > 0 ? round($m->total / $topManga->max('total') * 100) : 0 }}%"></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3.5 px-5 text-right text-xs text-slate-500 font-mono">{{ number_format($m->readers) }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="py-10 text-center">
                                <i class="fa-solid fa-eye-slash text-2xl" style="color:rgba(148,163,184,.35)"></i>
                                <p class="mt-2 text-sm text-slate-500">Belum ada data views 30 hari.</p>
                            </td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- 5. Baris bawah: komposisi katalog genre (4) + statistik cepat (4) + bookmark/chapter info (4) --}}
    <div class="mt-6 ana-grid">
        <div class="ana-span-4 adm-card p-6 rounded-2xl shadow-sm grow-y-wrap">
            <div class="flex items-center justify-between pb-2">
                <h3 class="font-display text-lg font-semibold text-white">Komposisi Katalog</h3>
                <span class="text-[11px] font-semibold text-sky-400 adm-chip px-2 py-0.5 rounded-lg">by Genre</span>
            </div>
            <p class="text-sm text-slate-500">Jumlah judul manga per genre.</p>
            <div class="space-y-3.5 mt-5">
                @forelse($mangaByGenre as $g)
                    <div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-slate-200">{{ $g->name }}</span>
                            <span class="text-xs text-slate-500 font-mono">{{ $g->total }} judul</span>
                        </div>
                        <div class="w-full h-1.5 rounded-full bg-white/5 overflow-hidden mt-1.5">
                            <div class="h-full rounded-full" style="width:{{ round($g->total / $genreCatalogMax * 100) }}%;background-color:#38bdf8"></div>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-slate-500 italic">Belum ada genre.</p>
                @endforelse
            </div>
        </div>

        <div class="ana-span-4 adm-card p-6 rounded-2xl shadow-sm">
            <h3 class="font-display text-lg font-semibold text-white pb-2">Perbandingan Interaksi</h3>
            <p class="text-sm text-slate-500 pb-5">Komposisi aktivitas user di NeoManga.</p>
            @php
                $inter = ['Rating' => $ratingVotes, 'Komentar' => $totalComments, 'Bookmark' => $totalBookmarks];
                $imax = max(1, max($inter));
                $ic = ['Rating' => '#f59e0b', 'Komentar' => '#38bdf8', 'Bookmark' => '#a78bfa'];
            @endphp
            <div class="space-y-5 mt-5">
                @foreach($inter as $k => $v)
                    <div>
                        <div class="flex items-center justify-between text-sm mb-1.5">
                            <span class="text-slate-200 inline-flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full" style="background-color:{{ $ic[$k] }}"></span>{{ $k }}
                            </span>
                            <span class="text-xs text-slate-500 font-mono">{{ number_format($v) }}</span>
                        </div>
                        <div class="w-full h-2 rounded-full bg-white/5 overflow-hidden">
                            <div class="h-full rounded-full" style="width:{{ round($v / $imax * 100) }}%;background-color:{{ $ic[$k] }}"></div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="mt-auto pt-5 border-t border-white/5 flex items-center justify-between text-xs">
                <span class="text-slate-500 inline-flex items-center gap-1.5">
                    <i class="fa-solid fa-check-circle text-emerald-400"></i> Total interaksi
                </span>
                <span class="font-display text-lg font-bold text-white">{{ number_format($ratingVotes + $totalComments + $totalBookmarks) }}</span>
            </div>
        </div>

        <div class="ana-span-4 adm-card p-6 rounded-2xl shadow-sm">
            <div class="flex items-center justify-between pb-2">
                <h3 class="font-display text-lg font-semibold text-white">Efisiensi Konten</h3>
                <span class="p-2 rounded-lg adm-chip text-brand"><i class="fa-solid fa-chart-simple text-sm"></i></span>
            </div>
            <p class="text-sm text-slate-500 pb-5">Rasio performa tiap judul di katalog.</p>
            <div class="space-y-4">
                @php
                    $ratioViews = $mangaCount > 0 ? round($totalViews / $mangaCount, 1) : 0;
                    $ratioCh = $mangaCount > 0 ? round($chapterCount / $mangaCount, 1) : 0;
                    $ratioCm = $totalViews > 0 ? round($totalComments / $totalViews * 100, 1) : 0;
                @endphp
                <div class="flex items-center justify-between rounded-xl adm-chip px-4 py-3">
                    <span class="text-sm text-slate-300 inline-flex items-center gap-2"><i class="fa-solid fa-eye text-brand text-xs"></i> Views / judul</span>
                    <span class="font-display text-lg font-bold text-white">{{ number_format($ratioViews, 1) }}</span>
                </div>
                <div class="flex items-center justify-between rounded-xl adm-chip px-4 py-3">
                    <span class="text-sm text-slate-300 inline-flex items-center gap-2"><i class="fa-solid fa-layer-group text-sky-400 text-xs"></i> Chapter / judul</span>
                    <span class="font-display text-lg font-bold text-white">{{ number_format($ratioCh, 1) }}</span>
                </div>
                <div class="flex items-center justify-between rounded-xl adm-chip px-4 py-3">
                    <span class="text-sm text-slate-300 inline-flex items-center gap-2"><i class="fa-solid fa-message text-fuchsia-400 text-xs"></i> Komentar / 100 views</span>
                    <span class="font-display text-lg font-bold text-white">{{ number_format($ratioCm, 1) }}</span>
                </div>
            </div>
            <div class="mt-5 rounded-xl px-4 py-3 bg-gradient-to-r from-brand/15 to-transparent border border-brand/20 flex items-center gap-3">
                <i class="fa-solid fa-circle-info text-brand text-sm"></i>
                <p class="text-[11px] text-slate-400 leading-snug">Rata-rata tiap judul dikunjungi <b class="text-slate-200">{{ number_format($ratioViews, 1) }}×</b> — pantau judul di bawah rata-rata untuk promosi.</p>
            </div>
        </div>
    </div>
@endsection
