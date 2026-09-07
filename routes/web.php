<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BookmarkController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MangaController;
use App\Http\Controllers\ChapterController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ChapterController as AdminChapterController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\MangaController as AdminMangaController;
use App\Http\Controllers\Admin\ModerationController;
use App\Http\Controllers\Admin\UserController as AdminUserController;

// ===== Panel Admin (auth + role admin) =====
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Manga
    Route::get('/manga', [AdminMangaController::class, 'index'])->name('manga.index');
    Route::get('/manga/create', [AdminMangaController::class, 'create'])->name('manga.create');
    Route::post('/manga', [AdminMangaController::class, 'store'])->name('manga.store');
    Route::get('/manga/{manga}/edit', [AdminMangaController::class, 'edit'])->name('manga.edit');
    Route::put('/manga/{manga}', [AdminMangaController::class, 'update'])->name('manga.update');
    Route::delete('/manga/{manga}', [AdminMangaController::class, 'destroy'])->name('manga.destroy');

    // Chapter per-manga
    Route::get('/manga/{manga}/chapters', [AdminChapterController::class, 'index'])->name('manga.chapters.index');
    Route::get('/manga/{manga}/chapters/create', [AdminChapterController::class, 'create'])->name('manga.chapters.create');
    Route::post('/manga/{manga}/chapters', [AdminChapterController::class, 'store'])->name('manga.chapters.store');
    Route::get('/manga/{manga}/chapters/{chapter}/edit', [AdminChapterController::class, 'edit'])->name('manga.chapters.edit');
    Route::put('/manga/{manga}/chapters/{chapter}', [AdminChapterController::class, 'update'])->name('manga.chapters.update');
    Route::delete('/manga/{manga}/chapters/{chapter}', [AdminChapterController::class, 'destroy'])->name('manga.chapters.destroy');

    // Chapter global
    Route::get('/chapters', [AdminChapterController::class, 'globalIndex'])->name('chapter.index');
    Route::get('/chapters/{chapter}/edit', [AdminChapterController::class, 'globalEdit'])->name('chapter.edit');
    Route::put('/chapters/{chapter}', [AdminChapterController::class, 'globalUpdate'])->name('chapter.update');
    Route::delete('/chapters/{chapter}', [AdminChapterController::class, 'globalDestroy'])->name('chapter.destroy');

    // Kategori
    Route::get('/categories', [CategoryController::class, 'index'])->name('category.index');
    Route::post('/categories', [CategoryController::class, 'store'])->name('category.store');
    Route::put('/categories/{genre}', [CategoryController::class, 'update'])->name('category.update');
    Route::delete('/categories/{genre}', [CategoryController::class, 'destroy'])->name('category.destroy');

    // Users & Moderasi
    Route::get('/users', [AdminUserController::class, 'index'])->name('user.index');
    Route::get('/moderasi', [ModerationController::class, 'index'])->name('moderation.index');
    Route::post('/moderasi/komentar/{comment}', [ModerationController::class, 'action'])->name('moderation.action');
    Route::post('/moderasi/user/{user}', [ModerationController::class, 'userAction'])->name('moderation.user');
});

// ===== Frontend publik =====
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/manga', [MangaController::class, 'mangaList'])->name('manga.list');
Route::get('/search', [MangaController::class, 'search'])->name('manga.search');
Route::get('/content/{slug}', [MangaController::class, 'show'])->name('manga.show');
Route::get('/chapter/{slug}', [ChapterController::class, 'show'])->name('chapter.show');

// Komentar publik (GET daftar)
Route::get('/manga/{manga}/comments', [CommentController::class, 'getMangaComments'])->name('comments.manga');
Route::get('/chapter/{chapter}/comments', [CommentController::class, 'getChapterComments'])->name('comments.chapter');

// Bookmark & History (auth)
Route::get('/bookmarks', [BookmarkController::class, 'index'])->name('bookmark.index');
Route::get('/history', [HistoryController::class, 'index'])->name('history.index');

// ===== Auth + data pribadi =====
Route::middleware('auth', 'verified')->group(function () {
    Route::post('/bookmark/toggle/{manga}', [BookmarkController::class, 'toggle'])->name('bookmark.toggle');
    Route::delete('/bookmark/{bookmark}', [BookmarkController::class, 'destroy'])->name('bookmark.destroy');
    Route::get('profile/show', [ProfileController::class, 'show'])->name('user.profile');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
    Route::delete('/history/{history}', [HistoryController::class, 'destroy'])->name('history.destroy');
    Route::delete('/history', [HistoryController::class, 'clear'])->name('history.clear');
    Route::post('/manga/{mangaId}/history/reset', [HistoryController::class, 'resetForManga'])->name('history.resetForManga');
});

// Komentar aksi (auth)
Route::post('/comments', [CommentController::class, 'store'])->name('comments.store');
Route::post('/comments/{comment}/reply', [CommentController::class, 'reply'])->name('comments.reply');
Route::post('/comments/{comment}/like', [CommentController::class, 'toggleLike'])->name('comments.like');

// ===== SEO: sitemap / robots / llms =====
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');
Route::get('/robots.txt', [SitemapController::class, 'robots'])->name('robots');
Route::get('/llms.txt', [SitemapController::class, 'llms'])->name('llms');

require __DIR__.'/auth.php';
