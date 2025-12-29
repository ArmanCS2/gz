<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'ad_id',
        'rating',
        'comment',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'rating' => 'integer',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function ad()
    {
        return $this->belongsTo(Ad::class);
    }

    // Scope برای نظرات تایید شده
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    // Scope برای نظرات در انتظار تایید
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
}
