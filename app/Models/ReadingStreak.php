<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReadingStreak extends Model
{
    protected $fillable = ['user_id', 'current_streak', 'longest_streak', 'last_active_date'];

    protected $casts = [
        'last_active_date' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}