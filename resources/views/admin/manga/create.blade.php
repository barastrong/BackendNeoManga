@extends('layouts.admin')

@section('title', 'Tambah Manga — Admin NeoManga')
@section('page-title', 'Manga')

@section('content')
<link rel="stylesheet" href="{{ asset('css/admin/manga/create.css') }}">

<div>
    <div class="flex flex-wrap items-end justify-between gap-4">
        <div>
            <div class="flex items-center gap-2">
                <span class="fm-eyebrow"><i class="fa-solid fa-plus mr-1.5"></i>Katalog</span>
                <span class="text-slate-600 text-xs">•</span>
                <span class="text-[11px] font-semibold tracking-wide text-brand/90 uppercase">Tambah Data</span>
            </div>
            <h1 class="font-display text-[26px] lg:text-3xl font-bold text-white tracking-tight mt-1.5">Tambah Manga Baru</h1>
            <p class="text-sm text-slate-400 mt-1">Lengkapi informasi manga di bawah. Field bertanda <span class="text-red-400">*</span> wajib diisi.</p>
        </div>
        <a href="{{ route('admin.manga.index') }}" class="fm-btn fm-btn-ghost">
            <i class="fa-solid fa-arrow-left text-xs"></i>Kembali ke Koleksi
        </a>
    </div>

    @if (session('success'))
        <div class="mt-6 flex items-center gap-3 px-5 py-4 rounded-2xl border text-sm" style="background:rgba(52,211,153,.08);border-color:rgba(52,211,153,.25);color:#6ee7b7">
            <i class="fa-solid fa-circle-check"></i><span>{{ session('success') }}</span>
        </div>
    @endif

    <form action="{{ route('admin.manga.store') }}" method="POST" enctype="multipart/form-data" class="mt-6 space-y-6">
        @csrf

        {{-- Section 1: Identitas --}}
        <div class="fm-card">
            <div class="fm-sec">
                <div class="fm-sec-title"><span class="n">1</span><i class="fa-solid fa-heading text-slate-500 text-sm"></i>Identitas Manga</div>
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
                    <div>
                        <label for="title" class="fm-label">Judul <span class="req">*</span></label>
                        <input type="text" name="title" id="title" value="{{ old('title') }}" placeholder="Contoh: One Piece" class="fm-input" required>
                        @error('title') <p class="fm-error"><i class="fa-solid fa-circle-exclamation"></i>{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="alternative_title" class="fm-label">Judul Alternatif <span class="text-slate-600 font-normal">(opsional)</span></label>
                        <input type="text" name="alternative_title" id="alternative_title" value="{{ old('alternative_title') }}" placeholder="Contoh: ワンピース" class="fm-input">
                        @error('alternative_title') <p class="fm-error"><i class="fa-solid fa-circle-exclamation"></i>{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="author" class="fm-label">Author <span class="req">*</span></label>
                        <input type="text" name="author" id="author" value="{{ old('author') }}" placeholder="Contoh: Eiichiro Oda" class="fm-input" required>
                        @error('author') <p class="fm-error"><i class="fa-solid fa-circle-exclamation"></i>{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="artist" class="fm-label">Artist <span class="text-slate-600 font-normal">(opsional)</span></label>
                        <input type="text" name="artist" id="artist" value="{{ old('artist') }}" placeholder="Jika sama, isi sama seperti author" class="fm-input">
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
                            <option value="ongoing" @selected(old('status', 'ongoing') == 'ongoing')>🟢 Ongoing</option>
                            <option value="completed" @selected(old('status') == 'completed')>🔵 Completed</option>
                            <option value="hiatus" @selected(old('status') == 'hiatus')>🟡 Hiatus</option>
                            <option value="cancelled" @selected(old('status') == 'cancelled')>🔴 Cancelled</option>
                        </select>
                        @error('status') <p class="fm-error"><i class="fa-solid fa-circle-exclamation"></i>{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="type" class="fm-label">Tipe <span class="req">*</span></label>
                        <select name="type" id="type" class="fm-select">
                            <option value="manga" @selected(old('type', 'manga') == 'manga')>📘 Manga (Jepang)</option>
                            <option value="manhwa" @selected(old('type') == 'manhwa')>📗 Manhwa (Korea)</option>
                            <option value="manhua" @selected(old('type') == 'manhua')>📕 Manhua (China)</option>
                            <option value="webtoon" @selected(old('type') == 'webtoon')>📱 Webtoon</option>
                        </select>
                        @error('type') <p class="fm-error"><i class="fa-solid fa-circle-exclamation"></i>{{ $message }}</p> @enderror
                    </div>
                </div>
                <div class="mt-5">
                    <label class="fm-label">Genres <span class="req">*</span> <span class="text-slate-600 font-normal">(pilih minimal 1)</span></label>
                    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-2.5 max-h-64 overflow-y-auto p-3 rounded-xl" style="background:rgba(0,0,0,.2);border:1px solid rgba(255,255,255,.05)">
                        @forelse($genres as $genre)
                            <label class="fm-opt">
                                <input type="checkbox" name="genres[]" id="genre-{{ $genre->id }}" value="{{ $genre->id }}"
                                       @if(is_array(old('genres')) && in_array($genre->id, old('genres'))) checked @endif>
                                <label for="genre-{{ $genre->id }}" class="!m-0">{{ $genre->name }}</label>
                            </label>
                        @empty
                            <p class="text-sm text-slate-500 col-span-full">Belum ada genre. Tambahkan lewat menu Genre.</p>
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
                <textarea name="description" id="description" rows="6" placeholder="Ceritakan tentang manga ini..." class="fm-textarea">{{ old('description') }}</textarea>
                @error('description') <p class="fm-error"><i class="fa-solid fa-circle-exclamation"></i>{{ $message }}</p> @enderror
            </div>
        </div>

        {{-- Section 4: Cover --}}
        <div class="fm-card">
            <div class="fm-sec">
                <div class="fm-sec-title"><span class="n">4</span><i class="fa-solid fa-image text-slate-500 text-sm"></i>Cover</div>
                <div x-data="{ fileName: '', dragging: false }">
                    <label for="cover_image" class="fm-upload" :class="dragging ? 'drag' : ''"
                           @dragover.prevent="dragging = true" @dragleave.prevent="dragging = false" @drop.prevent="dragging = false">
                        <template x-if="!fileName">
                            <div class="flex flex-col items-center text-center px-4">
                                <div class="w-12 h-12 rounded-2xl flex items-center justify-center mb-3" style="background:rgba(255,46,77,.12)">
                                    <i class="fa-solid fa-cloud-arrow-up text-lg" style="color:#ff2e4d"></i>
                                </div>
                                <p class="text-sm text-slate-300"><span class="font-semibold text-white">Klik untuk upload</span> atau seret ke sini</p>
                                <p class="text-xs text-slate-500 mt-1">PNG, JPG, WEBP — maks 2MB</p>
                            </div>
                        </template>
                        <template x-if="fileName">
                            <div class="flex items-center gap-3 px-4">
                                <i class="fa-solid fa-file-image text-xl" style="color:#34d399"></i>
                                <div class="text-left">
                                    <p class="text-sm font-semibold text-white" x-text="fileName"></p>
                                    <p class="text-xs text-emerald-400">Siap diupload ✓</p>
                                </div>
                            </div>
                        </template>
                        <input type="file" name="cover_image" id="cover_image" class="sr-only" required
                               @change="fileName = $event.target.files[0] ? $event.target.files[0].name : ''">
                    </label>
                    @error('cover_image') <p class="fm-error"><i class="fa-solid fa-circle-exclamation"></i>{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        {{-- Actions --}}
        <div class="flex flex-col sm:flex-row justify-end items-stretch sm:items-center gap-3 pt-2">
            <a href="{{ route('admin.manga.index') }}" class="fm-btn fm-btn-ghost">Batal</a>
            <button type="submit" name="action" value="create_again" class="fm-btn fm-btn-outline">
                <i class="fa-solid fa-plus text-xs"></i>Simpan &amp; Buat Lagi
            </button>
            <button type="submit" name="action" value="save" class="fm-btn fm-btn-primary">
                <i class="fa-solid fa-floppy-disk text-xs"></i>Simpan Manga
            </button>
        </div>
    </form>
</div>
@endsection
