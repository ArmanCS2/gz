<div class="container-fluid px-3 px-md-5">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb" style="background: rgba(255, 255, 255, 0.05); backdrop-filter: blur(10px); border-radius: 12px; padding: 12px 20px;">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" style="color: #00f0ff; text-decoration: none;">خانه</a></li>
            <li class="breadcrumb-item active" style="color: #ffffff;">فروشگاه</li>
        </ol>
    </nav>

    <div class="row">
        <!-- Filters Sidebar -->
        <div class="col-md-3 mb-4">
            <div class="glass-card p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="fw-bold mb-0" style="color: #ffffff; font-size: 1.25rem;">
                        <i class="bi bi-funnel me-2" style="color: #00f0ff;"></i>
                        فیلترها
                    </h2>
                    <button type="button" 
                            class="btn btn-link text-decoration-none p-0" 
                            style="color: #00f0ff; font-size: 12px;"
                            wire:click="resetFilters">
                        <i class="bi bi-arrow-counterclockwise me-1"></i> پاک کردن
                    </button>
                </div>
                
                <!-- Search -->
                <div class="mb-3">
                    <label class="form-label mb-2 d-block fw-semibold" style="color: #ffffff; font-size: 14px;">
                        <i class="bi bi-search me-1" style="color: #00f0ff;"></i> جستجو
                    </label>
                    <input type="text" 
                           class="modern-input w-100" 
                           wire:model.live.debounce.300ms="search" 
                           placeholder="جستجو در عنوان و توضیحات..."
                           style="color: #ffffff !important;">
                </div>

                <!-- Category -->
                <div class="mb-3">
                    <label class="form-label mb-2 d-block fw-semibold" for="store_category" style="color: #ffffff; font-size: 14px;">
                        <i class="bi bi-grid-3x3-gap me-1" style="color: #00f0ff;"></i> دسته‌بندی
                    </label>
                    <select id="store_category" class="modern-input w-100" wire:model.live="category" style="color: #ffffff !important;">
                        <option value="">همه دسته‌ها</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Type -->
                <div class="mb-3">
                    <label class="form-label mb-2 d-block fw-semibold" style="color: #ffffff; font-size: 14px;">
                        <i class="bi bi-tags me-1" style="color: #00f0ff;"></i> نوع آگهی
                    </label>
                    <div class="segmented-control">
                        <button type="button" 
                                class="{{ $type === 'all' ? 'active' : '' }}" 
                                wire:click="$set('type', 'all')">
                            همه
                        </button>
                        <button type="button" 
                                class="{{ $type === 'normal' ? 'active' : '' }}" 
                                wire:click="$set('type', 'normal')">
                            عادی
                        </button>
                        <button type="button" 
                                class="{{ $type === 'auction' ? 'active' : '' }}" 
                                wire:click="$set('type', 'auction')">
                            مزایده
                        </button>
                    </div>
                </div>
                
                <!-- Members Range -->
                <div class="mb-3">
                    <label class="form-label mb-2 d-block fw-semibold" style="color: #ffffff; font-size: 14px;">
                        <i class="bi bi-people me-1" style="color: #00f0ff;"></i> تعداد اعضا
                    </label>
                    <div class="row g-2">
                        <div class="col-6">
                            <input type="text" 
                                   class="modern-input w-100" 
                                   wire:model.live.debounce.300ms="min_members" 
                                   pattern="[0-9]*"
                                   inputmode="numeric"
                                   onkeypress="return event.charCode >= 48 && event.charCode <= 57"
                                   placeholder="حداقل"
                                   style="color: #ffffff !important; font-size: 13px;">
                        </div>
                        <div class="col-6">
                            <input type="text" 
                                   class="modern-input w-100" 
                                   wire:model.live.debounce.300ms="max_members" 
                                   pattern="[0-9]*"
                                   inputmode="numeric"
                                   onkeypress="return event.charCode >= 48 && event.charCode <= 57"
                                   placeholder="حداکثر"
                                   style="color: #ffffff !important; font-size: 13px;">
                        </div>
                    </div>
                </div>
                
                <!-- Price Range -->
                <div class="mb-3">
                    <label class="form-label mb-2 d-block fw-semibold" style="color: #ffffff; font-size: 14px;">
                        <i class="bi bi-cash-coin me-1" style="color: #00f0ff;"></i> قیمت (تومان)
                    </label>
                    <div class="row g-2">
                        <div class="col-6">
                            <input type="text" 
                                   class="modern-input w-100" 
                                   wire:model.live.debounce.300ms="min_price" 
                                   pattern="[0-9]*"
                                   inputmode="numeric"
                                   onkeypress="return event.charCode >= 48 && event.charCode <= 57"
                                   placeholder="حداقل"
                                   style="color: #ffffff !important; font-size: 13px;">
                        </div>
                        <div class="col-6">
                            <input type="text" 
                                   class="modern-input w-100" 
                                   wire:model.live.debounce.300ms="max_price" 
                                   pattern="[0-9]*"
                                   inputmode="numeric"
                                   onkeypress="return event.charCode >= 48 && event.charCode <= 57"
                                   placeholder="حداکثر"
                                   style="color: #ffffff !important; font-size: 13px;">
                        </div>
                    </div>
                </div>

                <!-- Sort -->
                <div class="mb-3">
                    <label class="form-label mb-2 d-block fw-semibold" for="store_sort" style="color: #ffffff; font-size: 14px;">
                        <i class="bi bi-sort-down me-1" style="color: #00f0ff;"></i> مرتب‌سازی
                    </label>
                    <select id="store_sort" class="modern-input w-100" wire:model.live="sort" style="color: #ffffff !important;">
                        <option value="latest">جدیدترین</option>
                        <option value="oldest">قدیمی‌ترین</option>
                        <option value="price_asc">قیمت: کم به زیاد</option>
                        <option value="price_desc">قیمت: زیاد به کم</option>
                        <option value="members_asc">اعضا: کم به زیاد</option>
                        <option value="members_desc">اعضا: زیاد به کم</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Ads Grid -->
        <div class="col-md-9">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold mb-0" style="color: #ffffff;">
                    <i class="bi bi-shop me-2" style="color: #00f0ff;"></i>
                    همه آگهی‌ها
                </h2>
                <span class="badge" style="background: rgba(0, 240, 255, 0.2); color: #00f0ff; border: 1px solid rgba(0, 240, 255, 0.3); padding: 8px 16px;">
                    {{ $ads->total() }} آگهی
                </span>
            </div>

            @if($ads->count() > 0)
                <div class="row g-4">
                    @foreach($ads as $ad)
                        <div class="col-md-6 col-lg-4 col-xl-3">
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
                    <h3 class="mt-3 mb-2" style="color: #ffffff;">آگهی‌ای یافت نشد</h3>
                    <p class="text-muted mb-0">لطفا فیلترها را تغییر دهید یا دوباره جستجو کنید.</p>
                </div>
            @endif
        </div>
    </div>
</div>
