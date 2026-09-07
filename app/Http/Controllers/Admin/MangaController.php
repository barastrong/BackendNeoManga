<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Chapter;
use App\Models\Genre;
use App\Models\Manga;
use App\Models\User;
use App\Services\CloudinaryStorageService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class MangaController extends Controller
{
    public function __construct(private CloudinaryStorageService $storage) {}

    public function index(Request $request)
    {
        $query = Manga::with('genres')->withCount('chapters');

        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                    ->orWhere('author', 'LIKE', "%{$search}%")
                    ->orWhere('artist', 'LIKE', "%{$search}%");
            });
        }

        $mangas = $query->latest()->paginate(10);

        $stats = [
            'manga'     => Manga::count(),
            'chapters'  => Chapter::count(),
            'ongoing'   => Manga::where('status', 'ongoing')->count(),
            'completed' => Manga::where('status', 'completed')->count(),
        ];

        return view('admin.manga.index', compact('mangas', 'stats'));
    }

    public function create()
    {
        $genres = Genre::all();
        return view('admin.manga.create', compact('genres'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255|unique:mangas,title',
            'alternative_title' => 'nullable|string|max:255',
            'artist' => 'nullable|string|max:255',
            'description' => 'required|string',
            'author' => 'required|string|max:255',
            'status' => 'required|in:ongoing,completed,hiatus,cancelled',
            'type' => 'required|in:manga,manhwa,manhua,webtoon',
            'cover_image' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            'genres' => 'required|array',
            'genres.*' => 'exists:genres,id',
        ]);

        $dataToStore = $validated;
        $dataToStore['slug']        = Str::slug($validated['title']);
        $dataToStore['cover_image'] = $this->storage->uploadCover($request->file('cover_image'), $validated['title']);
        $dataToStore['user_id']     = auth()->id();

        $manga = Manga::create($dataToStore);
        $manga->genres()->sync($validated['genres']);

        if ($request->input('action') === 'create_again') {
            return redirect()->route('admin.manga.create')
                ->with('success', 'Manga berhasil ditambahkan. Silakan tambah lagi.');
        }

        return redirect()->route('admin.manga.index')
            ->with('success', 'Manga berhasil ditambahkan.');
    }

    public function edit(Manga $manga)
    {
        $genres = Genre::all();
        $mangaGenres = $manga->genres->pluck('id')->toArray();
        return view('admin.manga.edit', compact('manga', 'genres', 'mangaGenres'));
    }

    public function update(Request $request, Manga $manga)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255', Rule::unique('mangas')->ignore($manga->id)],
            'alternative_title' => 'nullable|string|max:255',
            'artist' => 'nullable|string|max:255',
            'description' => 'required|string',
            'author' => 'required|string|max:255',
            'status' => 'required|in:ongoing,completed,hiatus,cancelled',
            'type' => 'required|in:manga,manhwa,manhua,webtoon',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'genres' => 'required|array',
            'genres.*' => 'exists:genres,id',
        ]);

        $dataToUpdate = $validated;
        $dataToUpdate['slug'] = Str::slug($validated['title']);

        if ($request->hasFile('cover_image')) {
            $this->storage->deleteCover($manga->cover_image);
            $dataToUpdate['cover_image'] = $this->storage->uploadCover($request->file('cover_image'), $validated['title']);
        }

        $manga->update($dataToUpdate);
        $manga->genres()->sync($validated['genres']);
        return redirect()->route('admin.manga.index')->with('success', 'Manga berhasil diperbarui.');
    }

    public function destroy(Manga $manga)
    {
        $this->storage->deleteCover($manga->cover_image);
        $manga->delete();
        return redirect()->route('admin.manga.index')->with('success', 'Manga berhasil dihapus.');
    }
}
