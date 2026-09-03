@extends('layouts.admin')

@section('title', 'Kategori — Admin NeoManga')
@section('page-title', 'Kategori')

@section('content')
<link rel="stylesheet" href="{{ asset('css/admin/category/index.css') }}">

<div>
    {{-- HEADER --}}
    <div class="flex flex-wrap items-end justify-between gap-4">
        <div>
            <div class="flex items-center gap-2">
                <span class="cat-eyebrow"><i class="fa-solid fa-tags mr-1.5"></i>Katalog</span>
                <span class="text-slate-600 text-xs">•</span>
                <span class="text-[11px] font-semibold tracking-wide text-brand/90 uppercase">Kelola Data</span>
            </div>
            <h1 class="font-display text-[26px] lg:text-3xl font-bold text-white tracking-tight mt-1.5">Kategori</h1>
            <p class="text-sm text-slate-400 mt-1">Kelola genre &amp; kategori untuk mengelompokkan manga.</p>
        </div>
        <button class="cat-btn cat-btn-primary" onclick="openModal('add')">
            <i class="fa-solid fa-plus text-xs"></i>Tambah Kategori
        </button>
    </div>

    {{-- STATS --}}
    <div class="mt-6 grid grid-cols-2 lg:grid-cols-4 gap-3.5">
        <div class="cat-stat">
            <span class="ic" style="background:rgba(255,46,77,.13);color:#ff2e4d"><i class="fa-solid fa-tags"></i></span>
            <div><p class="lbl">Total Kategori</p><p class="val">{{ number_format($genres->total()) }}</p></div>
        </div>
        <div class="cat-stat">
            <span class="ic" style="background:rgba(56,189,248,.13);color:#38bdf8"><i class="fa-solid fa-book-open"></i></span>
            <div><p class="lbl">Manga</p><p class="val">{{ number_format(\App\Models\Manga::count()) }}</p></div>
        </div>
        <div class="cat-stat">
            <span class="ic" style="background:rgba(52,211,153,.13);color:#34d399"><i class="fa-solid fa-link"></i></span>
            <div><p class="lbl">Rata2 per Manga</p><p class="val">{{ number_format(\App\Models\Manga::withCount('genres')->get()->avg('genres_count') ?? 0, 1) }}</p></div>
        </div>
        <div class="cat-stat">
            <span class="ic" style="background:rgba(167,139,250,.13);color:#a78bfa"><i class="fa-solid fa-magnifying-glass"></i></span>
            <div><p class="lbl">Halaman Ini</p><p class="val">{{ number_format($genres->count()) }}</p></div>
        </div>
    </div>

    {{-- ALERT --}}
    @if (session('success'))
        <div class="mt-5 flex items-center gap-3 px-5 py-4 rounded-2xl border text-sm" style="background:rgba(52,211,153,.08);border-color:rgba(52,211,153,.25);color:#6ee7b7">
            <i class="fa-solid fa-circle-check"></i><span>{{ session('success') }}</span>
        </div>
    @endif

    {{-- TABLE --}}
    <div class="cat-card mt-6">
        <div class="cat-toolbar px-5 py-4" style="border-bottom:1px solid rgba(255,255,255,.05)">
            <div class="flex items-center gap-2.5">
                <h2 class="font-display text-[15px] font-semibold text-white flex items-center gap-2">
                    <i class="fa-solid fa-layer-group text-brand"></i>Daftar Kategori
                </h2>
                <span class="cat-pill" style="background:rgba(255,46,77,.12);color:#ff8a9c">{{ $genres->total() }} total</span>
            </div>
            <form action="{{ route('admin.category.index') }}" method="GET" class="cat-search">
                <i class="fa-solid fa-magnifying-glass text-slate-500 text-xs"></i>
                <input type="text" name="search" placeholder="Cari kategori..." value="{{ request('search') }}">
                @if(request('search'))
                    <a href="{{ route('admin.category.index') }}" class="text-slate-500 hover:text-white"><i class="fa-solid fa-xmark text-xs"></i></a>
                @endif
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full" style="border-collapse:collapse;font-size:13px">
                <thead>
                    <tr>
                        <th class="cat-th" style="width:50px">No</th>
                        <th class="cat-th">Nama Kategori</th>
                        <th class="cat-th" style="width:70px">Slug</th>
                        <th class="cat-th text-center" style="width:90px">Jumlah Manga</th>
                        <th class="cat-th text-right" style="width:100px">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($genres as $genre)
                        <tr class="cat-tr">
                            <td class="cat-td text-slate-600 font-mono text-xs">{{ $genres->firstItem() + $loop->index }}</td>
                            <td class="cat-td">
                                <div class="flex items-center gap-3">
                                    <span class="cat-ico" style="background:rgba(255,46,77,.1);color:#ff2e4d"><i class="fa-solid fa-hashtag"></i></span>
                                    <div>
                                        <p class="font-semibold text-white">{{ $genre->name }}</p>
                                        <p class="text-[11px] text-slate-500">Dibuat {{ $genre->created_at?->format('d M Y') }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="cat-td"><span class="cat-slug cat-pill" style="background:rgba(255,255,255,.05);color:#64748b;font-family:ui-monospace,monospace">/{{ strtolower(str_replace(' ', '-', $genre->name)) }}</span></td>
                            <td class="cat-td text-center">
                                <span class="cat-mid" style="background:rgba(56,189,248,.1);color:#38bdf8">
                                    <i class="fa-solid fa-book-open text-[9px]"></i>{{ $genre->mangas_count }}
                                </span>
                            </td>
                            <td class="cat-td text-right whitespace-nowrap">
                                <button class="cat-ico-btn edit" title="Edit" onclick='openModal("edit", {{ $genre->id }}, {{ json_encode($genre->name) }})'>
                                    <i class="fa-solid fa-pen"></i>
                                </button>
                                <form action="{{ route('admin.category.destroy', $genre) }}" method="POST" class="inline"
                                      onsubmit="return confirm('Hapus kategori &quot;{{ $genre->name }}&quot;? Manga tidak ikut terhapus, hanya lepas dari kategori ini.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="cat-ico-btn danger" title="Hapus"><i class="fa-solid fa-trash-can"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">
                                <div class="cat-empty">
                                    <i class="fa-solid fa-tags ic"></i>
                                    <p class="mt-3 font-semibold text-white text-sm">{{ request('search') ? 'Tidak ada hasil untuk "' . request('search') . '"' : 'Belum ada kategori.' }}</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- PAGINATION --}}
    @if($genres->hasPages())
        <div class="mt-5 flex items-center justify-between flex-wrap gap-3">
            <p class="text-xs" style="color:#64748b">
                Menampilkan <span class="text-slate-300 font-semibold">{{ $genres->firstItem() }}</span>–
                <span class="text-slate-300 font-semibold">{{ $genres->lastItem() }}</span> dari
                <span class="text-slate-300 font-semibold">{{ $genres->total() }}</span>
            </p>
            <div class="flex gap-1.5">
                {{ $genres->appends(request()->query())->links('pagination::tailwind') }}
            </div>
        </div>
    @endif
</div>

{{-- MODAL --}}
<div class="cat-modal-bg" id="cat-modal">
    <div class="cat-modal">
        <div class="flex items-center justify-between mb-5">
            <h3 class="font-display text-lg font-bold text-white" id="cat-modal-title">Tambah Kategori</h3>
            <button class="cat-ico-btn" onclick="closeModal()"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <form method="POST" id="cat-form" action="{{ route('admin.category.store') }}" data-default-action="{{ route('admin.category.store') }}">
            @csrf
            <div id="cat-method-put"></div>
            <label for="cat-name" class="block text-xs font-semibold text-slate-400 mb-1.5">Nama Kategori</label>
            <input type="text" name="name" id="cat-name" class="cat-input" placeholder="cth: Action, Romance, Fantasy..." maxlength="60" required autofocus>
            <p class="text-[11px] text-slate-500 mt-2"><i class="fa-solid fa-info-circle mr-1"></i>Maksimal 60 karakter, nama tidak boleh sama dengan kategori lain.</p>
            <div class="flex justify-end gap-2.5 mt-6">
                <button type="button" class="cat-btn cat-btn-ghost" onclick="closeModal()">Batal</button>
                <button type="submit" class="cat-btn cat-btn-primary"><i class="fa-solid fa-floppy-disk text-xs"></i>Simpan</button>
            </div>
        </form>
    </div>
    <input type="hidden" id="cat-edit-id" value="{{ session('edit_id') ?? '' }}">
    <input type="hidden" id="cat-edit-name" value="{{ session('edit_name') ?? '' }}">
</div>

@push('scripts')
<script src="{{ asset('js/admin/category/index.js') }}"></script>
@endpush
@endsection
