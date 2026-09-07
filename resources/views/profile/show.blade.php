@extends('layouts.app')

@section('title', 'Profil ' . e($user->name) . ' — NeoManga')

@section('meta_description', 'Profil pengguna NeoManga: bookmark, riwayat baca, dan komentar.')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/profile/show.css') }}">
@endpush

@section('content')
<div class="pf-wrap">

    @if (session('success'))
        <div class="pf-flash" role="alert">
            <i class="fa-solid fa-circle-check"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    {{-- ===== Header Profil ===== --}}
    <div class="pf-hero">
        <div class="pf-glow a"></div>
        <div class="pf-glow b"></div>

        <div class="pf-hero-in">
            <div class="pf-avatar-ring">
                @if($user->photo_profile)
                    <img src="{{ $user->photo_profile }}" alt="{{ $user->name }}" class="pf-avatar">
                @else
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=ff2e4d&color=fff&size=224&font-size=0.42&rounded=true" alt="{{ $user->name }}" class="pf-avatar">
                @endif
            </div>

            <div class="pf-id">
                <div class="pf-name-row">
                    <h1 class="pf-name">{{ $user->name }}</h1>
                    @if($user->role === 'admin')
                        <span class="pf-badge admin"><i class="fa-solid fa-user-shield"></i> Admin</span>
                    @else
                        <span class="pf-badge member"><i class="fa-solid fa-circle-check"></i> Member</span>
                    @endif
                </div>

                <div class="pf-meta">
                    <span><i class="fa-regular fa-envelope"></i><span class="em">{{ $user->email }}</span></span>
                    <span><i class="fa-regular fa-calendar"></i>Bergabung {{ $user->created_at?->translatedFormat('d F Y') }}</span>
                </div>

                <div class="pf-actions">
                    @auth
                        @if(auth()->id() === $user->id)
                            <a href="{{ route('profile.edit') }}" class="btn btn-solid"><i class="fa-solid fa-gear"></i>Edit Profil</a>
                        @endif
                    @endauth
                    <a href="{{ route('history.index') }}" class="btn btn-ghost"><i class="fa-solid fa-clock-rotate-left"></i>Riwayat Baca</a>
                    <a href="{{ route('bookmark.index') }}" class="btn btn-ghost"><i class="fa-solid fa-bookmark"></i>Semua Bookmark</a>
                </div>
            </div>
        </div>

        {{-- Strip statistik --}}
        <div class="pf-stats">
            @php
                $stats = [
                    ['label' => 'Bookmark', 'icon' => 'fa-solid fa-bookmark', 'value' => $user->bookmarks_count],
                    ['label' => 'Manga Dibaca', 'icon' => 'fa-solid fa-clock-rotate-left', 'value' => $user->histories_count],
                    ['label' => 'Komentar', 'icon' => 'fa-regular fa-comment', 'value' => $user->comments_count],
                    ['label' => 'Member Sejak', 'icon' => 'fa-regular fa-calendar-check', 'value' => $user->created_at?->format('Y') ?? '-'],
                ];
            @endphp
            @foreach($stats as $stat)
                <div class="pf-stat">
                    <div class="ic"><i class="{{ $stat['icon'] }}"></i></div>
                    <div class="min-w-0">
                        <p class="v">{{ $stat['value'] }}</p>
                        <p class="l">{{ $stat['label'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="pf-grid">
        {{-- ===== Kolom kiri (2/3) ===== --}}
        <div class="pf-col">

            {{-- Bookmark terbaru --}}
            <section class="pf-card">
                <div class="pf-hdr">
                    <h2><i class="fa-solid fa-bookmark"></i>Bookmark Terbaru</h2>
                    @if($recentBookmarks->isNotEmpty())
                        <a href="{{ route('bookmark.index') }}" class="pf-more">Lihat semua <i class="fa-solid fa-arrow-right"></i></a>
                    @endif
                </div>

                @if($recentBookmarks->isNotEmpty())
                    <div class="pf-bms">
                        @foreach($recentBookmarks as $bookmark)
                            @if($bookmark->manga)
                                <a href="{{ route('manga.show', $bookmark->manga->slug) }}" class="pf-bm" title="{{ $bookmark->manga->title }}">
                                    <div class="th">
                                        @if($bookmark->manga->cover_image)
                                            <img src="{{ $bookmark->manga->cover_url }}" alt="{{ $bookmark->manga->title }}" loading="lazy">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center">
                                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            </div>
                                        @endif
                                    </div>
                                    <p class="tt">{{ $bookmark->manga->title }}</p>
                                </a>
                            @endif
                        @endforeach
                    </div>
                @else
                    <div class="pf-empty">
                        <div class="ic"><i class="fa-regular fa-bookmark"></i></div>
                        <p class="lead">Belum ada bookmark</p>
                        <p>Simpan manga favoritmu biar gampang ditemukan lagi nanti.</p>
                        <a href="{{ route('manga.list') }}">Jelajahi manga <i class="fa-solid fa-arrow-right"></i></a>
                    </div>
                @endif
            </section>

            {{-- Lanjutkan baca --}}
            <section class="pf-card">
                <div class="pf-hdr">
                    <h2><i class="fa-solid fa-clock-rotate-left"></i>Lanjutkan Baca</h2>
                    @if($recentHistories->isNotEmpty())
                        <a href="{{ route('history.index') }}" class="pf-more">Lihat semua <i class="fa-solid fa-arrow-right"></i></a>
                    @endif
                </div>

                @if($recentHistories->isNotEmpty())
                    <div class="pf-hist">
                        @foreach($recentHistories as $history)
                            @if($history->manga)
                                <a href="{{ route('chapter.show', $history->chapter?->slug ?? $history->manga->slug) }}" class="pf-hi">
                                    <div class="cv">
                                        @if($history->manga->cover_image)
                                            <img src="{{ $history->manga->cover_url }}" alt="{{ $history->manga->title }}" loading="lazy">
                                        @endif
                                    </div>
                                    <div class="tx">
                                        <p class="tt">{{ $history->manga->title }}</p>
                                        <div class="sub">
                                            @if($history->chapter)
                                                <span class="pf-ch"><i class="fa-solid fa-book-open-reader"></i>Chapter {{ $history->chapter->number }}</span>
                                            @else
                                                <span class="pf-ch plain">Mulai baca</span>
                                            @endif
                                            <span class="pf-when">{{ $history->updated_at?->diffForHumans(['short' => true, 'parts' => 1]) }}</span>
                                        </div>
                                    </div>
                                    <span class="pf-go">Lanjut <i class="fa-solid fa-arrow-right"></i></span>
                                </a>
                            @endif
                        @endforeach
                    </div>
                @else
                    <div class="pf-empty">
                        <div class="ic"><i class="fa-solid fa-clock-rotate-left"></i></div>
                        <p class="lead">Belum ada riwayat baca</p>
                        <p>Mulai baca manga dan riwayatmu bakal muncul di sini.</p>
                        <a href="{{ route('manga.list') }}">Mulai baca sekarang <i class="fa-solid fa-arrow-right"></i></a>
                    </div>
                @endif
            </section>
        </div>

        {{-- ===== Kolom kanan (1/3) ===== --}}
        <div class="pf-col">

            {{-- Genre favorit --}}
            <section class="pf-card pad2">
                <div class="pf-hdr">
                    <h3><i class="fa-solid fa-chart-pie"></i>Genre Favorit</h3>
                </div>
                @if($favoriteGenres->isNotEmpty())
                    <div class="pf-chips">
                        @foreach($favoriteGenres as $genre)
                            <span class="pf-chip">{{ $genre->name }} <b>{{ $genre->total }}x</b></span>
                        @endforeach
                    </div>
                    <p class="pf-note"><i class="fa-solid fa-lightbulb"></i>Genre favorit dihitung dari riwayat bacamu.</p>
                @else
                    <p class="pf-sub" style="margin:0">Baca beberapa manga dulu buat lihat genre favoritmu.</p>
                @endif
            </section>

            {{-- Komentar terbaru --}}
            <section class="pf-card pad2">
                <div class="pf-hdr">
                    <h3><i class="fa-regular fa-comment"></i>Komentar Terbaru</h3>
                </div>
                @if($recentComments->isNotEmpty())
                    <div class="pf-coms">
                        @foreach($recentComments as $comment)
                            <div class="pf-cm">
                                <span class="dot"></span>
                                <p class="tx">{{ $comment->content }}</p>
                                <p class="by">
                                    @if($comment->manga)
                                        <a href="{{ route('manga.show', $comment->manga->slug) }}">{{ $comment->manga->title }}</a>
                                        <span class="mx-1">·</span>
                                    @endif
                                    {{ $comment->created_at?->diffForHumans(['short' => true, 'parts' => 1]) }}
                                </p>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="pf-sub" style="margin:0">Belum ada komentar.</p>
                @endif
            </section>
        </div>
    </div>
</div>
@endsection
