<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Manga;
use App\Models\Genre;
use App\Services\CloudinaryStorageService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class MangaController extends Controller
{
    public function __construct(private CloudinaryStorageService $storage) {}

    public function index()
    {
        return response()->json(Manga::with('genres')->latest()->paginate(10));
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'title'             => 'required|string|max:255|unique:mangas,title',
                'alternative_title' => 'nullable|string|max:1000',
                'artist'            => 'nullable|string|max:255',
                'description'       => 'required|string',
                'author'            => 'required|string|max:255',
                'status'            => 'required|in:ongoing,completed,hiatus,cancelled',
                'type'              => 'required|in:manga,manhwa,manhua,webtoon',
                'cover_image'       => 'required|image|mimes:jpeg,png,jpg,webp,gif|max:5120',
                'genre_ids'         => 'required|string',
            ]);

            $coverUrl = $this->storage->uploadCover($request->file('cover_image'), $validated['title']);

            $manga = Manga::create([
                'title'             => $validated['title'],
                'alternative_title' => $validated['alternative_title'],
                'artist'            => $validated['artist'],
                'description'       => $validated['description'],
                'author'            => $validated['author'],
                'status'            => $validated['status'],
                'type'              => $validated['type'],
                'slug'              => Str::slug($validated['title']),
                'cover_image'       => $coverUrl,
                'user_id'           => 1,
            ]);

            $manga->genres()->sync(explode(',', $validated['genre_ids']));

            return response()->json(['message' => 'Manga berhasil ditambahkan', 'data' => $manga->load('genres')], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['message' => 'Validation failed', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error creating manga', 'error' => $e->getMessage()], 500);
        }
    }

    public function show(Manga $manga)
    {
        return response()->json($manga->load('genres'));
    }

    public function update(Request $request, Manga $manga)
    {
        try {
            $validated = $request->validate([
                'title'             => ['required', 'string', 'max:255', Rule::unique('mangas')->ignore($manga->id)],
                'alternative_title' => 'nullable|string|max:255',
                'artist'            => 'nullable|string|max:255',
                'description'       => 'required|string',
                'author'            => 'required|string|max:255',
                'status'            => 'required|in:ongoing,completed,hiatus,cancelled',
                'type'              => 'required|in:manga,manhwa,manhua,webtoon',
                'cover_image'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
                'genre_ids'         => 'required|string',
            ]);

            $data = $validated;
            $data['slug'] = Str::slug($validated['title']);

            if ($request->hasFile('cover_image')) {
                $this->storage->deleteCover($manga->cover_image);
                $data['cover_image'] = $this->storage->uploadCover($request->file('cover_image'), $validated['title']);
            }

            unset($data['genre_ids']);
            $manga->update($data);
            $manga->genres()->sync(explode(',', $validated['genre_ids']));

            return response()->json(['message' => 'Manga berhasil diperbarui', 'data' => $manga->load('genres')]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['message' => 'Validation failed', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error updating manga', 'error' => $e->getMessage()], 500);
        }
    }

    public function destroy(Manga $manga)
    {
        try {
            $this->storage->deleteCover($manga->cover_image);

            foreach ($manga->chapters as $chapter) {
                $images = $chapter->chapter_images ?? [];
                if (!empty($images)) {
                    $this->storage->deleteFiles('chapters', $images);
                }
            }

            $manga->delete();

            return response()->json(['message' => 'Manga berhasil dihapus']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error deleting manga', 'error' => $e->getMessage()], 500);
        }
    }

    public function genres()
    {
        return response()->json(Genre::all());
    }
}
