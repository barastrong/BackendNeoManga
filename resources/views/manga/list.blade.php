@extends('layouts.app')

@section('title', 'Daftar Manga — NeoManga')

@section('content')
<div class="container-nm py-6 md:py-8">
    {{-- Header halaman --}}
    <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-3 mb-6">
        <div>
            <h1 class="font-display text-2xl sm:text-3xl font-bold text-slate-900 dark:text-white">Daftar Manga</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Jelajahi koleksi manga, manhwa &amp; manhua kami</p>
        </div>
        <p class="text-sm text-slate-500 dark:text-slate-400">{{ $mangas->total() }} judul</p>
    </div>

    <form method="GET" action="{{ route('manga.list') }}" id="filterForm">
        <div class="p-4 sm:p-5 mb-8 rounded-2xl bg-white dark:bg-[#0d1220] border border-slate-200 dark:border-white/5 shadow-sm">
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                <div>
                    <label for="genreBtn" class="block text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide mb-1.5">Genre</label>
                    <button type="button" id="genreBtn"
                            class="w-full h-10 flex items-center justify-between text-left px-3.5 py-2 text-sm rounded-xl border border-slate-200 dark:border-white/10 bg-slate-50 dark:bg-white/5 text-slate-700 dark:text-slate-200 hover:border-slate-300 dark:hover:border-white/20 focus:outline-none focus:ring-2 focus:ring-[#ff2e4d]/50 transition-colors">
                        <span class="truncate" id="genreButtonText">
                            @php
                                $selectedGenres = request('genre', []);
                                $selectedGenreCount = is_array($selectedGenres) ? count($selectedGenres) : 0;
                            @endphp
                            @if($selectedGenreCount > 0)
                                {{ $selectedGenreCount }} genre dipilih
                            @else
                                Pilih Genre
                            @endif
                        </span>
                        <i class="fa-solid fa-chevron-down text-xs text-slate-400"></i>
                    </button>
                </div>

                <div>
                    <label for="status" class="block text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide mb-1.5">Status</label>
                    <div class="relative">
                        <select onchange="this.form.submit()" id="status" name="status"
                                class="w-full h-10 pl-3.5 pr-9 py-2 text-sm rounded-xl border border-slate-200 dark:border-white/10 bg-slate-50 dark:bg-white/5 text-slate-700 dark:text-slate-200 focus:outline-none focus:ring-2 focus:ring-[#ff2e4d]/50 appearance-none transition-colors">
                            <option value="">Semua</option>
                            <option value="ongoing" @if(request('status') == 'ongoing') selected @endif>Ongoing</option>
                            <option value="completed" @if(request('status') == 'completed') selected @endif>Tamat</option>
                            <option value="hiatus" @if(request('status') == 'hiatus') selected @endif>Hiatus</option>
                        </select>
                        <i class="fa-solid fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-xs text-slate-400 pointer-events-none"></i>
                    </div>
                </div>

                <div>
                    <label for="type" class="block text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide mb-1.5">Tipe</label>
                    <div class="relative">
                        <select onchange="this.form.submit()" id="type" name="type"
                                class="w-full h-10 pl-3.5 pr-9 py-2 text-sm rounded-xl border border-slate-200 dark:border-white/10 bg-slate-50 dark:bg-white/5 text-slate-700 dark:text-slate-200 focus:outline-none focus:ring-2 focus:ring-[#ff2e4d]/50 appearance-none transition-colors">
                            <option value="">Semua</option>
                            <option value="manga" @if(request('type') == 'manga') selected @endif>Manga (Jepang)</option>
                            <option value="manhwa" @if(request('type') == 'manhwa') selected @endif>Manhwa (Korea)</option>
                            <option value="manhua" @if(request('type') == 'manhua') selected @endif>Manhua (China)</option>
                            <option value="webtoon" @if(request('type') == 'webtoon') selected @endif>Webtoon</option>
                        </select>
                        <i class="fa-solid fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-xs text-slate-400 pointer-events-none"></i>
                    </div>
                </div>

                <div>
                    <label for="order" class="block text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide mb-1.5">Urutan</label>
                    <div class="relative">
                        <select onchange="this.form.submit()" id="order" name="order"
                                class="w-full h-10 pl-3.5 pr-9 py-2 text-sm rounded-xl border border-slate-200 dark:border-white/10 bg-slate-50 dark:bg-white/5 text-slate-700 dark:text-slate-200 focus:outline-none focus:ring-2 focus:ring-[#ff2e4d]/50 appearance-none transition-colors">
                            <option value="default" @if(request('order', 'default') == 'default') selected @endif>Default</option>
                            <option value="updated" @if(request('order') == 'updated') selected @endif>Terbaru Update</option>
                            <option value="newest" @if(request('order') == 'newest') selected @endif>Terbaru Ditambah</option>
                            <option value="popularity" @if(request('order') == 'popularity') selected @endif>Populer</option>
                            <option value="rating" @if(request('order') == 'rating') selected @endif>Rating</option>
                            <option value="a-z" @if(request('order') == 'a-z') selected @endif>A-Z</option>
                            <option value="z-a" @if(request('order') == 'z-a') selected @endif>Z-A</option>
                        </select>
                        <i class="fa-solid fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-xs text-slate-400 pointer-events-none"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- Modal genre --}}
        <div id="genreModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 hidden">
            <div id="genreModalContent" class="w-full max-w-2xl mx-4 bg-white dark:bg-[#0d1220] rounded-2xl shadow-2xl flex flex-col border border-slate-200 dark:border-white/10">
                <div class="p-5 border-b border-slate-200 dark:border-white/10 flex items-center justify-between">
                    <h3 class="text-lg font-display font-semibold text-slate-900 dark:text-white">Pilih Genre</h3>
                    <button type="button" id="closeGenreModalX" class="btn-icon -mr-2"><i class="fa-solid fa-xmark text-xl"></i></button>
                </div>
                <div class="p-6 max-h-[60vh] overflow-y-auto">
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-2.5">
                        @foreach($genres as $genre)
                            @php
                                $selectedGenres = request('genre', []);
                                $isChecked = is_array($selectedGenres) && in_array($genre->id, array_map('intval', $selectedGenres));
                            @endphp
                            <label class="flex items-center gap-3 p-3 rounded-xl border cursor-pointer transition-colors genre-checkbox-label @if($isChecked) bg-[#ff2e4d]/10 border-[#ff2e4d]/50 @else border-slate-200 dark:border-white/10 hover:bg-slate-50 dark:hover:bg-white/5 @endif">
                                <input type="checkbox" name="genre[]" value="{{ $genre->id }}" @if($isChecked) checked @endif
                                       class="genre-checkbox h-4 w-4 rounded accent-[#ff2e4d]">
                                <span class="text-sm font-medium text-slate-700 dark:text-slate-200">{{ $genre->name }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
                <div class="px-6 py-4 bg-slate-50 dark:bg-white/5 border-t border-slate-200 dark:border-white/10 flex justify-end items-center gap-3 rounded-b-2xl">
                    <button type="button" id="cancelGenreBtn" class="btn-ghost">Batal</button>
                    <button type="button" id="applyGenreBtn" class="btn-primary"><i class="fa-solid fa-check mr-1.5"></i>Terapkan</button>
                </div>
            </div>
        </div>
    </form>

    @if($mangas->count() > 0)
        <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-5 lg:grid-cols-6 xl:grid-cols-8 gap-x-4 gap-y-7">
            @foreach($mangas as $manga)
                @include('partials.manga-card', ['manga' => $manga])
            @endforeach
        </div>

        <div class="mt-10">
            @if ($mangas->hasPages())
                <nav class="flex items-center justify-center gap-2" aria-label="Navigasi halaman">
                    @if ($mangas->onFirstPage())
                        <span class="inline-flex items-center px-4 py-2 text-sm font-medium text-slate-400 bg-slate-100 dark:bg-white/5 rounded-xl cursor-not-allowed">
                            <i class="fa-solid fa-chevron-left mr-1.5 text-xs"></i>Sebelumnya
                        </span>
                    @else
                        <a href="{{ $mangas->previousPageUrl() }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-slate-700 dark:text-slate-200 bg-white dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-xl hover:bg-slate-50 dark:hover:bg-white/10 transition">
                            <i class="fa-solid fa-chevron-left mr-1.5 text-xs"></i>Sebelumnya
                        </a>
                    @endif

                    <span class="px-4 py-2 text-sm text-slate-500 dark:text-slate-400">
                        Halaman <span class="font-semibold text-slate-800 dark:text-white">{{ $mangas->currentPage() }}</span> / {{ $mangas->lastPage() }}
                    </span>

                    @if ($mangas->hasMorePages())
                        <a href="{{ $mangas->nextPageUrl() }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-slate-700 dark:text-slate-200 bg-white dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-xl hover:bg-slate-50 dark:hover:bg-white/10 transition">
                            Selanjutnya<i class="fa-solid fa-chevron-right ml-1.5 text-xs"></i>
                        </a>
                    @else
                        <span class="inline-flex items-center px-4 py-2 text-sm font-medium text-slate-400 bg-slate-100 dark:bg-white/5 rounded-xl cursor-not-allowed">
                            Selanjutnya<i class="fa-solid fa-chevron-right ml-1.5 text-xs"></i>
                        </span>
                    @endif
                </nav>
            @endif
        </div>
    @else
        <div class="text-center py-20 rounded-2xl bg-white dark:bg-[#0d1220] border border-dashed border-slate-300 dark:border-white/10">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-slate-100 dark:bg-white/5 text-slate-400 dark:text-slate-500 mb-4">
                <i class="fa-solid fa-magnifying-glass text-2xl"></i>
            </div>
            <h3 class="text-xl font-display font-semibold mb-2 text-slate-900 dark:text-white">Tidak Ada Hasil</h3>
            <p class="text-slate-500 dark:text-slate-400">Coba ubah filter atau kata kunci pencarianmu.</p>
        </div>
    @endif
</div>

<script src="{{ asset('js/manga/list.js') }}"></script>
@endsection
