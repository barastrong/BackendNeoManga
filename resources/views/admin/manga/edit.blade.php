@extends('layouts.admin')

@section('title', 'Edit Manga — Admin NeoManga')
@section('page-title', 'Manga')

@section('content')
<link rel="stylesheet" href="{{ asset('css/admin/manga/edit.css') }}">

@php
    $statusMeta = [
        'ongoing'   => ['Ongoing', '#34d399', 'rgba(52,211,153,.12)'],
        'completed' => ['Completed', '#38bdf8', 'rgba(56,189,248,.12)'],
        'hiatus'    => ['Hiatus', '#fbbf24', 'rgba(251,191,36,.12)'],
        'cancelled' => ['Cancelled', '#fb7185', 'rgba(244,63,94,.12)'],
    ];
    $curSt = $statusMeta[$manga->status] ?? ['—', '#94a3b8', 'rgba(148,163,184,.1)'];
@endphp

<div>
    <div class="flex flex-wrap items-end justify-between gap-4">
        <div class="min-w-0">
            <div class="flex items-center gap-2">
                <span class="fm-eyebrow"><i class="fa-solid fa-pen-to-square mr-1.5"></i>Katalog</span>
                <span class="text-slate-600 text-xs">•</span>
                <span class="text-[11px] font-semibold tracking-wide text-brand/90 uppercase">Edit Data</span>
            </div>
            <h1 class="font-display text-[26px] lg:text-3xl font-bold text-white tracking-tight mt-1.5 truncate">Edit Manga</h1>
            <p class="text-sm text-slate-400 mt-1 truncate">{{ $manga->title }}</p>
        </div>
        <div class="flex items-center gap-3">
            <span class="fm-status-now" style="background:{{ $curSt[2] }};color:{{ $curSt[1] }}">
                <span style="width:7px;height:7px;border-radius:99px;background:{{ $curSt[1] }};display:inline-block"></span>{{ $curSt[0] }}
            </span>
            <a href="{{ route('admin.manga.index') }}" class="fm-btn fm-btn-ghost">
                <i class="fa-solid fa-arrow-left text-xs"></i>Kembali
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="mt-6 flex items-center gap-3 px-5 py-4 rounded-2xl border text-sm" style="background:rgba(52,211,153,.08);border-color:rgba(52,211,153,.25);color:#6ee7b7">
            <i class="fa-solid fa-circle-check"></i><span>{{ session('success') }}</span>
        </div>
    @endif

    <form action="{{ route('admin.manga.update', $manga) }}" method="POST" enctype="multipart/form-data" class="mt-6 space-y-6">
        @csrf
        @method('PUT')

        {{-- Section 1: Identitas --}}
        <div class="fm-card">
            <div class="fm-sec">
                <div class="fm-sec-title"><span class="n">1</span><i class="fa-solid fa-heading text-slate-500 text-sm"></i>Identitas Manga</div>
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
                    <div>
                        <label for="title" class="fm-label">Judul <span class="req">*</span></label>
                        <input type="text" name="title" id="title" value="{{ old('title', $manga->title) }}" class="fm-input" required>
                        @error('title') <p class="fm-error"><i class="fa-solid fa-circle-exclamation"></i>{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="alternative_title" class="fm-label">Judul Alternatif <span class="text-slate-600 font-normal">(opsional)</span></label>
                        <input type="text" name="alternative_title" id="alternative_title" value="{{ old('alternative_title', $manga->alternative_title) }}" class="fm-input">
                        @error('alternative_title') <p class="fm-error"><i class="fa-solid fa-circle-exclamation"></i>{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="author" class="fm-label">Author <span class="req">*</span></label>
                        <input type="text" name="author" id="author" value="{{ old('author', $manga->author) }}" class="fm-input" required>
                        @error('author') <p class="fm-error"><i class="fa-solid fa-circle-exclamation"></i>{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="artist" class="fm-label">Artist <span class="text-slate-600 font-normal">(opsional)</span></label>
                        <input type="text" name="artist" id="artist" value="{{ old('artist', $manga->artist) }}" class="fm-input">
                        @error('artist') <p class="fm-error"><i class="fa-solid fa-circle-exclamation"></i>{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- Section 2: Klasifikasi --}}
        <div class="fm-card">
            <div class="fm-sec">
                <div class="fm-sec-title"><span class="n">2</span><i class="fa-solid fa-tags text-slate-500 text-sm"></i>Klasifikasi &amp; Genre</div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label for="status" class="fm-label">Status Rilis <span class="req">*</span></label>
                        <select name="status" id="status" class="fm-select">
                            <option value="ongoing" @selected(old('status', $manga->status) == 'ongoing')>🟢 Ongoing</option>
                            <option value="completed" @selected(old('status', $manga->status) == 'completed')>🔵 Completed</option>
                            <option value="hiatus" @selected(old('status', $manga->status) == 'hiatus')>🟡 Hiatus</option>
                            <option value="cancelled" @selected(old('status', $manga->status) == 'cancelled')>🔴 Cancelled</option>
                        </select>
                        @error('status') <p class="fm-error"><i class="fa-solid fa-circle-exclamation"></i>{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="type" class="fm-label">Tipe <span class="req">*</span></label>
                        <select name="type" id="type" class="fm-select">
                            <option value="manga" @selected(old('type', $manga->type) == 'manga')>📘 Manga (Jepang)</option>
                            <option value="manhwa" @selected(old('type', $manga->type) == 'manhwa')>📗 Manhwa (Korea)</option>
                            <option value="manhua" @selected(old('type', $manga->type) == 'manhua')>📕 Manhua (China)</option>
                            <option value="webtoon" @selected(old('type', $manga->type) == 'webtoon')>📱 Webtoon</option>
                        </select>
                        @error('type') <p class="fm-error"><i class="fa-solid fa-circle-exclamation"></i>{{ $message }}</p> @enderror
                    </div>
                </div>
                <div class="mt-5">
                    <label class="fm-label">Genres <span class="req">*</span></label>
                    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-2.5 max-h-64 overflow-y-auto p-3 rounded-xl" style="background:rgba(0,0,0,.2);border:1px solid rgba(255,255,255,.05)">
                        @forelse($genres as $genre)
                            <label class="fm-opt">
                                <input type="checkbox" name="genres[]" id="genre-{{ $genre->id }}" value="{{ $genre->id }}"
                                       @checked(in_array($genre->id, old('genres', $mangaGenres)))>
                                <label for="genre-{{ $genre->id }}" class="!m-0">{{ $genre->name }}</label>
                            </label>
                        @empty
                            <p class="text-sm text-slate-500 col-span-full">Belum ada genre.</p>
                        @endforelse
                    </div>
                    @error('genres') <p class="fm-error"><i class="fa-solid fa-circle-exclamation"></i>{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        {{-- Section 3: Deskripsi --}}
        <div class="fm-card">
            <div class="fm-sec">
                <div class="fm-sec-title"><span class="n">3</span><i class="fa-solid fa-align-left text-slate-500 text-sm"></i>Sinopsis</div>
                <label for="description" class="fm-label">Deskripsi <span class="req">*</span></label>
                <textarea name="description" id="description" rows="6" class="fm-textarea">{{ old('description', $manga->description) }}</textarea>
                @error('description') <p class="fm-error"><i class="fa-solid fa-circle-exclamation"></i>{{ $message }}</p> @enderror
            </div>
        </div>

        {{-- Section 4: Cover --}}
        <div class="fm-card">
            <div class="fm-sec">
                <div class="fm-sec-title"><span class="n">4</span><i class="fa-solid fa-image text-slate-500 text-sm"></i>Cover</div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-start">
                    <div x-data="{ fileName: '' }">
                        <label class="fm-label">Ganti Cover <span class="text-slate-600 font-normal">(opsional)</span></label>
                        <label for="cover_image" class="fm-upload">
                            <template x-if="!fileName">
                                <div class="flex flex-col items-center text-center px-4">
                                    <div class="w-11 h-11 rounded-2xl flex items-center justify-center mb-2.5" style="background:rgba(255,46,77,.12)">
                                        <i class="fa-solid fa-cloud-arrow-up text-lg" style="color:#ff2e4d"></i>
                                    </div>
                                    <p class="text-sm text-slate-300"><span class="font-semibold text-white">Klik untuk ganti</span></p>
                                    <p class="text-xs text-slate-500 mt-1">PNG, JPG, WEBP — maks 2MB</p>
                                </div>
                            </template>
                            <template x-if="fileName">
                                <div class="flex items-center gap-3 px-4">
                                    <i class="fa-solid fa-file-image text-xl" style="color:#34d399"></i>
                                    <div class="text-left">
                                        <p class="text-sm font-semibold text-white" x-text="fileName"></p>
                                        <p class="text-xs text-emerald-400">Siap diganti ✓</p>
                                    </div>
                                </div>
                            </template>
                            <input type="file" name="cover_image" id="cover_image" class="sr-only"
                                   @change="fileName = $event.target.files[0] ? $event.target.files[0].name : ''">
                        </label>
                        @error('cover_image') <p class="fm-error"><i class="fa-solid fa-circle-exclamation"></i>{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="fm-label">Cover Saat Ini</label>
                        @if($manga->cover_url)
                            <img src="{{ $manga->cover_url }}" alt="{{ $manga->title }}" class="fm-cover h-44 w-auto max-w-full object-cover">
                        @else
                            <div class="flex items-center justify-center h-44 rounded-2xl" style="background:rgba(255,255,255,.03);border:1px dashed rgba(255,255,255,.12)">
                                <span class="text-xs text-slate-500">Belum ada cover</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Actions --}}
        <div class="flex flex-col sm:flex-row justify-end items-stretch sm:items-center gap-3 pt-2">
            <a href="{{ route('admin.manga.index') }}" class="fm-btn fm-btn-ghost">Batal</a>
            <button type="submit" class="fm-btn fm-btn-primary">
                <i class="fa-solid fa-floppy-disk text-xs"></i>Update Manga
            </button>
        </div>
    </form>
</div>
@endsection
