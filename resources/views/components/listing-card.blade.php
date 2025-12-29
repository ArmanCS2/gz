@php
    $isAuction = $type === 'auction' || $ad->type === 'auction';
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
    $timeRemaining = $isAuction && isset($ad->auction_end_time) && $ad->auction_end_time ? \App\Helpers\DateHelper::diffForHumans($ad->auction_end_time) : null;
    $memberCount = $ad->member_count ?? 0;
    $title = $ad->title ?? 'عنوان آگهی';
    $bidsCount = isset($ad->bids) ? $ad->bids->count() : 0;
@endphp

@if(!$isPreview && isset($ad->id) && $ad->id > 0)
    <a href="{{ route('store.show', $ad->slug ?? $ad->id) }}" class="text-decoration-none">
@endif
    <div class="glass-card h-100 d-flex flex-column listing-card-hover" style="cursor: pointer;">
        <!-- Image -->
        <div class="position-relative listing-card-image-container" style="height: 200px; overflow: hidden; border-radius: 20px 20px 0 0;">
            @if($imageUrl)
                <img src="{{ $imageUrl }}" alt="{{ $ad->title }}" 
                     class="w-100 h-100 listing-card-image" 
                     style="object-fit: cover; transition: transform 0.3s;">
            @else
                <div class="w-100 h-100 d-flex align-items-center justify-content-center" 
                     style="background: linear-gradient(135deg, rgba(0, 240, 255, 0.1), rgba(176, 38, 255, 0.1));">
                    <i class="bi bi-image" style="font-size: 3rem; color: rgba(255,255,255,0.3);"></i>
                </div>
            @endif
            
            <!-- Badges -->
            <div class="position-absolute top-0 end-0 m-3 d-flex flex-column gap-2" style="z-index: 2;">
                @if($isAuction)
                    <span class="badge" style="background: linear-gradient(135deg, #ff006e, #b026ff); padding: 6px 12px; border-radius: 20px;">
                        <i class="bi bi-hammer me-1"></i> مزایده
                    </span>
                @endif
                @if($ad->user->verified ?? false)
                    <span class="trust-badge" style="font-size: 12px; padding: 4px 8px;">
                        <i class="bi bi-check-circle"></i> تأیید شده
                    </span>
                @endif
            </div>
            
            <!-- Views Badge -->
            <div class="position-absolute bottom-0 start-0 m-3" style="z-index: 2;">
                <span style="background: rgba(0,0,0,0.6); backdrop-filter: blur(10px); padding: 4px 10px; border-radius: 12px; font-size: 12px;">
                    <i class="bi bi-eye me-1"></i> {{ number_format(rand(50, 500)) }}
                </span>
            </div>
        </div>
        
        <!-- Content -->
        <div class="p-4 flex-grow-1 d-flex flex-column listing-card-content">
            <h5 class="fw-bold mb-2" style="color: #fff; line-height: 1.4; min-height: 3em;">
                {{ \Illuminate\Support\Str::limit($title, 50) }}
            </h5>
            
            <div class="d-flex align-items-center gap-3 mb-3 flex-wrap">
                <div class="d-flex align-items-center" style="color: #00f0ff;">
                    <i class="bi bi-people me-1"></i>
                    <small>{{ number_format($memberCount) }} عضو</small>
                </div>
                @if($isAuction && $timeRemaining)
                    <div class="d-flex align-items-center" style="color: #ff006e;">
                        <i class="bi bi-clock me-1"></i>
                        <small>{{ $timeRemaining }}</small>
                    </div>
                @endif
            </div>
            
            <div class="mt-auto">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-muted d-block">@if($isAuction) قیمت فعلی @else قیمت @endif</small>
                        <h4 class="mb-0 fw-bold" style="background: linear-gradient(135deg, #00f0ff, #b026ff); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                            {{ number_format($currentPrice) }} <small style="font-size: 0.7em;">تومان</small>
                        </h4>
                    </div>
                    @if($isAuction)
                        <div class="text-center">
                            <small class="text-muted d-block">تعداد پیشنهاد</small>
                            <strong style="color: #ff006e;">{{ $bidsCount }}</strong>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

<style>
/* Structure: ::before (hover effect) -> image -> content */
/* NO inset box-shadow, NO mask-composite - images always visible */
.listing-card-hover {
    position: relative;
}
.listing-card-hover::before {
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
.listing-card-hover:hover::before {
    opacity: 1;
}
.listing-card-image-container {
    position: relative;
    z-index: 1;
}
.listing-card-image {
    position: relative;
    z-index: 1;
}
.listing-card-content {
    position: relative;
    z-index: 1;
}
.listing-card-hover:hover .listing-card-image {
    transform: scale(1.1);
}
</style>
@if(!$isPreview && isset($ad->id) && $ad->id > 0)
    </a>
@endif
