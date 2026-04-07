<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Manga;
use App\Models\Genre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class MangaController extends Controller
{
    public function index()
    {
        $mangas = Manga::with('genres')->latest()->paginate(10);
        return response()->json($mangas);
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255|unique:mangas,title',
                'alternative_title' => 'nullable|string|max:1000',
                'artist' => 'nullable|string|max:255',
                'description' => 'required|string',
                'author' => 'required|string|max:255',
                'status' => 'required|in:ongoing,completed,hiatus,cancelled',
                'type' => 'required|in:manga,manhwa,manhua,webtoon',
                'cover_image' => 'required|image|mimes:jpeg,png,jpg,webp.gif|max:2048',
                'genre_ids' => 'required|string',
            ]);

            $genreIds = explode(',', $validated['genre_ids']);

            $title = $validated['title'];
            $slug = Str::slug($title);
            $imageFile = $request->file('cover_image');
            $imageName = $title . '.' . $imageFile->getClientOriginalExtension();
            $imagePath = $imageFile->storeAs('manga-covers', $imageName, 'public');

            $manga = Manga::create([
                'title' => $validated['title'],
                'alternative_title' => $validated['alternative_title'],
                'artist' => $validated['artist'],
                'description' => $validated['description'],
                'author' => $validated['author'],
                'status' => $validated['status'],
                'type' => $validated['type'],
                'slug' => $slug,
                'cover_image' => $imagePath,
                'user_id' => 1,
            ]);

            $manga->genres()->sync($genreIds);

            return response()->json([
                'message' => 'Manga berhasil ditambahkan',
                'data' => $manga->load('genres')
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error creating manga',
                'error' => $e->getMessage()
            ], 500);
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
                'title' => ['required', 'string', 'max:255', Rule::unique('mangas')->ignore($manga->id)],
                'alternative_title' => 'nullable|string|max:255',
                'artist' => 'nullable|string|max:255',
                'description' => 'required|string',
                'author' => 'required|string|max:255',
                'status' => 'required|in:ongoing,completed,hiatus,cancelled',
                'type' => 'required|in:manga,manhwa,manhua,webtoon',
                'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
                'genre_ids' => 'required|string',
            ]);

            $genreIds = explode(',', $validated['genre_ids']);

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

            unset($dataToUpdate['genre_ids']);
            $manga->update($dataToUpdate);
            $manga->genres()->sync($genreIds);

            return response()->json([
                'message' => 'Manga berhasil diperbarui',
                'data' => $manga->load('genres')
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error updating manga',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Manga $manga)
    {
        try {
            Storage::disk('public')->delete($manga->cover_image);
            Storage::disk('public')->deleteDirectory("chapters/{$manga->slug}");
            $manga->delete();

            return response()->json(['message' => 'Manga berhasil dihapus']);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error deleting manga',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function genres()
    {
        $genres = Genre::all();
        return response()->json($genres);
    }
}