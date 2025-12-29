@php
    // FAQPage Schema for homepage - Define once, use in both schema and display
    $homepageFAQs = [
        [
            'question' => 'از کجا گروه تلگرام بخریم؟',
            'answer' => 'گروهباز بزرگترین بازار آنلاین خرید و فروش گروه‌های تلگرام در ایران است. شما می‌توانید در صفحه خرید گروه تلگرام، از بین آگهی‌های واقعی و معتبر که مستقیماً از مالکان ارائه می‌شوند، انتخاب کنید. تمام آگهی‌ها دارای قیمت شفاف و بدون واسطه هستند.'
        ],
        [
            'question' => 'بهترین سایت خرید کانال تلگرام کدام است؟',
            'answer' => 'گروهباز یکی از معتبرترین پلتفرم‌های خرید و فروش کانال تلگرام در ایران است. این پلتفرم امکان ارتباط مستقیم با مالکان، مشاهده آگهی‌های واقعی و معاملات امن را فراهم می‌کند. ثبت آگهی در گروهباز رایگان است و قیمت‌ها شفاف و بدون واسطه هستند.'
        ],
        [
            'question' => 'آیا خرید گروه تلگرام امن است؟',
            'answer' => 'بله، گروهباز با سیستم امنیتی پیشرفته از خریداران و فروشندگان محافظت می‌کند. تمام آگهی‌ها توسط مالکان واقعی ثبت می‌شوند و اطلاعات ارائه شده معتبر است. معاملات در محیطی امن انجام می‌شود و سیستم پرداخت مطمئنی برای کاربران فراهم شده است.'
        ],
        [
            'question' => 'چطور کانال تلگرام خود را بفروشیم؟',
            'answer' => 'برای فروش کانال تلگرام خود در گروهباز، روی دکمه "ثبت آگهی" کلیک کنید. اطلاعات کانال خود را وارد کنید، قیمت مورد نظر را تعیین کنید و آگهی را منتشر کنید. ثبت آگهی رایگان است و شما می‌توانید مستقیماً با خریداران واقعی ارتباط برقرار کنید.'
        ],
        [
            'question' => 'قیمت گروه تلگرام چقدر است؟',
            'answer' => 'قیمت گروه‌های تلگرام بسته به تعداد اعضا، محتوا، فعالیت اعضا و عوامل دیگر متفاوت است. در گروهباز، قیمت هر آگهی به صورت شفاف نمایش داده می‌شود و شما می‌توانید قبل از خرید، جزئیات کامل را مشاهده کنید.'
        ],
        [
            'question' => 'آیا آگهی‌های گروهباز واقعی هستند؟',
            'answer' => 'بله، تمام آگهی‌های موجود در گروهباز توسط مالکان واقعی ثبت شده‌اند. ما تضمین می‌کنیم که اطلاعات ارائه شده معتبر و قابل اعتماد است. کاربران می‌توانند قبل از خرید، با مالک ارتباط برقرار کرده و جزئیات را بررسی کنند.'
        ],
        [
            'question' => 'هزینه ثبت آگهی در گروهباز چقدر است؟',
            'answer' => 'ثبت آگهی در گروهباز کاملاً رایگان است. شما می‌توانید بدون هیچ هزینه‌ای آگهی خود را منتشر کنید و با خریداران واقعی ارتباط برقرار کنید. قیمت‌گذاری آزاد است و شما می‌توانید قیمت مورد نظر خود را تعیین کنید.'
        ],
        [
            'question' => 'چطور می‌توانم از کلاهبرداری جلوگیری کنم؟',
            'answer' => 'گروهباز توصیه می‌کند که قبل از انجام معامله، با مالک ارتباط برقرار کنید و جزئیات را به دقت بررسی کنید. از انجام معاملات خارج از پلتفرم خودداری کنید و از سیستم پرداخت امن استفاده کنید. در صورت مشاهده هرگونه مشکوک، با پشتیبانی تماس بگیرید.'
        ]
    ];
    
    $faqSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'FAQPage',
        'mainEntity' => []
    ];
    
    foreach ($homepageFAQs as $faq) {
        $faqSchema['mainEntity'][] = [
            '@type' => 'Question',
            'name' => $faq['question'],
            'acceptedAnswer' => [
                '@type' => 'Answer',
                'text' => $faq['answer']
            ]
        ];
    }
@endphp

<div class="container-fluid px-3 px-md-5">
    <!-- Hero Section -->
    <section class="hero-section mb-5" style="min-height: 60vh; display: flex; align-items: center; position: relative; overflow: hidden;">
        <div class="container position-relative" style="z-index: 1;">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-5 mb-lg-0">
                    <h1 class="display-3 fw-bold mb-4" style="background: linear-gradient(135deg, #00f0ff, #b026ff); -webkit-background-clip: text; -webkit-text-fill-color: transparent; line-height: 1.2;">
                        خرید و فروش گروه‌ و کانال های تلگرام
                    </h1>
                    <p class="lead mb-4" style="color: rgba(255,255,255,0.8); font-size: 1.25rem;">
                        بزرگترین بازار خرید و فروش گروه‌های تلگرام با سیستم مزایده و فروش مستقیم
                    </p>
                    <div class="d-flex gap-3 flex-wrap">
                        <a href="{{ route('store.index') }}" class="btn btn-modern">
                            <i class="bi bi-shop me-2"></i> مشاهده فروشگاه
                        </a>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="glass-card p-4 text-center">
                        <div class="row g-3">
                            <div class="col-6">
                                <div class="counter" style="font-size: 2.5rem;">{{ number_format($settings->active_ads ?? 0) }}</div>
                                <p class="text-muted mb-0 mt-2" style="color: #9ca3af;">آگهی فعال</p>
                            </div>
                            <div class="col-6">
                                <div class="counter" style="font-size: 2.5rem;">{{ number_format($settings->total_members ?? 0) }}</div>
                                <p class="text-muted mb-0 mt-2" style="color: #9ca3af;">کل اعضا</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Advanced Search Section -->
    <section class="mb-5">
        <div class="glass-card p-4 p-md-5">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="mb-0 fw-bold search-section">
                    <i class="bi bi-funnel me-2" style="color: #00f0ff;"></i>
                    جستجوی پیشرفته
                </h2>
                <button type="button" class="btn btn-link text-decoration-none p-0" style="color: #e5e7eb;" wire:click="resetFilters">
                    <i class="bi bi-arrow-counterclockwise me-1"></i> پاک کردن فیلترها
                </button>
            </div>
            
            <form wire:submit.prevent="search">
                <!-- Row 1: Search Query & Category -->
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label mb-2 d-block fw-semibold" style="color: #ffffff; font-size: 14px;">
                            <i class="bi bi-search me-1" style="color: #00f0ff;"></i> جستجو در عنوان و توضیحات
                        </label>
                        <input type="text" 
                               class="modern-input w-100" 
                               placeholder="مثال: گروه فناوری یا برنامه‌نویسی"
                               wire:model.debounce.500ms="searchQuery"
                               style="color: #ffffff !important;">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label mb-2 d-block fw-semibold" for="home_category" style="color: #ffffff; font-size: 14px;">
                            <i class="bi bi-grid-3x3-gap me-1" style="color: #00f0ff;"></i> دسته‌بندی
                        </label>
                        <select id="home_category" class="modern-input w-100" wire:model.live="category" style="color: #ffffff !important;">
                            <option value="">همه دسته‌ها</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Row 2: Type Toggle & Members -->
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label mb-2 d-block fw-semibold" style="color: #ffffff; font-size: 14px;">
                            <i class="bi bi-tags me-1" style="color: #00f0ff;"></i> نوع آگهی
                        </label>
                        <div class="segmented-control">
                            <button type="button" 
                                    class="{{ $listingType === 'all' ? 'active' : '' }}" 
                                    wire:click="$set('listingType', 'all')">
                                <i class="bi bi-grid me-1"></i> همه
                            </button>
                            <button type="button" 
                                    class="{{ $listingType === 'auction' ? 'active' : '' }}" 
                                    wire:click="$set('listingType', 'auction')">
                                <i class="bi bi-hammer me-1"></i> مزایده
                            </button>
                            <button type="button" 
                                    class="{{ $listingType === 'normal' ? 'active' : '' }}" 
                                    wire:click="$set('listingType', 'normal')">
                                <i class="bi bi-shop me-1"></i> عادی
                            </button>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label mb-2 d-block fw-semibold" style="color: #ffffff; font-size: 14px;">
                            <i class="bi bi-people me-1" style="color: #00f0ff;"></i> محدوده تعداد اعضا
                        </label>
                        <div class="row g-2">
                            <div class="col-6">
                                <label class="form-label small mb-1 d-block" style="color: rgba(255,255,255,0.7); font-size: 12px;">حداقل</label>
                                <input type="text" 
                                       class="modern-input w-100" 
                                       placeholder="0"
                                       wire:model.debounce.500ms="minMembers" 
                                       pattern="[0-9]*"
                                       inputmode="numeric"
                                       onkeypress="return event.charCode >= 48 && event.charCode <= 57"
                                       style="color: #ffffff !important;">
                            </div>
                            <div class="col-6">
                                <label class="form-label small mb-1 d-block" style="color: rgba(255,255,255,0.7); font-size: 12px;">حداکثر</label>
                                <input type="text" 
                                       class="modern-input w-100" 
                                       placeholder="نامحدود"
                                       wire:model.debounce.500ms="maxMembers" 
                                       pattern="[0-9]*"
                                       inputmode="numeric"
                                       onkeypress="return event.charCode >= 48 && event.charCode <= 57"
                                       style="color: #ffffff !important;">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Row 3: Price Range -->
                <div class="row g-3 mb-4">
                    <div class="col-12">
                        <label class="form-label mb-3 d-block fw-semibold" style="color: #ffffff; font-size: 14px;">
                            <i class="bi bi-currency-exchange me-1" style="color: #00f0ff;"></i> محدوده قیمت (تومان)
                        </label>
                        <div class="glass-card p-3" style="background: rgba(255,255,255,0.03);">
                            <div class="row g-3 align-items-center">
                                <div class="col-md-2">
                                    <label class="form-label small mb-1 d-block text-center" style="color: rgba(255,255,255,0.7); font-size: 12px;">حداقل قیمت</label>
                                    <input type="text" 
                                           class="modern-input text-center" 
                                           wire:model.debounce.500ms="minPrice" 
                                           pattern="[0-9]*"
                                           inputmode="numeric"
                                           onkeypress="return event.charCode >= 48 && event.charCode <= 57"
                                           placeholder="0"
                                           style="font-weight: 600; color: #ffffff !important;">
                                </div>
                                <div class="col-md-8">
                                    <label class="form-label small mb-2 d-block text-center" style="color: rgba(255,255,255,0.7); font-size: 12px;">حداکثر قیمت</label>
                                    <div class="position-relative">
                                        <input type="range" 
                                               class="range-slider w-100" 
                                               min="0" 
                                               max="20000000" 
                                               step="100000" 
                                               wire:model.live="maxPrice"
                                               id="priceRange"
                                               x-on:input="$el.nextElementSibling.querySelector('.range-value-display').textContent = new Intl.NumberFormat('fa-IR').format($el.value)">
                                        <div class="d-flex justify-content-between mt-2">
                                            <small class="text-muted">۰ تومان</small>
                                            <small class="text-muted">۲۰,۰۰۰,۰۰۰ تومان</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label small mb-1 d-block text-center" style="color: rgba(255,255,255,0.7); font-size: 12px;">مقدار انتخاب شده</label>
                                    <div class="range-value-display text-center" style="color: #ffffff; font-weight: 600; padding: 8px 0;">
                                        {{ number_format($maxPrice) }} <small style="font-size: 0.75rem; color: rgba(255,255,255,0.7);">تومان</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="d-flex justify-content-end gap-2">
                    <button type="button" 
                            class="btn btn-modern" 
                            style="background: rgba(255,255,255,0.1); box-shadow: none;"
                            wire:click="resetFilters">
                        <i class="bi bi-x-circle me-2"></i> پاک کردن
                    </button>
                    <button type="submit" class="btn btn-modern">
                        <i class="bi bi-search me-2"></i> جستجو
                    </button>
                </div>
            </form>
        </div>
    </section>

    <!-- Latest Ads Slider -->
    @if($ads->count() > 0)
    <section class="mb-5" wire:key="ads-slider-{{ $ads->count() }}">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold" style="color: #ffffff;">
                <i class="bi bi-clock-history me-2" style="color: #00f0ff;"></i>
                جدیدترین آگهی‌ها
            </h2>
            <div class="d-flex gap-3">
                <a href="{{ route('store.index') }}" class="text-decoration-none" style="color: #00f0ff;">
                    مشاهده همه <i class="bi bi-arrow-left"></i>
                </a>
            </div>
        </div>
        <div class="swiper ads-swiper" id="ads-swiper-{{ $ads->count() }}">
            <div class="swiper-wrapper">
                @foreach($ads as $ad)
                    <div class="swiper-slide">
                        @include('components.ad-card', ['ad' => $ad])
                    </div>
                @endforeach
            </div>
            <!-- Navigation buttons -->
            <div class="swiper-button-prev"></div>
            <div class="swiper-button-next"></div>
            <!-- Pagination dots -->
            <div class="swiper-pagination"></div>
        </div>
    </section>
    @endif

    <!-- SEO Landing Pages Quick Links - Internal Linking -->
    <section class="mb-5">
        <div class="glass-card p-4">
            <h2 class="fw-bold mb-4" style="color: #ffffff; font-size: 1.5rem;">
                <i class="bi bi-link-45deg me-2" style="color: #00f0ff;"></i>
                خرید و فروش سریع
            </h2>
            <div class="row g-3">
                <!-- Telegram Groups -->
                <div class="col-md-6 col-lg-4">
                    <a href="{{ route('seo.landing', ['action' => 'خرید', 'type' => 'گروه-تلگرام']) }}" 
                       class="d-block p-3 glass-card text-decoration-none"
                       style="transition: all 0.3s; background: rgba(255,255,255,0.03);">
                        <h3 class="fw-bold mb-2" style="color: #00f0ff; font-size: 1.1rem;">
                            <i class="bi bi-telegram me-2"></i>خرید گروه تلگرام
                        </h3>
                        <p class="mb-0 text-muted small">خرید گروه تلگرام با قیمت واقعی و بدون واسطه</p>
                    </a>
                </div>
                <div class="col-md-6 col-lg-4">
                    <a href="{{ route('seo.landing', ['action' => 'فروش', 'type' => 'گروه-تلگرام']) }}" 
                       class="d-block p-3 glass-card text-decoration-none"
                       style="transition: all 0.3s; background: rgba(255,255,255,0.03);">
                        <h5 class="fw-bold mb-2" style="color: #00f0ff; font-size: 1.1rem;">
                            <i class="bi bi-telegram me-2"></i>فروش گروه تلگرام
                        </h5>
                        <p class="mb-0 text-muted small">فروش گروه تلگرام خود را رایگان ثبت کنید</p>
                    </a>
                </div>
                
                <!-- Telegram Channels -->
                <div class="col-md-6 col-lg-4">
                    <a href="{{ route('seo.landing', ['action' => 'خرید', 'type' => 'کانال-تلگرام']) }}" 
                       class="d-block p-3 glass-card text-decoration-none"
                       style="transition: all 0.3s; background: rgba(255,255,255,0.03);">
                        <h5 class="fw-bold mb-2" style="color: #00f0ff; font-size: 1.1rem;">
                            <i class="bi bi-broadcast me-2"></i>خرید کانال تلگرام
                        </h5>
                        <p class="mb-0 text-muted small">خرید کانال تلگرام با قیمت‌های شفاف</p>
                    </a>
                </div>
                <div class="col-md-6 col-lg-4">
                    <a href="{{ route('seo.landing', ['action' => 'فروش', 'type' => 'کانال-تلگرام']) }}" 
                       class="d-block p-3 glass-card text-decoration-none"
                       style="transition: all 0.3s; background: rgba(255,255,255,0.03);">
                        <h5 class="fw-bold mb-2" style="color: #00f0ff; font-size: 1.1rem;">
                            <i class="bi bi-broadcast me-2"></i>فروش کانال تلگرام
                        </h5>
                        <p class="mb-0 text-muted small">فروش کانال تلگرام خود را انجام دهید</p>
                    </a>
                </div>
                
                <!-- Instagram Pages -->
                <div class="col-md-6 col-lg-4">
                    <a href="{{ route('seo.landing', ['action' => 'خرید', 'type' => 'پیج-اینستاگرام']) }}" 
                       class="d-block p-3 glass-card text-decoration-none"
                       style="transition: all 0.3s; background: rgba(255,255,255,0.03);">
                        <h5 class="fw-bold mb-2" style="color: #00f0ff; font-size: 1.1rem;">
                            <i class="bi bi-instagram me-2"></i>خرید پیج اینستاگرام
                        </h5>
                        <p class="mb-0 text-muted small">خرید پیج اینستاگرام با فالوور واقعی</p>
                    </a>
                </div>
                <div class="col-md-6 col-lg-4">
                    <a href="{{ route('seo.landing', ['action' => 'فروش', 'type' => 'پیج-اینستاگرام']) }}" 
                       class="d-block p-3 glass-card text-decoration-none"
                       style="transition: all 0.3s; background: rgba(255,255,255,0.03);">
                        <h5 class="fw-bold mb-2" style="color: #00f0ff; font-size: 1.1rem;">
                            <i class="bi bi-instagram me-2"></i>فروش پیج اینستاگرام
                        </h5>
                        <p class="mb-0 text-muted small">فروش پیج اینستاگرام خود را انجام دهید</p>
                    </a>
                </div>
                
                <!-- Websites -->
                <div class="col-md-6 col-lg-4">
                    <a href="{{ route('seo.landing', ['action' => 'خرید', 'type' => 'سایت-آماده']) }}" 
                       class="d-block p-3 glass-card text-decoration-none"
                       style="transition: all 0.3s; background: rgba(255,255,255,0.03);">
                        <h5 class="fw-bold mb-2" style="color: #00f0ff; font-size: 1.1rem;">
                            <i class="bi bi-globe me-2"></i>خرید سایت آماده
                        </h5>
                        <p class="mb-0 text-muted small">خرید سایت آماده با ترافیک و درآمد</p>
                    </a>
                </div>
                <div class="col-md-6 col-lg-4">
                    <a href="{{ route('seo.landing', ['action' => 'فروش', 'type' => 'سایت-آماده']) }}" 
                       class="d-block p-3 glass-card text-decoration-none"
                       style="transition: all 0.3s; background: rgba(255,255,255,0.03);">
                        <h5 class="fw-bold mb-2" style="color: #00f0ff; font-size: 1.1rem;">
                            <i class="bi bi-globe me-2"></i>فروش سایت آماده
                        </h5>
                        <p class="mb-0 text-muted small">فروش سایت آماده خود را انجام دهید</p>
                    </a>
                </div>
                
                <!-- Domains -->
                <div class="col-md-6 col-lg-4">
                    <a href="{{ route('seo.landing', ['action' => 'خرید', 'type' => 'دامنه']) }}" 
                       class="d-block p-3 glass-card text-decoration-none"
                       style="transition: all 0.3s; background: rgba(255,255,255,0.03);">
                        <h5 class="fw-bold mb-2" style="color: #00f0ff; font-size: 1.1rem;">
                            <i class="bi bi-link-45deg me-2"></i>خرید دامنه
                        </h5>
                        <p class="mb-0 text-muted small">خرید دامنه با پسوندهای مختلف</p>
                    </a>
                </div>
                <div class="col-md-6 col-lg-4">
                    <a href="{{ route('seo.landing', ['action' => 'فروش', 'type' => 'دامنه']) }}" 
                       class="d-block p-3 glass-card text-decoration-none"
                       style="transition: all 0.3s; background: rgba(255,255,255,0.03);">
                        <h5 class="fw-bold mb-2" style="color: #00f0ff; font-size: 1.1rem;">
                            <i class="bi bi-link-45deg me-2"></i>فروش دامنه
                        </h5>
                        <p class="mb-0 text-muted small">فروش دامنه خود را انجام دهید</p>
                    </a>
                </div>
                
                <!-- YouTube Channels -->
                <div class="col-md-6 col-lg-4">
                    <a href="{{ route('seo.landing', ['action' => 'خرید', 'type' => 'کانال-یوتیوب']) }}" 
                       class="d-block p-3 glass-card text-decoration-none"
                       style="transition: all 0.3s; background: rgba(255,255,255,0.03);">
                        <h5 class="fw-bold mb-2" style="color: #00f0ff; font-size: 1.1rem;">
                            <i class="bi bi-youtube me-2"></i>خرید کانال یوتیوب
                        </h5>
                        <p class="mb-0 text-muted small">خرید کانال یوتیوب با مشترک و درآمد</p>
                    </a>
                </div>
                <div class="col-md-6 col-lg-4">
                    <a href="{{ route('seo.landing', ['action' => 'فروش', 'type' => 'کانال-یوتیوب']) }}" 
                       class="d-block p-3 glass-card text-decoration-none"
                       style="transition: all 0.3s; background: rgba(255,255,255,0.03);">
                        <h5 class="fw-bold mb-2" style="color: #00f0ff; font-size: 1.1rem;">
                            <i class="bi bi-youtube me-2"></i>فروش کانال یوتیوب
                        </h5>
                        <p class="mb-0 text-muted small">فروش کانال یوتیوب خود را انجام دهید</p>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Popular Categories -->
    <section class="mb-5">
        <h2 class="fw-bold mb-4" style="color: #ffffff;">
            <i class="bi bi-grid-3x3-gap me-2" style="color: #b026ff;"></i>
            دسته‌بندی‌های محبوب
        </h2>
        <div class="row g-4">
            @forelse($categories as $category)
                <div class="col-6 col-md-4 col-lg-2">
                    <div class="glass-card p-4 text-center" 
                         style="cursor: pointer;" 
                         x-data="{ hover: false }" 
                         @mouseenter="hover = true" 
                         @mouseleave="hover = false"
                         wire:click="$set('category', '{{ $category->id }}')"
                         @click="window.scrollTo({ top: 0, behavior: 'smooth' })">
                        <div class="category-icon mx-auto mb-3" :class="hover ? 'neon-glow-cyan' : ''">
                            <i class="bi {{ $category->icon ?? 'bi-grid-3x3-gap' }}"></i>
                        </div>
                        <h3 class="mb-1" style="color: #ffffff; font-size: 1rem;">{{ $category->name }}</h3>
                        <small class="text-muted">{{ number_format($category->activeAds()->count()) }} آگهی</small>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5 text-muted">
                    <i class="bi bi-inbox" style="font-size: 3rem; opacity: 0.5;"></i>
                    <p class="mt-3 mb-0">دسته‌بندی‌ای وجود ندارد</p>
                </div>
            @endforelse
        </div>
    </section>

    <!-- Entity Definition Section - AEO Optimization -->
    <section class="mb-5">
        <div class="glass-card p-4 p-md-5">
            <h2 class="fw-bold mb-4" style="color: #ffffff; font-size: 2rem;">
                <i class="bi bi-info-circle me-2" style="color: #00f0ff;"></i>
                گروهباز چیست؟
            </h2>
            <div class="row g-4">
                <div class="col-md-6">
                    <h3 class="fw-bold mb-3" style="color: #ffffff; font-size: 1.5rem;">تعریف پلتفرم</h3>
                    <p class="mb-3" style="color: #e5e7eb; line-height: 1.8; font-size: 1.05rem;">
                        <strong>گروهباز</strong> یک بازار آنلاین ایرانی است که امکان خرید و فروش گروه‌های تلگرام، کانال‌های تلگرام، پیج‌های اینستاگرام، سایت‌های آماده، دامنه‌ها و کانال‌های یوتیوب را فراهم می‌کند.
                    </p>
                    <p class="mb-3" style="color: #e5e7eb; line-height: 1.8; font-size: 1.05rem;">
                        کاربران می‌توانند به صورت مستقیم با مالکان واقعی ارتباط برقرار کنند و آگهی‌های معتبر را مشاهده کنند.
                    </p>
                </div>
                <div class="col-md-6">
                    <h3 class="fw-bold mb-3" style="color: #ffffff; font-size: 1.5rem;">مشکلی که حل می‌کند</h3>
                    <ul class="list-unstyled" style="color: #e5e7eb; line-height: 2;">
                        <li class="mb-2">
                            <i class="bi bi-check-circle me-2" style="color: #39ff14;"></i>
                            حذف واسطه‌ها در خرید و فروش
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-check-circle me-2" style="color: #39ff14;"></i>
                            دسترسی به آگهی‌های واقعی و معتبر
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-check-circle me-2" style="color: #39ff14;"></i>
                            قیمت‌گذاری شفاف و بدون پنهان‌کاری
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-check-circle me-2" style="color: #39ff14;"></i>
                            معاملات امن با سیستم پرداخت مطمئن
                        </li>
                    </ul>
                </div>
            </div>
            <div class="mt-4 pt-4" style="border-top: 1px solid rgba(255,255,255,0.1);">
                <h3 class="fw-bold mb-3" style="color: #ffffff; font-size: 1.5rem;">برای چه کسانی مناسب است؟</h3>
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="glass-card p-3" style="background: rgba(0, 240, 255, 0.05);">
                            <h4 class="fw-bold mb-2" style="color: #00f0ff; font-size: 1.1rem;">
                                <i class="bi bi-cart-plus me-2"></i>خریداران
                            </h4>
                            <p class="mb-0 small" style="color: #e5e7eb;">
                                افرادی که به دنبال خرید گروه تلگرام، کانال، پیج اینستاگرام یا سایر دارایی‌های دیجیتال هستند.
                            </p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="glass-card p-3" style="background: rgba(176, 38, 255, 0.05);">
                            <h4 class="fw-bold mb-2" style="color: #b026ff; font-size: 1.1rem;">
                                <i class="bi bi-shop me-2"></i>فروشندگان
                            </h4>
                            <p class="mb-0 small" style="color: #e5e7eb;">
                                مالکان گروه‌ها، کانال‌ها و پیج‌هایی که می‌خواهند دارایی خود را به فروش برسانند.
                            </p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="glass-card p-3" style="background: rgba(57, 255, 20, 0.05);">
                            <h4 class="fw-bold mb-2" style="color: #39ff14; font-size: 1.1rem;">
                                <i class="bi bi-people me-2"></i>کاربران عمومی
                            </h4>
                            <p class="mb-0 small" style="color: #e5e7eb;">
                                هر کسی که به دنبال مشاهده و مقایسه آگهی‌های مختلف در یک مکان است.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- AI-Friendly Q&A Section - Critical for AEO -->
    <section class="mb-5">
        <div class="glass-card p-4 p-md-5">
            <h2 class="fw-bold mb-4" style="color: #ffffff; font-size: 2rem;">
                <i class="bi bi-question-circle me-2" style="color: #00f0ff;"></i>
                سوالات متداول
            </h2>
            <div class="accordion" id="homepageFAQAccordion">
                @foreach($homepageFAQs as $index => $faq)
                <div class="accordion-item mb-3" style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 12px;">
                    <h3 class="accordion-header">
                        <button class="accordion-button {{ $index > 0 ? 'collapsed' : '' }}" 
                                type="button" 
                                data-bs-toggle="collapse" 
                                data-bs-target="#homepageFaq{{ $index }}"
                                style="background: rgba(255,255,255,0.05); color: #ffffff; border: none;">
                            {{ $faq['question'] }}
                        </button>
                    </h3>
                    <div id="homepageFaq{{ $index }}" 
                         class="accordion-collapse collapse {{ $index === 0 ? 'show' : '' }}"
                         data-bs-parent="#homepageFAQAccordion">
                        <div class="accordion-body" style="color: #e5e7eb; line-height: 1.8;">
                            {{ $faq['answer'] }}
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Transparency & Trust Section - E-E-A-T Signals -->
    <section class="mb-5">
        <div class="glass-card p-4 p-md-5">
            <h2 class="fw-bold mb-4" style="color: #ffffff; font-size: 2rem;">
                <i class="bi bi-shield-check me-2" style="color: #39ff14;"></i>
                امنیت و اعتماد
            </h2>
            <div class="row g-4">
                <div class="col-md-6">
                    <h3 class="fw-bold mb-3" style="color: #ffffff; font-size: 1.3rem;">تایید آگهی‌ها</h3>
                    <p class="mb-3" style="color: #e5e7eb; line-height: 1.8;">
                        تمام آگهی‌های ثبت شده در گروهباز توسط تیم ما بررسی می‌شوند. ما اطمینان حاصل می‌کنیم که اطلاعات ارائه شده معتبر و از مالکان واقعی است.
                    </p>
                    <ul class="list-unstyled" style="color: #e5e7eb;">
                        <li class="mb-2">
                            <i class="bi bi-check2-circle me-2" style="color: #39ff14;"></i>
                            بررسی اطلاعات آگهی قبل از انتشار
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-check2-circle me-2" style="color: #39ff14;"></i>
                            تایید هویت مالکان
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-check2-circle me-2" style="color: #39ff14;"></i>
                            حذف آگهی‌های مشکوک
                        </li>
                    </ul>
                </div>
                <div class="col-md-6">
                    <h3 class="fw-bold mb-3" style="color: #ffffff; font-size: 1.3rem;">مسئولیت کاربران</h3>
                    <p class="mb-3" style="color: #e5e7eb; line-height: 1.8;">
                        کاربران باید قبل از انجام معامله، اطلاعات را به دقت بررسی کنند و با مالک ارتباط برقرار کنند.
                    </p>
                    <ul class="list-unstyled" style="color: #e5e7eb;">
                        <li class="mb-2">
                            <i class="bi bi-exclamation-triangle me-2" style="color: #ff006e;"></i>
                            همیشه قبل از خرید، جزئیات را بررسی کنید
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-exclamation-triangle me-2" style="color: #ff006e;"></i>
                            از سیستم پرداخت امن استفاده کنید
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-exclamation-triangle me-2" style="color: #ff006e;"></i>
                            در صورت مشکوک بودن، با پشتیبانی تماس بگیرید
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Trust Badges & Statistics -->
    <section class="mb-5">
        <div class="row g-4">
            <div class="col-md-3 col-6">
                <div class="glass-card p-4 text-center">
                    <div class="trust-badge mx-auto mb-3">
                        <i class="bi bi-shield-check"></i>
                        <span>امن و قابل اعتماد</span>
                    </div>
                    <h4 class="counter mb-0" style="color: #ffffff;">{{ $settings->satisfaction_percent ?? 100 }}٪</h4>
                    <small class="text-muted" style="color: #9ca3af;">رضایت کاربران</small>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="glass-card p-4 text-center">
                    <i class="bi bi-people-fill" style="font-size: 2.5rem; color: #00f0ff; margin-bottom: 1rem;"></i>
                    <h4 class="counter mb-0" style="color: #ffffff;">{{ number_format($settings->active_users ?? 0) }}</h4>
                    <small class="text-muted" style="color: #9ca3af;">کاربر فعال</small>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="glass-card p-4 text-center">
                    <i class="bi bi-check-circle-fill" style="font-size: 2.5rem; color: #39ff14; margin-bottom: 1rem;"></i>
                    <h4 class="counter mb-0" style="color: #ffffff;">{{ number_format($settings->successful_deals ?? 0) }}</h4>
                    <small class="text-muted" style="color: #9ca3af;">معامله موفق</small>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="glass-card p-4 text-center">
                    <i class="bi bi-star-fill" style="font-size: 2.5rem; color: #ff006e; margin-bottom: 1rem;"></i>
                    <h4 class="counter mb-0" style="color: #ffffff;">5</h4>
                    <small class="text-muted" style="color: #9ca3af;">امتیاز کلی</small>
                </div>
            </div>
        </div>
    </section>

    <style>
        /* Ads Swiper Styles */
        .ads-swiper {
            padding: 20px 60px 60px 60px;
            position: relative;
        }

        .ads-swiper .swiper-slide {
            height: auto;
            display: flex;
        }

        .ads-swiper .swiper-slide > * {
            width: 100%;
        }

        /* Navigation buttons */
        .ads-swiper .swiper-button-next,
        .ads-swiper .swiper-button-prev {
            width: 50px;
            height: 50px;
            background: rgba(0, 240, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(0, 240, 255, 0.3);
            border-radius: 50%;
            color: #00f0ff;
            transition: all 0.3s ease;
            top: 50%;
            transform: translateY(-50%);
            margin-top: 0;
            z-index: 10;
        }

        .ads-swiper .swiper-button-next:hover,
        .ads-swiper .swiper-button-prev:hover {
            background: rgba(0, 240, 255, 0.2);
            border-color: #00f0ff;
            transform: translateY(-50%) scale(1.1);
        }

        .ads-swiper .swiper-button-next::after,
        .ads-swiper .swiper-button-prev::after {
            font-size: 20px;
            font-weight: bold;
        }

        .ads-swiper .swiper-button-next {
            left: 0;
            right: auto;
        }

        .ads-swiper .swiper-button-prev {
            right: 0;
            left: auto;
        }

        /* Pagination dots */
        .ads-swiper .swiper-pagination {
            bottom: 20px;
            position: absolute;
        }

        .ads-swiper .swiper-pagination-bullet {
            width: 12px;
            height: 12px;
            background: rgba(255, 255, 255, 0.3);
            opacity: 1;
            transition: all 0.3s ease;
        }

        .ads-swiper .swiper-pagination-bullet-active {
            background: #00f0ff;
            width: 24px;
            border-radius: 6px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .ads-swiper {
                padding: 20px 50px 60px 50px;
            }

            .ads-swiper .swiper-button-next,
            .ads-swiper .swiper-button-prev {
                width: 40px;
                height: 40px;
            }

            .ads-swiper .swiper-button-next::after,
            .ads-swiper .swiper-button-prev::after {
                font-size: 16px;
            }
        }

        @media (max-width: 576px) {
            .ads-swiper {
                padding: 20px 45px 60px 45px;
            }

            .ads-swiper .swiper-button-next,
            .ads-swiper .swiper-button-prev {
                width: 35px;
                height: 35px;
            }

            .ads-swiper .swiper-button-next::after,
            .ads-swiper .swiper-button-prev::after {
                font-size: 14px;
            }
        }
    </style>

    <script>
        (function() {
            let swiperInstance = null;
            
            function initSwiper() {
                if (typeof Swiper === 'undefined') {
                    // Wait for Swiper to load
                    setTimeout(initSwiper, 100);
                    return;
                }
                
                const swiperEl = document.querySelector('.ads-swiper');
                if (!swiperEl) return;
                
                // Destroy existing instance if it exists
                if (swiperInstance) {
                    try {
                        swiperInstance.destroy(true, true);
                    } catch (e) {
                        console.log('Swiper destroy error:', e);
                    }
                    swiperInstance = null;
                }
                
                // Wait a bit for DOM to be ready after Livewire update
                setTimeout(() => {
                    const swiperContainer = document.querySelector('.ads-swiper');
                    if (swiperContainer && !swiperContainer.swiper) {
                        swiperInstance = new Swiper(swiperContainer, {
                            slidesPerView: 1,
                            spaceBetween: 20,
                            loop: false,
                            autoplay: {
                                delay: 5000,
                                disableOnInteraction: false,
                            },
                            breakpoints: {
                                640: {
                                    slidesPerView: 2,
                                    spaceBetween: 20,
                                },
                                768: {
                                    slidesPerView: 2,
                                    spaceBetween: 24,
                                },
                                1024: {
                                    slidesPerView: 3,
                                    spaceBetween: 24,
                                },
                                1280: {
                                    slidesPerView: 4,
                                    spaceBetween: 24,
                                },
                            },
                            pagination: {
                                el: swiperContainer.querySelector('.swiper-pagination'),
                                clickable: true,
                            },
                            navigation: {
                                nextEl: swiperContainer.querySelector('.swiper-button-next'),
                                prevEl: swiperContainer.querySelector('.swiper-button-prev'),
                            },
                            rtl: true,
                        });
                    }
                }, 200);
            }
            
            // Initialize on page load
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', initSwiper);
            } else {
                initSwiper();
            }
            
            // Listen for Livewire updates
            document.addEventListener('livewire:init', () => {
                Livewire.hook('morph.updated', ({ el, component }) => {
                    // Check if swiper element exists in the updated DOM
                    const swiperEl = document.querySelector('.ads-swiper');
                    if (swiperEl) {
                        setTimeout(initSwiper, 150);
                    }
                });
            });
        })();
    </script>
    
    <!-- FAQPage Schema for AEO -->
    <script type="application/ld+json">
    {!! json_encode($faqSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
    </script>
</div>

