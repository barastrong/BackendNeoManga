<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MangaView extends Model
{
    protected $fillable = ['manga_id', 'user_id', 'view_date', 'period'];

    public function manga(): BelongsTo
    {
        return $this->belongsTo(Manga::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
