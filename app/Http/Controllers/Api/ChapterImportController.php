<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Manga;
use App\Models\Chapter;
use App\Models\Genre;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Services\CloudinaryStorageService;

class ChapterImportController extends Controller
{
    public function __construct(private CloudinaryStorageService $storage) {}
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'manga_slug' => 'required|string|max:255',
            'chapter_number' => 'required|string|max:50',
            'images' => 'required|array',
            'images.*' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $manga = Manga::where('slug', $request->manga_slug)->first();

            if (!$manga) {
                return response()->json(['message' => 'Manga not found'], 404);
            }

            if (Chapter::where('manga_id', $manga->id)->where('number', $request->chapter_number)->exists()) {
                return response()->json(['message' => 'Chapter ' . $request->chapter_number . ' already exists.'], 200);
            }

            $imagePaths = $this->storage->uploadChapterImages(
                $request->file('images'),
                $manga->slug,
                $request->chapter_number
            );

            Chapter::create([
                'manga_id' => $manga->id,
                'number' => $request->chapter_number,
                'slug' => Str::random(10), // Slug unik untuk setiap chapter
                'chapter_images' => $imagePaths,
                'status' => 'published',
            ]);
            
            return response()->json([
                'message' => 'Chapter ' . $request->chapter_number . ' imported successfully for manga: ' . $manga->title,
            ], 201);

        } catch (\Exception $e) {
            return response()->json(['message' => 'An server error occurred.', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Import manga dari scraper Kiryuu (cover via URL).
     * Syarat genre: array nama genre -> match/auto-create ke tabel genres.
     */
    public function importManga(Request $request)
    {
        try {
            $validated = $request->validate([
                'title'             => 'required|string|max:255',
                'alternative_title' => 'nullable|string|max:255',
                'slug'              => 'required|string|max:255',
                'synopsis'          => 'nullable|string',
                'cover_image'       => 'nullable|url|max:1000',
                'genres'            => 'nullable|array',
                'genres.*'          => 'string|max:100',
                'status'            => 'nullable|string|max:50',
                'type'              => 'nullable|string|max:50',
            ]);

            if (Manga::where('slug', $validated['slug'])->exists()) {
                return response()->json(['message' => 'Manga already exists', 'slug' => $validated['slug']], 200);
            }

            $status = strtolower(trim($validated['status'] ?? ''));
            $statusMap = ['ongoing' => 'ongoing', 'on-going' => 'ongoing', 'completed' => 'completed', 'hiatus' => 'hiatus', 'canceled' => 'cancelled', 'cancelled' => 'cancelled', 'discontinued' => 'cancelled'];
            $status = $statusMap[$status] ?? 'ongoing';

            $type = strtolower(trim($validated['type'] ?? ''));
            $type = in_array($type, ['manga', 'manhwa', 'manhua', 'webtoon']) ? $type : 'manga';

            $coverUrl = null;
            if (!empty($validated['cover_image'])) {
                try {
                    $coverUrl = $this->storage->uploadFromUrl('manga-covers', $validated['slug'], $validated['cover_image']);
                } catch (\Exception $e) {
                    $coverUrl = null;
                }
            }

            $manga = Manga::create([
                'user_id'          => 1,
                'title'            => $validated['title'],
                'alternative_title'=> $validated['alternative_title'] ?? '',
                'slug'             => $validated['slug'],
                'description'      => $validated['synopsis'] ?: ('Baca ' . $validated['title'] . ' bahasa Indonesia di NeoManga.'),
                'author'           => null,
                'status'           => $status,
                'type'             => $type,
                'cover_image'      => $coverUrl,
            ]);

            // Attach genres: match by name (case-insensitive), auto-create if missing
            $genreIds = [];
            foreach ($validated['genres'] ?? [] as $genreName) {
                $genreName = trim($genreName);
                if ($genreName === '') continue;
                $genre = Genre::firstOrCreate(
                    ['name' => $genreName],
                    ['name' => $genreName]
                );
                $genreIds[] = $genre->id;
            }
            if ($genreIds) $manga->genres()->sync($genreIds);

            return response()->json([
                'message' => 'Manga imported successfully',
                'data' => $manga->load('genres'),
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['message' => 'Validation failed', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error importing manga', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     *
     * @param string $slug
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkMangaExists($slug)
    {
    $manga = Manga::where('slug', $slug)->first();
    if (!$manga) {
        return response()->json(['exists' => false]);
    }
    
    // Ambil SEMUA nomor chapter tanpa kecuali
    $existingChapters = $manga->chapters()
                              ->pluck('number')
                              ->map(function($num) {
                                  // Hilangkan .00 atau spasi, contoh "1425.00" jadi "1425"
                                  return (string)($num + 0); 
                              })
                              ->toArray();

    $latestChapterNumber = $manga->chapters()
                                 ->selectRaw('MAX(CAST(number AS DECIMAL(10,2))) as max_number')
                                 ->value('max_number');

    return response()->json([
        'exists' => true,
        'latest_chapter' => $latestChapterNumber,
        'existing_list' => $existingChapters 
    ]);
    }
}