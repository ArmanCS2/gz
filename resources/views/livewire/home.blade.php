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
                <h3 class="mb-0 fw-bold search-section">
                    <i class="bi bi-funnel me-2" style="color: #00f0ff;"></i>
                    جستجوی پیشرفته
                </h3>
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
                        <label class="form-label mb-2 d-block fw-semibold" style="color: #ffffff; font-size: 14px;">
                            <i class="bi bi-grid-3x3-gap me-1" style="color: #00f0ff;"></i> دسته‌بندی
                        </label>
                        <select class="modern-input w-100" wire:model.live="category" style="color: #ffffff !important;">
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
                        <h6 class="mb-1" style="color: #ffffff;">{{ $category->name }}</h6>
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
</div>

