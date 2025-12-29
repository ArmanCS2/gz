<?php

namespace App\Http\Controllers;

use App\Models\Ad;
use App\Models\Category;
use App\Models\SiteSetting;
use Illuminate\Http\Request;

class SeoLandingController extends Controller
{
    /**
     * Generate SEO landing pages based on action (buy/sell) and type
     */
    public function landing(Request $request, $action, $type)
    {
        // Validate action and type
        $validActions = ['خرید', 'فروش'];
        $validTypes = [
            'گروه-تلگرام', 
            'کانال-تلگرام', 
            'پیج-اینستاگرام',
            'سایت-آماده',
            'دامنه',
            'کانال-یوتیوب'
        ];
        
        if (!in_array($action, $validActions) || !in_array($type, $validTypes)) {
            abort(404);
        }
        
        $siteSettings = SiteSetting::getSettings();
        
        // Map URL types to ad_type and labels
        $typeMap = [
            'گروه-تلگرام' => ['ad_type' => 'telegram', 'label' => 'گروه تلگرام', 'is_channel' => false],
            'کانال-تلگرام' => ['ad_type' => 'telegram', 'label' => 'کانال تلگرام', 'is_channel' => true],
            'پیج-اینستاگرام' => ['ad_type' => 'instagram', 'label' => 'پیج اینستاگرام', 'is_channel' => false],
            'سایت-آماده' => ['ad_type' => 'website', 'label' => 'سایت آماده', 'is_channel' => false],
            'دامنه' => ['ad_type' => 'domain', 'label' => 'دامنه', 'is_channel' => false],
            'کانال-یوتیوب' => ['ad_type' => 'youtube', 'label' => 'کانال یوتیوب', 'is_channel' => false],
        ];
        
        $typeInfo = $typeMap[$type];
        $isBuy = $action === 'خرید';
        
        // Get active ads matching the criteria
        $query = Ad::where('status', 'active')
            ->where('is_active', true)
            ->where(function($q) use ($typeInfo) {
                // Check if ad_type column exists and filter by it
                // Also handle null ad_type (default to 'telegram' for backward compatibility)
                if ($typeInfo['ad_type'] === 'telegram') {
                    $q->where(function($subQ) {
                        $subQ->where('ad_type', 'telegram')
                             ->orWhereNull('ad_type'); // Include old ads without ad_type
                });
                } else {
                    $q->where('ad_type', $typeInfo['ad_type']);
                }
            })
            ->where(function($q) {
                $q->whereNull('expire_at')
                  ->orWhere('expire_at', '>', now());
            });
        
        // For telegram channels vs groups, we could filter by ad_extra in the future
        // For now, we'll show all telegram ads regardless of channel/group distinction
        
        $ads = $query->orderBy('created_at', 'desc')
            ->take(12)
            ->get();
        
        // Build SEO-optimized page title for CTR
        $pageTitle = $action . ' ' . $typeInfo['label'] . ' | قیمت واقعی | بدون واسطه | ' . ($siteSettings->site_name ?? 'گروه باز');
        
        // Build meta description
        $pageDescription = $this->generateMetaDescription($action, $typeInfo['label'], $ads->count());
        
        // Generate content
        $content = $this->generateContent($action, $typeInfo, $isBuy);
        
        // Related pages for internal linking
        $relatedPages = $this->getRelatedPages($action, $type);
        
        // FAQ data
        $faqs = $this->getFAQs($action, $typeInfo['label']);
        
        $canonicalUrl = route('seo.landing', ['action' => $action, 'type' => $type]);
        
        return view('seo.landing', compact(
            'action',
            'type',
            'typeInfo',
            'isBuy',
            'ads',
            'pageTitle',
            'pageDescription',
            'content',
            'relatedPages',
            'faqs',
            'siteSettings',
            'canonicalUrl'
        ))->layout('layouts.app', [
            'title' => $pageTitle,
            'description' => $pageDescription,
            'canonical' => $canonicalUrl,
            'robots' => 'index, follow',
        ]);
    }
    
    /**
     * Generate meta description
     */
    private function generateMetaDescription($action, $label, $adCount)
    {
        $descriptions = [
            'خرید' => [
                'گروه تلگرام' => "خرید گروه تلگرام با {$adCount}+ آگهی واقعی و مستقیم از مالک. قیمت‌های شفاف، بدون واسطه، معاملات امن. مشاهده و خرید آنلاین.",
                'کانال تلگرام' => "خرید کانال تلگرام با {$adCount}+ آگهی معتبر. قیمت‌های واقعی، ارتباط مستقیم با مالک، معاملات امن. خرید آنلاین کانال تلگرام.",
                'پیج اینستاگرام' => "خرید پیج اینستاگرام با {$adCount}+ آگهی واقعی. قیمت‌های شفاف، بدون واسطه، معاملات امن. خرید مستقیم از مالک.",
                'سایت آماده' => "خرید سایت آماده با {$adCount}+ آگهی واقعی. سایت‌های آماده با ترافیک و درآمد، قیمت‌های شفاف، معاملات امن.",
                'دامنه' => "خرید دامنه با {$adCount}+ آگهی واقعی. دامنه‌های معتبر با پسوندهای مختلف، قیمت‌های شفاف، معاملات امن.",
                'کانال یوتیوب' => "خرید کانال یوتیوب با {$adCount}+ آگهی واقعی. کانال‌های یوتیوب با مشترک و درآمد، قیمت‌های شفاف، معاملات امن.",
            ],
            'فروش' => [
                'گروه تلگرام' => "فروش گروه تلگرام خود را در بزرگترین بازار آنلاین ثبت کنید. {$adCount}+ آگهی فعال، قیمت‌گذاری آزاد، معاملات امن.",
                'کانال تلگرام' => "فروش کانال تلگرام خود را به راحتی انجام دهید. {$adCount}+ آگهی فعال، قیمت‌گذاری آزاد، معاملات امن و سریع.",
                'پیج اینستاگرام' => "فروش پیج اینستاگرام خود را در بازار آنلاین انجام دهید. {$adCount}+ آگهی فعال، قیمت‌گذاری آزاد، معاملات امن.",
                'سایت آماده' => "فروش سایت آماده خود را در بازار آنلاین انجام دهید. {$adCount}+ آگهی فعال، قیمت‌گذاری آزاد، معاملات امن.",
                'دامنه' => "فروش دامنه خود را در بازار آنلاین انجام دهید. {$adCount}+ آگهی فعال، قیمت‌گذاری آزاد، معاملات امن.",
                'کانال یوتیوب' => "فروش کانال یوتیوب خود را در بازار آنلاین انجام دهید. {$adCount}+ آگهی فعال، قیمت‌گذاری آزاد، معاملات امن.",
            ],
        ];
        
        return $descriptions[$action][$label] ?? "{$action} {$label} در بزرگترین بازار آنلاین. آگهی‌های واقعی، قیمت‌های شفاف، معاملات امن.";
    }
    
    /**
     * Generate dynamic Persian content
     */
    private function generateContent($action, $typeInfo, $isBuy)
    {
        $label = $typeInfo['label'];
        
        if ($isBuy) {
            return [
                'intro' => "اگر به دنبال {$label} با کیفیت و معتبر هستید، در این صفحه می‌توانید از بین {$label}های مختلف که توسط مالکان واقعی به فروش گذاشته شده‌اند، انتخاب کنید. تمام آگهی‌ها به صورت مستقیم از مالک اصلی ارائه می‌شوند و قیمت‌ها شفاف و بدون واسطه هستند.",
                'benefits' => [
                    'آگهی‌های واقعی و معتبر از مالکان اصلی',
                    'قیمت‌های شفاف و بدون واسطه',
                    'معاملات امن با سیستم پرداخت مطمئن',
                    'امکان مشاهده جزئیات کامل قبل از خرید',
                    'پشتیبانی ۲۴ ساعته برای راهنمایی',
                ],
                'guide' => [
                    'مرحله ۱: در لیست زیر {$label} مورد نظر خود را انتخاب کنید',
                    'مرحله ۲: جزئیات کامل آگهی را مطالعه کنید',
                    'مرحله ۳: در صورت تمایل، با مالک ارتباط برقرار کنید',
                    'مرحله ۴: پس از توافق، معامله را به صورت امن انجام دهید',
                ],
                'trust' => 'تمام آگهی‌های این صفحه توسط مالکان واقعی ثبت شده‌اند و ما تضمین می‌کنیم که اطلاعات ارائه شده معتبر و قابل اعتماد است.',
            ];
        } else {
            return [
                'intro' => "اگر {$label} دارید و می‌خواهید آن را به فروش برسانید، اینجا بهترین مکان برای شماست. در بزرگترین بازار آنلاین {$label}، می‌توانید آگهی خود را به رایگان ثبت کنید و مستقیماً با خریداران واقعی ارتباط برقرار کنید.",
                'benefits' => [
                    'ثبت آگهی رایگان و بدون محدودیت',
                    'قیمت‌گذاری آزاد توسط شما',
                    'ارتباط مستقیم با خریداران واقعی',
                    'معاملات امن و سریع',
                    'پشتیبانی کامل در تمام مراحل',
                ],
                'guide' => [
                    'مرحله ۱: روی دکمه "ثبت آگهی" کلیک کنید',
                    'مرحله ۲: اطلاعات {$label} خود را وارد کنید',
                    'مرحله ۳: قیمت مورد نظر خود را تعیین کنید',
                    'مرحله ۴: آگهی خود را منتشر کنید و منتظر تماس خریداران بمانید',
                ],
                'trust' => 'ما با سیستم امنیتی پیشرفته، از شما و خریداران محافظت می‌کنیم و تمام معاملات در محیطی امن انجام می‌شود.',
            ];
        }
    }
    
    /**
     * Get related pages for internal linking
     */
    private function getRelatedPages($action, $type)
    {
        $oppositeAction = $action === 'خرید' ? 'فروش' : 'خرید';
        
        $related = [
            [
                'title' => $oppositeAction . ' ' . str_replace('-', ' ', $type),
                'url' => route('seo.landing', ['action' => $oppositeAction, 'type' => $type]),
                'description' => $oppositeAction . ' ' . str_replace('-', ' ', $type) . ' در بازار آنلاین',
            ],
        ];
        
        // All available types
        $allTypes = [
            'گروه-تلگرام' => 'گروه تلگرام',
            'کانال-تلگرام' => 'کانال تلگرام',
            'پیج-اینستاگرام' => 'پیج اینستاگرام',
            'سایت-آماده' => 'سایت آماده',
            'دامنه' => 'دامنه',
            'کانال-یوتیوب' => 'کانال یوتیوب',
        ];
        
        // Add 2-3 related types (excluding current and opposite)
        $relatedTypes = array_filter($allTypes, function($key) use ($type) {
            return $key !== $type;
        }, ARRAY_FILTER_USE_KEY);
        
        // Take first 2 related types
        $relatedTypes = array_slice($relatedTypes, 0, 2, true);
        
        foreach ($relatedTypes as $relatedType => $relatedLabel) {
            $related[] = [
                'title' => $action . ' ' . $relatedLabel,
                'url' => route('seo.landing', ['action' => $action, 'type' => $relatedType]),
                'description' => $action . ' ' . $relatedLabel . ' با قیمت‌های واقعی',
            ];
        }
        
        return $related;
    }
    
    /**
     * Get FAQ data for Schema.org
     */
    private function getFAQs($action, $label)
    {
        if ($action === 'خرید') {
            return [
                [
                    'question' => "چگونه می‌توانم {$label} بخرم؟",
                    'answer' => "برای خرید {$label}، ابتدا در لیست آگهی‌ها {$label} مورد نظر خود را انتخاب کنید. سپس جزئیات کامل را مطالعه کرده و در صورت تمایل با مالک ارتباط برقرار کنید. پس از توافق، معامله را به صورت امن انجام دهید.",
                ],
                [
                    'question' => "قیمت {$label} چقدر است؟",
                    'answer' => "قیمت {$label}ها بسته به تعداد اعضا، محتوا و عوامل دیگر متفاوت است. در هر آگهی قیمت به صورت شفاف نمایش داده شده است.",
                ],
                [
                    'question' => "آیا آگهی‌ها واقعی هستند؟",
                    'answer' => "بله، تمام آگهی‌ها توسط مالکان واقعی ثبت شده‌اند و ما تضمین می‌کنیم که اطلاعات ارائه شده معتبر است.",
                ],
                [
                    'question' => "معامله چگونه انجام می‌شود؟",
                    'answer' => "پس از انتخاب {$label} و توافق با مالک، می‌توانید معامله را به صورت مستقیم و امن انجام دهید. ما سیستم پرداخت مطمئنی برای شما فراهم کرده‌ایم.",
                ],
            ];
        } else {
            return [
                [
                    'question' => "چگونه می‌توانم {$label} خود را بفروشم؟",
                    'answer' => "برای فروش {$label} خود، روی دکمه 'ثبت آگهی' کلیک کنید و اطلاعات {$label} خود را وارد کنید. سپس قیمت مورد نظر را تعیین کرده و آگهی را منتشر کنید.",
                ],
                [
                    'question' => "هزینه ثبت آگهی چقدر است؟",
                    'answer' => "ثبت آگهی در سایت ما رایگان است و شما می‌توانید بدون هیچ هزینه‌ای آگهی خود را منتشر کنید.",
                ],
                [
                    'question' => "چقدر طول می‌کشد تا {$label} من فروش برود؟",
                    'answer' => "زمان فروش بستگی به قیمت، کیفیت و عوامل دیگر دارد. معمولاً آگهی‌های با قیمت مناسب و اطلاعات کامل سریع‌تر فروش می‌روند.",
                ],
                [
                    'question' => "آیا معاملات امن هستند؟",
                    'answer' => "بله، ما با سیستم امنیتی پیشرفته از شما و خریداران محافظت می‌کنیم و تمام معاملات در محیطی امن انجام می‌شود.",
                ],
            ];
        }
    }
}

