<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    use HasFactory;

    protected $table = 'ratings'; 

    protected $fillable = [
        'user_id',
        'manga_id',
        'rating',
    ];

    /**
     * Relasi: Rating milik satu User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi: Rating milik satu Manga
     */
    public function manga()
    {
        return $this->belongsTo(Manga::class);
    }
}
