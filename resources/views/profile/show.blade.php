@extends('layouts.app')

@section('title', 'Profil ' . e($user->name) . ' — NeoManga')

@section('meta_description', 'Profil pengguna NeoManga: bookmark, riwayat baca, dan komentar.')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/profile/show.css') }}">
@endpush

@section('content')
<div class="container-nm py-6 md:py-8">

    {{-- Flash message --}}
    @if (session('success'))
        <div class="mb-6 rounded-xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-300 px-4 py-3 text-sm flex items-center gap-2" role="alert">
            <i class="fa-solid fa-circle-check"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    {{-- ===== Header Profil ===== --}}
    <div class="profile-hero relative overflow-hidden rounded-3xl ring-1 ring-white/10 shadow-2xl shadow-black/30 mb-4">
        {{-- Glow dekoratif --}}
        <div class="absolute -top-24 -right-16 w-80 h-80 bg-[#ff2e4d]/20 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-24 -left-16 w-72 h-72 bg-indigo-500/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative px-6 py-8 sm:px-8 sm:py-10 flex flex-col sm:flex-row sm:items-center gap-6">
            {{-- Avatar --}}
            <div class="flex-shrink-0">
                <div class="profile-avatar-ring">
                    @if($user->photo_profile)
                        <img src="{{ $user->photo_profile }}" alt="{{ $user->name }}" class="w-24 h-24 sm:w-28 sm:h-28 rounded-[1.1rem] object-cover">
                    @else
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=ff2e4d&color=fff&size=224&font-size=0.42&rounded=true" alt="{{ $user->name }}" class="w-24 h-24 sm:w-28 sm:h-28 rounded-[1.1rem] object-cover">
                    @endif
                </div>
            </div>

            <div class="flex-1 min-w-0">
                <div class="flex items-center flex-wrap gap-x-3 gap-y-2">
                    <h1 class="font-display text-2xl sm:text-4xl font-bold text-white tracking-tight">{{ $user->name }}</h1>
                    @if($user->role === 'admin')
                        <span class="inline-flex items-center gap-1.5 text-[10px] font-bold uppercase tracking-wider bg-[#ff2e4d]/15 text-[#ff4d66] border border-[#ff2e4d]/30 rounded-full px-3 py-1">
                            <i class="fa-solid fa-user-shield"></i> Admin
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 text-[10px] font-bold uppercase tracking-wider bg-emerald-500/10 text-emerald-400 border border-emerald-500/25 rounded-full px-3 py-1">
                            <i class="fa-solid fa-circle-check"></i> Member
                        </span>
                    @endif
                </div>

                <div class="flex items-center flex-wrap gap-x-5 gap-y-1 mt-2.5 text-sm text-slate-400">
                    <span class="inline-flex items-center gap-2 min-w-0">
                        <i class="fa-regular fa-envelope text-[#ff2e4d]/80 flex-shrink-0"></i>
                        <span class="truncate">{{ $user->email }}</span>
                    </span>
                    <span class="inline-flex items-center gap-2">
                        <i class="fa-regular fa-calendar text-[#ff2e4d]/80"></i>
                        Bergabung {{ $user->created_at?->translatedFormat('d F Y') }}
                    </span>
                </div>

                <div class="mt-5 flex flex-wrap items-center gap-2.5">
                    <a href="{{ route('profile.edit') }}" class="btn-primary !py-2.5 !px-5 text-sm">
                        <i class="fa-solid fa-gear mr-1.5"></i>Edit Profil
                    </a>
                    <a href="{{ route('history.index') }}" class="btn-ghost !py-2.5 !px-5 text-sm !bg-white/10 !text-white hover:!bg-white/20">
                        <i class="fa-solid fa-clock-rotate-left mr-1.5"></i>Riwayat Baca
                    </a>
                    <a href="{{ route('bookmark.index') }}" class="btn-ghost !py-2.5 !px-5 text-sm !bg-white/10 !text-white hover:!bg-white/20">
                        <i class="fa-solid fa-bookmark mr-1.5"></i>Semua Bookmark
                    </a>
                </div>
            </div>
        </div>

        {{-- Strip statistik di dalam banner --}}
        <div class="relative border-t border-white/10 bg-black/20 backdrop-blur-sm">
            <div class="grid grid-cols-2 sm:grid-cols-4 divide-x divide-white/10">
                @php
                    $stats = [
                        ['label' => 'Bookmark', 'icon' => 'fa-solid fa-bookmark', 'value' => $user->bookmarks_count],
                        ['label' => 'Manga Dibaca', 'icon' => 'fa-solid fa-clock-rotate-left', 'value' => $user->histories_count],
                        ['label' => 'Komentar', 'icon' => 'fa-regular fa-comment', 'value' => $user->comments_count],
                        ['label' => 'Member Sejak', 'icon' => 'fa-regular fa-calendar-check', 'value' => $user->created_at?->format('Y') ?? '-'],
                    ];
                @endphp
                @foreach($stats as $i => $stat)
                    <div class="stat-card px-4 sm:px-6 py-4 {{ $i % 2 === 1 ? 'border-l border-white/10 sm:border-l' : '' }} {{ $i === 1 ? '!border-l-0' : '' }} {{ $i === 2 ? 'sm:border-l sm:border-l-white/10' : '' }} {{ $i === 3 ? 'sm:border-l' : '' }}">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 flex-shrink-0 rounded-lg bg-[#ff2e4d]/15 text-[#ff4d66] flex items-center justify-center text-sm">
                                <i class="{{ $stat['icon'] }}"></i>
                            </div>
                            <div class="min-w-0">
                                <p class="font-display text-xl sm:text-2xl font-bold text-white leading-none">{{ $stat['value'] }}</p>
                                <p class="text-[11px] text-slate-400 mt-1 truncate">{{ $stat['label'] }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mt-4">
        {{-- ===== Kolom kiri (2/3) ===== --}}
        <div class="lg:col-span-2 space-y-10">

            {{-- Bookmark terbaru --}}
            <section>
                <div class="flex items-end justify-between flex-wrap gap-3 mb-5">
                    <h2 class="section-title !mb-0 !text-slate-900 dark:!text-white">
                        <i class="fa-solid fa-bookmark text-[#ff2e4d] text-xl"></i>Bookmark Terbaru
                    </h2>
                    <a href="{{ route('bookmark.index') }}" class="text-xs font-semibold text-[#ff2e4d] hover:text-[#e62242] transition-colors">
                        Lihat semua <i class="fa-solid fa-arrow-right ml-1"></i>
                    </a>
                </div>

                @if($recentBookmarks->isNotEmpty())
                    <div class="grid grid-cols-4 sm:grid-cols-6 lg:grid-cols-8 gap-x-3 gap-y-5">
                        @foreach($recentBookmarks as $bookmark)
                            @if($bookmark->manga)
                                <a href="{{ route('manga.show', $bookmark->manga->slug) }}" class="bm-card group block">
                                    <div class="bm-thumb relative aspect-[3/4] rounded-lg overflow-hidden ring-1 ring-slate-200 dark:ring-white/10 bg-slate-100 dark:bg-slate-800">
                                        @if($bookmark->manga->cover_image)
                                            <img src="{{ $bookmark->manga->cover_url }}" alt="{{ $bookmark->manga->title }}" loading="lazy" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center">
                                                <svg class="w-6 h-6 text-slate-400 dark:text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            </div>
                                        @endif
                                    </div>
                                    <p class="clamp-2 mt-1.5 text-[11px] font-medium text-slate-600 dark:text-slate-300 leading-snug group-hover:text-[#ff2e4d] transition-colors" title="{{ $bookmark->manga->title }}">{{ $bookmark->manga->title }}</p>
                                </a>
                            @endif
                        @endforeach
                    </div>
                @else
                    <div class="rounded-2xl border-2 border-dashed border-slate-200 dark:border-white/10 p-8 text-center">
                        <p class="text-3xl mb-3"><i class="fa-regular fa-bookmark text-slate-300 dark:text-slate-600"></i></p>
                        <p class="text-sm text-slate-500 dark:text-slate-400">Belum ada bookmark.</p>
                        <a href="{{ route('manga.list') }}" class="inline-block mt-3 text-xs font-semibold text-[#ff2e4d] hover:text-[#e62242]">Jelajahi manga &rsaquo;</a>
                    </div>
                @endif
            </section>

            {{-- Riwayat baca terbaru --}}
            <section>
                <div class="flex items-end justify-between flex-wrap gap-3 mb-5">
                    <h2 class="section-title !mb-0 !text-slate-900 dark:!text-white">
                        <i class="fa-solid fa-clock-rotate-left text-[#ff2e4d] text-xl"></i>Lanjutkan Baca
                    </h2>
                    <a href="{{ route('history.index') }}" class="text-xs font-semibold text-[#ff2e4d] hover:text-[#e62242] transition-colors">
                        Lihat semua <i class="fa-solid fa-arrow-right ml-1"></i>
                    </a>
                </div>

                @if($recentHistories->isNotEmpty())
                    <div class="space-y-2.5">
                        @foreach($recentHistories as $history)
                            @if($history->manga)
                                <a href="{{ route('chapter.show', $history->chapter?->slug ?? $history->manga->slug) }}" class="history-item flex items-center gap-4 p-3 rounded-xl bg-white dark:bg-slate-900 ring-1 ring-slate-200 dark:ring-white/10 hover:ring-[#ff2e4d]/40 transition-all group">
                                    <div class="w-11 h-[3.7rem] sm:w-12 sm:h-16 flex-shrink-0 rounded-md overflow-hidden ring-1 ring-slate-200 dark:ring-white/10 bg-slate-100 dark:bg-slate-800">
                                        @if($history->manga->cover_image)
                                            <img src="{{ $history->manga->cover_url }}" alt="{{ $history->manga->title }}" loading="lazy" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center">
                                                <svg class="w-4 h-4 text-slate-400 dark:text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="font-semibold text-sm text-slate-800 dark:text-white truncate group-hover:text-[#ff2e4d] transition-colors">{{ $history->manga->title }}</p>
                                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 flex items-center gap-1.5">
                                            @if($history->chapter)
                                                <span class="inline-flex items-center gap-1 rounded-md bg-[#ff2e4d]/10 text-[#ff4d66] px-2 py-0.5 text-[11px] font-semibold">
                                                    <i class="fa-solid fa-book-open-reader text-[9px]"></i>Chapter {{ $history->chapter->number }}
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1 rounded-md bg-slate-100 dark:bg-white/10 text-slate-500 dark:text-slate-400 px-2 py-0.5 text-[11px] font-semibold">
                                                    Mulai baca
                                                </span>
                                            @endif
                                            <span class="text-[11px] text-slate-400 dark:text-slate-500">{{ $history->updated_at?->diffForHumans(['short' => true, 'parts' => 1]) }}</span>
                                        </p>
                                    </div>
                                    <div class="flex-shrink-0 hidden sm:flex items-center gap-1.5 text-[11px] font-semibold text-[#ff2e4d] opacity-0 group-hover:opacity-100 transition-opacity">
                                        Lanjut <i class="fa-solid fa-arrow-right text-[9px]"></i>
                                    </div>
                                </a>
                            @endif
                        @endforeach
                    </div>
                @else
                    <div class="rounded-2xl border-2 border-dashed border-slate-200 dark:border-white/10 p-8 text-center">
                        <p class="text-3xl mb-3"><i class="fa-solid fa-clock-rotate-left text-slate-300 dark:text-slate-600"></i></p>
                        <p class="text-sm text-slate-500 dark:text-slate-400">Belum ada riwayat baca.</p>
                        <a href="{{ route('manga.list') }}" class="inline-block mt-3 text-xs font-semibold text-[#ff2e4d] hover:text-[#e62242]">Mulai baca sekarang &rsaquo;</a>
                    </div>
                @endif
            </section>
        </div>

        {{-- ===== Kolom kanan (1/3) ===== --}}
        <div class="space-y-8">

            {{-- Genre favorit --}}
            <section class="rounded-2xl bg-white dark:bg-slate-900 ring-1 ring-slate-200 dark:ring-white/10 p-5">
                <h3 class="section-title !mb-4 !text-base">
                    <i class="fa-solid fa-chart-pie text-[#ff2e4d]"></i>Genre Favorit
                </h3>
                @if($favoriteGenres->isNotEmpty())
                    <div class="flex flex-wrap gap-2">
                        @foreach($favoriteGenres as $genre)
                            <span class="genre-chip inline-flex items-center gap-1.5 text-xs font-medium bg-slate-100 dark:bg-white/5 text-slate-600 dark:text-slate-300 rounded-full px-3 py-1.5">
                                {{ $genre->name }}
                                <span class="text-[10px] font-bold text-[#ff2e4d]">{{ $genre->total }}x</span>
                            </span>
                        @endforeach
                    </div>
                    <div class="mt-4 pt-4 border-t border-slate-100 dark:border-white/5">
                        <p class="text-[11px] text-slate-400 dark:text-slate-500 leading-relaxed">
                            <i class="fa-solid fa-lightbulb text-amber-400/80 mr-1"></i>Genre favorit dihitung dari riwayat bacamu.
                        </p>
                    </div>
                @else
                    <p class="text-sm text-slate-400 dark:text-slate-500 italic">Baca beberapa manga dulu buat liat genre favoritmu.</p>
                @endif
            </section>

            {{-- Komentar terbaru --}}
            <section class="rounded-2xl bg-white dark:bg-slate-900 ring-1 ring-slate-200 dark:ring-white/10 p-5">
                <h3 class="section-title !mb-4 !text-base">
                    <i class="fa-regular fa-comment text-[#ff2e4d]"></i>Komentar Terbaru
                </h3>
                @if($recentComments->isNotEmpty())
                    <div class="space-y-4">
                        @foreach($recentComments as $comment)
                            <div class="comment-tl relative pl-5">
                                <span class="comment-dot absolute left-0 top-1.5 w-2.5 h-2.5 rounded-full bg-[#ff2e4d]"></span>
                                <p class="clamp-3 text-sm text-slate-700 dark:text-slate-300 leading-snug">{{ $comment->content }}</p>
                                <p class="text-[11px] text-slate-400 dark:text-slate-500 mt-1.5">
                                    @if($comment->manga)
                                        <a href="{{ route('manga.show', $comment->manga->slug) }}" class="font-semibold text-[#ff2e4d]/90 hover:text-[#ff2e4d]">{{ $comment->manga->title }}</a>
                                        <span class="mx-1">&middot;</span>
                                    @endif
                                    {{ $comment->created_at?->diffForHumans(['short' => true, 'parts' => 1]) }}
                                </p>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-sm text-slate-400 dark:text-slate-500 italic">Belum ada komentar.</p>
                @endif
            </section>
        </div>
    </div>
</div>
@endsection