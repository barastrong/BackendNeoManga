@extends('layouts.adminSidebar')

@section('content')
    <div class="mb-8">
        <a href="{{ route('admin.manga.index') }}" class="inline-flex items-center text-sm font-medium text-slate-500 hover:text-indigo-600 transition-colors duration-200">
            <i class="fa-solid fa-arrow-left mr-2"></i>
            Kembali ke Daftar Manga
        </a>
        <div class="mt-2 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <p class="text-sm text-slate-500">Daftar Chapter untuk:</p>
                <h1 class="text-3xl font-bold text-slate-800">{{ $manga->title }}</h1>
            </div>
            <a href="{{ route('admin.manga.chapters.create', $manga) }}" class="inline-flex items-center justify-center rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition-all duration-200 hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                <i class="fa-solid fa-plus mr-2"></i>
                Tambah Chapter
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="flex items-start bg-green-50 border-l-4 border-green-400 text-green-800 p-4 rounded-lg mb-6 shadow-sm" role="alert">
            <i class="fa-solid fa-check-circle mr-3 mt-1"></i>
            <div>
                <p class="font-bold">Sukses!</p>
                <p>{{ session('success') }}</p>
            </div>
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-slate-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Chapter</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Detail</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-slate-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse ($chapters as $chapter)
                        <tr class="hover:bg-slate-50 transition-colors duration-200">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="font-semibold text-slate-900">Chapter {{ $chapter->number }}</div>
                                <div class="text-xs text-slate-500">Dibuat: {{ $chapter->created_at->format('d M Y') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center text-slate-600">
                                    <i class="fa-solid fa-images w-4 text-slate-400 mr-2"></i>
                                    <span>{{ $chapter->image_count }} Gambar</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $statusClass = $chapter->status === 'published' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800';
                                @endphp
                                <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold {{ $statusClass }}">
                                    {{ ucfirst($chapter->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                <div class="flex items-center justify-end space-x-1">
                                    <a href="{{ route('admin.manga.chapters.edit', [$manga, $chapter]) }}" class="flex h-8 w-8 items-center justify-center rounded-full text-slate-500 transition hover:bg-indigo-100 hover:text-indigo-600" title="Edit Chapter">
                                        <i class="fa-solid fa-pen-to-square fa-sm"></i>
                                    </a>
                                    <form action="{{ route('admin.manga.chapters.destroy', [$manga, $chapter]) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus Chapter {{ $chapter->number }}?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="flex h-8 w-8 items-center justify-center rounded-full text-slate-500 transition hover:bg-red-100 hover:text-red-600" title="Hapus Chapter">
                                            <i class="fa-solid fa-trash-can fa-sm"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-24 text-center">
                                <div class="flex flex-col items-center">
                                    <i class="fa-solid fa-folder-open fa-4x text-slate-300 mb-4"></i>
                                    <h3 class="text-lg font-semibold text-slate-700">Belum Ada Chapter</h3>
                                    <p class="mt-1 text-sm text-slate-500">Manga ini belum memiliki chapter. Silakan tambahkan satu.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    @if ($chapters->hasPages())
        <div class="mt-6 flex flex-col items-center justify-between gap-4 sm:flex-row">
            <div>
                <p class="text-sm text-slate-700">
                    Menampilkan
                    <span class="font-semibold text-slate-900">{{ $chapters->firstItem() }}</span>
                    sampai
                    <span class="font-semibold text-slate-900">{{ $chapters->lastItem() }}</span>
                    dari
                    <span class="font-semibold text-slate-900">{{ $chapters->total() }}</span>
                    hasil
                </p>
            </div>
            <div class="inline-flex items-center space-x-2">
                @if ($chapters->onFirstPage())
                    <span class="inline-flex items-center rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-400 cursor-not-allowed">
                        <i class="fa-solid fa-arrow-left mr-2"></i> Previous
                    </span>
                @else
                    <a href="{{ $chapters->previousPageUrl() }}" class="inline-flex items-center rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 shadow-sm transition hover:bg-slate-50">
                        <i class="fa-solid fa-arrow-left mr-2"></i> Previous
                    </a>
                @endif
                @if ($chapters->hasMorePages())
                    <a href="{{ $chapters->nextPageUrl() }}" class="inline-flex items-center rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 shadow-sm transition hover:bg-slate-50">
                        Next <i class="fa-solid fa-arrow-right ml-2"></i>
                    </a>
                @else
                    <span class="inline-flex items-center rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-400 cursor-not-allowed">
                        Next <i class="fa-solid fa-arrow-right ml-2"></i>
                    </span>
                @endif
            </div>
        </div>
    @endif
@endsection