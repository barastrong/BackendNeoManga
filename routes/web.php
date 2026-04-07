<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BookmarkController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MangaController;
use App\Http\Controllers\ChapterController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\AdminPanelController;

Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [AdminPanelController::class, 'index'])->name('dashboard');
    Route::get('/search', [AdminPanelController::class, 'search'])->name('search');
    Route::get('/manga', [AdminPanelController::class, 'mangaIndex'])->name('manga.index');
    Route::get('/manga/create', [AdminPanelController::class, 'mangaCreate'])->name('manga.create');
    Route::post('/manga', [AdminPanelController::class, 'mangaStore'])->name('manga.store');
    Route::get('/manga/{manga}/edit', [AdminPanelController::class, 'mangaEdit'])->name('manga.edit');
    Route::put('/manga/{manga}', [AdminPanelController::class, 'mangaUpdate'])->name('manga.update');
    Route::delete('/manga/{manga}', [AdminPanelController::class, 'mangaDestroy'])->name('manga.destroy');
    Route::get('/users', [AdminPanelController::class, 'userIndex'])->name('user.index');
    Route::get('/manga/{manga}/chapters', [AdminPanelController::class, 'chapterIndex'])->name('manga.chapters.index');
    Route::get('/manga/{manga}/chapters/create', [AdminPanelController::class, 'chapterCreate'])->name('manga.chapters.create');
    Route::post('/manga/{manga}/chapters', [AdminPanelController::class, 'chapterStore'])->name('manga.chapters.store');
    Route::get('/manga/{manga}/chapters/{chapter}/edit', [AdminPanelController::class, 'chapterEdit'])->name('manga.chapters.edit');
    Route::put('/manga/{manga}/chapters/{chapter}', [AdminPanelController::class, 'chapterUpdate'])->name('manga.chapters.update');
    Route::delete('/manga/{manga}/chapters/{chapter}', [AdminPanelController::class, 'chapterDestroy'])->name('manga.chapters.destroy');
});

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/content/{slug}', [MangaController::class, 'show'])->name('manga.show');
Route::get('/chapter/{slug}', [ChapterController::class, 'show'])->name('chapter.show');
Route::get('/manga/{manga}/comments', [CommentController::class, 'getMangaComments'])->name('comments.manga');
Route::get('/chapter/{chapter}/comments', [CommentController::class, 'getChapterComments'])->name('comments.chapter');
Route::post('/comments', [CommentController::class, 'store'])->name('comments.store');
Route::post('/comments/{comment}/reply', [CommentController::class, 'reply'])->name('comments.reply');
Route::post('/comments/{comment}/like', [CommentController::class, 'toggleLike'])->name('comments.like');
Route::get('/manga', [MangaController::class, 'mangaList'])->name('manga.list');
Route::get('/bookmarks', [BookmarkController::class, 'index'])->name('bookmark.index');
Route::get('/history', [HistoryController::class, 'index'])->name('history.index');
Route::get('/search', [MangaController::class, 'search'])->name('manga.search');

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

require __DIR__.'/auth.php';