@extends('layouts.admin')

@section('title', 'Tambah Chapter — Admin NeoManga')
@section('page-title', 'Chapter')

@section('content')
<link rel="stylesheet" href="{{ asset('css/admin/chapter/create.css') }}">

<div>
    <div class="flex flex-wrap items-end justify-between gap-4">
        <div class="min-w-0">
            <a href="{{ route('admin.manga.chapters.index', $manga) }}" class="inline-flex items-center gap-2 text-xs font-semibold text-slate-500 hover:text-brand transition-colors">
                <i class="fa-solid fa-arrow-left"></i>Kembali ke Daftar Chapter
            </a>
            <div class="mt-3 flex items-center gap-2">
                <span class="fm-eyebrow"><i class="fa-solid fa-plus mr-1.5"></i>Katalog</span>
                <span class="text-slate-600 text-xs">•</span>
                <span class="text-[11px] font-semibold tracking-wide text-brand/90 uppercase">Tambah Data</span>
            </div>
            <h1 class="font-display text-[26px] lg:text-3xl font-bold text-white tracking-tight mt-1.5">Tambah Chapter Baru</h1>
            <p class="text-sm text-slate-400 mt-1 truncate">{{ $manga->title }}</p>
        </div>
    </div>

    @if (session('success'))
        <div class="mt-6 flex items-center gap-3 px-5 py-4 rounded-2xl border text-sm" style="background:rgba(52,211,153,.08);border-color:rgba(52,211,153,.25);color:#6ee7b7">
            <i class="fa-solid fa-circle-check"></i><span>{{ session('success') }}</span>
        </div>
    @endif

    <form action="{{ route('admin.manga.chapters.store', $manga) }}" method="POST" enctype="multipart/form-data" class="mt-6 space-y-6">
        @csrf

        {{-- Section 1: Info chapter --}}
        <div class="fm-card">
            <div class="fm-sec">
                <div class="fm-sec-title"><span class="n">1</span><i class="fa-solid fa-hashtag text-slate-500 text-sm"></i>Info Chapter</div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label for="number" class="fm-label">Nomor Chapter <span class="req">*</span></label>
                        <input type="number" step="0.1" name="number" id="number" value="{{ old('number') }}" placeholder="cth: 1 atau 12.5" class="fm-input" required>
                        @error('number') <p class="fm-error"><i class="fa-solid fa-circle-exclamation"></i>{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="status" class="fm-label">Status <span class="req">*</span></label>
                        <select name="status" id="status" class="fm-select">
                            <option value="draft" @selected(old('status') == 'draft')>📝 Draft</option>
                            <option value="published" @selected(old('status') == 'published')>✅ Published</option>
                        </select>
                        @error('status') <p class="fm-error"><i class="fa-solid fa-circle-exclamation"></i>{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- Section 2: Gambar --}}
        <div class="fm-card">
            <div class="fm-sec">
                <div class="fm-sec-title"><span class="n">2</span><i class="fa-solid fa-images text-slate-500 text-sm"></i>Gambar Chapter</div>
                <div x-data="{ files: [] }">
                    <label for="chapter_images" class="fm-upload">
                        <template x-if="!files.length">
                            <div class="flex flex-col items-center text-center px-4">
                                <div class="w-12 h-12 rounded-2xl flex items-center justify-center mb-3" style="background:rgba(255,46,77,.12)">
                                    <i class="fa-solid fa-cloud-arrow-up text-lg" style="color:#ff2e4d"></i>
                                </div>
                                <p class="text-sm text-slate-300"><span class="font-semibold text-white">Klik untuk pilih</span> atau seret ke sini</p>
                                <p class="text-xs text-slate-500 mt-1">Bisa pilih banyak — PNG, JPG, WEBP</p>
                            </div>
                        </template>
                        <template x-if="files.length">
                            <div class="flex flex-col items-center px-4 py-3">
                                <p class="text-sm font-semibold text-white" x-text="files.length + ' file dipilih ✓'"></p>
                                <p class="text-xs text-emerald-400 mt-1">Siap diupload</p>
                            </div>
                        </template>
                        <input type="file" name="chapter_images[]" id="chapter_images" class="sr-only" multiple required
                               @change="files = Array.from($event.target.files).map(f => f.name)">
                    </label>
                    <div class="fm-files" x-show="files.length">
                        <template x-for="f in files" :key="f">
                            <span class="fm-file"><i class="fa-regular fa-image text-[10px]"></i><span x-text="f"></span></span>
                        </template>
                    </div>
                    @error('chapter_images') <p class="fm-error"><i class="fa-solid fa-circle-exclamation"></i>{{ $message }}</p> @enderror
                    @error('chapter_images.*') <p class="fm-error"><i class="fa-solid fa-circle-exclamation"></i>{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        {{-- Actions --}}
        <div class="flex flex-col sm:flex-row justify-end items-stretch sm:items-center gap-3 pt-2">
            <a href="{{ route('admin.manga.chapters.index', $manga) }}" class="fm-btn fm-btn-ghost">Batal</a>
            <button type="submit" class="fm-btn fm-btn-primary">
                <i class="fa-solid fa-floppy-disk text-xs"></i>Simpan Chapter
            </button>
        </div>
    </form>
</div>
@endsection
