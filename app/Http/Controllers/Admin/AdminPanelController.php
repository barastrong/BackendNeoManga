<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Chapter;
use App\Models\Genre;
use App\Models\Manga;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class AdminPanelController extends Controller
{
    public function index()
    {
        $mangaCount = Manga::count();
        $chapterCount = Chapter::count();
        $userCount = User::count();
        $latestMangas = Manga::latest()->take(5)->get();
        return view('admin.dashboard', compact('mangaCount', 'chapterCount', 'userCount', 'latestMangas'));
    }

    public function mangaIndex(Request $request)
    {
        $query = Manga::with('genres');
        
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                  ->orWhere('author', 'LIKE', "%{$search}%")
                  ->orWhere('artist', 'LIKE', "%{$search}%");
            });
        }
        
        $mangas = $query->latest()->paginate(10);
        return view('admin.manga.index', compact('mangas'));
    }

    public function mangaCreate()
    {
        $genres = Genre::all();
        return view('admin.manga.create', compact('genres'));
    }

    public function mangaStore(Request $request)
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

        $title = $validated['title'];
        $slug = Str::slug($title);
        $imageFile = $request->file('cover_image');
        $imageName = $title . '.' . $imageFile->getClientOriginalExtension();
        $imagePath = $imageFile->storeAs('manga-covers', $imageName, 'public');

        $dataToStore = $validated;
        $dataToStore['slug'] = $slug;
        $dataToStore['cover_image'] = $imagePath;
        $dataToStore['user_id'] = auth()->id();

        $manga = Manga::create($dataToStore);
        $manga->genres()->sync($validated['genres']);

        if ($request->input('action') === 'create_again') {
            return redirect()->route('admin.manga.create')
                ->with('success', 'Manga berhasil ditambahkan. Silakan tambah lagi.');
        }

        return redirect()->route('admin.manga.index')
            ->with('success', 'Manga berhasil ditambahkan.');
    }

    public function mangaEdit(Manga $manga)
    {
        $genres = Genre::all();
        $mangaGenres = $manga->genres->pluck('id')->toArray();
        return view('admin.manga.edit', compact('manga', 'genres', 'mangaGenres'));
    }

    public function mangaUpdate(Request $request, Manga $manga)
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
        $slug = Str::slug($validated['title']);
        $dataToUpdate['slug'] = $slug;

        if ($request->hasFile('cover_image')) {
            Storage::disk('public')->delete($manga->cover_image);

            $imageFile = $request->file('cover_image');
            $imageName = $slug . '.' . $imageFile->getClientOriginalExtension();
            $imagePath = $imageFile->storeAs('manga-covers', $imageName, 'public');
            $dataToUpdate['cover_image'] = $imagePath;
        }

        $manga->update($dataToUpdate);
        $manga->genres()->sync($validated['genres']);
        return redirect()->route('admin.manga.index')->with('success', 'Manga berhasil diperbarui.');
    }

    public function mangaDestroy(Manga $manga)
    {
        Storage::disk('public')->delete($manga->cover_image);
        Storage::disk('public')->deleteDirectory("chapters/{$manga->slug}");
        $manga->delete();
        return redirect()->route('admin.manga.index')->with('success', 'Manga berhasil dihapus.');
    }

    public function chapterIndex(Manga $manga)
    {
        $chapters = $manga->chapters()->latest('created_at')->paginate(20);
        return view('admin.chapter.index', compact('manga', 'chapters'));
    }

    public function chapterCreate(Manga $manga)
    {
        return view('admin.chapter.create', compact('manga'));
    }

    public function chapterStore(Request $request, Manga $manga)
    {
        $validated = $request->validate([
            'number' => ['required', 'numeric', Rule::unique('chapters')->where('manga_id', $manga->id)],
            'status' => 'required|in:draft,published',
            'chapter_images' => 'required|array',
            'chapter_images.*' => 'image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $imagePaths = [];
        if ($request->hasFile('chapter_images')) {
            foreach ($request->file('chapter_images') as $image) {
                $path = $image->store("chapters/{$manga->slug}", 'public');
                $imagePaths[] = $path;
            }
        }

        $manga->chapters()->create([
            'number' => $validated['number'],
            'status' => $validated['status'],
            'chapter_images' => $imagePaths,
        ]);

        return redirect()->route('admin.manga.chapters.index', $manga)->with('success', "Chapter {$validated['number']} berhasil ditambahkan.");
    }

    public function chapterEdit(Manga $manga, Chapter $chapter)
    {
        return view('admin.chapter.edit', compact('manga', 'chapter'));
    }

    public function chapterUpdate(Request $request, Manga $manga, Chapter $chapter)
    {
        $validated = $request->validate([
            'number' => ['required', 'numeric', Rule::unique('chapters')->where('manga_id', $manga->id)->ignore($chapter->id)],
            'status' => 'required|in:draft,published',
            'chapter_images' => 'nullable|array',
            'chapter_images.*' => 'image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $dataToUpdate = [
            'number' => $validated['number'],
            'status' => $validated['status'],
        ];

        if ($request->hasFile('chapter_images')) {
            foreach ($chapter->chapter_images as $oldImage) {
                Storage::disk('public')->delete($oldImage);
            }

            $imagePaths = [];
            foreach ($request->file('chapter_images') as $image) {
                $path = $image->store("chapters/{$manga->slug}", 'public');
                $imagePaths[] = $path;
            }
            $dataToUpdate['chapter_images'] = $imagePaths;
        }

        $chapter->update($dataToUpdate);

        return redirect()->route('admin.manga.chapters.index', $manga)->with('success', "Chapter {$chapter->number} berhasil diperbarui.");
    }

    public function chapterDestroy(Manga $manga, Chapter $chapter)
    {
        foreach ($chapter->chapter_images as $image) {
            Storage::disk('public')->delete($image);
        }

        $chapter->delete();

        return redirect()->route('admin.manga.chapters.index', $manga)->with('success', "Chapter {$chapter->number} berhasil dihapus.");
    }

    public function userIndex()
    {
        $users = User::latest()->paginate(15);
        return view('admin.user.index', compact('users'));
    }
}