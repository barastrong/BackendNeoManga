<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'manga_id',
        'content',
        'parent_id',
        'likes_count', // Pastikan kolom ini bisa diisi
    ];

    /**
     * Relasi: Komentar ini milik satu User.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi: Komentar ini milik satu Manga.
     */
    public function manga(): BelongsTo
    {
        return $this->belongsTo(Manga::class);
    }

    /**
     * Relasi: Komentar ini punya banyak balasan (replies).
     */
    public function replies(): HasMany
    {
        return $this->hasMany(Comment::class, 'parent_id');
    }

    /**
     * Relasi: Komentar ini di-like oleh banyak User (Many-to-Many).
     * INI YANG HILANG DAN MENYEBABKAN ERROR.
     */
    public function likes(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'comment_likes');
    }

    /**
     * Cek apakah user yang sedang login sudah like komentar ini.
     * Like berbasis session (toggleLike di CommentController) — konsisten di sini.
     */
    public function isLikedBy(?int $userId = null): bool
    {
        $likedComments = session()->get('liked_comments', []);
        return in_array($this->id, $likedComments, true);
    }

    public function reports(): HasMany
    {
        return $this->hasMany(CommentReport::class);
    }
}