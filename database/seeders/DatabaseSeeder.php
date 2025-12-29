<?php

namespace Database\Seeders;

use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create default site settings
        SiteSetting::firstOrCreate(
            ['id' => 1],
            [
                'site_name' => 'GroohBaz',
                'ad_daily_price' => 10000,
                'auction_daily_price' => 5000,
                'ad_auto_approve' => false,
            ]
        );

        User::firstOrCreate(
            ['mobile' => '09123456789'],
            [
                'name' => 'مدیر سیستم',
                'is_admin' => true,
                'is_verified' => true,
            ]
        );

        // Seed categories and ads
        $this->call([
            CategoriesSeeder::class,
            AdsSeeder::class,
        ]);
    }
}
