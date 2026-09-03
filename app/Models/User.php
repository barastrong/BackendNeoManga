<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\History;
use App\Models\CommentReport;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'photo_profile',
        'role',
        'otp_code',
        'otp_expires_at',
        'google_id',
        'banned_at'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified' => 'integer',
            'password' => 'hashed',
            'otp_expires_at' => 'datetime',
        ];
    }
    
    /**
     *
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isBanned(): bool
    {
        return $this->banned_at !== null;
    }

    public function scopeActive($q)
    {
        return $q->whereNull('banned_at');
    }

    public function scopeBanned($q)
    {
        return $q->whereNotNull('banned_at');
    }

    public function commentReports(): HasMany
    {
        return $this->hasMany(CommentReport::class, 'reporter_id');
    }

    public function comments(): HasMany 
    { 
        return $this->hasMany(Comment::class); 
    }
    public function bookmarks(): HasMany
    {
        return $this->hasMany(Bookmark::class);
    }
    public function histories(): HasMany
    {
        return $this->hasMany(History::class);
    }
    public function likedComments(): BelongsToMany
    {
        return $this->belongsToMany(Comment::class, 'comment_likes');
    }
}
