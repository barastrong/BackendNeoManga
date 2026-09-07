<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Chapter;
use App\Models\Manga;
use App\Services\CloudinaryStorageService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ChapterController extends Controller
{
    public function __construct(private CloudinaryStorageService $storage) {}

    // ===== Per-manga (dari halaman kelola manga) =====

    public function index(Manga $manga)
    {
        $chapters = $manga->chapters()->latest('created_at')->paginate(20);
        return view('admin.chapter.index', compact('manga', 'chapters'));
    }

    public function create(Manga $manga)
    {
        return view('admin.chapter.create', compact('manga'));
    }

    public function store(Request $request, Manga $manga)
    {
        $validated = $request->validate([
            'number' => ['required', 'numeric', Rule::unique('chapters')->where('manga_id', $manga->id)],
            'status' => 'required|in:draft,published',
            'chapter_images' => 'required|array',
            'chapter_images.*' => 'image|mimes:jpeg,png,jpg,webp',
        ]);

        $imagePaths = $this->storage->uploadChapterImages(
            $request->file('chapter_images'),
            $manga->slug,
            $validated['number']
        );

        $manga->chapters()->create([
            'number'         => $validated['number'],
            'status'         => $validated['status'],
            'chapter_images' => $imagePaths,
        ]);

        return redirect()->route('admin.manga.chapters.index', $manga)
            ->with('success', "Chapter {$validated['number']} berhasil ditambahkan.");
    }

    public function edit(Manga $manga, Chapter $chapter)
    {
        return view('admin.chapter.edit', compact('manga', 'chapter'));
    }

    public function update(Request $request, Manga $manga, Chapter $chapter)
    {
        $validated = $request->validate([
            'number' => ['required', 'numeric', Rule::unique('chapters')->where('manga_id', $manga->id)->ignore($chapter->id)],
            'status' => 'required|in:draft,published',
            'chapter_images' => 'nullable|array',
            'chapter_images.*' => 'image|mimes:jpeg,png,jpg,webp',
        ]);

        $dataToUpdate = [
            'number' => $validated['number'],
            'status' => $validated['status'],
        ];

        if ($request->hasFile('chapter_images')) {
            // URL chapter_images asli = Cloudinary (res.cloudinary.com/...).
            // Kirim URL mentah langsung; CloudinaryStorageService::deleteFiles
            // yang parse public_id-nya.
            $oldUrls = $chapter->chapter_images ?? [];
            $this->storage->deleteFiles('chapters', array_filter($oldUrls));

            $dataToUpdate['chapter_images'] = $this->storage->uploadChapterImages(
                $request->file('chapter_images'),
                $manga->slug,
                $validated['number']
            );
        }

        $chapter->update($dataToUpdate);

        return redirect()->route('admin.manga.chapters.index', $manga)
            ->with('success', "Chapter {$chapter->number} berhasil diperbarui.");
    }

    public function destroy(Manga $manga, Chapter $chapter)
    {
        // Hapus file Cloudinary dulu (URL mentah, service yang parse public_id),
        // baru hapus record DB.
        $urls = $chapter->chapter_images ?? [];
        $this->storage->deleteFiles('chapters', array_filter($urls));

        $chapter->delete();

        return redirect()->route('admin.manga.chapters.index', $manga)
            ->with('success', "Chapter {$chapter->number} berhasil dihapus.");
    }

    // ===== Global (dari menu Chapter di sidebar) =====

    public function globalIndex(Request $request)
    {
        $query = Chapter::with('manga');

        if ($request->has('search') && !empty($request->search)) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('number', 'LIKE', "%{$s}%")
                    ->orWhereHas('manga', fn ($qm) => $qm->where('title', 'LIKE', "%{$s}%"));
            });
        }

        if ($request->has('status') && in_array($request->status, ['draft', 'published'])) {
            $query->where('status', $request->status);
        }

        $chapters = $query->latest('created_at')->paginate(15)->withQueryString();

        $stats = [
            'total'     => Chapter::count(),
            'published' => Chapter::where('status', 'published')->count(),
            'draft'     => Chapter::where('status', 'draft')->count(),
            'manga'     => Chapter::distinct('manga_id')->count('manga_id'),
        ];

        return view('admin.chapter.global', compact('chapters', 'stats'));
    }

    public function globalEdit(Chapter $chapter)
    {
        $manga = $chapter->manga;
        return view('admin.chapter.edit', compact('manga', 'chapter'));
    }

    public function globalUpdate(Request $request, Chapter $chapter)
    {
        $manga = $chapter->manga;
        return $this->update($request, $manga, $chapter);
    }

    public function globalDestroy(Chapter $chapter)
    {
        $manga = $chapter->manga;

        // Hapus file Cloudinary dulu, baru record DB.
        $urls = $chapter->chapter_images ?? [];
        $this->storage->deleteFiles('chapters', array_filter($urls));
        $chapter->delete();

        // Tetap di halaman global (admin.chapter.index), bukan per-manga.
        return redirect()->route('admin.chapter.index')
            ->with('success', "Chapter {$chapter->number} dari \"{$manga->title}\" berhasil dihapus.");
    }
}
