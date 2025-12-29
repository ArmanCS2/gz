<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Helpers\SlugHelper;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'icon',
        'order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = static::generateUniqueSlug($category->name);
            }
        });

        static::updating(function ($category) {
            // If name changed, regenerate slug
            if ($category->isDirty('name')) {
                $category->slug = static::generateUniqueSlug($category->name, $category->id);
            }
        });
    }

    /**
     * Generate unique slug for category
     */
    protected static function generateUniqueSlug($name, $excludeId = null)
    {
        // Use SlugHelper for better Persian text handling
        $baseSlug = SlugHelper::persianSlug($name);
        
        // If slug is empty or just 'ad' (default), create a better slug
        if (empty($baseSlug) || $baseSlug === 'ad') {
            $baseSlug = 'category-' . time();
        }
        
        $slug = $baseSlug;
        $counter = 1;
        
        while (static::where('slug', $slug)
            ->when($excludeId, function ($query) use ($excludeId) {
                $query->where('id', '!=', $excludeId);
            })
            ->exists()) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }
        
        return $slug;
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function publishedPosts()
    {
        return $this->hasMany(Post::class)->where('status', 'published')
            ->where('published_at', '<=', now());
    }

    public function ads()
    {
        return $this->hasMany(Ad::class);
    }

    public function activeAds()
    {
        // فقط آگهی‌هایی که منقضی نشده‌اند
        return $this->hasMany(Ad::class)
            ->where('status', 'active')
            ->where('is_active', true)
            ->where(function($q) {
                // آگهی‌هایی که expire_at ندارند یا expire_at آنها در آینده است
                $q->whereNull('expire_at')
                  ->orWhere('expire_at', '>', now());
            });
    }
}


