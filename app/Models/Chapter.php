<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Chapter extends Model
{
    use HasFactory;

    protected $fillable = [
        'manga_id',
        'slug',
        'number',
        'status',
        'chapter_images',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'chapter_images' => 'array',
    ];

    /**
     * Boot method untuk auto-generate slug
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($chapter) {
            if (empty($chapter->slug) && $chapter->manga && $chapter->number) {
                $chapter->slug = Str::slug($chapter->manga->title . '-chapter-' . $chapter->number);
            }
        });
    }

    /**
     * Relasi: Chapter milik satu Manga
     */
    public function manga()
    {
        return $this->belongsTo(Manga::class);
    }

    /**
     * Scope untuk filter berdasarkan status
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope untuk published chapters
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    /**
     * Scope untuk draft chapters
     */
    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    private function getChapterImagesArray()
    {
        $images = $this->chapter_images;
        
        if (is_string($images)) {
            $images = json_decode($images, true);
        }
        
        return is_array($images) ? $images : [];
    }

    public function getCoverImageUrlAttribute()
    {
        $images = $this->getChapterImagesArray();

        if (!empty($images)) {
            $imagePath = $images[0];
            
            if (str_starts_with($imagePath, 'storage/')) {
                return asset($imagePath);
            } else {
                return asset('storage/' . $imagePath);
            }
        }

        return asset('images/no-image.png');
    }

    public function getImageUrlsAttribute()
    {
        $images = $this->getChapterImagesArray();

        if (!empty($images)) {
            return array_map(function ($image) {
                if (str_starts_with($image, 'storage/')) {
                    return asset($image);
                } else {
                    return asset('storage/' . $image);
                }
            }, $images);
        }

        return [];
    }

    public function getImageCountAttribute()
    {
        $images = $this->getChapterImagesArray();
        return count($images);
    }

    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            'draft' => 'Draft',
            'published' => 'Published',
            'fixed' => 'Fixed',
            default => 'Not Set'
        };
    }

       public function latestPublishedChapter()
    {
        return $this->hasOne(Chapter::class)->ofMany([
            'created_at' => 'max',
        ], function ($query) {
            $query->where('status', 'published');
        });
    }

    public function getFirstImageAttribute()
    {
        $images = $this->getChapterImagesArray();
        
        if (!empty($images)) {
            return $images[0];
        }
        
        return null;
    }

    public function getFirstImageUrlAttribute()
    {
        $images = $this->getChapterImagesArray();
        
        if (!empty($images)) {
            $imagePath = $images[0];
            
            if (str_starts_with($imagePath, 'storage/')) {
                return asset($imagePath);
            } else {
                return asset('storage/' . $imagePath);
            }
        }
        
        return asset('images/no-image.png');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
    

}