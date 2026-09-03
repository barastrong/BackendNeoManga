<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Chapter;
use App\Models\Comment;
use App\Models\CommentReport;
use App\Models\Genre;
use App\Models\Manga;
use App\Models\User;
use App\Services\CloudinaryStorageService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class AdminPanelController extends Controller
{
    public function __construct(private CloudinaryStorageService $storage) {}

    public function index()
    {
        $mangaCount = Manga::count();
        $chapterCount = Chapter::count();
        $userCount = User::count();
        $latestMangas = Manga::latest()->take(5)->get();
        $viewCount = \App\Models\MangaView::count();
        $commentCount = \App\Models\Comment::count();

        // Grafik view harian — 30 hari terakhir (area chart + filter 7/14/30)
        $viewsDaily = \App\Models\MangaView::where('view_date', '>=', now()->subDays(29)->toDateString())
            ->selectRaw('view_date, COUNT(*) as total')
            ->groupBy('view_date')
            ->orderBy('view_date')
            ->pluck('total', 'view_date');

        $chartData = collect(range(29, 0))->map(fn ($i) => [
            'date'  => now()->subDays($i)->toDateString(),
            'label' => now()->subDays($i)->locale('id')->isoFormat('dd'),
            'total' => (int) ($viewsDaily[now()->subDays($i)->toDateString()] ?? 0),
        ]);
        $chartMax = max(1, $chartData->max('total'));

        $viewsToday = (int) ($viewsDaily[now()->toDateString()] ?? 0);
        $viewsYesterday = (int) ($viewsDaily[now()->subDay()->toDateString()] ?? 0);
        $viewsWeek  = \App\Models\MangaView::where('view_date', '>=', now()->startOfWeek()->toDateString())->count();
        $viewsMonth = \App\Models\MangaView::where('view_date', '>=', now()->startOfMonth()->toDateString())->count();
        $views30d   = $chartData->sum('total');
        $viewDelta  = $viewsToday - $viewsYesterday;

        // Rilis chapter terbaru (untuk tabel log upload)
        $recentChapters = Chapter::with('manga:id,title,slug,cover_image,type,user_id', 'manga.user:id,name')
            ->latest()
            ->take(6)
            ->get();

        // Distribusi genre (top 5 berdasar jumlah manga per genre)
        $genreDistribution = \Illuminate\Support\Facades\DB::table('manga_genres')
            ->join('genres', 'manga_genres.genre_id', '=', 'genres.id')
            ->select('genres.id', 'genres.name', \Illuminate\Support\Facades\DB::raw('COUNT(*) as total'))
            ->groupBy('genres.id', 'genres.name')
            ->orderByDesc('total')
            ->take(5)
            ->get();
        $genreMax = max(1, $genreDistribution->max('total'));

        // Komentar terbaru (feed moderasi ringan)
        $recentComments = \App\Models\Comment::with('user:id,name', 'manga:id,title,slug')
            ->latest()
            ->take(4)
            ->get();

        // Statistik sekunder & trend
        $bookmarkCount = \App\Models\Bookmark::count();
        $mangasThisMonth = Manga::where('created_at', '>=', now()->startOfMonth())->count();
        $chaptersToday   = Chapter::where('created_at', '>=', now()->startOfDay())->count();
        $usersThisMonth  = User::where('created_at', '>=', now()->startOfMonth())->count();

        return view('admin.dashboard', compact(
            'mangaCount', 'chapterCount', 'userCount', 'latestMangas', 'viewCount', 'commentCount',
            'chartData', 'chartMax', 'viewsToday', 'viewsYesterday', 'viewsWeek', 'viewsMonth', 'views30d', 'viewDelta',
            'recentChapters', 'genreDistribution', 'genreMax', 'recentComments',
            'bookmarkCount', 'mangasThisMonth', 'chaptersToday', 'usersThisMonth'
        ));
    }

    public function mangaIndex(Request $request)
    {
        $query = Manga::with('genres')->withCount('chapters');
        
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                  ->orWhere('author', 'LIKE', "%{$search}%")
                  ->orWhere('artist', 'LIKE', "%{$search}%");
            });
        }
        
        $mangas = $query->latest()->paginate(10);

        // Stats untuk header grid
        $stats = [
            'manga'     => \App\Models\Manga::count(),
            'chapters'  => \App\Models\Chapter::count(),
            'ongoing'   => \App\Models\Manga::where('status', 'ongoing')->count(),
            'completed' => \App\Models\Manga::where('status', 'completed')->count(),
        ];

        return view('admin.manga.index', compact('mangas', 'stats'));
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
        $slug  = Str::slug($title);

        $dataToStore = $validated;
        $dataToStore['slug']        = $slug;
        $dataToStore['cover_image'] = $this->storage->uploadCover($request->file('cover_image'), $title);
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
            $this->storage->deleteCover($manga->cover_image);
            $dataToUpdate['cover_image'] = $this->storage->uploadCover($request->file('cover_image'), $validated['title']);
        }

        $manga->update($dataToUpdate);
        $manga->genres()->sync($validated['genres']);
        return redirect()->route('admin.manga.index')->with('success', 'Manga berhasil diperbarui.');
    }

    public function mangaDestroy(Manga $manga)
    {
        $this->storage->deleteCover($manga->cover_image);
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
            'chapter_images.*' => 'image|mimes:jpeg,png,jpg,webp',
        ]);

        $dataToUpdate = [
            'number' => $validated['number'],
            'status' => $validated['status'],
        ];

        if ($request->hasFile('chapter_images')) {
            $oldPaths = array_map(fn($url) => $this->pathFromUrl($url, 'chapters'), $chapter->chapter_images ?? []);
            $this->storage->deleteFiles('chapters', array_filter($oldPaths));

            $dataToUpdate['chapter_images'] = $this->storage->uploadChapterImages(
                $request->file('chapter_images'),
                $manga->slug,
                $validated['number']
            );
        }

        $chapter->update($dataToUpdate);

        return redirect()->route('admin.manga.chapters.index', $manga)->with('success', "Chapter {$chapter->number} berhasil diperbarui.");
    }

    public function chapterDestroy(Manga $manga, Chapter $chapter)
    {
        $paths = array_map(fn($url) => $this->pathFromUrl($url, 'chapters'), $chapter->chapter_images ?? []);
        $this->storage->deleteFiles('chapters', array_filter($paths));

        $chapter->delete();

        return redirect()->route('admin.manga.chapters.index', $manga)->with('success', "Chapter {$chapter->number} berhasil dihapus.");
    }

    private function pathFromUrl(string $url, string $bucket): ?string
    {
        $base = rtrim(env('SUPABASE_URL'), '/') . "/storage/v1/object/public/{$bucket}/";
        return str_starts_with($url, $base) ? substr($url, strlen($base)) : null;
    }

    public function userIndex()
    {
        $users = User::latest()->paginate(15);
        return view('admin.user.index', compact('users'));
    }

    // ===== Moderasi Komentar =====

    public function moderationIndex(Request $request)
    {
        $tab = $request->get('tab', 'reports');

        // KPI
        $pendingCount = \App\Models\CommentReport::where('status', 'pending')->count();
        $bannedUsers  = User::banned()->count();
        $userCount    = User::count();
        $commentCount = \App\Models\Comment::count();
        $resolved24h  = \App\Models\CommentReport::where('status', 'resolved')
            ->where('handled_at', '>=', now()->subDay())->count();

        // Antrean report (komentar kena report, dedupe, status pending)
        $reports = \App\Models\CommentReport::with(['comment.user', 'comment.manga:id,title,slug', 'reporter:id,name'])
            ->where('status', 'pending')
            ->latest()
            ->get()
            ->groupBy('comment_id')
            ->map(function ($group) {
                $first = $group->first();
                return (object) [
                    'comment'       => $first->comment,
                    'reasons'       => $group->pluck('reason')->unique()->values(),
                    'reporterCount' => $group->count(),
                    'latest'        => $first->created_at,
                ];
            })
            ->values();

        // Semua komentar terbaru (tab Komentar) — termasuk yang ga kena report
        $comments = Comment::with(['user:id,name,photo_profile,role,banned_at', 'manga:id,title,slug'])
            ->withCount('reports')
            ->latest()
            ->take(100)
            ->get();

        // User terbaru (tab Pengguna)
        $users = User::withCount('comments')->latest()->take(50)->get();

        return view('admin.moderation.index', compact(
            'tab', 'pendingCount', 'bannedUsers', 'userCount', 'commentCount', 'resolved24h',
            'reports', 'comments', 'users'
        ));
    }

    public function moderationAction(Request $request, Comment $comment)
    {
        $validated = $request->validate([
            'action'    => 'required|in:delete,delete_ban,ban,resolve',
            'comment_id' => 'sometimes',
        ]);

        $reports = \App\Models\CommentReport::where('comment_id', $comment->id)->where('status', 'pending')->get();

        switch ($validated['action']) {
            case 'delete':
                // Hapus komentar + balasannya
                $comment->replies()->delete();
                $comment->delete();
                break;

            case 'delete_ban':
                $comment->user?->update(['banned_at' => now()]);
                $comment->replies()->delete();
                $comment->delete();
                break;

            case 'ban':
                $comment->user?->update(['banned_at' => now()]);
                break;

            case 'resolve':
                // Abaikan report, komentar tetap
                break;
        }

        // Tutup semua report pending untuk komentar ini
        \App\Models\CommentReport::where('comment_id', $comment->id)->where('status', 'pending')
            ->update([
                'status'     => $validated['action'] === 'resolve' ? 'dismissed' : 'resolved',
                'handled_by' => auth()->id(),
                'handled_at' => now(),
            ]);

        return back()->with('success', 'Aksi moderasi "' . $validated['action'] . '" diterapkan.');
    }

    public function moderationUserAction(Request $request, User $user)
    {
        $validated = $request->validate([
            'action' => 'required|in:ban,unban',
        ]);

        $user->update(['banned_at' => $validated['action'] === 'ban' ? now() : null]);

        return back()->with('success', 'Pengguna ' . ($validated['action'] === 'ban' ? 'di-ban' : 'di-unban') . '.');
    }

    // ===== Kelola Kategori (Genre) =====

    public function categoryIndex(Request $request)
    {
        $query = Genre::withCount('mangas');

        if ($request->has('search') && !empty($request->search)) {
            $query->where('name', 'LIKE', "%{$request->search}%");
        }

        $genres = $query->orderBy('name')->paginate(21);
        return view('admin.category.index', compact('genres'));
    }

    public function categoryStore(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:60|unique:genres,name',
        ]);

        Genre::create(['name' => trim($validated['name'])]);

        return back()->with('success', "Kategori \"{$validated['name']}\" berhasil ditambahkan.");
    }

    public function categoryUpdate(Request $request, Genre $genre)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:60', Rule::unique('genres', 'name')->ignore($genre->id)],
        ]);

        $genre->update(['name' => trim($validated['name'])]);

        return back()->with('success', "Kategori diubah menjadi \"{$validated['name']}\".");
    }

    public function categoryDestroy(Genre $genre)
    {
        $name = $genre->name;
        $genre->mangas()->detach(); // lepas relasi dulu, jangan hapus manga
        $genre->delete();

        return back()->with('success', "Kategori \"{$name}\" berhasil dihapus.");
    }

    // ===== Kelola Chapter (Global) =====

    public function chaptersIndex(Request $request)
    {
        $query = Chapter::with('manga');

        if ($request->has('search') && !empty($request->search)) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('number', 'LIKE', "%{$s}%")
                  ->orWhereHas('manga', fn($qm) => $qm->where('title', 'LIKE', "%{$s}%"));
            });
        }

        if ($request->has('status') && in_array($request->status, ['draft', 'published'])) {
            $query->where('status', $request->status);
        }

        $chapters = $query->latest('created_at')->paginate(15)->withQueryString();

        $stats = [
            'total'     => \App\Models\Chapter::count(),
            'published' => \App\Models\Chapter::where('status', 'published')->count(),
            'draft'     => \App\Models\Chapter::where('status', 'draft')->count(),
            'manga'     => \App\Models\Chapter::distinct('manga_id')->count('manga_id'),
        ];

        return view('admin.chapter.global', compact('chapters', 'stats'));
    }

    public function chapterGlobalEdit(Chapter $chapter)
    {
        $manga = $chapter->manga;
        return view('admin.chapter.edit', compact('manga', 'chapter'));
    }

    public function chapterGlobalUpdate(Request $request, Chapter $chapter)
    {
        $manga = $chapter->manga;
        return $this->chapterUpdate($request, $manga, $chapter);
    }

    public function chapterGlobalDestroy(Chapter $chapter)
    {
        $manga = $chapter->manga;
        return $this->chapterDestroy($manga, $chapter);
    }
}