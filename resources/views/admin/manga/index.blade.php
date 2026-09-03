@extends('layouts.admin')

@section('title', 'Koleksi Manga — Admin NeoManga')
@section('page-title', 'Manga')

@section('content')
<link rel="stylesheet" href="{{ asset('css/admin/manga/index.css') }}">

{{-- HEADER --}}
<div class="flex flex-wrap items-end justify-between gap-4">
    <div>
        <div class="flex items-center gap-2">
            <span class="mg-eyebrow"><i class="fa-solid fa-book-open mr-1.5"></i>Katalog</span>
            <span class="text-slate-600 text-xs">•</span>
            <span class="text-[11px] font-semibold tracking-wide text-brand/90 uppercase">Manga &amp; Komik</span>
        </div>
        <h1 class="font-display text-[26px] lg:text-3xl font-bold text-white tracking-tight mt-1.5">Koleksi Manga</h1>
        <p class="text-sm text-slate-400 mt-1">Jelajahi, kelola, dan perbarui seluruh koleksi manga.</p>
    </div>
    <div class="flex flex-wrap items-center gap-3">
        <form action="{{ route('admin.manga.index') }}" method="GET" class="mg-search">
            <i class="fa-solid fa-magnifying-glass text-slate-500 text-xs"></i>
            <input type="text" name="search" placeholder="Cari judul, author, artist..." value="{{ request('search') }}">
            @if(request('search'))
                <a href="{{ route('admin.manga.index') }}" class="text-slate-500 hover:text-white transition-colors" title="Reset"><i class="fa-solid fa-xmark text-xs"></i></a>
            @endif
        </form>
        <a href="{{ route('admin.manga.create') }}" class="mg-btn mg-btn-primary">
            <i class="fa-solid fa-plus text-xs"></i>Tambah Manga
        </a>
    </div>
</div>

{{-- STATS --}}
@php
    $stManga = $stats['manga'] ?? $mangas->total();
    $stChapter = $stats['chapters'] ?? 0;
    $stOngoing = $stats['ongoing'] ?? 0;
    $stDone = $stats['completed'] ?? 0;
@endphp
<div class="mt-6 grid grid-cols-2 xl:grid-cols-4 gap-3.5">
    <div class="mg-stat">
        <span class="ic" style="background:rgba(255,46,77,.13);color:#ff2e4d"><i class="fa-solid fa-book-open"></i></span>
        <div><p class="lbl">Total Judul</p><p class="val">{{ number_format($stManga) }}</p></div>
    </div>
    <div class="mg-stat">
        <span class="ic" style="background:rgba(56,189,248,.13);color:#38bdf8"><i class="fa-solid fa-layer-group"></i></span>
        <div><p class="lbl">Total Chapter</p><p class="val">{{ number_format($stChapter) }}</p></div>
    </div>
    <div class="mg-stat">
        <span class="ic" style="background:rgba(52,211,153,.13);color:#34d399"><i class="fa-solid fa-play"></i></span>
        <div><p class="lbl">Ongoing</p><p class="val">{{ number_format($stOngoing) }}</p></div>
    </div>
    <div class="mg-stat">
        <span class="ic" style="background:rgba(167,139,250,.13);color:#a78bfa"><i class="fa-solid fa-check-double"></i></span>
        <div><p class="lbl">Completed</p><p class="val">{{ number_format($stDone) }}</p></div>
    </div>
</div>

{{-- ALERTS --}}
@if (session('success'))
    <div class="mg-alert mt-5" style="background:rgba(52,211,153,.08);border:1px solid rgba(52,211,153,.25);color:#6ee7b7">
        <i class="fa-solid fa-circle-check"></i><span>{{ session('success') }}</span>
    </div>
@endif
@if (request('search'))
    <div class="mg-alert mt-5" style="background:rgba(56,189,248,.07);border:1px solid rgba(56,189,248,.2);color:#7dd3fc">
        <i class="fa-solid fa-magnifying-glass"></i>
        <span>Menampilkan hasil untuk "<strong>{{ request('search') }}</strong>" — {{ $mangas->total() }} ditemukan.</span>
        <a href="{{ route('admin.manga.index') }}" class="ml-auto text-xs font-semibold hover:underline" style="color:#7dd3fc">Reset filter</a>
    </div>
@endif

{{-- GRID --}}
<div class="mt-6 mg-grid">
    @forelse ($mangas as $manga)
        @php
            $typeMeta = [
                'manga'   => ['MANGA',   '#e2e8f0'],
                'manhwa'  => ['MANHWA',  '#4DB6AC'],
                'manhua'  => ['MANHUA',  '#D32F2F'],
                'webtoon' => ['WEBTOON', '#00D564'],
            ];
            $tp = $typeMeta[$manga->type] ?? ['KOMIK', '#94a3b8'];
            $stMeta = [
                'ongoing'   => ['Ongoing',   '#34d399'],
                'completed' => ['Completed', '#38bdf8'],
                'hiatus'    => ['Hiatus',    '#fbbf24'],
                'cancelled' => ['Cancelled', '#fb7185'],
            ];
            $stt = $stMeta[$manga->status] ?? ['—', '#94a3b8'];
            $chCount = $manga->chapters_count ?? $manga->chapters()->count();
        @endphp

        <div class="mg-item">
            <div class="mg-coverwrap" x-data="{ open: false }">
                {{-- Badge tipe --}}
                <span class="mg-type" style="color:{{ $tp[1] }}">{{ $tp[0] }}</span>

                {{-- Aksi ⋯ --}}
                <div class="mg-cover-acts">
                    <button class="mg-ico" @click="open = !open" title="Aksi lain" style="color:{{ $tp[1] }}">
                        <i class="fa-solid fa-ellipsis-vertical"></i>
                    </button>
                </div>

                {{-- Cover --}}
                @if($manga->cover_url)
                    <a href="{{ route('admin.manga.edit', $manga) }}" title="{{ $manga->title }}">
                        <img class="mg-cover" src="{{ $manga->cover_url }}" alt="{{ $manga->title }}" loading="lazy">
                    </a>
                @else
                    <a href="{{ route('admin.manga.edit', $manga) }}" class="mg-coverph" title="{{ $manga->title }}">
                        <i class="fa-solid fa-book-open" style="font-size:26px"></i>
                        <span style="font-size:10px;max-width:90%;text-align:center;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">{{ $manga->title }}</span>
                    </a>
                @endif

                {{-- Overlay hover: edit + chapter --}}
                <div class="mg-overlay">
                    <a href="{{ route('admin.manga.edit', $manga) }}" class="mg-mini"><i class="fa-solid fa-pen text-[10px]"></i>Edit</a>
                    <a href="{{ route('admin.manga.chapters.index', $manga) }}" class="mg-mini ghost"><i class="fa-solid fa-layer-group text-[10px]"></i>{{ $chCount }}</a>
                </div>

                {{-- Dropdown ⋯ --}}
                <div x-show="open" @click.away="open = false" x-cloak class="mg-dropdown">
                    <a href="{{ route('admin.manga.edit', $manga) }}"><i class="fa-solid fa-pen-to-square mg-dd-ic"></i>Edit Manga</a>
                    <a href="{{ route('admin.manga.chapters.index', $manga) }}"><i class="fa-solid fa-layer-group mg-dd-ic"></i>Kelola Chapter</a>
                    <div class="sep"></div>
                    <form action="{{ route('admin.manga.destroy', $manga) }}" method="POST"
                          onsubmit="return confirm('Yakin hapus manga &quot;{{ $manga->title }}&quot;? Semua chapter ikut terhapus.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="danger"><i class="fa-solid fa-trash-can mg-dd-ic"></i>Hapus Manga</button>
                    </form>
                </div>
            </div>

            {{-- Info --}}
            <div class="mg-info">
                <a href="{{ route('admin.manga.edit', $manga) }}" class="mg-title">{{ $manga->title }}</a>
                @if($manga->author)
                    <p class="mg-author"><i class="fa-solid fa-user-pen mr-1 text-[9px]"></i>{{ $manga->author }}</p>
                @else
                    <p class="mg-author"><i class="fa-solid fa-user-pen mr-1 text-[9px]"></i>Tanpa Author</p>
                @endif
                <div class="mg-foot">
                    <span class="mg-st" style="color:{{ $stt[1] }}"><i style="background:{{ $stt[1] }}"></i>{{ $stt[0] }}</span>
                    <span class="mg-ch"><i class="fa-solid fa-list mr-1 text-[9px]"></i>{{ $chCount }} ch</span>
                </div>
            </div>
        </div>
    @empty
        <div class="mg-empty">
            <i class="fa-solid fa-book-open ic"></i>
            <h3 class="text-lg font-semibold text-white mt-3">
                {{ request('search') ? 'Tidak ada hasil untuk "' . request('search') . '"' : 'Koleksi Manga Kosong' }}
            </h3>
            <p class="text-sm text-slate-500 mt-1">
                {{ request('search') ? 'Coba kata kunci lain.' : 'Mulai dengan menambahkan manga pertama ke sistem.' }}
            </p>
            @unless(request('search'))
                <a href="{{ route('admin.manga.create') }}" class="mg-btn mg-btn-primary mt-5">
                    <i class="fa-solid fa-plus text-xs"></i>Tambah Manga Sekarang
                </a>
            @endunless
        </div>
    @endforelse
</div>

{{-- PAGINATION --}}
@if ($mangas->hasPages())
    <div class="mt-6 flex flex-col items-center justify-between gap-3 sm:flex-row" style="display:flex;flex-wrap:wrap;align-items:center;justify-content:space-between;gap:12px">
        <p class="text-xs" style="color:#64748b">
            Menampilkan <span style="color:#cbd5e1;font-weight:600">{{ $mangas->firstItem() }}</span>–
            <span style="color:#cbd5e1;font-weight:600">{{ $mangas->lastItem() }}</span> dari
            <span style="color:#cbd5e1;font-weight:600">{{ $mangas->total() }}</span> manga
        </p>
        <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap">
            @if ($mangas->onFirstPage())
                <span class="mg-pg dis"><i class="fa-solid fa-chevron-left text-[10px]"></i></span>
            @else
                <a href="{{ $mangas->appends(request()->query())->previousPageUrl() }}" class="mg-pg"><i class="fa-solid fa-chevron-left text-[10px]"></i></a>
            @endif

            @php
                $cur = $mangas->currentPage();
                $last = $mangas->lastPage();
                $start = max(1, $cur - 2);
                $end = min($last, $cur + 2);
            @endphp
            @for($i = $start; $i <= $end; $i++)
                @if($i == $cur)
                    <span class="mg-pg cur">{{ $i }}</span>
                @else
                    <a href="{{ $mangas->appends(request()->query())->url($i) }}" class="mg-pg">{{ $i }}</a>
                @endif
            @endfor

            @if ($mangas->hasMorePages())
                <a href="{{ $mangas->appends(request()->query())->nextPageUrl() }}" class="mg-pg"><i class="fa-solid fa-chevron-right text-[10px]"></i></a>
            @else
                <span class="mg-pg dis"><i class="fa-solid fa-chevron-right text-[10px]"></i></span>
            @endif
        </div>
    </div>
@endif
@endsection
