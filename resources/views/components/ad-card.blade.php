@php
    $isAuction = (isset($type) && $type === 'auction') || ($ad->type ?? 'normal') === 'auction';
    $isPreview = isset($preview) && $preview;
    
    // SINGLE SOURCE OF TRUTH - Handle both Eloquent models and stdClass objects
    $imageUrl = null;
    if (isset($ad) && $ad instanceof \App\Models\Ad) {
        // Eloquent model - use cover_image accessor
        $imageUrl = $ad->cover_image;
    } elseif (isset($ad) && is_object($ad)) {
        // stdClass or other object (preview) - manually get first image
        if (isset($ad->images) && $ad->images !== null) {
            $images = $ad->images;
            // Handle Laravel Collection
            if (is_object($images) && method_exists($images, 'first')) {
                $firstImage = $images->first();
            } elseif (is_array($images) && count($images) > 0) {
                $firstImage = $images[0];
            } else {
                $firstImage = null;
            }
            
            if ($firstImage !== null) {
                if (is_object($firstImage) && isset($firstImage->image) && !empty($firstImage->image)) {
                    // Preview images have full URL in ->image property
                    $imageUrl = $firstImage->image;
                } elseif (is_array($firstImage) && isset($firstImage['image']) && !empty($firstImage['image'])) {
                    $imageUrl = $firstImage['image'];
                } elseif (is_string($firstImage) && !empty($firstImage)) {
                    // If it's a direct string URL
                    $imageUrl = $firstImage;
                }
            }
        } elseif (isset($ad->cover_image) && !empty($ad->cover_image)) {
            // Fallback: if cover_image property exists directly
            $imageUrl = $ad->cover_image;
        }
    }
    
    $currentPrice = $isAuction ? ($ad->base_price ?? 0) : ($ad->price ?? 0);
    $memberCount = $ad->member_count ?? 0;
    $title = $ad->title ?? 'عنوان آگهی';
    $bidsCount = isset($ad->bids) ? (is_countable($ad->bids) ? $ad->bids->count() : 0) : 0;
    $views = isset($ad->views) ? $ad->views : rand(50, 500);
@endphp

@php
    // Handle test ads (stdClass) vs real Eloquent models
    $adId = isset($ad->id) ? (is_object($ad) && method_exists($ad, 'getKey') ? $ad->getKey() : $ad->id) : null;
    $isTestAd = $adId && $adId >= 9991 && $adId <= 10000; // Test ad IDs range
@endphp

@if(!$isPreview && $adId && !$isTestAd)
    <a href="{{ route('store.show', $ad->slug ?? $adId) }}" class="text-decoration-none">
@endif
<div class="glass-card h-100 d-flex flex-column ad-card-hover" style="cursor: {{ $isTestAd ? 'default' : 'pointer' }};">
    <!-- Image -->
    <div class="position-relative ad-card-image-container" style="height: 200px; overflow: hidden; border-radius: 20px 20px 0 0;">
        @if($imageUrl)
            <img src="{{ $imageUrl }}" alt="{{ $title }}" 
                 width="400" height="200"
                 class="w-100 h-100 ad-card-image" 
                 loading="lazy"
                 style="object-fit: cover; transition: transform 0.3s;">
        @else
            <div class="w-100 h-100 d-flex align-items-center justify-content-center" 
                 style="background: linear-gradient(135deg, rgba(0, 240, 255, 0.1), rgba(176, 38, 255, 0.1)); min-height: 200px;">
                <i class="bi bi-image" style="font-size: 3rem; color: rgba(255,255,255,0.3);"></i>
            </div>
        @endif
        
        <!-- Badges -->
        <div class="position-absolute top-0 end-0 m-3 d-flex flex-column gap-2" style="z-index: 2;">
            @php
                $adType = isset($ad->ad_type) ? $ad->ad_type : (isset($ad->ad_type) ? $ad->ad_type : 'telegram');
                $adTypeLabels = [
                    'telegram' => 'گروه تلگرام',
                    'instagram' => 'پیج اینستاگرام',
                    'website' => 'سایت آماده',
                    'domain' => 'دامنه',
                    'youtube' => 'کانال یوتیوب',
                ];
                $adTypeColors = [
                    'telegram' => 'linear-gradient(135deg, #0088cc, #229ED9)',
                    'instagram' => 'linear-gradient(135deg, #E4405F, #F77737)',
                    'website' => 'linear-gradient(135deg, #00f0ff, #b026ff)',
                    'domain' => 'linear-gradient(135deg, #39ff14, #00f0ff)',
                    'youtube' => 'linear-gradient(135deg, #FF0000, #CC0000)',
                ];
            @endphp
            @if($adType !== 'telegram')
                <span class="badge" style="background: {{ $adTypeColors[$adType] ?? $adTypeColors['telegram'] }}; padding: 6px 12px; border-radius: 20px;">
                    {{ $adTypeLabels[$adType] ?? 'گروه تلگرام' }}
                </span>
            @endif
            @if($isAuction)
                <span class="badge" style="background: linear-gradient(135deg, #ff006e, #b026ff); padding: 6px 12px; border-radius: 20px;">
                    <i class="bi bi-hammer me-1"></i> مزایده
                </span>
            @endif
            @if(isset($ad->user) && ($ad->user->is_verified ?? false))
                <span class="trust-badge" style="font-size: 12px; padding: 4px 8px;">
                    <i class="bi bi-check-circle"></i> تأیید شده
                </span>
            @endif
        </div>
        
        <!-- Views Badge -->
        <div class="position-absolute bottom-0 start-0 m-3" style="z-index: 2;">
            <span style="background: rgba(0,0,0,0.6); backdrop-filter: blur(10px); padding: 4px 10px; border-radius: 12px; font-size: 12px;">
                <i class="bi bi-eye me-1"></i> {{ number_format($views) }}
            </span>
        </div>
    </div>
    
    <!-- Content -->
    <div class="p-4 flex-grow-1 d-flex flex-column ad-card-content">
        <h5 class="fw-bold mb-2 ad-card-title" style="line-height: 1.4; min-height: 3em;">
            {{ \Illuminate\Support\Str::limit($title, 50) }}
        </h5>
        
        <div class="d-flex align-items-center gap-3 mb-3 flex-wrap">
            @php
                $adType = isset($ad->ad_type) ? $ad->ad_type : 'telegram';
                $keyMetric = null;
                $keyMetricLabel = '';
                
                if ($ad instanceof \App\Models\Ad) {
                    $keyMetric = $ad->key_metric;
                } elseif (isset($ad->ad_extra) && is_array($ad->ad_extra)) {
                    $extra = $ad->ad_extra;
                    if ($adType === 'instagram') {
                        $keyMetric = $extra['instagram_followers'] ?? null;
                        $keyMetricLabel = 'فالوور';
                    } elseif ($adType === 'website') {
                        $keyMetric = $extra['website_monthly_visits'] ?? null;
                        $keyMetricLabel = 'بازدید ماهانه';
                    } elseif ($adType === 'youtube') {
                        $keyMetric = $extra['youtube_subscribers'] ?? null;
                        $keyMetricLabel = 'مشترک';
                    } elseif ($adType === 'domain') {
                        $keyMetric = ($extra['domain_name'] ?? '') . ($extra['domain_extension'] ?? '');
                        $keyMetricLabel = '';
                    }
                }
                
                if ($adType === 'telegram' || ($adType === 'domain' && !empty($keyMetric))) {
                    // For telegram, show member count; for domain, show domain name
                    if ($adType === 'telegram') {
                        $keyMetric = $memberCount;
                        $keyMetricLabel = 'عضو';
                    }
                }
            @endphp
            
            @if($keyMetric !== null)
                <div class="d-flex align-items-center" style="color: #00f0ff;">
                    @if($adType === 'domain')
                        <i class="bi bi-globe me-1"></i>
                        <small style="color: #e5e7eb; font-weight: 500;">{{ $keyMetric }}</small>
                    @else
                        <i class="bi bi-{{ $adType === 'instagram' ? 'people' : ($adType === 'website' ? 'eye' : ($adType === 'youtube' ? 'people' : 'people')) }} me-1"></i>
                        <small style="color: #e5e7eb; font-weight: 500;">{{ number_format($keyMetric) }} {{ $keyMetricLabel ?: 'عضو' }}</small>
                    @endif
                </div>
            @endif
        </div>
        
        <div class="mt-auto">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <small class="text-muted d-block mb-1" style="font-size: 0.85rem;">@if($isAuction) قیمت فعلی @else قیمت @endif</small>
                    <div class="ad-card-price">
                        {{ number_format($currentPrice) }} <small style="font-size: 0.6em; font-weight: 600;">تومان</small>
                    </div>
                </div>
                @if($isAuction)
                    <div class="text-center">
                        <small class="text-muted d-block mb-1" style="font-size: 0.85rem;">تعداد پیشنهاد</small>
                        <div class="ad-card-bid" style="font-size: 1.25rem;">{{ $bidsCount }}</div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
/* Structure: ::before (hover effect) -> image -> content */
/* NO inset box-shadow, NO mask-composite - images always visible */
.ad-card-hover {
    position: relative;
}
.ad-card-hover::before {
    content: '';
    position: absolute;
    inset: -2px;
    border-radius: 22px;
    pointer-events: none;
    opacity: 0;
    transition: opacity 0.3s;
    z-index: 0;
    background: linear-gradient(135deg, rgba(0, 240, 255, 0.5), rgba(176, 38, 255, 0.5));
    box-shadow: 0 0 20px rgba(0, 240, 255, 0.3);
}
.ad-card-hover:hover::before {
    opacity: 1;
}
.ad-card-image-container {
    position: relative;
    z-index: 1;
}
.ad-card-image {
    position: relative;
    z-index: 1;
}
.ad-card-content {
    position: relative;
    z-index: 1;
}
.ad-card-hover:hover .ad-card-image {
    transform: scale(1.1);
}
</style>
@if(!$isPreview && $adId && !$isTestAd)
    </a>
@endif
