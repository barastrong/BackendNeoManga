@extends('layouts.app')

@section('title', 'Leaderboard Pembaca — NeoManga')

@section('meta_description', 'Leaderboard pembaca NeoManga. Lihat pembaca paling aktif & rajin baca minggu ini: podium 3 besar + daftar lengkap.')

@push('styles')
<link rel="stylesheet" href="/css/ranking.css?v=20260918-2">
@endpush

@section('content')
<div class="rk-wrap">

    <div class="rk-screen">
        <div class="rk-glow a"></div>
        <div class="rk-glow b"></div>
        <div class="rk-in">

            {{-- Header --}}
            <div class="rk-head">
                <div>
                    <h1><i class="fa-solid fa-trophy"></i>Leaderboard Pembaca</h1>
                    <p class="rk-sub">Pembaca paling aktif &amp; rajin baca di NeoManga</p>
                </div>
            </div>

            {{-- Tabs periode --}}
            <div class="rk-tabs" role="tablist">
                @foreach(['today' => 'Hari Ini', 'week' => 'Minggu Ini', 'month' => 'Bulan Ini'] as $key => $label)
                    <a href="{{ request()->fullUrlWithQuery(['period' => $key]) }}"
                       class="rk-tab {{ $period === $key ? 'active' : '' }}"
                       role="tab" aria-selected="{{ $period === $key ? 'true' : 'false' }}">{{ $label }}</a>
                @endforeach
            </div>

            @if($users->isNotEmpty())
                @php
                    $top3 = $users->take(3);
                    $rest = $users->slice(3);
                    $maxReads = (int) $users->max('reads_count') ?: 1;
                    $podiumOrder = [
                        ['rank' => 2, 'slot' => 'second', 'bar' => 'silver'],
                        ['rank' => 1, 'slot' => 'first',  'bar' => 'gold'],
                        ['rank' => 3, 'slot' => 'third',  'bar' => 'bronze'],
                    ];
                @endphp

                <div class="rk-content">

                    {{-- ===== Podium 3 besar ===== --}}
                    <div class="rk-podium">
                        @foreach($podiumOrder as $po)
                            @php $u = $top3->get($po['rank'] - 1); @endphp
                            @if($u)
                                <a href="{{ route('user.public', $u->id) }}" class="rk-slot {{ $po['slot'] }}" title="{{ $u->name }}">
                                    <div class="rk-cover-wrap">
                                        @if($po['rank'] === 1)<span class="rk-crown">👑</span>@endif
                                        @if($u->photo_profile)
                                            <img class="rk-cover rk-ava" src="{{ $u->photo_profile }}" alt="{{ $u->name }}" loading="lazy">
                                        @else
                                            <img class="rk-cover rk-ava" src="https://ui-avatars.com/api/?name={{ urlencode($u->name) }}&background=ff2e4d&color=fff&size=128&font-size=0.42&rounded=true" alt="{{ $u->name }}" loading="lazy">
                                        @endif
                                        <span class="rk-rankchip">{{ $po['rank'] }}</span>
                                    </div>
                                    <div class="rk-sname">{{ $u->name }}</div>
                                    <div class="rk-lvl" style="color:{{ $u->level['color'] }};border-color:{{ $u->level['color'] }}55;background:{{ $u->level['color'] }}14">{{ $u->level['emoji'] }} Lv {{ $u->level['level'] }} · {{ $u->level['title'] }}</div>
                                    <div class="rk-bar {{ $po['bar'] }}">
                                        <span><span class="rk-bnum">{{ $po['rank'] }}</span></span>
                                        <span class="rk-bpts">{{ number_format($u->reads_count) }} dibaca</span>
                                    </div>
                                </a>
                            @endif
                        @endforeach
                    </div>

                    {{-- ===== List peringkat 4-10 ===== --}}
                    <div class="rk-list">
                        @foreach($rest as $i => $u)
                            @php $rank = $i + 4; @endphp
                            <a href="{{ route('user.public', $u->id) }}" class="rk-item" title="{{ $u->name }}">
                                <div class="rk-av-wrap">
                                    @if($u->photo_profile)
                                        <img class="rk-av rk-avasq" src="{{ $u->photo_profile }}" alt="{{ $u->name }}" loading="lazy">
                                    @else
                                        <img class="rk-av rk-avasq" src="https://ui-avatars.com/api/?name={{ urlencode($u->name) }}&background=ff2e4d&color=fff&size=128&font-size=0.42&rounded=true" alt="{{ $u->name }}" loading="lazy">
                                    @endif
                                    <span class="rk-rank">{{ $rank }}</span>
                                </div>
                                <div class="rk-info">
                                    <div class="rk-name">{{ $u->name }}</div>
                                    <div class="rk-pts"><b>{{ number_format($u->reads_count) }}</b> dibaca</div>
                                    <div class="rk-track">
                                        <div class="rk-fill" style="width:{{ round($u->reads_count / $maxReads * 100) }}%"></div>
                                    </div>
                                </div>
                                <div class="rk-right">
                                    <span class="rk-lvl" style="color:{{ $u->level['color'] }};border-color:{{ $u->level['color'] }}55;background:{{ $u->level['color'] }}14">{{ $u->level['emoji'] }} Lv {{ $u->level['level'] }} · {{ $u->level['title'] }}</span>
                                    <div class="rk-when">{{ $u->comments_count ?? '' }}</div>
                                </div>
                            </a>
                        @endforeach
                    </div>

                </div>
            @else
                <div class="rk-empty">
                    <div class="ic"><i class="fa-solid fa-trophy"></i></div>
                    <p class="lead">Belum ada data periode ini</p>
                    <p class="sub">Login &amp; baca chapter — nanti masuk leaderboard pembaca.</p>
                </div>
            @endif

        </div>
    </div>
</div>
@endsection