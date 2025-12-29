<?php

namespace Database\Seeders;

use App\Models\Ad;
use App\Models\AdImage;
use App\Models\Bid;
use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;

class AdsSeeder extends Seeder
{
    /**
     * Realistic Persian first names
     */
    private array $firstNames = [
        'علی', 'محمد', 'حسن', 'حسین', 'رضا', 'امیر', 'سعید', 'مهدی', 'احمد', 'حامد',
        'فرهاد', 'کامران', 'بهرام', 'آرمان', 'آرش', 'پویا', 'نیما', 'سامان', 'امید', 'داریوش',
        'فاطمه', 'زهرا', 'مریم', 'سارا', 'نازنین', 'نیلوفر', 'پریسا', 'مهسا', 'سحر', 'نرگس',
        'لیلا', 'مینا', 'شیدا', 'آتوسا', 'آرزو', 'بهاره', 'گلناز', 'سمیرا', 'شهرزاد', 'یاسمن',
    ];

    /**
     * Realistic Persian last names
     */
    private array $lastNames = [
        'احمدی', 'محمدی', 'حسینی', 'رضایی', 'کریمی', 'موسوی', 'نوری', 'صادقی', 'جعفری', 'اکبری',
        'علیزاده', 'رحمانی', 'کاظمی', 'شریفی', 'امینی', 'باقری', 'حیدری', 'قاسمی', 'زمانی', 'طاهری',
        'فرهادی', 'نظری', 'میرزایی', 'کمالی', 'صالحی', 'نصیری', 'مهدوی', 'سلیمی', 'حسنی', 'یوسفی',
    ];

    /**
     * Realistic ad titles by category theme
     */
    private array $adTitles = [
        'tech' => [
            'کانال برنامه‌نویسی پایتون',
            'گروه توسعه‌دهندگان وب',
            'کانال هوش مصنوعی و یادگیری ماشین',
            'گروه برنامه‌نویسان اندروید',
            'کانال آموزش React و Vue',
            'گروه توسعه‌دهندگان Node.js',
            'کانال طراحی UI/UX',
            'گروه برنامه‌نویسان PHP',
            'کانال آموزش Flutter',
            'گروه DevOps و Cloud',
            'کانال امنیت سایبری',
            'گروه برنامه‌نویسان iOS',
            'کانال بلاک‌چین و Web3',
            'گروه مهندسان نرم‌افزار',
        ],
        'business' => [
            'کانال بازاریابی دیجیتال',
            'گروه کارآفرینان',
            'کانال فروش و بازاریابی',
            'گروه مشاوره کسب‌وکار',
            'کانال مدیریت پروژه',
            'گروه سرمایه‌گذاران',
            'کانال برندسازی',
            'گروه تجارت الکترونیک',
            'کانال مدیریت منابع انسانی',
            'گروه استارتاپ‌ها',
        ],
        'crypto' => [
            'کانال تحلیل تکنیکال بیت‌کوین',
            'گروه تریدرهای ارز دیجیتال',
            'کانال اخبار کریپتو',
            'گروه سرمایه‌گذاری در NFT',
            'کانال آموزش ترید',
            'گروه تحلیل فاندامنتال',
            'کانال آلت‌کوین‌ها',
            'گروه ماینینگ',
            'کانال DeFi و استیکینگ',
            'گروه سرمایه‌گذاران حرفه‌ای',
        ],
        'education' => [
            'کانال آموزش زبان انگلیسی',
            'گروه دانشجویان مهندسی',
            'کانال کنکور کارشناسی ارشد',
            'گروه آموزش ریاضی',
            'کانال مهارت‌های نرم',
            'گروه دانشجویان پزشکی',
            'کانال آموزش آنلاین',
            'گروه کتابخوانی',
            'کانال آموزش موسیقی',
            'گروه دانشجویان MBA',
        ],
        'entertainment' => [
            'کانال طنز و خنده',
            'گروه فیلم و سریال',
            'کانال موسیقی ایرانی',
            'گروه ماشین و خودرو',
            'کانال عکس و تصویر',
            'گروه سفر و گردشگری',
            'کانال آشپزی',
            'گروه مد و فشن',
            'کانال حیوانات خانگی',
            'گروه هنر و نقاشی',
        ],
        'news' => [
            'کانال اخبار فناوری',
            'گروه تحلیل سیاسی',
            'کانال اخبار اقتصادی',
            'گروه اخبار ورزشی',
            'کانال اخبار بین‌الملل',
            'گروه اخبار محلی',
            'کانال تحلیل بازار',
            'گروه اخبار اجتماعی',
        ],
        'health' => [
            'کانال سلامت و تندرستی',
            'گروه تغذیه سالم',
            'کانال تناسب اندام',
            'گروه پزشکی عمومی',
            'کانال روانشناسی',
            'گروه یوگا و مدیتیشن',
            'کانال طب سنتی',
            'گروه سلامت زنان',
        ],
        'sports' => [
            'کانال فوتبال ایران',
            'گروه والیبال',
            'کانال بسکتبال',
            'گروه کشتی',
            'کانال بدنسازی',
            'گروه دوچرخه‌سواری',
            'کانال شنا',
            'گروه ورزش‌های رزمی',
        ],
        'jobs' => [
            'گروه کاریابی IT',
            'کانال فرصت‌های شغلی',
            'گروه استخدام تهران',
            'کانال کارآموزی',
            'گروه فریلنسرها',
            'کانال استخدام دورکاری',
            'گروه کارآفرینی',
            'کانال مهارت‌آموزی',
        ],
        'gaming' => [
            'گروه گیمرهای PC',
            'کانال بازی‌های موبایل',
            'گروه پابجی و کالاف',
            'کانال بازی‌های آنلاین',
            'گروه گیمرهای کنسول',
            'کانال استریمینگ',
            'گروه بازی‌های ایرانی',
            'کانال eSports',
        ],
    ];

    /**
     * Realistic ad descriptions templates
     */
    private array $descriptionTemplates = [
        'کانال فعال با {members} عضو که به صورت روزانه محتوای باکیفیت منتشر می‌کند. اعضای فعال و تعاملی دارند و بحث‌های مفیدی در گروه شکل می‌گیرد.',
        'گروه {members} نفری با مدیریت حرفه‌ای و قوانین مشخص. محتوا به صورت منظم و با کیفیت بالا ارائه می‌شود.',
        'کانال {members} عضوی با سابقه {years} ساله. محتوای اختصاصی و کاربردی که برای اعضا ارزشمند است.',
        'گروه فعال با {members} عضو واقعی و بدون ربات. اعضا در بحث‌ها مشارکت فعال دارند و فضای دوستانه‌ای حاکم است.',
        'کانال {members} نفری با رشد مستمر. محتوای به‌روز و مرتبط که برای مخاطبان جذاب است.',
        'گروه {members} عضوی با مدیریت ۲۴ ساعته. قوانین شفاف و اجرای منظم آن‌ها.',
        'کانال با {members} عضو که به صورت هفتگی محتوای جدید و مفید منتشر می‌کند. اعضا راضی و فعال هستند.',
        'گروه {members} نفری با تمرکز بر کیفیت محتوا. اعضای متخصص و با تجربه در گروه حضور دارند.',
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Delete all existing ads (including soft deleted ones) - cascade will handle related records
        $this->command->info('Deleting all existing ads...');
        Ad::withTrashed()->forceDelete();
        $this->command->info('All ads deleted successfully.');

        // Ensure categories exist
        $categories = Category::all();
        if ($categories->isEmpty()) {
            $this->command->warn('Categories table is empty. Please run CategoriesSeeder first.');
            return;
        }

        // Create seller users (reuse across multiple ads) - reduced for speed
        $sellers = $this->createSellerUsers(20);

        // Create 56 ads
        $totalAds = 56;
        $auctionCount = 0;
        $normalCount = 0;

        // Ensure uploads/ads directory exists
        $uploadDir = public_path('uploads/ads');
        if (!File::exists($uploadDir)) {
            File::makeDirectory($uploadDir, 0755, true);
        }

        // Create placeholder images (15 images to reuse)
        $imagePaths = $this->createPlaceholderImages(15);

        $this->command->info("Creating {$totalAds} ads...");

        for ($i = 1; $i <= $totalAds; $i++) {
            // Determine ad type (mix: ~30% auction, 70% normal)
            $isAuction = ($i % 10 < 3);
            $type = $isAuction ? 'auction' : 'normal';

            if ($isAuction) {
                $auctionCount++;
            } else {
                $normalCount++;
            }

            // Select random seller
            $seller = $sellers->random();

            // Generate member count (with realistic distribution)
            $memberCount = $this->generateMemberCount();

            // Generate price based on member count
            $price = $this->calculatePrice($memberCount, $type);

            // Select random category theme
            $categoryTheme = array_rand($this->adTitles);
            $title = $this->adTitles[$categoryTheme][array_rand($this->adTitles[$categoryTheme])];

            // Assign category based on theme (70% chance of having a category)
            $categoryId = null;
            if (rand(1, 10) <= 7) {
                // Map theme to category slug/name
                $themeToCategoryMap = [
                    'tech' => 'فناوری',
                    'business' => 'کسب‌وکار',
                    'crypto' => 'ارز دیجیتال',
                    'education' => 'آموزش',
                    'entertainment' => 'سرگرمی',
                    'news' => 'اخبار',
                    'health' => 'سلامت',
                    'sports' => 'ورزش',
                    'jobs' => 'استخدام',
                    'gaming' => 'بازی',
                ];
                
                $categoryName = $themeToCategoryMap[$categoryTheme] ?? null;
                if ($categoryName) {
                    $category = Category::where('name', $categoryName)->first();
                    if ($category) {
                        $categoryId = $category->id;
                    }
                }
            }

            // Generate description
            $description = $this->generateDescription($memberCount);

            // Generate creation date (spread over last 30-60 days)
            $daysAgo = rand(1, 60);
            $createdAt = Carbon::now()->subDays($daysAgo)->subHours(rand(0, 23))->subMinutes(rand(0, 59));

            // Generate telegram link
            $telegramLink = $this->generateTelegramLink($title);

            // Prepare ad data
            $adData = [
                'user_id' => $seller->id,
                'category_id' => $categoryId,
                'title' => $title,
                'description' => $description,
                'telegram_link' => $telegramLink,
                'telegram_id' => '@' . strtolower(str_replace(' ', '', $title)),
                'member_count' => $memberCount,
                'construction_year' => $this->generateConstructionYear(),
                'type' => $type,
                'is_active' => true,
                'status' => 'active',
                'show_contact' => false, // Contact hidden as per requirements
                'paid_at' => $createdAt->copy()->subHours(rand(1, 12)), // All seeded ads are paid and approved
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ];

            if ($type === 'normal') {
                $adData['price'] = $price;
                // Set expire_at to future (at least 30 days from now)
                $adData['expire_at'] = Carbon::now()->addDays(rand(30, 60));
            } else {
                $adData['base_price'] = $price;
                $adData['current_bid'] = $price;
                // Set auction_end_time to future (at least 3 days from now)
                $adData['auction_end_time'] = Carbon::now()->addDays(rand(3, 14));
                // Set expire_at to future (after auction ends)
                $adData['expire_at'] = $adData['auction_end_time']->copy()->addDays(7);
                $adData['paid_at'] = $createdAt->copy()->subHours(rand(1, 12)); // All seeded ads are paid and approved
            }

            // Create ad
            $ad = Ad::create($adData);

            // Add images (1-3 images per ad)
            $imageCount = rand(1, 3);
            $imageData = [];
            for ($j = 0; $j < $imageCount; $j++) {
                $imagePath = $imagePaths[array_rand($imagePaths)];
                $imageData[] = [
                    'ad_id' => $ad->id,
                    'image' => $imagePath,
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt,
                ];
            }
            // Bulk insert images for better performance
            if (!empty($imageData)) {
                AdImage::insert($imageData);
            }

            // Create bids for auction ads (skip for faster seeding)
            // if ($type === 'auction' && $ad->auction_end_time > now()) {
            //     $this->createBidsForAuction($ad, $sellers, $createdAt);
            // }

            if ($i % 25 === 0) {
                $this->command->info("Created {$i} ads...");
            }
        }

        $this->command->info("Successfully created {$totalAds} ads!");
        $this->command->info("  - Normal ads: {$normalCount}");
        $this->command->info("  - Auction ads: {$auctionCount}");
    }

    /**
     * Create seller users with Persian names
     */
    private function createSellerUsers(int $count): \Illuminate\Support\Collection
    {
        $users = collect();

        for ($i = 0; $i < $count; $i++) {
            $firstName = $this->firstNames[array_rand($this->firstNames)];
            $lastName = $this->lastNames[array_rand($this->lastNames)];
            $name = $firstName . ' ' . $lastName;

            // Generate unique mobile number (09XXXXXXXXX format)
            do {
                $mobile = '09' . str_pad(rand(100000000, 999999999), 9, '0', STR_PAD_LEFT);
            } while (User::where('mobile', $mobile)->exists());

            $user = User::create([
                'name' => $name,
                'mobile' => $mobile,
                'is_admin' => false,
                'is_verified' => rand(0, 1) === 1, // 50% verified
            ]);

            $users->push($user);
        }

        return $users;
    }

    /**
     * Generate realistic member count
     */
    private function generateMemberCount(): int
    {
        $rand = rand(1, 100);

        if ($rand <= 40) {
            // 40% - Low (1k-5k)
            return rand(1000, 5000);
        } elseif ($rand <= 75) {
            // 35% - Medium (5k-50k)
            return rand(5000, 50000);
        } elseif ($rand <= 95) {
            // 20% - High (50k-200k)
            return rand(50000, 200000);
        } else {
            // 5% - Very High (200k-500k+)
            return rand(200000, 500000);
        }
    }

    /**
     * Calculate price based on member count and type
     * Returns realistic rounded prices
     */
    private function calculatePrice(int $memberCount, string $type): float
    {
        $basePrice = 0;

        if ($memberCount < 5000) {
            // Low members: 500k - 2M (rounded to 50k increments)
            $min = 500000;
            $max = 2000000;
            $randomPrice = rand($min, $max);
            $basePrice = round($randomPrice / 50000) * 50000; // Round to nearest 50k
        } elseif ($memberCount < 50000) {
            // Medium: 2M - 10M (rounded to 100k increments)
            $min = 2000000;
            $max = 10000000;
            $randomPrice = rand($min, $max);
            $basePrice = round($randomPrice / 100000) * 100000; // Round to nearest 100k
        } elseif ($memberCount < 200000) {
            // High: 10M - 50M (rounded to 500k increments)
            $min = 10000000;
            $max = 50000000;
            $randomPrice = rand($min, $max);
            $basePrice = round($randomPrice / 500000) * 500000; // Round to nearest 500k
        } else {
            // Very High: 50M - 200M (rounded to 1M increments)
            $min = 50000000;
            $max = 200000000;
            $randomPrice = rand($min, $max);
            $basePrice = round($randomPrice / 1000000) * 1000000; // Round to nearest 1M
        }

        // Auction base price is typically 60-80% of normal price (rounded)
        if ($type === 'auction') {
            $discountPercent = rand(60, 80);
            $basePrice = $basePrice * ($discountPercent / 100);
            // Round auction prices to nearest 50k for consistency
            if ($basePrice < 1000000) {
                $basePrice = round($basePrice / 50000) * 50000;
            } elseif ($basePrice < 10000000) {
                $basePrice = round($basePrice / 100000) * 100000;
            } else {
                $basePrice = round($basePrice / 500000) * 500000;
            }
        }

        return round($basePrice, 2);
    }

    /**
     * Generate realistic description
     */
    private function generateDescription(int $memberCount): string
    {
        $template = $this->descriptionTemplates[array_rand($this->descriptionTemplates)];
        $years = rand(1, 5);

        $description = str_replace('{members}', number_format($memberCount), $template);
        $description = str_replace('{years}', $years, $description);

        // Add some variety
        $additionalInfo = [
            'مدیریت گروه به صورت حرفه‌ای انجام می‌شود.',
            'محتوای منتشر شده به صورت روزانه و منظم است.',
            'اعضای گروه در بحث‌ها مشارکت فعال دارند.',
            'گروه بدون تبلیغات مزاحم و اسپم است.',
            'قوانین گروه به صورت شفاف اعلام شده است.',
            'گروه دارای اعضای واقعی و فعال است.',
        ];

        if (rand(0, 1)) {
            $description .= ' ' . $additionalInfo[array_rand($additionalInfo)];
        }

        return $description;
    }

    /**
     * Generate construction year (Solar calendar, stored as Gregorian)
     */
    private function generateConstructionYear(): ?int
    {
        // 70% chance of having construction year
        if (rand(1, 10) > 3) {
            // Between 1398 and 1403 (Solar) = 2019-2024 (Gregorian)
            $solarYear = rand(1398, 1403);
            // Approximate conversion (Solar to Gregorian)
            return $solarYear + 621;
        }

        return null;
    }

    /**
     * Generate Telegram link
     */
    private function generateTelegramLink(string $title): string
    {
        $slug = strtolower(str_replace([' ', '‌', 'کانال', 'گروه'], ['', '', '', ''], $title));
        $slug = preg_replace('/[^a-z0-9]/', '', $slug);
        $random = rand(1000, 9999);
        return "https://t.me/{$slug}{$random}";
    }

    /**
     * Create placeholder images
     */
    private function createPlaceholderImages(int $count): array
    {
        $imagePaths = [];
        $uploadDir = public_path('uploads/ads');

        // Check if GD extension is available
        if (!extension_loaded('gd')) {
            $this->command->warn('GD extension not available. Creating simple placeholder files instead.');
            // Create simple placeholder files
            for ($i = 1; $i <= $count; $i++) {
                $filename = "placeholder_{$i}_" . time() . ".txt";
                $filepath = $uploadDir . DIRECTORY_SEPARATOR . $filename;
                file_put_contents($filepath, "Telegram Group Preview Image {$i}");
                $imagePaths[] = "uploads/ads/{$filename}";
            }
            return $imagePaths;
        }

        $timestamp = time();
        for ($i = 1; $i <= $count; $i++) {
            $filename = "placeholder_{$i}_{$timestamp}.jpg";
            $filepath = $uploadDir . DIRECTORY_SEPARATOR . $filename;

            try {
                // Create a simple colored placeholder image using GD (optimized)
                $width = 800;
                $height = 600;
                $image = imagecreatetruecolor($width, $height);

                if ($image === false) {
                    throw new \Exception('Failed to create image');
                }

                // Random background color (dark theme compatible)
                $colors = [
                    [30, 30, 50],   // Dark blue
                    [40, 30, 60],   // Dark purple
                    [30, 40, 50],   // Dark teal
                    [50, 30, 40],   // Dark red
                    [30, 50, 40],   // Dark green
                ];
                $color = $colors[($i - 1) % count($colors)]; // Use modulo for faster selection
                $bgColor = imagecolorallocate($image, $color[0], $color[1], $color[2]);
                imagefill($image, 0, 0, $bgColor);

                // Add some simple pattern/text
                $textColor = imagecolorallocate($image, 200, 200, 200);
                imagestring($image, 5, $width / 2 - 100, $height / 2 - 20, "Telegram Group", $textColor);
                imagestring($image, 3, $width / 2 - 80, $height / 2 + 10, "Preview Image", $textColor);

                // Save image with lower quality for faster processing
                imagejpeg($image, $filepath, 75);
                imagedestroy($image);

                $imagePaths[] = "uploads/ads/{$filename}";
            } catch (\Exception $e) {
                // Fallback: create a simple text file
                $this->command->warn("Failed to create image {$i}: " . $e->getMessage());
                $filename = "placeholder_{$i}_{$timestamp}.txt";
                $filepath = $uploadDir . DIRECTORY_SEPARATOR . $filename;
                file_put_contents($filepath, "Telegram Group Preview Image {$i}");
                $imagePaths[] = "uploads/ads/{$filename}";
            }
        }

        return $imagePaths;
    }

    /**
     * Create bids for auction ad
     */
    private function createBidsForAuction(Ad $ad, \Illuminate\Support\Collection $users, Carbon $adCreatedAt): void
    {
        $bidCount = rand(3, 10);
        $currentBid = $ad->base_price;
        $bidUsers = $users->random(min($bidCount, $users->count()));

        // Ensure seller doesn't bid on their own ad
        $bidUsers = $bidUsers->reject(function ($user) use ($ad) {
            return $user->id === $ad->user_id;
        });

        $bidStartTime = $adCreatedAt->copy()->addHours(rand(1, 6));
        $auctionEndTime = $ad->auction_end_time;

        foreach ($bidUsers->take($bidCount) as $index => $bidder) {
            // Each bid is 5-15% higher than previous
            $incrementPercent = rand(5, 15) / 100;
            $currentBid = $currentBid * (1 + $incrementPercent);
            $currentBid = round($currentBid, 2);

            // Bid time is spread between ad creation and auction end
            $bidTime = $bidStartTime->copy()->addHours($index * rand(2, 12));
            if ($bidTime > $auctionEndTime) {
                $bidTime = $auctionEndTime->copy()->subHours(rand(1, 6));
            }

            Bid::create([
                'user_id' => $bidder->id,
                'ad_id' => $ad->id,
                'amount' => $currentBid,
                'created_at' => $bidTime,
                'updated_at' => $bidTime,
            ]);
        }

        // Update ad's current_bid
        $ad->current_bid = $currentBid;
        $ad->save();
    }
}

