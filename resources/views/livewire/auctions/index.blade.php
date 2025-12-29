<div class="container-fluid px-3 px-md-5">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb" style="background: rgba(255, 255, 255, 0.05); backdrop-filter: blur(10px); border-radius: 12px; padding: 12px 20px;">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" style="color: #00f0ff; text-decoration: none;">خانه</a></li>
            <li class="breadcrumb-item active" style="color: #ffffff;">مزایده‌ها</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-3 mb-4">
            <div class="glass-card p-4">
                <h5 class="fw-bold mb-4" style="color: #ffffff;">
                    <i class="bi bi-funnel me-2" style="color: #ff006e;"></i>
                    فیلترها
                </h5>
                
                <div class="mb-3">
                    <label class="form-label mb-2 d-block fw-semibold" style="color: #ffffff; font-size: 14px;">
                        <i class="bi bi-search me-1" style="color: #ff006e;"></i> جستجو
                    </label>
                    <input type="text" 
                           class="modern-input w-100" 
                           wire:model.live.debounce.300ms="search" 
                           placeholder="جستجو در عنوان و توضیحات..."
                           style="color: #ffffff !important;">
                </div>
                
                <div class="mb-3">
                    <label class="form-label mb-2 d-block fw-semibold" style="color: #ffffff; font-size: 14px;">
                        <i class="bi bi-people me-1" style="color: #ff006e;"></i> حداقل تعداد عضو
                    </label>
                    <input type="text" 
                           class="modern-input w-100" 
                           wire:model.live.debounce.300ms="min_members" 
                           pattern="[0-9]*"
                           inputmode="numeric"
                           onkeypress="return event.charCode >= 48 && event.charCode <= 57"
                           placeholder="0"
                           style="color: #ffffff !important;">
                </div>
            </div>
        </div>

        <div class="col-md-9">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold mb-0" style="color: #ffffff;">
                    <i class="bi bi-hammer me-2" style="color: #ff006e;"></i>
                    همه آگهی‌ها
                </h2>
                <span class="badge" style="background: rgba(255, 0, 110, 0.2); color: #ff006e; border: 1px solid rgba(255, 0, 110, 0.3); padding: 8px 16px;">
                    {{ $ads->total() }} آگهی
                </span>
            </div>

            @if($ads->count() > 0)
                <div class="row g-4">
                    @foreach($ads as $ad)
                        <div class="col-md-6 col-lg-4 col-xl-3"
                             x-data="{ loaded: false }"
                             x-intersect="loaded = true"
                             x-show="loaded"
                             x-transition:enter="transition ease-out duration-500"
                             x-transition:enter-start="opacity-0 transform translate-y-8"
                             x-transition:enter-end="opacity-100 transform translate-y-0">
                            @include('components.ad-card', ['ad' => $ad])
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-5 d-flex justify-content-center">
                    {{ $ads->links('pagination::bootstrap-5') }}
                </div>
            @else
                <div class="glass-card p-5 text-center">
                    <i class="bi bi-inbox" style="font-size: 4rem; color: rgba(255,255,255,0.3);"></i>
                    <h5 class="mt-3 mb-2" style="color: #ffffff;">آگهی‌ای یافت نشد</h5>
                    <p class="text-muted mb-0">لطفا فیلترها را تغییر دهید یا دوباره جستجو کنید.</p>
                </div>
            @endif
        </div>
    </div>
</div>

