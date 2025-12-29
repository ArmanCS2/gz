<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    protected $fillable = [
        'site_name',
        'logo',
        'ad_daily_price',
        'auction_daily_price',
        'zarinpal_merchant_id',
        'melipayamak_username',
        'melipayamak_password',
        'melipayamak_from_number',
        'ad_auto_approve',
        'active_ads',
        'total_members',
        'active_users',
        'successful_deals',
        'satisfaction_percent',
        'rating',
    ];

    protected function casts(): array
    {
        return [
            'ad_daily_price' => 'decimal:2',
            'auction_daily_price' => 'decimal:2',
            'ad_auto_approve' => 'boolean',
            'active_ads' => 'integer',
            'total_members' => 'integer',
            'active_users' => 'integer',
            'successful_deals' => 'integer',
            'satisfaction_percent' => 'integer',
            'rating' => 'decimal:1',
        ];
    }

    public static function getSettings()
    {
        $settings = static::first();
        if (!$settings) {
            $settings = static::create([
                'site_name' => 'GroohBaz',
                'ad_daily_price' => 10000,
                'auction_daily_price' => 5000,
                'ad_auto_approve' => false, // مقدار پیش‌فرض false
                'active_ads' => 0,
                'total_members' => 0,
                'active_users' => 0,
                'successful_deals' => 0,
                'satisfaction_percent' => 100,
                'rating' => 4.9,
            ]);
        }
        return $settings;
    }
}





