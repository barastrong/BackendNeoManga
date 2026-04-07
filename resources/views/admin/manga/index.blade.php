@extends('layouts.adminSidebar')

@section('content')
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-bold text-slate-800">Koleksi Manga</h1>
            <p class="mt-1 text-slate-500">Jelajahi, kelola, dan perbarui koleksi manga Anda.</p>
        </div>
        <div class="flex gap-3">
            <form action="{{ route('admin.manga.index') }}" method="GET" class="relative">
                <input type="text" name="search" placeholder="Cari manga..." 
                       class="w-64 pl-10 pr-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                       value="{{ request('search') }}">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fa-solid fa-search text-slate-400"></i>
                </div>
            </form>
            <a href="{{ route('admin.manga.create') }}" class="inline-flex items-center justify-center rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition-all duration-200 hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                <i class="fa-solid fa-plus mr-2"></i>
                Tambah Manga
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

    <div class="grid grid-cols-1 gap-6">
        @forelse ($mangas as $manga)
            <div class="bg-white rounded-xl shadow-md border border-transparent hover:border-indigo-500 hover:shadow-xl transition-all duration-300 p-5">
                <div class="flex flex-col sm:flex-row gap-5">
                    <div class="w-full sm:w-24 flex-shrink-0">
                        <img class="w-full h-36 sm:h-full object-cover rounded-lg shadow-lg" src="{{ asset('storage/' . $manga->cover_image) }}" alt="{{ $manga->title }}">
                    </div>

                    <div class="flex-1 flex flex-col justify-between">
                        <div>
                            <div class="flex justify-between items-start">
                                <div>
                                    @php
                                        $typeClasses = [
                                            'manga'   => 'text-[#2C2C2C]',   
                                            'manhwa'  => 'text-[#4DB6AC]',   
                                            'manhua'  => 'text-[#D32F2F]',   
                                            'webtoon' => 'text-[#00D564]',   
                                        ];
                                    @endphp
                                    <span class="text-xs font-bold uppercase tracking-wider {{ $typeClasses[$manga->type] ?? 'text-slate-500' }}">{{ $manga->type }}</span>
                                    <h2 class="text-lg font-bold text-slate-800 hover:text-indigo-600 transition-colors">
                                        <p>{{ $manga->title }}</p>
                                    </h2>
                                    <p class="text-sm text-slate-500">{{ $manga->alternative_title }}</p>
                                </div>
                                
                                <div x-data="{ open: false }" class="relative">
                                    <button @click="open = !open" class="flex h-8 w-8 items-center justify-center rounded-full text-slate-400 hover:bg-slate-100 hover:text-slate-600 transition">
                                        <i class="fa-solid fa-ellipsis-vertical"></i>
                                    </button>
                                    <div x-show="open" @click.away="open = false" x-cloak class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-xl z-10 ring-1 ring-black ring-opacity-5" x-transition>
                                        <a href="{{ route('admin.manga.chapters.index', $manga) }}" class="flex items-center px-4 py-2 text-sm text-slate-700 hover:bg-slate-100">
                                            <i class="fa-solid fa-layer-group w-5 mr-3"></i> Chapter
                                        </a>
                                        <a href="{{ route('admin.manga.edit', $manga) }}" class="flex items-center px-4 py-2 text-sm text-slate-700 hover:bg-slate-100">
                                            <i class="fa-solid fa-pen-to-square w-5 mr-3"></i> Edit
                                        </a>
                                        <div class="border-t border-slate-100"></div>
                                        <form action="{{ route('admin.manga.destroy', $manga) }}" method="POST" onsubmit="return confirm('Yakin mau hapus manga \'{{ $manga->title }}\'?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="flex items-center w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                                <i class="fa-solid fa-trash-can w-5 mr-3"></i> Hapus
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mt-3 flex flex-wrap gap-1.5">
                                @foreach($manga->genres as $genre)
                                    <span class="px-2 py-1 text-xs bg-slate-100 text-slate-700 rounded-full font-medium">{{ $genre->name }}</span>
                                @endforeach
                            </div>
                        </div>

                        <div class="mt-4 pt-4 border-t border-slate-200 flex items-center justify-between text-sm">
                            <div class="text-slate-500">
                                <span class="font-semibold text-slate-600">{{ $manga->author }}</span> / <span class="font-semibold text-slate-600">{{ $manga->artist }}</span>
                            </div>
                            @php
                                $statusClasses = [ 'ongoing' => 'bg-green-100 text-green-800', 'completed' => 'bg-blue-100 text-blue-800', 'hiatus' => 'bg-yellow-100 text-yellow-800', 'cancelled' => 'bg-red-100 text-red-800' ];
                            @endphp
                            <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold {{ $statusClasses[$manga->status] ?? 'bg-slate-100 text-slate-800' }}">{{ ucfirst($manga->status) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-24 px-6 bg-white rounded-xl shadow-md border">
                <i class="fa-solid fa-book-bookmark fa-4x text-slate-300 mb-4"></i>
                <h3 class="text-xl font-semibold text-slate-700">Koleksi Manga Anda Kosong</h3>
                <p class="mt-2 text-slate-500">Mari mulai dengan menambahkan manga pertama Anda ke dalam sistem.</p>
                <a href="{{ route('admin.manga.create') }}" class="mt-6 inline-flex items-center justify-center rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition-all duration-200 hover:bg-indigo-500">
                    <i class="fa-solid fa-plus mr-2"></i>
                    Tambah Manga Sekarang
                </a>
            </div>
        @endforelse
    </div>
    
@if ($mangas->hasPages())
    <div class="mt-8 flex flex-col items-center justify-between gap-4 sm:flex-row">
        <div>
            <p class="text-sm text-slate-700">
                Menampilkan
                <span class="font-semibold text-slate-900">{{ $mangas->firstItem() }}</span>
                sampai
                <span class="font-semibold text-slate-900">{{ $mangas->lastItem() }}</span>
                dari
                <span class="font-semibold text-slate-900">{{ $mangas->total() }}</span>
                hasil
            </p>
        </div>

        <div class="inline-flex items-center space-x-2">
            @if ($mangas->onFirstPage())
                <span class="inline-flex items-center rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-400 cursor-not-allowed">
                    <i class="fa-solid fa-arrow-left mr-2"></i>
                    Previous
                </span>
            @else
                <a href="{{ $mangas->appends(request()->query())->previousPageUrl() }}" class="inline-flex items-center rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 shadow-sm transition hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    <i class="fa-solid fa-arrow-left mr-2"></i>
                    Previous
                </a>
            @endif

            @if ($mangas->hasMorePages())
                <a href="{{ $mangas->appends(request()->query())->nextPageUrl() }}" class="inline-flex items-center rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 shadow-sm transition hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    Next
                    <i class="fa-solid fa-arrow-right ml-2"></i>
                </a>
            @else
                <span class="inline-flex items-center rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-400 cursor-not-allowed">
                    Next
                    <i class="fa-solid fa-arrow-right ml-2"></i>
                </span>
            @endif
        </div>
    </div>
@endif
@endsection