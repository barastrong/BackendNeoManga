<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserTag extends Model
{
    protected $fillable = [
        'name', 'icon', 'color', 'requirement_type', 'requirement_value',
        'description', 'is_active', 'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}