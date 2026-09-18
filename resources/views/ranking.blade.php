@extends('layouts.app')

@section('title', 'Ranking Manga — NeoManga')

@section('meta_description', 'Ranking manga, manhwa & manhua paling populer di NeoManga. Lihat manga terpopuler minggu ini: podium 3 besar + daftar lengkap.')

@push('styles')
<link rel="stylesheet" href="/css/ranking.css?v=20260918-1">
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
                    <h1><i class="fa-solid fa-trophy"></i>Leaderboard Manga</h1>
                    <p class="rk-sub">Paling ramai dibaca pembaca NeoManga</p>
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

            @if($mangas->isNotEmpty())
                @php
                    $top3 = $mangas->take(3);
                    $rest = $mangas->slice(3);
                    $maxViews = (int) $mangas->max('views_count') ?: 1;
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
                            @php $m = $top3->get($po['rank'] - 1); @endphp
                            @if($m)
                                <a href="{{ route('manga.show', $m->slug) }}" class="rk-slot {{ $po['slot'] }}" title="{{ $m->title }}">
                                    <div class="rk-cover-wrap">
                                        @if($po['rank'] === 1)<span class="rk-crown">👑</span>@endif
                                        @if($m->cover_image)
                                            <img class="rk-cover" src="{{ $m->cover_url }}" alt="{{ $m->title }}" loading="lazy">
                                        @else
                                            <div class="rk-cover" style="display:flex;align-items:center;justify-content:center;color:#5f6a80"><i class="fa-solid fa-book"></i></div>
                                        @endif
                                        <span class="rk-rankchip">{{ $po['rank'] }}</span>
                                    </div>
                                    <div class="rk-sname">{{ $m->title }}</div>
                                    <div class="rk-bar {{ $po['bar'] }}">
                                        <span><span class="rk-bnum">{{ $po['rank'] }}</span></span>
                                        <span class="rk-bpts">{{ number_format($m->views_count) }} dibaca</span>
                                    </div>
                                </a>
                            @endif
                        @endforeach
                    </div>

                    {{-- ===== List peringkat 4-10 ===== --}}
                    <div class="rk-list">
                        @foreach($rest as $i => $m)
                            @php $rank = $i + 4; @endphp
                            <a href="{{ route('manga.show', $m->slug) }}" class="rk-item" title="{{ $m->title }}">
                                <div class="rk-av-wrap">
                                    @if($m->cover_image)
                                        <img class="rk-av" src="{{ $m->cover_url }}" alt="{{ $m->title }}" loading="lazy">
                                    @else
                                        <div class="rk-av" style="display:flex;align-items:center;justify-content:center;color:#5f6a80"><i class="fa-solid fa-book"></i></div>
                                    @endif
                                    <span class="rk-rank">{{ $rank }}</span>
                                </div>
                                <div class="rk-info">
                                    <div class="rk-name">{{ $m->title }}</div>
                                    <div class="rk-pts"><b>{{ number_format($m->views_count) }}</b> dibaca</div>
                                    <div class="rk-track">
                                        <div class="rk-fill" style="width:{{ round($m->views_count / $maxViews * 100) }}%"></div>
                                    </div>
                                </div>
                                <div class="rk-right">
                                    @if($m->latestPublishedChapter)
                                        <span class="rk-ch">Ch. {{ $m->latestPublishedChapter->number }}</span>
                                        <div class="rk-when">{{ $m->latestPublishedChapter->created_at?->diffForHumans(['short' => true, 'parts' => 1]) }}</div>
                                    @endif
                                </div>
                            </a>
                        @endforeach
                    </div>

                </div>
            @else
                <div class="rk-empty">
                    <div class="ic"><i class="fa-solid fa-trophy"></i></div>
                    <p class="lead">Belum ada data periode ini</p>
                    <p class="sub">Buka halaman manga atau baca chapter — nanti masuk leaderboard.</p>
                </div>
            @endif

        </div>
    </div>
</div>
@endsection