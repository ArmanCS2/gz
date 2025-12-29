<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use App\Helpers\SlugHelper;

class Ad extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'slug',
        'description',
        'telegram_link',
        'telegram_id',
        'member_count',
        'construction_year',
        'type',
        'ad_type',
        'ad_extra',
        'price',
        'base_price',
        'current_bid',
        'auction_end_time',
        'is_active',
        'paid_at',
        'expire_at',
        'show_contact',
        'status',
    ];
    
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($ad) {
            if (empty($ad->slug)) {
                $ad->slug = static::generateUniqueSlug($ad->title);
            }
            // Set default ad_type to 'telegram' if not set
            if (empty($ad->ad_type)) {
                $ad->ad_type = 'telegram';
            }
        });
        
        static::updating(function ($ad) {
            if ($ad->isDirty('title')) {
                $ad->slug = static::generateUniqueSlug($ad->title, $ad->id);
            }
        });
    }
    
    /**
     * Generate unique Persian slug
     */
    protected static function generateUniqueSlug($title, $excludeId = null)
    {
        $baseSlug = SlugHelper::persianSlug($title);
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
    
    public function getRouteKeyName()
    {
        return 'slug';
    }

    protected function casts(): array
    {
        return [
            'member_count' => 'integer',
            'construction_year' => 'integer',
            'price' => 'decimal:2',
            'base_price' => 'decimal:2',
            'current_bid' => 'decimal:2',
            'auction_end_time' => 'datetime',
            'is_active' => 'boolean',
            'paid_at' => 'datetime',
            'expire_at' => 'datetime',
            'show_contact' => 'boolean',
            'ad_extra' => 'array',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function images()
    {
        return $this->hasMany(AdImage::class);
    }

    /**
     * Get the cover image (first image) as public asset URL
     * SINGLE SOURCE OF TRUTH for image access
     * Returns null if no image exists
     */
    public function getCoverImageAttribute()
    {
        // Ensure images are loaded
        if (!$this->relationLoaded('images')) {
            $this->load('images');
        }
        
        $firstImage = $this->images->first();
        if ($firstImage && !empty($firstImage->image)) {
            return asset(ltrim($firstImage->image, '/'));
        }
        
        return null;
    }

    /**
     * Get all images for this ad
     * Used only for single ad page gallery
     */
    public function getAllImages()
    {
        // Ensure images are loaded
        if (!$this->relationLoaded('images')) {
            $this->load('images');
        }
        
        return $this->images->filter(function($image) {
            return !empty($image->image);
        })->map(function($image) {
            return asset(ltrim($image->image, '/'));
        });
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function bids()
    {
        return $this->hasMany(Bid::class)->orderBy('amount', 'desc');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function approvedReviews()
    {
        return $this->hasMany(Review::class)->where('status', 'approved');
    }

    // محاسبه میانگین امتیاز
    public function getAverageRatingAttribute()
    {
        $approvedReviews = $this->approvedReviews;
        if ($approvedReviews->count() === 0) {
            return 0;
        }
        return round($approvedReviews->avg('rating'), 1);
    }

    // تعداد نظرات تایید شده
    public function getReviewsCountAttribute()
    {
        return $this->approvedReviews()->count();
    }

    public function getIsExpiredAttribute()
    {
        if ($this->expire_at && $this->expire_at < now()) {
            return true;
        }
        return false;
    }

    public function checkExpiration()
    {
        if ($this->is_expired && $this->is_active) {
            $this->is_active = false;
            $this->status = 'expired';
            $this->save();
        }
    }

    public function isAuctionActive()
    {
        if ($this->type !== 'auction') {
            return false;
        }
        if (!$this->auction_end_time) {
            return false;
        }
        return $this->auction_end_time > now() && $this->status === 'active';
    }

    /**
     * Get ad extra field value
     */
    public function getExtra($key, $default = null)
    {
        $extra = $this->ad_extra ?? [];
        return $extra[$key] ?? $default;
    }

    /**
     * Set ad extra field value
     */
    public function setExtra($key, $value)
    {
        $extra = $this->ad_extra ?? [];
        $extra[$key] = $value;
        $this->ad_extra = $extra;
        return $this;
    }

    /**
     * Get ad type label in Persian
     */
    public function getAdTypeLabelAttribute()
    {
        $labels = [
            'telegram' => 'گروه تلگرام',
            'instagram' => 'پیج اینستاگرام',
            'website' => 'سایت آماده',
            'domain' => 'دامنه',
            'youtube' => 'کانال یوتیوب',
        ];
        return $labels[$this->ad_type ?? 'telegram'] ?? 'گروه تلگرام';
    }

    /**
     * Get key metric for display (for CTR)
     */
    public function getKeyMetricAttribute()
    {
        $adType = $this->ad_type ?? 'telegram';
        
        switch ($adType) {
            case 'instagram':
                return $this->getExtra('instagram_followers');
            case 'website':
                return $this->getExtra('website_monthly_visits');
            case 'youtube':
                return $this->getExtra('youtube_subscribers');
            case 'domain':
                return $this->getExtra('domain_name');
            default:
                return $this->member_count;
        }
    }
}







