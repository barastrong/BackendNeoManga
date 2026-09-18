@extends('layouts.app')

@section('title', 'Ranking Manga — NeoManga')

@section('meta_description', 'Ranking manga, manhwa & manhua paling populer di NeoManga minggu ini. Lihat apa yang lagi ramai dibaca!')

@section('content')
<div class="container-nm py-6 md:py-10">

    {{-- Header --}}
    <div class="flex flex-wrap items-end justify-between gap-4 mb-8">
        <div>
            <h1 class="font-display text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white">
                <i class="fa-solid fa-trophy text-[#ff2e4d] mr-2"></i>Ranking Manga
            </h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1.5">Manga paling ramai dibaca pembaca NeoManga.</p>
        </div>
        <div class="inline-flex items-center gap-1 p-1 bg-slate-100 dark:bg-white/5 rounded-xl">
            @foreach(['today' => 'Hari Ini', 'week' => 'Minggu Ini', 'month' => 'Bulan Ini'] as $key => $label)
                <a href="{{ request()->fullUrlWithQuery(['period' => $key]) }}"
                   class="px-3.5 py-1.5 text-xs font-semibold rounded-lg transition-all
                          {{ $period === $key ? 'bg-[#ff2e4d] text-white shadow' : 'text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-white' }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>
    </div>

    @if($mangas->isNotEmpty())
        <div class="space-y-3">
            @foreach($mangas as $i => $manga)
                <a href="{{ route('manga.show', $manga->slug) }}"
                   class="flex items-center gap-4 p-3 sm:p-4 rounded-2xl bg-white dark:bg-[#0d1220] border border-slate-200/70 dark:border-white/5 hover:border-[#ff2e4d]/50 hover:shadow-lg transition-all group">
                    {{-- Nomor ranking --}}
                    <div class="w-10 sm:w-12 flex-shrink-0 text-center">
                        <span class="font-display text-2xl sm:text-3xl font-extrabold {{ $i < 3 ? 'text-[#ff2e4d]' : 'text-slate-300 dark:text-slate-600' }}">
                            {{ $i + 1 }}
                        </span>
                    </div>

                    {{-- Cover --}}
                    <div class="w-12 h-16 sm:w-14 sm:h-20 flex-shrink-0 rounded-lg overflow-hidden bg-slate-100 dark:bg-white/5 ring-1 ring-slate-200 dark:ring-white/10">
                        @if($manga->cover_image)
                            <img src="{{ $manga->cover_url }}" alt="{{ $manga->title }}" loading="lazy" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-slate-300 dark:text-slate-600">
                                <i class="fa-solid fa-book"></i>
                            </div>
                        @endif
                    </div>

                    {{-- Info --}}
                    <div class="min-w-0 flex-1">
                        <p class="font-display font-semibold text-sm sm:text-base text-slate-900 dark:text-white truncate group-hover:text-[#ff2e4d] transition-colors">
                            {{ $manga->title }}
                        </p>
                        <div class="flex flex-wrap items-center gap-x-3 gap-y-1 mt-1 text-xs text-slate-500 dark:text-slate-400">
                            @if(isset($manga->genres) && $manga->genres->isNotEmpty())
                                <span>
                                    @foreach($manga->genres->take(3) as $genre)
                                        {{ $genre->name }}@if(!$loop->last), @endif
                                    @endforeach
                                </span>
                            @endif
                            <span class="inline-flex items-center gap-1 text-[#ff2e4d] font-semibold">
                                <i class="fa-solid fa-eye"></i>{{ number_format($manga->views_count) }} dibaca
                            </span>
                        </div>
                    </div>

                    <i class="fa-solid fa-chevron-right text-slate-300 dark:text-slate-600 group-hover:text-[#ff2e4d] group-hover:translate-x-0.5 transition-all flex-shrink-0"></i>
                </a>
            @endforeach
        </div>
    @else
        <div class="text-center py-16 rounded-2xl bg-white dark:bg-[#0d1220] border border-dashed border-slate-300 dark:border-white/10">
            <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-slate-100 dark:bg-white/5 text-slate-400 mb-4">
                <i class="fa-solid fa-trophy text-xl"></i>
            </div>
            <p class="font-display font-semibold text-slate-700 dark:text-slate-200">Belum ada data view periode ini</p>
            <p class="text-sm text-slate-400 mt-1">Buka halaman manga atau baca chapter — nanti masuk ranking.</p>
        </div>
    @endif

</div>
@endsection