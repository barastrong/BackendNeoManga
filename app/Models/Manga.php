<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Manga extends Model
{
    use HasFactory;

    protected $table = 'mangas';

    protected $fillable = [
        'user_id',
        'title',
        'alternative_title',
        'slug',
        'description',
        'author',
        'artist',
        'status',
        'cover_image',
        'type',
    ];

    /**
     * Relasi: Manga memiliki banyak Genre (Many-to-Many)
     */
    public function genres()
    {
        return $this->belongsToMany(Genre::class, 'manga_genres');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

       public function histories()
    {
        return $this->hasMany(History::class);
    }

    /**
     * Relasi: Manga memiliki banyak Rating
     */
    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    /**
     * Relasi: Manga memiliki banyak Bookmark
     */
    public function bookmarks()
    {
        return $this->hasMany(Bookmark::class);
    }

    /**
     * Relasi: Manga memiliki banyak Chapter
     */
    public function chapters()
    {
        return $this->hasMany(Chapter::class);
    }

    /**
     * Accessor untuk mendapatkan jumlah followers (bookmarks)
     */
    public function getFollowersCountAttribute()
    {
        return $this->bookmarks()->count();
    }

    /**
     * Method untuk cek apakah manga sudah dibookmark oleh user tertentu
     */
    public function isBookmarkedBy($userId)
    {
        if (!$userId) return false;
        
        return $this->bookmarks()->where('user_id', $userId)->exists();
    }

public function comments()
{
    return $this->hasMany(Comment::class);
}

    public function latestPublishedChapter()
    {
        // Di sini kita memanggil scopePublished()
        return $this->hasOne(Chapter::class)->published()->latest('id');
    }
    
}