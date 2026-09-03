@extends('layouts.app')

@section('title', 'NeoManga — Baca Manga, Manhwa & Manhua Bahasa Indonesia')

@section('content')
<div class="container-nm py-6 md:py-8">

    {{-- Banner kecil --}}
    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-[#131a2c] via-[#0d1220] to-[#1a1030] ring-1 ring-white/10 mb-10">
        <div class="absolute -top-20 right-10 w-64 h-64 bg-[#ff2e4d]/15 rounded-full blur-3xl pointer-events-none"></div>
        <div class="relative px-6 py-8 sm:px-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="font-display text-2xl sm:text-3xl font-bold text-white leading-tight">
                    Baca Manga <span class="text-[#ff2e4d]">Gratis</span> di NeoManga
                </h1>
                <p class="text-slate-400 text-sm mt-1.5 max-w-lg">
                    Koleksi manga, manhwa &amp; manhua bahasa Indonesia terlengkap. Update terbaru setiap hari.
                </p>
            </div>
            <a href="{{ route('manga.list') }}" class="btn-primary flex-shrink-0 self-start sm:self-auto">
                <i class="fa-solid fa-compass mr-2"></i>Jelajahi Manga
            </a>
        </div>
    </div>

    {{-- ═══════════ SECTION 1: MANGA POPULER (berdasar user views) ═══════════ --}}
    <section class="mb-12">
        <div class="flex items-end justify-between flex-wrap gap-3 mb-6">
            <h2 class="section-title !mb-0">
                <i class="fa-solid fa-fire text-[#ff2e4d] text-xl"></i>Manga Populer
            </h2>
            <div class="inline-flex items-center gap-1 p-1 bg-slate-100 dark:bg-white/5 rounded-xl">
                @foreach(['today' => 'Hari Ini', 'week' => 'Minggu Ini', 'month' => 'Bulan Ini'] as $key => $label)
                    <a href="{{ request()->fullUrlWithQuery(['period' => $key]) }}"
                       class="px-3.5 py-1.5 text-xs font-semibold rounded-lg transition-all
                              {{ $period === $key ? 'bg-brand text-white shadow' : 'text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-white' }}">
                        {{ $label }}
                    </a>
                @endforeach
            </div>
        </div>

        @if($popularMangas->isNotEmpty())
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-x-5 gap-y-8">
                @foreach($popularMangas as $manga)
                    @include('partials.manga-card', ['manga' => $manga])
                @endforeach
            </div>
        @else
            <div class="text-center py-14 rounded-2xl bg-white dark:bg-[#0d1220] border border-dashed border-slate-300 dark:border-white/10">
                <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-slate-100 dark:bg-white/5 text-slate-400 mb-4">
                    <i class="fa-solid fa-chart-line text-xl"></i>
                </div>
                <p class="font-display font-semibold text-slate-700 dark:text-slate-200">Belum ada data view periode ini</p>
                <p class="text-sm text-slate-400 mt-1">Buka halaman manga atau baca chapter — nanti muncul di sini.</p>
            </div>
        @endif
    </section>

    {{-- ═══════════ SECTION 2: UPDATE TERBARU (chapter baru masuk) ═══════════ --}}
    <section class="mb-12">
        <h2 class="section-title">
            <i class="fa-solid fa-bolt text-[#ff2e4d] text-xl"></i>Update Terbaru
        </h2>

        @if($mangas->count() > 0)
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-x-5 gap-y-8">
                @foreach($mangas as $manga)
                    @include('partials.manga-card', ['manga' => $manga])
                @endforeach
            </div>

            @if($mangas->hasMorePages() || $mangas->count() >= 12)
                <div class="mt-10 flex justify-center">
                    <a href="{{ route('manga.list') }}" class="btn-primary">
                        <i class="fa-solid fa-angles-down mr-2"></i>Lihat Semua
                    </a>
                </div>
            @endif
        @else
            <div class="text-center py-14 rounded-2xl bg-white dark:bg-[#0d1220] border border-dashed border-slate-300 dark:border-white/10">
                <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-slate-100 dark:bg-white/5 text-slate-400 mb-4">
                    <i class="fa-solid fa-book-open text-xl"></i>
                </div>
                <p class="font-display font-semibold text-slate-700 dark:text-slate-200">Belum ada update chapter</p>
                <p class="text-sm text-slate-400 mt-1">Begitu chapter manga di-upload, langsung muncul di sini.</p>
            </div>
        @endif
    </section>
</div>
@endsection
