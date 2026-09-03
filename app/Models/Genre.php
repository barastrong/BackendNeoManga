<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Genre extends Model
{
    use HasFactory;
    protected $table = 'genres';
    protected $fillable = [
        'name',
    ];

    public function mangas()
    {
        return $this->belongsToMany(Manga::class, 'manga_genres');
    }

    public function getMangaCountAttribute(): int
    {
        return $this->mangas()->count();
    }
}
