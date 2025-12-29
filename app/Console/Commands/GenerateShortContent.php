<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Post;
use App\Models\User;
use App\Models\Category;
use App\Helpers\SlugHelper;
use Illuminate\Support\Str;

class GenerateShortContent extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'groohbaz:generate-content';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate short content using OpenRouter API';

    /**
     * Topics for content generation - High engagement topics
     */
    protected $topics = [
        // آگهی و تبلیغات
        'نحوه نوشتن آگهی جذاب',
        'قیمت‌گذاری آگهی در تلگرام',
        'بهترین زمان انتشار آگهی',
        'آگهی رایگان در کانال تلگرام',
        'نکات مهم در آگهی‌نویسی',
        'آگهی موفق چه ویژگی‌هایی دارد',
        'تفاوت آگهی پولی و رایگان',
        'چگونه آگهی خود را ویروسی کنیم',
        
        // خرید و فروش
        'خرید و فروش امن در تلگرام',
        'نکات مهم در خرید آنلاین',
        'چگونه فروشنده معتبر پیدا کنیم',
        'قیمت‌گذاری محصولات دست دوم',
        'مذاکره در خرید و فروش',
        'روش‌های پرداخت امن',
        'بررسی کیفیت محصول قبل از خرید',
        'بازگشت کالا در خرید آنلاین',
        'خرید گروهی و مزایای آن',
        'فروش سریع محصولات',
        
        // گروه تلگرام
        'مدیریت گروه تلگرام',
        'افزایش اعضای گروه تلگرام',
        'قوانین گروه تلگرام',
        'تبلیغات در گروه تلگرام',
        'بهترین گروه‌های خرید و فروش',
        'چگونه گروه پرطرفدار بسازیم',
        'مدیریت اسپم در گروه',
        'تبادل لینک گروه‌ها',
        'گروه‌های تخصصی و مزایا',
        'نحوه جذب عضو واقعی',
        
        // کانال تلگرام
        'ساخت کانال تلگرام موفق',
        'افزایش ممبر کانال',
        'محتوا برای کانال تلگرام',
        'تبلیغات در کانال',
        'مدیریت کانال تلگرام',
        'بهترین زمان پست در کانال',
        'کانال‌های پولی و رایگان',
        'نحوه کسب درآمد از کانال',
        'طراحی کانال جذاب',
        'استراتژی محتوای کانال',
        
        // بازار آنلاین
        'بازار آنلاین چیست',
        'مزایای خرید از بازار آنلاین',
        'معرفی بهترین بازارهای آنلاین',
        'امنیت در بازار آنلاین',
        'نحوه ثبت آگهی در بازار آنلاین',
        'قیمت‌گذاری در بازار آنلاین',
        'مذاکره در بازار آنلاین',
        'بازار آنلاین محلی',
        'مقایسه بازار فیزیکی و آنلاین',
        'آینده بازار آنلاین',
        
        // موضوعات تخصصی با نرخ کلیک بالا
        'خرید گوشی دست دوم',
        'فروش ماشین در تلگرام',
        'خرید و فروش ملک',
        'آگهی استخدام',
        'خدمات فنی و حرفه‌ای',
        'خرید لوازم خانگی',
        'فروش پوشاک',
        'خرید و فروش کتاب',
        'خدمات آموزشی',
        'مشاوره آنلاین',
        'خرید و فروش ارز دیجیتال',
        'خدمات طراحی',
        'خرید و فروش موبایل',
        'خدمات آرایشی و زیبایی',
        'خرید و فروش لپ تاپ',
        'خدمات تعمیرات',
        'خرید و فروش دوچرخه',
        'خدمات ترجمه',
        'خرید و فروش کنسول بازی',
        'خدمات برنامه‌نویسی',
    ];

    /**
     * Get the AI model to use
     * Priority: config > default professional model
     * 
     * Available professional models (best for Persian content):
     * 
     * Premium (Best Quality):
     * - 'openai/gpt-4o' - Best quality, excellent for Persian, higher cost
     * - 'anthropic/claude-3.5-sonnet' - Excellent for content generation, great for Persian
     * 
     * Recommended (Best Balance):
     * - 'openai/gpt-4o-mini' - Great quality, affordable, excellent for Persian (DEFAULT)
     * 
     * Good Alternatives:
     * - 'google/gemini-pro-1.5' - Good quality, reasonable cost
     * - 'qwen/qwen-2.5-72b-instruct' - Better Qwen model, good for Persian
     * 
     * Basic (Current):
     * - 'qwen/qwen-2.5-7b-instruct' - Basic model, lower cost
     * 
     * To change model, set OPENROUTER_MODEL in .env file
     */
    protected function getModel(): string
    {
        // Check if model is configured in services config
        $configuredModel = config('services.openrouter.model');
        
        if ($configuredModel) {
            return $configuredModel;
        }
        
        // Default to GPT-4o-mini: Best balance of quality and cost for Persian content
        return 'openai/gpt-4o-mini';
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting short content generation...');

        try {
            // Get OpenRouter API key
            $apiKey = config('services.openrouter.api_key');
            
            if (empty($apiKey)) {
                $this->error('OpenRouter API key is not configured. Please set OPENROUTER_API_KEY in your .env file.');
                Log::error('OpenRouter API key missing');
                return Command::FAILURE;
            }

            // Get default user (admin or first user)
            $user = User::where('is_admin', true)->first() ?? User::first();
            
            if (!$user) {
                $this->error('No user found. Please create at least one user.');
                Log::error('No user found for content generation');
                return Command::FAILURE;
            }

            // Select random topic (avoid recently used topics)
            $topic = $this->selectUniqueTopic();
            
            if (!$topic) {
                $this->warn('All topics have been used recently. Selecting random topic...');
                $topic = $this->topics[array_rand($this->topics)];
            }

            // Show which model is being used
            $model = $this->getModel();
            $this->info("Using AI model: {$model}");
            $this->line("Topic: {$topic}");

            // Build prompt
            $prompt = $this->buildPrompt($topic);

            // Call OpenRouter API
            $response = $this->callOpenRouterAPI($apiKey, $prompt);

            if (!$response) {
                $this->error('Failed to get response from OpenRouter API');
                return Command::FAILURE;
            }

            // Validate response structure
            if (!isset($response['choices']) || !is_array($response['choices']) || empty($response['choices'])) {
                $this->error('Invalid API response structure');
                $this->warn('Response: ' . json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
                Log::error('Invalid API response structure', ['response' => $response]);
                return Command::FAILURE;
            }

            // Extract and decode JSON
            $contentData = $this->extractContent($response);

            if (!$contentData) {
                $this->error('Failed to extract valid content from API response');
                $this->warn('Check logs for detailed error information');
                return Command::FAILURE;
            }

            // Validate content
            if (empty($contentData['title']) || empty($contentData['content'])) {
                $this->error('Invalid content structure: title or content is missing');
                Log::error('Invalid content structure', ['data' => $contentData]);
                return Command::FAILURE;
            }

            // Convert Latin numbers to Persian numbers in title
            $contentData['title'] = $this->convertLatinNumbersToPersian($contentData['title']);
            
            // Remove ALL HTML tags from title (comprehensive cleaning)
            $contentData['title'] = strip_tags($contentData['title']); // Remove all HTML tags
            // Also remove any remaining HTML entities
            $contentData['title'] = html_entity_decode($contentData['title'], ENT_QUOTES | ENT_HTML5, 'UTF-8');
            // Clean up any double spaces
            $contentData['title'] = preg_replace('/\s+/', ' ', $contentData['title']);
            // Trim whitespace
            $contentData['title'] = trim($contentData['title']);
            
            // Validate title format (Persian only)
            if (!$this->isValidPersianTitle($contentData['title'])) {
                // Debug: find invalid characters
                $invalidChars = $this->findInvalidCharacters($contentData['title']);
                $this->error('Title contains invalid characters. Title must contain ONLY Persian letters and standard punctuation.');
                $this->warn('Invalid title: ' . $contentData['title']);
                if (!empty($invalidChars)) {
                    $this->warn('Invalid characters found: ' . json_encode($invalidChars, JSON_UNESCAPED_UNICODE));
                }
                Log::error('Invalid title format', [
                    'title' => $contentData['title'],
                    'invalid_chars' => $invalidChars,
                ]);
                return Command::FAILURE;
            }

            // Check for duplicate title
            if (Post::where('title', $contentData['title'])->exists()) {
                $this->warn('Post with this title already exists. Skipping...');
                Log::info('Duplicate title detected', ['title' => $contentData['title']]);
                return Command::SUCCESS;
            }

            // Generate slug
            $slug = $this->generateUniqueSlug($contentData['title']);

            // Get or create appropriate category based on topic and title
            $category = $this->getOrCreateCategory($topic, $contentData['title']);

            // Generate SEO-friendly excerpt (first 200 chars, usually contains keywords)
            // Extract from beginning of content where keywords are likely to be
            $excerpt = Str::limit(strip_tags($contentData['content']), 200);
            // Ensure excerpt ends with complete sentence if possible
            $excerpt = preg_replace('/[.!?].*$/', '', $excerpt);
            if (strlen($excerpt) < 150) {
                // If too short, get more content
                $excerpt = Str::limit(strip_tags($contentData['content']), 200);
            }
            // Ensure excerpt is SEO-friendly (contains keywords from title)
            // The excerpt should be descriptive and keyword-rich for better SEO
            
            // Create post
            $post = Post::create([
                'title' => $contentData['title'],
                'slug' => $slug,
                'content' => $contentData['content'],
                'excerpt' => $excerpt,
                'category_id' => $category ? $category->id : null,
                'user_id' => $user->id,
                'status' => 'published',
                'published_at' => now(),
            ]);

            $this->info("Successfully created post: {$post->title}");
            if ($category) {
                $this->info("Category assigned: {$category->name}");
            }
            Log::info('Short content generated successfully', [
                'post_id' => $post->id,
                'title' => $post->title,
                'category_id' => $category ? $category->id : null,
                'category_name' => $category ? $category->name : null,
            ]);

            return Command::SUCCESS;

        } catch (\Exception $e) {
            $this->error('An error occurred: ' . $e->getMessage());
            Log::error('Content generation failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return Command::FAILURE;
        }
    }

    /**
     * Select a unique topic that hasn't been used recently
     */
    protected function selectUniqueTopic(): ?string
    {
        // Get recently used topics (last 30 days, limit 50 posts)
        $recentPosts = Post::where('created_at', '>=', now()->subDays(30))
            ->orderBy('created_at', 'desc')
            ->limit(50)
            ->pluck('title')
            ->toArray();
        
        // Extract topics from recent post titles (simple matching)
        $usedTopics = [];
        foreach ($recentPosts as $postTitle) {
            // Check if any topic matches the post title (fuzzy match)
            foreach ($this->topics as $topic) {
                // If topic is contained in title or vice versa, consider it used
                if (stripos($postTitle, $topic) !== false || stripos($topic, $postTitle) !== false) {
                    $usedTopics[] = $topic;
                }
            }
        }
        
        // Remove duplicates
        $usedTopics = array_unique($usedTopics);
        
        // Get available topics
        $availableTopics = array_diff($this->topics, $usedTopics);
        
        // If we have available topics, select randomly
        if (!empty($availableTopics)) {
            return $availableTopics[array_rand($availableTopics)];
        }
        
        // If all topics are used, try to find least recently used
        // Get topics used in last 7 days only
        $recentPosts7Days = Post::where('created_at', '>=', now()->subDays(7))
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->pluck('title')
            ->toArray();
        
        $usedTopics7Days = [];
        foreach ($recentPosts7Days as $postTitle) {
            foreach ($this->topics as $topic) {
                if (stripos($postTitle, $topic) !== false || stripos($topic, $postTitle) !== false) {
                    $usedTopics7Days[] = $topic;
                }
            }
        }
        
        $usedTopics7Days = array_unique($usedTopics7Days);
        $availableTopics7Days = array_diff($this->topics, $usedTopics7Days);
        
        if (!empty($availableTopics7Days)) {
            return $availableTopics7Days[array_rand($availableTopics7Days)];
        }
        
        // Last resort: return null to use random selection
        return null;
    }

    /**
     * Build the prompt for OpenRouter API
     */
    protected function buildPrompt(string $topic): string
    {
        return "یک مقاله کوتاه و کاربردی به زبان فارسی درباره «{$topic}» بنویس.

اولویت اول: محتوا باید کاملاً انسانی، طبیعی، با معنی و مفهوم باشد. 

بسیار مهم - محتوا باید با معنی و مفهوم باشد:
- هر جمله باید معنی و مفهوم داشته باشد، نه فقط پر کردن کلمات
- محتوا باید داستان و روایت داشته باشد، نه فقط لیست نکات
- هر پاراگراف باید یک ایده کامل و مفهومی را بیان کند
- جملات باید به هم مرتبط باشند و یک داستان واحد را روایت کنند
- از جملات بی‌معنی و تکراری پرهیز کن
- محتوا باید مثل یک انسان واقعی که تجربه دارد و می‌خواهد به خواننده کمک کند نوشته شود
- هر کلمه و جمله باید ارزش و معنی داشته باشد
- محتوا باید مفید، کاربردی و با مفهوم عمیق باشد

بسیار مهم - تنوع و منحصر به فرد بودن:
- محتوا باید کاملاً متنوع و منحصر به فرد باشد
- از تکرار جملات، عبارات و ساختارهای مشابه در مقالات مختلف پرهیز کن
- هر مقاله باید سبک و رویکرد متفاوتی داشته باشد
- از کلیشه‌ها و الگوهای تکراری پرهیز کن
- هر بار یک زاویه جدید و متفاوت برای موضوع انتخاب کن
- استفاده از مثال‌ها و داستان‌های متنوع و متفاوت
- هر مقاله باید یک هویت منحصر به فرد داشته باشد
- از ساختارهای مختلف برای مقالات استفاده کن (نه همیشه یک ساختار)
- استفاده از لحن‌های متنوع (گاهی صمیمی‌تر، گاهی حرفه‌ای‌تر)
- هر مقاله باید یک پیام و رویکرد منحصر به فرد داشته باشد

هدف سئو: این مقاله باید در جستجوی گوگل رتبه بالا داشته باشد. برای این کار باید:
- کلمات کلیدی را به صورت طبیعی و متنوع استفاده کنی
- از کلمات کلیدی LSI (هم‌معنی) استفاده کنی
- به سوالات کاربران پاسخ دهی
- محتوای کامل، جامع و طولانی بنویسی (600-1000 کلمه) - هر چه طولانی‌تر بهتر
- کلمه کلیدی اصلی را در عنوان و ابتدای محتوا استفاده کنی

مهم: فقط و فقط JSON معتبر برگردان. هیچ متن اضافی قبل یا بعد از JSON ننویس.
ساختار دقیق JSON (دقت کنید که کلیدها باید دقیقا \"title\" و \"content\" باشند):

{
  \"title\": \"عنوان جذاب و با نرخ کلیک بالا\",
  \"content\": \"<p>محتوای مقاله</p>\"
}

توجه: در JSON، کلیدها باید فقط حروف انگلیسی باشند: \"title\" و \"content\". هیچ تگ HTML در نام کلیدها نگذارید.

اولویت اول و مهم‌ترین نکته: عنوان باید فوق‌العاده کلیک‌خور و جذاب باشد. عنوان باید طوری باشد که کاربران نتوانند در برابر کلیک روی آن مقاومت کنند!

قوانین عنوان (title) - برای کلیک‌خوری بالا:

فرمت و ساختار:
- زبان: فقط فارسی
- فقط حروف فارسی و علائم نگارشی استاندارد مجاز است
- بدون حروف لاتین (a-z, A-Z)
- بدون حروف سیریلیک
- بدون نمادهای خاص
- فقط حروف فارسی، اعداد فارسی (۰-۹)، فاصله و علائم نگارشی فارسی
- مهم: حتماً از اعداد فارسی استفاده کن (۰۱۲۳۴۵۶۷۸۹) نه اعداد لاتین (0123456789)
- بسیار مهم: عنوان نباید شامل هیچ تگ HTML باشد (نه <p>، نه <strong>، هیچ تگی)
- عنوان باید فقط متن خالص باشد، بدون هیچ تگ HTML
- طول عنوان: 8 تا 15 کلمه (نه خیلی کوتاه، نه خیلی بلند)

تکنیک‌های کلیک‌خوری (Clickbait - اما اخلاقی):
- استفاده از اعداد: اعداد همیشه کلیک‌خور هستند (۵ روش، ۱۰ نکته، ۷ راه، ۳ ترفند)
- استفاده از کلمات قدرتمند: راهنمای کامل، نکات طلایی، روش‌های حرفه‌ای، ترفندهای مخفی، اصول موفقیت
- ایجاد کنجکاوی: «رازهایی که نمی‌دانید»، «نکاتی که کسی به شما نمی‌گوید»، «روشی که همه استفاده می‌کنند»
- نشان دادن ارزش: «کامل»، «جامع»، «حرفه‌ای»، «موثر»، «سریع»، «آسان»
- استفاده از کلمات احساسی: «حیاتی»، «ضروری»، «مهم»، «طلایی»، «عالی»، «بهترین»
- ایجاد فوریت: «فوری»، «الان»، «همین امروز»
- استفاده از سوال: «چگونه...؟»، «چرا...؟»، «چه زمانی...؟»

کلمات جذاب برای کلیک:
- چگونه، بهترین، سریع، آسان، رایگان، موفق، حرفه‌ای، کامل، جامع
- راهنمای، نکات، روش‌ها، ترفندها، اصول، رازها، تکنیک‌ها
- حیاتی، ضروری، مهم، طلایی، عالی، فوق‌العاده
- مخفی، ناشناخته، خاص، ویژه، منحصر به فرد

مثال‌های عالی برای کلیک‌خوری:
- «۵ روش حرفه‌ای برای افزایش ممبر کانال تلگرام (راهنمای کامل)»
- «۱۰ نکته طلایی برای نوشتن آگهی جذاب که همه را ترغیب می‌کند»
- «راهنمای کامل خرید امن در بازار آنلاین: ۷ نکته حیاتی»
- «چگونه در ۳ مرحله ساده کانال تلگرام خود را پرطرفدار کنیم؟»
- «رازهای مخفی قیمت‌گذاری موفق که کسی به شما نمی‌گوید»
- «۷ ترفند حرفه‌ای برای جذب عضو واقعی به گروه تلگرام»

اجتناب از:
- عنوان‌های کلیشه‌ای و تکراری
- عنوان‌های خیلی ساده و بدون جذابیت
- عنوان‌های خیلی طولانی یا خیلی کوتاه

قوانین محتوا - بسیار مهم: محتوا باید کاملاً انسانی، با مفهوم و مناسب سئو باشد:

سئو و بهینه‌سازی برای رتبه بالا در گوگل (بسیار مهم):
- کلمه کلیدی اصلی باید در عنوان، 100 کلمه اول، و چند بار در محتوا استفاده شود
- استفاده طبیعی و متنوع از کلمات کلیدی اصلی و مرتبط در کل محتوا
- استفاده گسترده از کلمات کلیدی مرتبط و هم‌معنی (LSI keywords) - این برای سئو بسیار مهم است
- استفاده از کلمات کلیدی در ابتدای پاراگراف اول (طبیعی)
- استفاده از تگ <strong> برای تاکید روی کلمات کلیدی مهم و عبارات کلیدی (طبیعی، 2-3 بار)
- کلمات کلیدی را به صورت طبیعی و در جملات معنی‌دار استفاده کن (نه تکرار بی‌رویه)
- تراکم کلمات کلیدی: حدود 1.5-2.5% از کل محتوا (طبیعی و بدون افراط)
- استفاده از عبارات کلیدی طولانی (long-tail keywords) مرتبط با موضوع
- استفاده از کلمات کلیدی در جملات سوالی و پاسخ‌ها
- محتوا باید به سوالات کاربران پاسخ دهد (FAQ style)
- استفاده از کلمات کلیدی مترادف و هم‌معنی برای تنوع

ساختار و خوانایی - با معنی و مفهوم و متنوع:
- زبان: فارسی (ایران) - لحن طبیعی و دوستانه
- سبک: کاملاً انسانی، مثل یک انسان واقعی که تجربه دارد می‌نویسد
- هر جمله باید معنی و مفهوم داشته باشد - از جملات بی‌معنی پرهیز کن
- محتوا باید داستان و روایت داشته باشد، نه فقط لیست نکات
- استفاده از تجربیات واقعی و مثال‌های عملی با معنی و متنوع
- جملات باید روان، طبیعی، قابل فهم و با معنی باشند
- از کلیشه‌ها، جملات تکراری و بی‌معنی پرهیز کن
- محتوا باید مفید، کاربردی، با ارزش و با مفهوم عمیق باشد
- استفاده از مثال‌های ملموس و واقعی که معنی دارند و متنوع هستند
- لحن متنوع: گاهی صمیمی و دوستانه، گاهی حرفه‌ای‌تر (نه همیشه یک لحن)
- توضیح واضح و ساده با معنی (نه پیچیده و تکنیکی)
- پاراگراف‌های کوتاه و خوانا (3-4 جمله) که هر کدام یک ایده کامل و مفهومی را بیان می‌کنند
- استفاده از تگ <strong> برای تاکید روی نکات مهم و با معنی (طبیعی)
- هر پاراگراف باید به پاراگراف بعدی مرتبط باشد و یک داستان واحد را روایت کند
- ساختار متنوع: گاهی با سوال شروع کن، گاهی با داستان، گاهی با نکته مهم
- هر مقاله باید ساختار و رویکرد متفاوتی داشته باشد

محدودیت‌ها:
- بدون ایموجی
- بدون markdown
- بدون توضیحات اضافی
- بدون ذکر AI یا هوش مصنوعی
- محتوا باید HTML معتبر باشد
- فقط تگ‌های مجاز: <p>, <strong>
- بدون h1 یا h2
- طول محتوا: 600 تا 1000 کلمه (برای سئو بهتر است محتوا طولانی و جامع باشد)
- بسیار مهم: محتوا باید طولانی، جامع و کامل باشد - نه کوتاه و مختصر
- هر چه محتوا طولانی‌تر و جامع‌تر باشد، برای سئو و کاربران بهتر است

ساختار محتوا برای سئو - با معنی و مفهوم:
- شروع با یک مقدمه جذاب و با معنی (2-3 جمله) که کلمه کلیدی اصلی را در جمله اول یا دوم شامل شود
  * مقدمه باید معنی داشته باشد و خواننده را درگیر کند، نه فقط پر کردن کلمات
- بدنه محتوا: 6-10 پاراگراف با مثال‌های عملی و کاربردی که معنی دارند (برای محتوای طولانی‌تر)
  * هر پاراگراف باید یک نکته یا موضوع کامل و مفهومی را پوشش دهد
  * پاراگراف‌ها باید به هم مرتبط باشند و یک داستان واحد را روایت کنند
  * استفاده طبیعی از کلمات کلیدی در جملات با معنی
  * استفاده از کلمات کلیدی LSI در پاراگراف‌های مختلف به صورت طبیعی
  * هر جمله باید معنی و ارزش داشته باشد
- استفاده از تگ <strong> برای تاکید روی نکات مهم و با معنی (2-3 بار در کل محتوا)
- پاسخ به سوالات رایج کاربران در محتوا با جملات معنی‌دار (این برای سئو بسیار موثر است)
- پایان با CTA نرم و طبیعی که شامل کلمه کلیدی مرتبط باشد و معنی داشته باشد (مثل: «اگر سوالی درباره [کلمه کلیدی] دارید، در بخش نظرات بپرسید»)

یادآوری مهم - اولویت اول: 
- عنوان مقاله مهم‌ترین بخش است و باید فوق‌العاده کلیک‌خور باشد!
- عنوان باید طوری باشد که کاربران نتوانند در برابر کلیک روی آن مقاومت کنند
- استفاده از اعداد، کلمات قدرتمند، و ایجاد کنجکاوی برای کلیک‌خوری ضروری است
- عنوان باید خواننده را کنجکاو کند، ارزش محتوا را نشان دهد و جذاب باشد
- کلمه کلیدی اصلی باید در عنوان باشد
- عنوان باید مثل یک clickbait اخلاقی باشد: جذاب، کنجکاوی‌برانگیز، و با ارزش
- محتوا باید کاملاً انسانی باشد: مثل یک دوست که تجربه دارد و می‌خواهد به شما کمک کند.
- بسیار مهم: محتوا باید با معنی و مفهوم باشد - هر جمله باید معنی داشته باشد
- بسیار مهم: محتوا باید کاملاً متنوع و منحصر به فرد باشد - هر مقاله باید متفاوت باشد
- از جملات کلیشه‌ای، بی‌معنی و تکراری مثل «در این مقاله می‌خواهیم...» یا «امیدواریم مفید باشد» پرهیز کن.
- از تکرار ساختارها، جملات و الگوهای مشابه در مقالات مختلف پرهیز کن
- محتوا باید مفید، کاربردی و با مفهوم عمیق باشد، نه فقط پر کردن کلمات.
- هر کلمه و جمله باید ارزش و معنی داشته باشد
- محتوا باید داستان و روایت داشته باشد، نه فقط لیست نکات
- مثال‌ها باید واقعی، ملموس، با معنی و متنوع باشند - هر بار مثال‌های جدید
- لحن متنوع: گاهی صمیمی و دوستانه، گاهی حرفه‌ای‌تر - نه همیشه یک لحن
- پاراگراف‌ها باید به هم مرتبط باشند و یک داستان واحد را روایت کنند
- هر مقاله باید یک هویت و رویکرد منحصر به فرد داشته باشد
- برای سئو و رتبه بالا در گوگل:
  * کلمات کلیدی را به صورت طبیعی و متنوع در جملات با معنی استفاده کن
  * کلمه کلیدی اصلی باید در عنوان، 100 کلمه اول، و چند بار در محتوا باشد
  * استفاده گسترده از کلمات کلیدی LSI (هم‌معنی) برای رتبه بهتر - اما در جملات معنی‌دار
  * پاسخ به سوالات رایج کاربران در محتوا با جملات معنی‌دار (بسیار موثر برای سئو)
  * استفاده از عبارات کلیدی طولانی (long-tail keywords) در جملات طبیعی
  * محتوا باید کامل، جامع، طولانی و با معنی باشد (600-1000 کلمه) تا گوگل آن را ارزشمند بداند
  * هر چه محتوا طولانی‌تر و جامع‌تر باشد، برای سئو بهتر است

یادآوری نهایی - بسیار مهم:
- هر جمله باید معنی و مفهوم داشته باشد - از جملات بی‌معنی و تکراری پرهیز کن
- محتوا باید داستان و روایت داشته باشد، نه فقط لیست نکات
- هر پاراگراف باید یک ایده کامل و مفهومی را بیان کند
- پاراگراف‌ها باید به هم مرتبط باشند و یک داستان واحد را روایت کنند
- هر کلمه و جمله باید ارزش و معنی داشته باشد
- بسیار مهم: محتوا باید کاملاً متنوع و منحصر به فرد باشد
- هر مقاله باید سبک، ساختار و رویکرد متفاوتی داشته باشد
- از تکرار الگوها، جملات و ساختارهای مشابه پرهیز کن
- استفاده از مثال‌ها، داستان‌ها و زوایای متنوع برای هر مقاله
- هر مقاله باید یک هویت و پیام منحصر به فرد داشته باشد
- بسیار مهم: محتوا باید طولانی و جامع باشد (600-1000 کلمه) - نه کوتاه
- هر چه محتوا طولانی‌تر و جامع‌تر باشد، برای سئو و کاربران بهتر است
- محتوا باید کامل و جامع باشد، نه مختصر و خلاصه

فقط JSON برگردان، بدون هیچ متن اضافی.";
    }

    /**
     * Call OpenRouter API
     */
    protected function callOpenRouterAPI(string $apiKey, string $prompt): ?array
    {
        try {
            $response = Http::timeout(60)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $apiKey,
                    'Content-Type' => 'application/json',
                    'HTTP-Referer' => 'https://groupbaz.ir',
                    'X-Title' => 'groupbaz',
                ])->post('https://openrouter.ai/api/v1/chat/completions', [
                    'model' => $this->getModel(),
                    'messages' => [
                        [
                            'role' => 'user',
                            'content' => $prompt,
                        ],
                    ],
                    'temperature' => 0.8,
                    'max_tokens' => 3000, // Increased for longer content (600-1000 words)
                ]);

            if (!$response->successful()) {
                Log::error('OpenRouter API request failed', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
                return null;
            }

            return $response->json();

        } catch (\Exception $e) {
            Log::error('OpenRouter API exception', [
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }

    /**
     * Extract content from API response
     */
    protected function extractContent(array $response): ?array
    {
        try {
            // Extract message content
            $messageContent = $response['choices'][0]['message']['content'] ?? null;

            if (!$messageContent) {
                $this->warn('Raw API response: ' . json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
                Log::error('No message content in API response', ['response' => $response]);
                return null;
            }

            // Log raw content for debugging
            $this->line('Raw content received: ' . substr($messageContent, 0, 200) . '...');
            Log::info('Raw API content', ['content' => $messageContent]);

            // Clean the content (remove markdown code blocks if present)
            $cleanedContent = trim($messageContent);
            
            // Remove markdown code blocks
            $cleanedContent = preg_replace('/^```json\s*/i', '', $cleanedContent);
            $cleanedContent = preg_replace('/^```\s*/', '', $cleanedContent);
            $cleanedContent = preg_replace('/\s*```$/', '', $cleanedContent);
            $cleanedContent = trim($cleanedContent);

            // Try to extract JSON if there's extra text around it
            // Look for JSON object pattern
            if (preg_match('/\{[^{}]*(?:\{[^{}]*\}[^{}]*)*\}/s', $cleanedContent, $matches)) {
                $cleanedContent = $matches[0];
            }

            // Decode JSON
            $decoded = json_decode($cleanedContent, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                $this->warn('JSON decode failed. Attempting to fix common issues...');
                
                // Try to fix common JSON issues
                // Remove any text before first {
                $firstBrace = strpos($cleanedContent, '{');
                if ($firstBrace !== false && $firstBrace > 0) {
                    $cleanedContent = substr($cleanedContent, $firstBrace);
                }
                
                // Check if JSON is incomplete (missing closing brace)
                $openBraces = substr_count($cleanedContent, '{');
                $closeBraces = substr_count($cleanedContent, '}');
                
                // If JSON is incomplete, try to fix it
                if ($openBraces > $closeBraces) {
                    // Find the last unclosed string and close it properly
                    // Look for "content": "..." pattern that might be incomplete
                    if (preg_match('/"content"\s*:\s*"([^"]*)$/', $cleanedContent, $matches)) {
                        // Content value is incomplete, close it
                        $cleanedContent .= '"';
                    }
                    // Add missing closing braces
                    $cleanedContent .= str_repeat('}', $openBraces - $closeBraces);
                }
                
                // Remove any text after last }
                $lastBrace = strrpos($cleanedContent, '}');
                if ($lastBrace !== false) {
                    $cleanedContent = substr($cleanedContent, 0, $lastBrace + 1);
                }
                
                // Fix corrupted keys (e.g., "<p>tent" -> "content")
                // Pattern: key that starts with <p> and ends with tent or conten
                $cleanedContent = preg_replace('/"<p>tent"/', '"content"', $cleanedContent);
                $cleanedContent = preg_replace('/"<p>conten"/', '"content"', $cleanedContent);
                $cleanedContent = preg_replace('/"conten<p>/', '"content"', $cleanedContent);
                // Fix key that has <p> in the middle: "<p>tent" or similar
                $cleanedContent = preg_replace('/"<p>([^"]*tent[^"]*)"/', '"content"', $cleanedContent);
                // Fix any key that contains "conten" but is corrupted (but not if it's already "content")
                $cleanedContent = preg_replace('/"([^"]*conten[^"]*)"/', '"content"', $cleanedContent);
                // Make sure we don't have duplicate "content" keys
                if (substr_count($cleanedContent, '"content"') > 1) {
                    // Keep only the first occurrence
                    $cleanedContent = preg_replace('/"content"\s*:\s*"[^"]*",\s*"content"/', '"content"', $cleanedContent, 1);
                }
                
                // Fix corrupted title key
                $cleanedContent = preg_replace('/"<p>title"/', '"title"', $cleanedContent);
                $cleanedContent = preg_replace('/"title<p>/', '"title"', $cleanedContent);
                
                // Try decoding again
                $decoded = json_decode($cleanedContent, true);
                
                if (json_last_error() !== JSON_ERROR_NONE) {
                    // Try to fix incomplete JSON strings
                    // Check if content value is incomplete (not properly closed)
                    if (preg_match('/"content"\s*:\s*"([^"]*(?:\\.[^"]*)*)$/', $cleanedContent, $matches)) {
                        // Content string is not closed, try to close it
                        $cleanedContent = preg_replace('/"content"\s*:\s*"([^"]*(?:\\.[^"]*)*)$/', '"content": "$1"', $cleanedContent);
                        // Try decoding again
                        $decoded = json_decode($cleanedContent, true);
                    }
                    
                    if (json_last_error() !== JSON_ERROR_NONE) {
                        // Last resort: try to extract using regex
                        $this->warn('JSON still invalid. Attempting regex extraction...');
                        $decoded = $this->extractUsingRegex($messageContent); // Use original messageContent for regex
                        
                        if (!$decoded) {
                            $this->error('JSON decode error: ' . json_last_error_msg());
                            $this->warn('Cleaned content: ' . substr($cleanedContent, 0, 500));
                            Log::error('JSON decode error', [
                                'error' => json_last_error_msg(),
                                'raw_content' => $messageContent,
                                'cleaned_content' => $cleanedContent,
                            ]);
                            return null;
                        }
                    }
                }
            }

            return $decoded;

        } catch (\Exception $e) {
            $this->error('Content extraction exception: ' . $e->getMessage());
            Log::error('Content extraction failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return null;
        }
    }

    /**
     * Extract content using regex as fallback when JSON is malformed
     */
    protected function extractUsingRegex(string $content): ?array
    {
        $result = [];
        
        // Try to extract title (usually short, single line)
        if (preg_match('/"title"\s*:\s*"([^"]+)"/', $content, $titleMatches)) {
            $result['title'] = stripslashes($titleMatches[1]);
        } elseif (preg_match('/"title"\s*:\s*"([^"]*(?:\\.[^"]*)*)"/', $content, $titleMatches)) {
            $result['title'] = stripslashes($titleMatches[1]);
        }
        
        // Remove HTML tags from title if extracted
        if (!empty($result['title'])) {
            $result['title'] = strip_tags($result['title']);
            $result['title'] = html_entity_decode($result['title'], ENT_QUOTES | ENT_HTML5, 'UTF-8');
            $result['title'] = preg_replace('/\s+/', ' ', $result['title']);
            $result['title'] = trim($result['title']);
        }
        
        // Try to extract content - handle multi-line, escaped, and incomplete content
        // First, try to find the content key (may be corrupted like "<p>tent")
        $contentPatterns = [
            '/"content"\s*:\s*"((?:[^"\\\\]|\\\\.)*)"/s',  // Normal content key (complete)
            '/"content"\s*:\s*"((?:[^"\\\\]|\\\\.)*)$/s',  // Normal content key (incomplete - no closing quote)
            '/"<p>tent"\s*:\s*"((?:[^"\\\\]|\\\\.)*)"/s',   // Corrupted key "<p>tent" (complete)
            '/"<p>tent"\s*:\s*"((?:[^"\\\\]|\\\\.)*)$/s',   // Corrupted key "<p>tent" (incomplete)
            '/"<p>[^"]*tent[^"]*"\s*:\s*"((?:[^"\\\\]|\\\\.)*)"/s', // Corrupted key with <p> and tent (complete)
            '/"<p>[^"]*tent[^"]*"\s*:\s*"((?:[^"\\\\]|\\\\.)*)$/s', // Corrupted key with <p> and tent (incomplete)
            '/"conten[^"]*"\s*:\s*"((?:[^"\\\\]|\\\\.)*)"/s', // Partially corrupted (complete)
            '/"conten[^"]*"\s*:\s*"((?:[^"\\\\]|\\\\.)*)$/s', // Partially corrupted (incomplete)
        ];
        
        foreach ($contentPatterns as $pattern) {
            if (preg_match($pattern, $content, $contentMatches)) {
                $extracted = stripslashes($contentMatches[1]);
                // If content doesn't start with <p>, add it
                if (!empty($extracted) && strpos(trim($extracted), '<p>') !== 0) {
                    $extracted = '<p>' . $extracted;
                }
                // Ensure content ends properly
                if (!empty($extracted) && !preg_match('/<\/p>$/', $extracted)) {
                    // Try to close any open <p> tags
                    $openPTags = substr_count($extracted, '<p>');
                    $closePTags = substr_count($extracted, '</p>');
                    if ($openPTags > $closePTags) {
                        $extracted .= '</p>';
                    }
                }
                $result['content'] = $extracted;
                break;
            }
        }
        
        // If still not found, try to extract content value that starts with <p>
        // Look for any key after title that has a value starting with <p>
        if (empty($result['content'])) {
            // Pattern: find value after title that starts with <p> (complete)
            if (preg_match('/"title"\s*:\s*"[^"]*",\s*"[^"]*"\s*:\s*"<p>((?:[^"\\\\]|\\\\.)*)"/s', $content, $contentMatches)) {
                $result['content'] = '<p>' . stripslashes($contentMatches[1]);
            } 
            // Pattern: find value after title that starts with <p> (incomplete - no closing quote)
            elseif (preg_match('/"title"\s*:\s*"[^"]*",\s*"[^"]*"\s*:\s*"<p>((?:[^"\\\\]|\\\\.)*)$/s', $content, $contentMatches)) {
                $extracted = '<p>' . stripslashes($contentMatches[1]);
                // Close the paragraph tag if needed
                if (!preg_match('/<\/p>$/', $extracted)) {
                    $extracted .= '</p>';
                }
                $result['content'] = $extracted;
            }
            // Get the second value (which should be content)
            elseif (preg_match('/"title"\s*:\s*"[^"]*",\s*"[^"]*"\s*:\s*"((?:[^"\\\\]|\\\\.)*)"/s', $content, $contentMatches)) {
                $result['content'] = stripslashes($contentMatches[1]);
            }
            // Get incomplete second value
            elseif (preg_match('/"title"\s*:\s*"[^"]*",\s*"[^"]*"\s*:\s*"((?:[^"\\\\]|\\\\.)*)$/s', $content, $contentMatches)) {
                $extracted = stripslashes($contentMatches[1]);
                if (!empty($extracted) && strpos(trim($extracted), '<p>') === 0 && !preg_match('/<\/p>$/', $extracted)) {
                    $extracted .= '</p>';
                }
                $result['content'] = $extracted;
            }
        }
        
        // If we found both, return the result
        if (!empty($result['title']) && !empty($result['content'])) {
            $this->info('Successfully extracted content using regex fallback');
            return $result;
        }
        
        // Log what we found for debugging
        Log::warning('Regex extraction partial', [
            'found_title' => !empty($result['title']),
            'found_content' => !empty($result['content']),
            'content_preview' => substr($content, 0, 500),
        ]);
        
        return null;
    }

    /**
     * Get or create appropriate category based on topic and title
     */
    protected function getOrCreateCategory(string $topic, string $title): ?Category
    {
        // Category mapping based on keywords
        $categoryMappings = [
            // آگهی و تبلیغات
            'آگهی' => 'آگهی و تبلیغات',
            'تبلیغات' => 'آگهی و تبلیغات',
            'قیمت‌گذاری آگهی' => 'آگهی و تبلیغات',
            'نوشتن آگهی' => 'آگهی و تبلیغات',
            'آگهی‌نویسی' => 'آگهی و تبلیغات',
            
            // خرید و فروش
            'خرید و فروش' => 'خرید و فروش',
            'خرید آنلاین' => 'خرید و فروش',
            'فروش' => 'خرید و فروش',
            'خرید' => 'خرید و فروش',
            'مذاکره' => 'خرید و فروش',
            'پرداخت' => 'خرید و فروش',
            
            // گروه تلگرام
            'گروه تلگرام' => 'گروه تلگرام',
            'گروه' => 'گروه تلگرام',
            'اعضای گروه' => 'گروه تلگرام',
            'مدیریت گروه' => 'گروه تلگرام',
            
            // کانال تلگرام
            'کانال تلگرام' => 'کانال تلگرام',
            'کانال' => 'کانال تلگرام',
            'ممبر کانال' => 'کانال تلگرام',
            'مدیریت کانال' => 'کانال تلگرام',
            'ساخت کانال' => 'کانال تلگرام',
            
            // بازار آنلاین
            'بازار آنلاین' => 'بازار آنلاین',
            'بازار' => 'بازار آنلاین',
            'بازار فیزیکی' => 'بازار آنلاین',
            
            // راهنما و آموزش
            'راهنما' => 'راهنما و آموزش',
            'آموزش' => 'راهنما و آموزش',
            'نکات' => 'راهنما و آموزش',
            'روش' => 'راهنما و آموزش',
            'ترفند' => 'راهنما و آموزش',
        ];
        
        // Combine topic and title for better matching
        $searchText = $topic . ' ' . $title;
        
        // Try to find matching category
        $matchedCategoryName = null;
        foreach ($categoryMappings as $keyword => $categoryName) {
            if (stripos($searchText, $keyword) !== false) {
                $matchedCategoryName = $categoryName;
                break;
            }
        }
        
        // Default category if no match found
        if (!$matchedCategoryName) {
            $matchedCategoryName = 'راهنما و آموزش'; // Default category
        }
        
        // Find or create category
        $category = Category::where('name', $matchedCategoryName)->first();
        
        if (!$category) {
            // Create category if it doesn't exist
            $category = Category::create([
                'name' => $matchedCategoryName,
                'slug' => SlugHelper::persianSlug($matchedCategoryName),
                'description' => 'دسته‌بندی برای مقالات مرتبط با ' . $matchedCategoryName,
                'is_active' => true,
                'order' => 0,
            ]);
            
            $this->info("Created new category: {$matchedCategoryName}");
        } elseif (!$category->is_active) {
            // If category exists but is inactive, activate it
            $category->update(['is_active' => true]);
        }
        
        return $category;
    }

    /**
     * Convert Latin numbers to Persian numbers
     */
    protected function convertLatinNumbersToPersian(string $text): string
    {
        $latinToPersian = [
            '0' => '۰',
            '1' => '۱',
            '2' => '۲',
            '3' => '۳',
            '4' => '۴',
            '5' => '۵',
            '6' => '۶',
            '7' => '۷',
            '8' => '۸',
            '9' => '۹',
        ];
        
        return strtr($text, $latinToPersian);
    }

    /**
     * Find invalid characters in title for debugging
     */
    protected function findInvalidCharacters(string $title): array
    {
        $invalid = [];
        $allowed = '/[\x{0600}-\x{06FF}\s\.،؛:؟!\-\(\)\[\]\{\}«»\/۰-۹\x{200C}\x{200D}]/u';
        
        // Check each character
        $chars = preg_split('//u', $title, -1, PREG_SPLIT_NO_EMPTY);
        foreach ($chars as $char) {
            if (!preg_match($allowed, $char)) {
                // Get Unicode code point
                $unicode = '';
                if (function_exists('mb_ord')) {
                    $unicode = 'U+' . strtoupper(dechex(mb_ord($char, 'UTF-8')));
                } else {
                    // Fallback for older PHP versions
                    $unicode = bin2hex(mb_convert_encoding($char, 'UCS-2BE', 'UTF-8'));
                    $unicode = 'U+' . strtoupper($unicode);
                }
                $invalid[] = [
                    'char' => $char,
                    'unicode' => $unicode,
                ];
            }
        }
        
        return $invalid;
    }

    /**
     * Validate if title contains only Persian letters and standard punctuation
     */
    protected function isValidPersianTitle(string $title): bool
    {
        // Check for Latin letters (a-z, A-Z)
        if (preg_match('/[a-zA-Z]/', $title)) {
            return false;
        }
        
        // Check for Cyrillic characters
        if (preg_match('/[\x{0400}-\x{04FF}]/u', $title)) {
            return false;
        }
        
        // Allowed: Persian letters (\x{0600}-\x{06FF}), Persian numbers (۰-۹), 
        // spaces, ZWNJ (U+200C) and ZWJ (U+200D) for compound words like "قیمت‌گذاری",
        // and standard Persian punctuation: . ، ؛ : ؟ ! - ( ) [ ] { } « » /
        // Remove all allowed characters
        $cleaned = preg_replace('/[\x{0600}-\x{06FF}\s\.،؛:؟!\-\(\)\[\]\{\}«»\/۰-۹\x{200C}\x{200D}]/u', '', $title);
        
        // If anything remains after removing allowed characters, it contains invalid symbols
        return empty($cleaned);
    }

    /**
     * Generate unique slug for post
     */
    protected function generateUniqueSlug(string $title): string
    {
        $baseSlug = SlugHelper::persianSlug($title);
        $slug = $baseSlug;
        $counter = 1;

        while (Post::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }
}

