@extends('layouts.admin')

@section('title', 'Chapter — Admin NeoManga')
@section('page-title', 'Chapter')

@section('content')
<link rel="stylesheet" href="{{ asset('css/admin/chapter/index.css') }}">

<div>
    <div class="flex flex-wrap items-end justify-between gap-4">
        <div class="min-w-0">
            <a href="{{ route('admin.manga.index') }}" class="inline-flex items-center gap-2 text-xs font-semibold text-slate-500 hover:text-brand transition-colors">
                <i class="fa-solid fa-arrow-left"></i>Kembali ke Koleksi Manga
            </a>
            <div class="mt-3 flex items-center gap-2">
                <span class="ch-eyebrow"><i class="fa-solid fa-layer-group mr-1.5"></i>Katalog</span>
                <span class="text-slate-600 text-xs">•</span>
                <span class="text-[11px] font-semibold tracking-wide text-brand/90 uppercase">Chapter</span>
            </div>
            <h1 class="font-display text-[26px] lg:text-3xl font-bold text-white tracking-tight mt-1.5">{{ $manga->title }}</h1>
            <p class="text-sm text-slate-400 mt-1">Daftar chapter untuk manga ini.</p>
        </div>
        <a href="{{ route('admin.manga.chapters.create', $manga) }}" class="ch-btn ch-btn-primary">
            <i class="fa-solid fa-plus text-xs"></i>Tambah Chapter
        </a>
    </div>

    @if (session('success'))
        <div class="mt-5 flex items-center gap-3 px-5 py-4 rounded-2xl border text-sm" style="background:rgba(52,211,153,.08);border-color:rgba(52,211,153,.25);color:#6ee7b7">
            <i class="fa-solid fa-circle-check"></i><span>{{ session('success') }}</span>
        </div>
    @endif

    <div class="ch-card mt-6">
        <div class="overflow-x-auto">
            <table class="w-full" style="border-collapse:collapse;font-size:13.5px;min-width:640px">
                <thead>
                    <tr>
                        <th class="ch-th">Chapter</th>
                        <th class="ch-th">Gambar</th>
                        <th class="ch-th text-center">Status</th>
                        <th class="ch-th">Dibuat</th>
                        <th class="ch-th text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($chapters as $chapter)
                        @php
                            $imgCount = is_array($chapter->chapter_images) ? count($chapter->chapter_images) : (is_string($chapter->chapter_images) ? count(json_decode($chapter->chapter_images, true) ?? []) : 0);
                        @endphp
                        <tr class="ch-tr">
                            <td class="ch-td">
                                <span class="inline-flex items-center gap-1.5 font-bold text-white">
                                    <i class="fa-solid fa-hashtag text-[10px]" style="color:#ff8a9c"></i>{{ $chapter->number }}
                                </span>
                                <p class="text-[11px] text-slate-500 mt-0.5">{{ $chapter->created_at->format('d M Y') }}</p>
                            </td>
                            <td class="ch-td">
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
                                <a href="{{ route('admin.manga.chapters.edit', [$manga, $chapter]) }}" class="ch-ico-btn edit" title="Edit Chapter"><i class="fa-solid fa-pen"></i></a>
                                <form action="{{ route('admin.manga.chapters.destroy', [$manga, $chapter]) }}" method="POST" class="inline"
                                      onsubmit="return confirm('Hapus chapter {{ $chapter->number }}? Semua gambar ikut terhapus.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="ch-ico-btn danger" title="Hapus"><i class="fa-solid fa-trash-can"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">
                                <div class="ch-empty">
                                    <i class="fa-solid fa-layer-group ic"></i>
                                    <p class="mt-3 font-semibold text-white text-sm">Belum Ada Chapter</p>
                                    <p class="text-sm mt-1">Manga ini belum memiliki chapter. Silakan tambahkan satu.</p>
                                    <a href="{{ route('admin.manga.chapters.create', $manga) }}" class="ch-btn ch-btn-primary mt-4">
                                        <i class="fa-solid fa-plus text-xs"></i>Tambah Chapter Pertama
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if ($chapters->hasPages())
        <div class="mt-5 flex items-center justify-between flex-wrap gap-3">
            <p class="text-xs" style="color:#64748b">
                Menampilkan <span class="text-slate-300 font-semibold">{{ $chapters->firstItem() }}</span>–
                <span class="text-slate-300 font-semibold">{{ $chapters->lastItem() }}</span> dari
                <span class="text-slate-300 font-semibold">{{ $chapters->total() }}</span>
            </p>
            <div class="flex gap-1.5">
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
