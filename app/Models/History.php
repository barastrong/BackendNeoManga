<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'manga_id',
        'chapter_id',
    ];

    /**
     * Relasi: History milik satu User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi: History milik satu Manga
     */
    public function manga()
    {
        return $this->belongsTo(Manga::class);
    }

    /**
     * Relasi: History milik satu Chapter
     */
    public function chapter()
    {
        return $this->belongsTo(Chapter::class);
    }
}
