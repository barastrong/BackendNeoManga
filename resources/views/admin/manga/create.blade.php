@extends('layouts.adminSidebar')

@section('content')
<h1 class="text-3xl font-bold text-slate-800 mb-6">Tambah Manga Baru</h1>
@if (session('success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 px-4 py-3 rounded-lg relative mb-5" role="alert">
        <strong class="font-bold">Sukses!</strong>
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
@endif

<div class="bg-white p-6 md:p-8 rounded-xl shadow-lg">
    <form action="{{ route('admin.manga.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        <div>
            <label for="title" class="block text-sm font-semibold text-gray-700 mb-2">Judul</label>
            <input type="text" name="title" id="title" value="{{ old('title') }}" placeholder="Contoh: One Piece" class="block w-full px-4 py-2 border border-slate-300 rounded-lg shadow-sm" required>
            @error('title') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
        </div>
        <div>
            <label for="alternative_title" class="block text-sm font-semibold text-gray-700 mb-2">Judul Alternatif (Opsional)</label>
            <input type="text" name="alternative_title" id="alternative_title" value="{{ old('alternative_title') }}" placeholder="Contoh: ワンピース" class="block w-full px-4 py-2 border border-slate-300 rounded-lg shadow-sm">
            @error('alternative_title') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="author" class="block text-sm font-semibold text-gray-700 mb-2">Author</label>
                <input type="text" name="author" id="author" value="{{ old('author') }}" placeholder="Contoh: Eiichiro Oda" class="block w-full px-4 py-2 border border-slate-300 rounded-lg shadow-sm" required>
                @error('author') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
            </div>
            <div>
                <label for="artist" class="block text-sm font-semibold text-gray-700 mb-2">Artist (Opsional)</label>
                <input type="text" name="artist" id="artist" value="{{ old('artist') }}" placeholder="Jika sama, isi sama seperti author" class="block w-full px-4 py-2 border border-slate-300 rounded-lg shadow-sm">
                @error('artist') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
            </div>
        </div>
        <div>
            <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">Deskripsi</label>
            <textarea name="description" id="description" rows="5" placeholder="Ceritakan tentang manga ini..." class="block w-full px-4 py-2 border border-slate-300 rounded-lg shadow-sm">{{ old('description') }}</textarea>
            @error('description') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="status" class="block text-sm font-semibold text-gray-700 mb-2">Status</label>
                <select name="status" id="status" class="block w-full px-4 py-2 border border-slate-300 bg-white rounded-lg shadow-sm">
                    <option value="ongoing" @selected(old('status') == 'ongoing')>Ongoing</option>
                    <option value="completed" @selected(old('status') == 'completed')>Completed</option>
                    <option value="hiatus" @selected(old('status') == 'hiatus')>Hiatus</option>
                    <option value="cancelled" @selected(old('status') == 'cancelled')>Cancelled</option>
                </select>
                @error('status') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
            </div>
            <div>
                <label for="type" class="block text-sm font-semibold text-gray-700 mb-2">Tipe</label>
                <select name="type" id="type" class="block w-full px-4 py-2 border border-slate-300 bg-white rounded-lg shadow-sm">
                    <option value="manga" @selected(old('type', 'manga') == 'manga')>Manga</option>
                    <option value="manhwa" @selected(old('type') == 'manhwa')>Manhwa</option>
                    <option value="manhua" @selected(old('type') == 'manhua')>Manhua</option>
                    <option value="webtoon" @selected(old('type') == 'webtoon')>Webtoon</option>
                </select>
                @error('type') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
            </div>
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Genres</label>
            <div class="p-4 border border-slate-300 rounded-lg grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4 max-h-60 overflow-y-auto">
                @foreach($genres as $genre)
                <div class="flex items-center">
                    <input type="checkbox" name="genres[]" id="genre-{{ $genre->id }}" value="{{ $genre->id }}" @if(is_array(old('genres')) && in_array($genre->id, old('genres'))) checked @endif class="h-4 w-4 rounded text-indigo-600 border-gray-300 focus:ring-indigo-500">
                    <label for="genre-{{ $genre->id }}" class="ml-2 text-sm text-gray-600">{{ $genre->name }}</label>
                </div>
                @endforeach
            </div>
            @error('genres') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
        </div>
        
        <div x-data="{ fileName: '' }">
            <label class="block text-sm font-semibold text-gray-700 mb-2">Cover Image</label>
            <label for="cover_image" class="relative flex flex-col items-center justify-center w-full h-32 border-2 border-slate-300 border-dashed rounded-lg cursor-pointer bg-slate-50 hover:bg-slate-100 transition">
                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                    <svg class="w-8 h-8 mb-4 text-slate-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"/></svg>
                    <p class="mb-2 text-sm text-slate-500"><span class="font-semibold">Click to upload</span> or drag and drop</p>
                    <p class="text-xs text-slate-500">PNG, JPG, or WEBP (MAX. 2MB)</p>
                </div>
                <input type="file" name="cover_image" id="cover_image" class="sr-only" @change="fileName = $event.target.files[0] ? $event.target.files[0].name : ''" required>
            </label>
            <p x-show="fileName" class="text-sm text-gray-500 mt-2">File dipilih: <span x-text="fileName" class="font-semibold"></span></p>
            @error('cover_image') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
        </div>

        <div class="pt-4 flex justify-end items-center gap-x-3 border-t border-slate-200 mt-8">
            <a href="{{ route('admin.manga.index') }}" class="px-6 py-2 text-sm font-semibold text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-50">Batal</a>

            <button type="submit" name="action" value="create_again" class="px-6 py-2 text-sm font-semibold text-indigo-600 bg-indigo-100 border border-transparent rounded-lg hover:bg-indigo-200">Simpan & Buat Lagi</button>
            
            <button type="submit" name="action" value="save" class="px-6 py-2 text-sm font-semibold text-white bg-indigo-600 rounded-lg hover:bg-indigo-700">Simpan Manga</button>
            
        </div>
    </form>
</div>
@endsection