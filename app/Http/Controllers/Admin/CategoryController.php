<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Genre;
use App\Models\Manga;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Genre::withCount('mangas');

        if ($request->has('search') && !empty($request->search)) {
            $query->where('name', 'LIKE', "%{$request->search}%");
        }

        $genres = $query->orderBy('name')->paginate(21);
        return view('admin.category.index', compact('genres'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:60|unique:genres,name',
        ]);

        Genre::create(['name' => trim($validated['name'])]);

        return back()->with('success', "Kategori \"{$validated['name']}\" berhasil ditambahkan.");
    }

    public function update(Request $request, Genre $genre)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:60', Rule::unique('genres', 'name')->ignore($genre->id)],
        ]);

        $genre->update(['name' => trim($validated['name'])]);

        return back()->with('success', "Kategori diubah menjadi \"{$validated['name']}\".");
    }

    public function destroy(Genre $genre)
    {
        $name = $genre->name;
        $genre->mangas()->detach(); // lepas relasi dulu, jangan hapus manga
        $genre->delete();

        return back()->with('success', "Kategori \"{$name}\" berhasil dihapus.");
    }

    /**
     * Aksi massal kategori: delete.
     */
    public function bulk(Request $request)
    {
        $validated = $request->validate([
            'ids'    => 'required|array',
            'ids.*'  => 'exists:genres,id',
            'action' => 'required|in:delete',
        ]);

        $genres = Genre::whereIn('id', $validated['ids'])->get();
        foreach ($genres as $genre) {
            $genre->mangas()->detach();
        }
        Genre::whereIn('id', $genres->pluck('id'))->delete();

        return back()->with('success', count($genres) . ' kategori berhasil dihapus.');
    }
}
