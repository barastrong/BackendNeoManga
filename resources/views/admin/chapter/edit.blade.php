@extends('layouts.adminSidebar')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.manga.chapters.index', $manga) }}" class="inline-flex items-center text-sm font-semibold text-slate-600 hover:text-indigo-600 transition">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12" /></svg>
        Kembali ke Daftar Chapter
    </a>
    <h1 class="text-3xl font-bold text-slate-800 mt-2">Edit Chapter {{ $chapter->number }} - <span class="text-indigo-600">{{ $manga->title }}</span></h1>
</div>

<div class="bg-white p-6 md:p-8 rounded-xl shadow-lg">
    <form action="{{ route('admin.manga.chapters.update', [$manga, $chapter]) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="number" class="block text-sm font-semibold text-gray-700 mb-2">Nomor Chapter</label>
                <input type="number" step="0.1" name="number" id="number" value="{{ old('number', $chapter->number) }}" class="block w-full px-4 py-2 border border-slate-300 rounded-lg shadow-sm" required>
                @error('number') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
            </div>
            <div>
                <label for="status" class="block text-sm font-semibold text-gray-700 mb-2">Status</label>
                <select name="status" id="status" class="block w-full px-4 py-2 border border-slate-300 bg-white rounded-lg shadow-sm">
                    <option value="draft" @selected(old('status', $chapter->status) == 'draft')>Draft</option>
                    <option value="published" @selected(old('status', $chapter->status) == 'published')>Published</option>
                </select>
                @error('status') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
            </div>
        </div>
        
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Gambar Saat Ini</label>
            <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 gap-4 p-4 border rounded-lg">
                @forelse($chapter->image_urls as $imageUrl)
                    <img src="{{ $imageUrl }}" class="w-full h-auto object-cover rounded-md shadow-sm">
                @empty
                    <p class="col-span-full text-sm text-slate-500">Tidak ada gambar.</p>
                @endforelse
            </div>
        </div>

        <div x-data="{ files: [] }">
            <label class="block text-sm font-semibold text-gray-700 mb-2">Ganti Gambar (Opsional, akan mengganti semua gambar lama)</label>
            <label for="chapter_images" class="relative flex flex-col items-center justify-center w-full h-32 border-2 border-slate-300 border-dashed rounded-lg cursor-pointer bg-slate-50 hover:bg-slate-100 transition">
                <p class="mb-2 text-sm text-slate-500"><span class="font-semibold">Click to upload</span> atau drag and drop</p>
                <input type="file" name="chapter_images[]" id="chapter_images" class="sr-only" multiple @change="files = Array.from($event.target.files).map(file => file.name)">
            </label>
            <div x-show="files.length > 0" class="text-sm text-gray-500 mt-2">
                <p><span x-text="files.length" class="font-semibold"></span> file baru dipilih:</p>
                <ul class="list-disc list-inside"><template x-for="file in files" :key="file"><li x-text="file"></li></template></ul>
            </div>
            @error('chapter_images.*') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
        </div>

        <div class="pt-4 flex justify-end items-center gap-x-4 border-t border-slate-200 mt-8">
            <a href="{{ route('admin.manga.chapters.index', $manga) }}" class="px-6 py-2 text-sm font-semibold text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-50">Batal</a>
            <button type="submit" class="px-6 py-2 text-sm font-semibold text-white bg-indigo-600 rounded-lg hover:bg-indigo-700">Update Chapter</button>
        </div>
    </form>
</div>
@endsection