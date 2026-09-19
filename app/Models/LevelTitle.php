<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LevelTitle extends Model
{
    protected $table = 'level_titles';

    protected $primaryKey = 'level';

    public $incrementing = false;

    protected $keyType = 'int';

    protected $fillable = ['level', 'title'];

    public $timestamps = true;
}