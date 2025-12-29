<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Helpers\SlugHelper;
use Illuminate\Database\Seeder;

class CategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Only seed if categories table is empty
        if (Category::count() > 0) {
            $this->command->info('Categories table is not empty. Skipping CategoriesSeeder.');
            return;
        }

        $categories = [
            [
                'name' => 'فناوری',
                'description' => 'گروه‌ها و کانال‌های مرتبط با فناوری، برنامه‌نویسی، هوش مصنوعی و تکنولوژی',
                'icon' => 'bi-laptop',
                'order' => 1,
            ],
            [
                'name' => 'کسب‌وکار',
                'description' => 'گروه‌های تجاری، بازاریابی، فروش و کسب‌وکار',
                'icon' => 'bi-briefcase',
                'order' => 2,
            ],
            [
                'name' => 'ارز دیجیتال',
                'description' => 'گروه‌های مرتبط با ارزهای دیجیتال، بلاک‌چین و سرمایه‌گذاری',
                'icon' => 'bi-currency-bitcoin',
                'order' => 3,
            ],
            [
                'name' => 'آموزش',
                'description' => 'گروه‌های آموزشی، درسی، دانشگاهی و مهارت‌آموزی',
                'icon' => 'bi-book',
                'order' => 4,
            ],
            [
                'name' => 'سرگرمی',
                'description' => 'گروه‌های تفریحی، طنز، فیلم و سریال',
                'icon' => 'bi-emoji-smile',
                'order' => 5,
            ],
            [
                'name' => 'اخبار',
                'description' => 'کانال‌های خبری، اطلاع‌رسانی و تحلیل اخبار',
                'icon' => 'bi-newspaper',
                'order' => 6,
            ],
            [
                'name' => 'سلامت',
                'description' => 'گروه‌های مرتبط با سلامت، پزشکی، تغذیه و تناسب اندام',
                'icon' => 'bi-heart',
                'order' => 7,
            ],
            [
                'name' => 'ورزش',
                'description' => 'گروه‌های ورزشی، فوتبال، والیبال و سایر رشته‌های ورزشی',
                'icon' => 'bi-trophy',
                'order' => 8,
            ],
            [
                'name' => 'استخدام',
                'description' => 'گروه‌های کاریابی، فرصت‌های شغلی و استخدام',
                'icon' => 'bi-briefcase-fill',
                'order' => 9,
            ],
            [
                'name' => 'بازی',
                'description' => 'گروه‌های بازی، گیمینگ و سرگرمی‌های دیجیتال',
                'icon' => 'bi-controller',
                'order' => 10,
            ],
        ];

        foreach ($categories as $categoryData) {
            Category::create([
                'name' => $categoryData['name'],
                'slug' => SlugHelper::persianSlug($categoryData['name']),
                'description' => $categoryData['description'],
                'icon' => $categoryData['icon'],
                'order' => $categoryData['order'],
                'is_active' => true,
            ]);
        }

        $this->command->info('Categories seeded successfully!');
    }
}

