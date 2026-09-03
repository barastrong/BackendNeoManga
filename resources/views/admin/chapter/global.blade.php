@extends('layouts.admin')

@section('title', 'Kelola Chapter — Admin NeoManga')
@section('page-title', 'Chapter')

@section('content')
<link rel="stylesheet" href="{{ asset('css/admin/chapter/global.css') }}">

<div>
    {{-- HEADER --}}
    <div class="flex flex-wrap items-end justify-between gap-4">
        <div>
            <div class="flex items-center gap-2">
                <span class="ch-eyebrow"><i class="fa-solid fa-layer-group mr-1.5"></i>Katalog</span>
                <span class="text-slate-600 text-xs">•</span>
                <span class="text-[11px] font-semibold tracking-wide text-brand/90 uppercase">Kelola Data</span>
            </div>
            <h1 class="font-display text-[26px] lg:text-3xl font-bold text-white tracking-tight mt-1.5">Kelola Chapter</h1>
            <p class="text-sm text-slate-400 mt-1">Semua chapter dari seluruh manga dalam satu tempat.</p>
        </div>
        <div class="flex flex-wrap items-center gap-2.5">
            <a href="{{ route('admin.category.index') }}" class="ch-btn ch-btn-ghost"><i class="fa-solid fa-tags text-xs"></i>Kategori</a>
            <a href="{{ route('admin.manga.index') }}" class="ch-btn ch-btn-ghost"><i class="fa-solid fa-book-open text-xs"></i>Manga</a>
            <a href="{{ route('admin.manga.create') }}" class="ch-btn ch-btn-primary"><i class="fa-solid fa-plus text-xs"></i>Tambah Manga</a>
        </div>
    </div>

    {{-- STATS --}}
    <div class="mt-6 grid grid-cols-2 lg:grid-cols-4 gap-3.5">
        <div class="ch-stat">
            <span class="ic" style="background:rgba(255,46,77,.13);color:#ff2e4d"><i class="fa-solid fa-layer-group"></i></span>
            <div><p class="lbl">Total Chapter</p><p class="val">{{ number_format($stats['total']) }}</p></div>
        </div>
        <div class="ch-stat">
            <span class="ic" style="background:rgba(52,211,153,.13);color:#34d399"><i class="fa-solid fa-check"></i></span>
            <div><p class="lbl">Published</p><p class="val">{{ number_format($stats['published']) }}</p></div>
        </div>
        <div class="ch-stat">
            <span class="ic" style="background:rgba(251,191,36,.13);color:#fbbf24"><i class="fa-solid fa-pen"></i></span>
            <div><p class="lbl">Draft</p><p class="val">{{ number_format($stats['draft']) }}</p></div>
        </div>
        <div class="ch-stat">
            <span class="ic" style="background:rgba(56,189,248,.13);color:#38bdf8"><i class="fa-solid fa-book-open"></i></span>
            <div><p class="lbl">Manga</p><p class="val">{{ number_format($stats['manga']) }}</p></div>
        </div>
    </div>

    {{-- ALERT --}}
    @if (session('success'))
        <div class="mt-5 flex items-center gap-3 px-5 py-4 rounded-2xl border text-sm" style="background:rgba(52,211,153,.08);border-color:rgba(52,211,153,.25);color:#6ee7b7">
            <i class="fa-solid fa-circle-check"></i><span>{{ session('success') }}</span>
        </div>
    @endif

    {{-- FILTER + SEARCH --}}
    <div class="mt-6 flex flex-wrap items-center justify-between gap-3">
        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('admin.chapter.index') }}" class="ch-btn-filter {{ !request('status') ? 'active' : '' }}">Semua</a>
            <a href="{{ route('admin.chapter.index', ['status' => 'published']) }}" class="ch-btn-filter {{ request('status') == 'published' ? 'active' : '' }}"><i class="fa-solid fa-circle text-[7px]" style="color:#34d399"></i>Published</a>
            <a href="{{ route('admin.chapter.index', ['status' => 'draft']) }}" class="ch-btn-filter {{ request('status') == 'draft' ? 'active' : '' }}"><i class="fa-solid fa-circle text-[7px]" style="color:#fbbf24"></i>Draft</a>
        </div>
        <form action="{{ route('admin.chapter.index') }}" method="GET" class="ch-search">
            <input type="hidden" name="status" value="{{ request('status') }}">
            <i class="fa-solid fa-magnifying-glass text-slate-500 text-xs"></i>
            <input type="text" name="search" placeholder="Cari judul manga / no chapter..." value="{{ request('search') }}">
            @if(request('search'))
                <a href="{{ route('admin.chapter.index', ['status' => request('status')]) }}" class="text-slate-500 hover:text-white"><i class="fa-solid fa-xmark text-xs"></i></a>
            @endif
        </form>
    </div>

    {{-- TABLE --}}
    <div class="ch-card mt-4">
        <div class="overflow-x-auto">
            <table class="w-full" style="border-collapse:collapse;font-size:13px;min-width:760px">
                <thead>
                    <tr>
                        <th class="ch-th" style="width:60px">Cover</th>
                        <th class="ch-th">Manga</th>
                        <th class="ch-th">Chapter</th>
                        <th class="ch-th text-center">Gambar</th>
                        <th class="ch-th text-center">Status</th>
                        <th class="ch-th">Update</th>
                        <th class="ch-th text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($chapters as $chapter)
                        @php
                            $imgCount = is_array($chapter->chapter_images) ? count($chapter->chapter_images) : (is_string($chapter->chapter_images) ? count(json_decode($chapter->chapter_images, true) ?? []) : 0);
                        @endphp
                        <tr class="ch-tr">
                            <td class="ch-td">
                                @if($chapter->manga?->cover_url)
                                    <img src="{{ $chapter->manga->cover_url }}" class="ch-cover" alt="">
                                @else
                                    <div class="ch-cover flex items-center justify-center" style="background:#0d1220;border:1px dashed rgba(255,255,255,.15)"><i class="fa-solid fa-book-open text-slate-600" style="font-size:12px"></i></div>
                                @endif
                            </td>
                            <td class="ch-td">
                                <a href="{{ route('admin.manga.edit', $chapter->manga) }}" class="font-semibold text-white hover:text-brand transition-colors line-clamp-1">{{ $chapter->manga?->title ?? '—' }}</a>
                            </td>
                            <td class="ch-td">
                                <span class="inline-flex items-center gap-1.5 font-bold" style="color:#ff8a9c">
                                    <i class="fa-solid fa-hashtag text-[10px]"></i>{{ $chapter->number }}
                                </span>
                            </td>
                            <td class="ch-td text-center">
                                <span class="ch-pill" style="background:rgba(255,255,255,.05);color:#94a3b8"><i class="fa-regular fa-image text-[9px]"></i>{{ $imgCount }}</span>
                            </td>
                            <td class="ch-td text-center">
                                @if($chapter->status === 'published')
                                    <span class="ch-pill" style="background:rgba(52,211,153,.12);color:#34d399"><i class="fa-solid fa-circle text-[6px]"></i>Published</span>
                                @else
                                    <span class="ch-pill" style="background:rgba(251,191,36,.12);color:#fbbf24"><i class="fa-solid fa-circle text-[6px]"></i>Draft</span>
                                @endif
                            </td>
                            <td class="ch-td text-slate-500 text-xs whitespace-nowrap">{{ $chapter->created_at->diffForHumans() }}</td>
                            <td class="ch-td text-right whitespace-nowrap">
                                <a href="{{ route('admin.chapter.edit', $chapter) }}" class="ch-ico-btn edit" title="Edit Chapter"><i class="fa-solid fa-pen"></i></a>
                                <a href="{{ route('chapter.show', $chapter->manga?->slug . '/chapter-' . $chapter->number) }}" target="_blank" class="ch-ico-btn" title="Lihat"><i class="fa-solid fa-eye"></i></a>
                                <form action="{{ route('admin.chapter.destroy', $chapter) }}" method="POST" class="inline"
                                      onsubmit="return confirm('Hapus chapter {{ $chapter->number }} dari &quot;{{ $chapter->manga?->title }}&quot;? Semua gambar chapter ikut terhapus.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="ch-ico-btn danger" title="Hapus"><i class="fa-solid fa-trash-can"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                <div class="ch-empty">
                                    <i class="fa-solid fa-layer-group ic"></i>
                                    <p class="mt-3 font-semibold text-white text-sm">Tidak ada chapter ditemukan.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- PAGINATION --}}
    @if($chapters->hasPages())
        <div class="mt-5 flex items-center justify-between flex-wrap gap-3">
            <p class="text-xs" style="color:#64748b">
                Menampilkan <span class="text-slate-300 font-semibold">{{ $chapters->firstItem() }}</span>–
                <span class="text-slate-300 font-semibold">{{ $chapters->lastItem() }}</span> dari
                <span class="text-slate-300 font-semibold">{{ $chapters->total() }}</span>
            </p>
            <div class="flex gap-1.5">
                {{-- prev --}}
                @if($chapters->onFirstPage())
                    <span class="ch-pag dis"><i class="fa-solid fa-chevron-left text-[10px]"></i></span>
                @else
                    <a href="{{ $chapters->previousPageUrl() }}" class="ch-pag"><i class="fa-solid fa-chevron-left text-[10px]"></i></a>
                @endif
                @for($i = max(1, $chapters->currentPage() - 2); $i <= min($chapters->lastPage(), $chapters->currentPage() + 2); $i++)
                    @if($i == $chapters->currentPage())
                        <span class="ch-pag cur">{{ $i }}</span>
                    @else
                        <a href="{{ $chapters->url($i) }}" class="ch-pag">{{ $i }}</a>
                    @endif
                @endfor
                @if($chapters->hasMorePages())
                    <a href="{{ $chapters->nextPageUrl() }}" class="ch-pag"><i class="fa-solid fa-chevron-right text-[10px]"></i></a>
                @else
                    <span class="ch-pag dis"><i class="fa-solid fa-chevron-right text-[10px]"></i></span>
                @endif
            </div>
        </div>
    @endif
</div>
@endsection
