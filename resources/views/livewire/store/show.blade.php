<div class="container-fluid px-3 px-md-5">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb" style="background: rgba(255,255,255,0.05); padding: 12px 20px; border-radius: 12px;">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" style="color: #00f0ff; text-decoration: none;">خانه</a></li>
            <li class="breadcrumb-item"><a href="{{ route('store.index') }}" style="color: #00f0ff; text-decoration: none;">فروشگاه</a></li>
            @php
                $adType = $ad->ad_type ?? 'telegram';
                $adTypeLabels = [
                    'telegram' => 'گروه تلگرام',
                    'instagram' => 'پیج اینستاگرام',
                    'website' => 'سایت آماده',
                    'domain' => 'دامنه',
                    'youtube' => 'کانال یوتیوب',
                ];
                $adTypeSlugs = [
                    'telegram' => 'گروه-تلگرام',
                    'instagram' => 'پیج-اینستاگرام',
                    'website' => 'سایت-آماده',
                    'domain' => 'دامنه',
                    'youtube' => 'کانال-یوتیوب',
                ];
                if (isset($adTypeLabels[$adType]) && isset($adTypeSlugs[$adType])) {
                    $seoLandingUrl = route('seo.landing', [
                        'action' => 'خرید',
                        'type' => $adTypeSlugs[$adType]
                    ]);
            @endphp
            <li class="breadcrumb-item">
                <a href="{{ $seoLandingUrl }}" style="color: #00f0ff; text-decoration: none;">
                    خرید {{ $adTypeLabels[$adType] }}
                </a>
            </li>
            @php
                }
            @endphp
            <li class="breadcrumb-item active" style="color: #ffffff;">{{ \Illuminate\Support\Str::limit($ad->title, 30) }}</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-6 mb-4">
            @php
                // Use model method - SINGLE SOURCE OF TRUTH
                $allImages = $ad->getAllImages();
            @endphp
            @if($allImages->count() > 0)
                <div id="carouselExample" class="carousel slide">
                    <div class="carousel-inner">
                        @foreach($allImages as $index => $imageUrl)
                            <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                <img src="{{ $imageUrl }}" 
                                     width="800" height="400"
                                     class="d-block w-100" 
                                     style="height: 400px; object-fit: cover;"
                                     @if($index === 0) loading="eager" @else loading="lazy" @endif
                                     alt="{{ $ad->title }} - تصویر {{ $index + 1 }}">
                            </div>
                        @endforeach
                    </div>
                    @if($allImages->count() > 1)
                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon"></span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
                            <span class="carousel-control-next-icon"></span>
                        </button>
                    @endif
                </div>
            @else
                <div class="bg-secondary d-flex align-items-center justify-content-center" style="height: 400px;">
                    <i class="bi bi-image text-white" style="font-size: 5rem;"></i>
                </div>
            @endif
        </div>

        <div class="col-md-6">
            @php
                // Build SEO-optimized H1 with action + type + metric + title
                $adType = $ad->ad_type ?? 'telegram';
                $adTypeLabels = [
                    'telegram' => 'گروه تلگرام',
                    'instagram' => 'پیج اینستاگرام',
                    'website' => 'سایت آماده',
                    'domain' => 'دامنه',
                    'youtube' => 'کانال یوتیوب',
                ];
                $keyMetric = $ad->key_metric;
                $metricText = '';
                
                if ($adType === 'instagram' && $keyMetric) {
                    $metricText = number_format($keyMetric) . 'k فالوور';
                } elseif ($adType === 'website' && $keyMetric) {
                    $metricText = number_format($keyMetric) . ' بازدید ماهانه';
                } elseif ($adType === 'youtube' && $keyMetric) {
                    $metricText = number_format($keyMetric) . ' مشترک';
                } elseif ($adType === 'domain' && $keyMetric) {
                    $metricText = $keyMetric;
                } elseif ($adType === 'telegram' && $keyMetric) {
                    $metricText = number_format($keyMetric) . ' عضو';
                }
                
                $h1Parts = ['خرید', $adTypeLabels[$adType] ?? 'آگهی'];
                if ($metricText) {
                    $h1Parts[] = $metricText;
                }
                $h1Parts[] = $ad->title;
                $seoH1 = implode(' ', $h1Parts);
            @endphp
            <h1 class="fw-bold mb-3" style="color: #ffffff;">{{ $seoH1 }}</h1>
            <div class="mb-4 d-flex gap-2 flex-wrap">
                <span class="badge" style="background: rgba(0, 240, 255, 0.2); color: #00f0ff; border: 1px solid rgba(0, 240, 255, 0.3); padding: 8px 16px;">
                    <i class="bi bi-people me-1"></i> {{ number_format($ad->member_count) }} عضو
                </span>
                @if($ad->type === 'auction')
                    <span class="badge" style="background: rgba(255, 0, 110, 0.2); color: #ff006e; border: 1px solid rgba(255, 0, 110, 0.3); padding: 8px 16px;">
                        <i class="bi bi-hammer me-1"></i> مزایده
                    </span>
                @endif
            </div>

            @if($ad->type === 'normal')
                <div class="glass-card p-4 mb-4">
                    <small class="text-muted d-block mb-2">قیمت</small>
                    <div class="ad-card-price" style="font-size: 2rem;">{{ number_format($ad->price) }} <small>تومان</small></div>
                </div>
            @else
                <div class="glass-card p-4 mb-4" style="background: rgba(255, 0, 110, 0.1); border-color: rgba(255, 0, 110, 0.3);">
                    <h2 class="fw-bold mb-3" style="color: #ffffff;">
                        <i class="bi bi-hammer me-2" style="color: #ff006e;"></i>
                        اطلاعات مزایده
                    </h2>
                    <div class="row g-3 mb-3">
                        <div class="col-6">
                            <small class="text-muted d-block mb-1">قیمت پایه</small>
                            <div style="color: #e5e7eb; font-weight: 600;">{{ number_format($ad->base_price) }} تومان</div>
                        </div>
                        <div class="col-6">
                            <small class="text-muted d-block mb-1">قیمت فعلی</small>
                            <div class="ad-card-price" style="font-size: 1.25rem;">{{ number_format($ad->current_bid ?? $ad->base_price) }} تومان</div>
                        </div>
                    </div>

                    @auth
                        @if($ad->user_id !== auth()->id() && $ad->isAuctionActive())
                            <div class="mt-4">
                                @if(!$showBidForm)
                                    <button type="button" 
                                            class="btn btn-modern w-100" 
                                            style="background: linear-gradient(135deg, #ff006e, #b026ff);"
                                            wire:click="$set('showBidForm', true)">
                                        <i class="bi bi-gavel me-2"></i> ثبت پیشنهاد قیمت
                                    </button>
                                @else
                                    <div class="glass-card p-3" style="background: rgba(255,255,255,0.03);">
                                        <form wire:submit.prevent="submitBid">
                                            <div class="mb-3">
                                                <label class="filter-label mb-2 d-block">
                                                    <i class="bi bi-currency-exchange me-1"></i> مبلغ پیشنهادی (تومان)
                                                </label>
                                                <div class="floating-label-group">
                                                    <input type="text" 
                                                           class="modern-input @error('bidAmount') border-danger @enderror" 
                                                           placeholder=" " 
                                                           wire:model.debounce.500ms="bidAmount"
                                                           dir="ltr"
                                                           id="bid-amount">
                                                    <label class="floating-label" for="bid-amount">
                                                        مبلغ پیشنهادی (تومان)
                                                    </label>
                                                </div>
                                                @error('bidAmount') 
                                                    <span class="text-danger small d-block mt-2">
                                                        <i class="bi bi-exclamation-circle me-1"></i> {{ $message }}
                                                    </span> 
                                                @enderror
                                            </div>
                                            <div class="d-flex gap-2">
                                                <button type="submit" class="btn btn-modern flex-grow-1" style="background: linear-gradient(135deg, #ff006e, #b026ff);">
                                                    <i class="bi bi-check-circle me-2"></i> ثبت پیشنهاد
                                                </button>
                                                <button type="button" 
                                                        class="btn btn-modern" 
                                                        style="background: rgba(255,255,255,0.1); box-shadow: none;"
                                                        wire:click="$set('showBidForm', false)">
                                                    <i class="bi bi-x"></i>
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                @endif
                            </div>
                        @endif
                    @else
                        <div class="mt-3">
                            <button type="button" 
                                    class="btn btn-modern w-100" 
                                    style="background: rgba(255,255,255,0.1); box-shadow: none;"
                                    data-bs-toggle="modal" 
                                    data-bs-target="#loginModal">
                                <i class="bi bi-box-arrow-in-left me-2"></i> برای ثبت پیشنهاد وارد شوید
                            </button>
                        </div>
                    @endauth
                </div>
            @endif

            <div class="glass-card p-4 mb-4">
                <h5 class="fw-bold mb-3" style="color: #ffffff;">
                    <i class="bi bi-file-text me-2" style="color: #00f0ff;"></i>
                    توضیحات کامل
                </h5>
                <div style="color: #e5e7eb; line-height: 1.8; font-size: 1.05rem;">
                    {{ $ad->description }}
                    
                    @php
                        // Expand description if it's too short for SEO (minimum 150 words)
                        $wordCount = str_word_count($ad->description, 0, 'آابپتثجچحخدذرزژسشصضطظعغفقکگلمنوهی');
                        if ($wordCount < 150) {
                            $adType = $ad->ad_type ?? 'telegram';
                            $adTypeLabels = [
                                'telegram' => 'گروه تلگرام',
                                'instagram' => 'پیج اینستاگرام',
                            ];
                            $typeLabel = $adTypeLabels[$adType] ?? 'آگهی';
                    @endphp
                    <div class="mt-4 pt-4" style="border-top: 1px solid rgba(255,255,255,0.1);">
                        <p style="color: #e5e7eb; line-height: 1.8;">
                            این {{ $typeLabel }} به صورت مستقیم از مالک اصلی به فروش می‌رسد و تمام اطلاعات ارائه شده معتبر و قابل اعتماد است. 
                            برای خرید این {{ $typeLabel }} می‌توانید با مالک ارتباط برقرار کرده و معامله را به صورت امن انجام دهید. 
                            قیمت ارائه شده شفاف و بدون واسطه است و شما می‌توانید با اطمینان خاطر اقدام به خرید کنید.
                        </p>
                        <p style="color: #e5e7eb; line-height: 1.8;">
                            تمام آگهی‌های این سایت توسط مالکان واقعی ثبت شده‌اند و ما تضمین می‌کنیم که اطلاعات ارائه شده معتبر است. 
                            برای اطمینان بیشتر، می‌توانید نظرات سایر کاربران را مشاهده کرده و از تجربه آن‌ها استفاده کنید.
                        </p>
                    </div>
                    @php
                        }
                    @endphp
                </div>
            </div>

            @if($ad->type === 'auction' && $ad->bids->count() > 0)
                <div class="glass-card p-4 mb-4">
                    <h5 class="fw-bold mb-3" style="color: #ffffff;">
                        <i class="bi bi-list-ol me-2" style="color: #ff006e;"></i>
                        آخرین پیشنهادها
                    </h5>
                    <div class="list-group" style="background: transparent;">
                        @foreach($ad->bids->take(5) as $bid)
                            <div class="d-flex justify-content-between align-items-center p-2 mb-2" style="background: rgba(255,255,255,0.03); border-radius: 8px;">
                                <div>
                                    <div style="color: #ffffff; font-weight: 600;">{{ $bid->user->name }}</div>
                                    <small class="text-muted">{{ \App\Helpers\DateHelper::diffForHumans($bid->created_at) }}</small>
                                </div>
                                <div class="ad-card-price" style="font-size: 1.1rem;">
                                    {{ number_format($bid->amount) }} <small>تومان</small>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            @if($ad->show_contact && $ad->user)
                <div class="glass-card p-4">
                    <h5 class="fw-bold mb-3" style="color: #ffffff;">
                        <i class="bi bi-person-lines-fill me-2" style="color: #00f0ff;"></i>
                        اطلاعات تماس
                    </h5>
                    <div style="color: #e5e7eb;">
                        <p class="mb-2"><i class="bi bi-person me-2"></i> {{ $ad->user->name }}</p>
                        <p class="mb-3"><i class="bi bi-telephone me-2"></i> {{ $ad->user->mobile }}</p>
                        <a href="{{ $ad->telegram_link }}" target="_blank" class="btn btn-modern">
                            <i class="bi bi-telegram me-2"></i> لینک تلگرام
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Related Ads Section - Internal Linking -->
    @php
        $relatedAds = \App\Models\Ad::where('status', 'active')
            ->where('is_active', true)
            ->where('id', '!=', $ad->id)
            ->where(function($q) use ($ad) {
                $q->where('ad_type', $ad->ad_type)
                  ->orWhere('category_id', $ad->category_id);
            })
            ->where(function($q) {
                $q->whereNull('expire_at')
                  ->orWhere('expire_at', '>', now());
            })
            ->orderBy('created_at', 'desc')
            ->take(6)
            ->get();
    @endphp
    
    @if($relatedAds->count() > 0)
    <div class="row mt-5">
        <div class="col-12">
            <h2 class="fw-bold mb-4" style="color: #ffffff; font-size: 1.75rem;">
                <i class="bi bi-grid-3x3-gap me-2" style="color: #00f0ff;"></i>
                آگهی‌های مرتبط
            </h2>
            <div class="row g-4">
                @foreach($relatedAds as $relatedAd)
                <div class="col-md-6 col-lg-4">
                    <div class="glass-card h-100" style="transition: all 0.3s;">
                        <a href="{{ route('store.show', $relatedAd->slug) }}" class="text-decoration-none">
                            @if($relatedAd->cover_image)
                            <img src="{{ $relatedAd->cover_image }}" 
                                 alt="{{ $relatedAd->title }}"
                                 class="w-100" 
                                 style="height: 200px; object-fit: cover; border-radius: 20px 20px 0 0;"
                                 loading="lazy">
                            @endif
                            <div class="p-4">
                                <h3 class="fw-bold mb-2" style="color: #ffffff; font-size: 1.1rem;">
                                    {{ $relatedAd->title }}
                                </h3>
                                <p class="text-muted mb-3 small">
                                    {{ Str::limit($relatedAd->description, 80) }}
                                </p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="badge" style="background: rgba(0, 240, 255, 0.2); color: #00f0ff;">
                                        <i class="bi bi-people me-1"></i>
                                        {{ number_format($relatedAd->member_count ?? $relatedAd->key_metric) }}
                                        @if($relatedAd->ad_type === 'instagram')
                                            فالوور
                                        @else
                                            عضو
                                        @endif
                                    </span>
                                    @if($relatedAd->price)
                                    <span class="fw-bold small" style="color: #ffffff;">
                                        {{ number_format($relatedAd->price) }} تومان
                                    </span>
                                    @endif
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <!-- بخش نظرات و امتیازدهی -->
    <div class="row mt-5">
        <div class="col-12">
            @livewire('store.reviews', ['ad' => $ad])
        </div>
    </div>
</div>

@push('json-ld')
@php
    $adType = $ad->ad_type ?? 'telegram';
    $adTypeLabels = [
        'telegram' => 'گروه تلگرام',
        'instagram' => 'پیج اینستاگرام',
    ];
    $breadcrumbItems = [
        [
            '@type' => 'ListItem',
            'position' => 1,
            'name' => 'خانه',
            'item' => route('home')
        ],
        [
            '@type' => 'ListItem',
            'position' => 2,
            'name' => 'فروشگاه',
            'item' => route('store.index')
        ],
    ];
    
    if (isset($adTypeLabels[$adType])) {
        $seoLandingUrl = route('seo.landing', [
            'action' => 'خرید',
            'type' => $adType === 'instagram' ? 'پیج-اینستاگرام' : 'گروه-تلگرام'
        ]);
        $breadcrumbItems[] = [
            '@type' => 'ListItem',
            'position' => 3,
            'name' => 'خرید ' . $adTypeLabels[$adType],
            'item' => $seoLandingUrl
        ];
    }
    
    $breadcrumbItems[] = [
        '@type' => 'ListItem',
        'position' => count($breadcrumbItems) + 1,
        'name' => $ad->title,
        'item' => route('store.show', $ad->slug)
    ];
@endphp
<script type="application/ld+json">
{!! json_encode(array_filter([
    '@context' => 'https://schema.org',
    '@type' => 'Product',
    'name' => $ad->title,
    'description' => \Illuminate\Support\Str::limit($ad->description, 200),
    'image' => $ad->cover_image,
    'url' => route('store.show', $ad->slug),
    'brand' => [
        '@type' => 'Brand',
        'name' => $ad->user->name ?? 'مالک',
    ],
    'offers' => [
        '@type' => 'Offer',
        'price' => $ad->type === 'auction' ? ($ad->current_bid ?? $ad->base_price) : $ad->price,
        'priceCurrency' => 'IRR',
        'availability' => 'https://schema.org/InStock',
        'seller' => [
            '@type' => 'Person',
            'name' => $ad->user->name ?? 'مالک',
        ],
    ],
    'aggregateRating' => $ad->approvedReviews->count() > 0 ? [
        '@type' => 'AggregateRating',
        'ratingValue' => round($ad->approvedReviews->avg('rating'), 1),
        'reviewCount' => $ad->approvedReviews->count()
    ] : null
]), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
</script>
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'BreadcrumbList',
    'itemListElement' => $breadcrumbItems
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
</script>
@endpush



