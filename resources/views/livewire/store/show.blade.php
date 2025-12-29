<div class="container-fluid px-3 px-md-5">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb" style="background: rgba(255,255,255,0.05); padding: 12px 20px; border-radius: 12px;">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" style="color: #00f0ff; text-decoration: none;">خانه</a></li>
            <li class="breadcrumb-item"><a href="{{ route('store.index') }}" style="color: #00f0ff; text-decoration: none;">فروشگاه</a></li>
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
            <h1 class="fw-bold mb-3" style="color: #ffffff;">{{ $ad->title }}</h1>
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
                    <h4 class="fw-bold mb-3" style="color: #ffffff;">
                        <i class="bi bi-hammer me-2" style="color: #ff006e;"></i>
                        اطلاعات مزایده
                    </h4>
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
                    توضیحات
                </h5>
                <div style="color: #e5e7eb; line-height: 1.8;">{{ $ad->description }}</div>
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

    <!-- بخش نظرات و امتیازدهی -->
    <div class="row mt-5">
        <div class="col-12">
            @livewire('store.reviews', ['ad' => $ad])
        </div>
    </div>
</div>

@push('json-ld')
<script type="application/ld+json">
{!! json_encode(array_filter([
    '@context' => 'https://schema.org',
    '@type' => 'Product',
    'name' => $ad->title,
    'description' => \Illuminate\Support\Str::limit($ad->description, 200),
    'image' => $ad->cover_image,
    'offers' => [
        '@type' => 'Offer',
        'price' => $ad->type === 'auction' ? ($ad->current_bid ?? $ad->base_price) : $ad->price,
        'priceCurrency' => 'IRR',
        'availability' => 'https://schema.org/InStock',
        'url' => route('store.show', $ad->slug)
    ],
    'aggregateRating' => [
        '@type' => 'AggregateRating',
        'ratingValue' => $ad->approvedReviews->avg('rating') ?? 5,
        'reviewCount' => $ad->approvedReviews->count()
    ]
]), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
</script>
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'BreadcrumbList',
    'itemListElement' => [
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
        [
            '@type' => 'ListItem',
            'position' => 3,
            'name' => $ad->title,
            'item' => route('store.show', $ad->slug)
        ]
    ]
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
</script>
@endpush



