<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'mobile',
        'is_admin',
        'is_verified',
    ];

    protected $hidden = [
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'is_admin' => 'boolean',
            'is_verified' => 'boolean',
        ];
    }

    public function ads()
    {
        return $this->hasMany(Ad::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function bids()
    {
        return $this->hasMany(Bid::class);
    }

    public function acceptedRules()
    {
        return $this->belongsToMany(SiteRule::class, 'rule_user')->withPivot('accepted_at')->withTimestamps();
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

    public function comments()
    {
        return $this->hasMany(PostComment::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}
