<div class="container-fluid px-3 px-md-5">
    <!-- Rules Modal -->
    @if($show_rules_modal)
        <div class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,0.8); z-index: 9999;">
            <div class="modal-dialog modal-lg modal-dialog-scrollable">
                <div class="modal-content glass-card border-0" style="background: rgba(17, 17, 24, 0.95);">
                    <div class="modal-header border-bottom border-secondary">
                        <h5 class="modal-title fw-bold" style="background: linear-gradient(135deg, #00f0ff, #b026ff); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                            قوانین و مقررات
                        </h5>
                    </div>
                    <div class="modal-body" style="max-height: 400px; overflow-y: auto;">
                        @foreach($rules as $rule)
                            <div class="mb-4">
                                <h6 class="fw-bold mb-2" style="color: #00f0ff;">{{ $rule->title }}</h6>
                                <div style="color: rgba(255,255,255,0.8);">{!! $rule->content !!}</div>
                            </div>
                        @endforeach
                    </div>
                    <div class="modal-footer border-top border-secondary">
                        <button type="button" class="btn btn-modern" wire:click="acceptRules">
                            <i class="bi bi-check-circle me-2"></i> قبول قوانین
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Page Header -->
    <div class="mb-5">
        <h1 class="display-5 fw-bold mb-3" style="background: linear-gradient(135deg, #00f0ff, #b026ff); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
            ثبت آگهی جدید
        </h1>
        <p style="color: #9ca3af;">اطلاعات آگهی خود را به دقت وارد کنید</p>
    </div>

    <!-- Step Indicator -->
    <div class="step-indicator mb-5">
        <div class="step {{ $currentStep === 1 ? 'active' : '' }} {{ $currentStep > 1 ? 'completed' : '' }}" 
             @if($currentStep >= 1)
             wire:click="goToStep(1)"
             style="cursor: pointer;"
             @endif>
            <div class="step-number">۱</div>
            <small class="text-muted mt-2">اطلاعات پایه</small>
        </div>
        <div class="step {{ $currentStep === 2 ? 'active' : '' }} {{ $currentStep > 2 ? 'completed' : '' }}" 
             @if($currentStep >= 2)
             wire:click="goToStep(2)"
             style="cursor: pointer;"
             @endif>
            <div class="step-number">۲</div>
            <small class="text-muted mt-2">قیمت‌گذاری</small>
        </div>
        <div class="step {{ $currentStep === 3 ? 'active' : '' }} {{ $currentStep > 3 ? 'completed' : '' }}" 
             @if($currentStep >= 3)
             wire:click="goToStep(3)"
             style="cursor: pointer;"
             @endif>
            <div class="step-number">۳</div>
            <small class="text-muted mt-2">تماس و جزئیات</small>
        </div>
        <div class="step {{ $currentStep === 4 ? 'active' : '' }} {{ $currentStep > 4 ? 'completed' : '' }}" 
             @if($currentStep >= 4)
             wire:click="goToStep(4)"
             style="cursor: pointer;"
             @endif>
            <div class="step-number">۴</div>
            <small class="text-muted mt-2">تصاویر</small>
        </div>
        <div class="step {{ $currentStep === 5 ? 'active' : '' }} {{ $currentStep > 5 ? 'completed' : '' }}" 
             @if($currentStep >= 5)
             wire:click="goToStep(5)"
             style="cursor: pointer;"
             @endif>
            <div class="step-number">۵</div>
            <small class="text-muted mt-2">پرداخت</small>
        </div>
    </div>

    <form wire:submit.prevent="save">
        <div class="row g-4">
            <!-- Main Form -->
            <div class="col-lg-8">
                <!-- Step 1: Basic Information -->
                @if($currentStep === 1)
                <div class="glass-card p-4 p-md-5 mb-4">
                    <h3 class="fw-bold mb-4">
                        <i class="bi bi-info-circle me-2" style="color: #00f0ff;"></i>
                        اطلاعات پایه
                    </h3>

                    <!-- Auction Question -->
                    <div class="mb-4">
                        <div class="glass-card p-4" style="background: rgba(0, 240, 255, 0.05); border-color: rgba(0, 240, 255, 0.2);">
                            <div class="d-flex align-items-start gap-3">
                                <div class="flex-shrink-0">
                                    <i class="bi bi-question-circle" style="font-size: 2rem; color: #00f0ff;"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h5 class="fw-bold mb-2" style="color: #ffffff;">آیا برای این آگهی امکان فعال‌سازی مزایده وجود دارد؟</h5>
                                    <p class="mb-3" style="color: #e5e7eb; font-size: 0.95rem;">
                                        در مزایده، کاربران می‌توانند پیشنهاد قیمت خود را ارسال کنند و شما می‌توانید بهترین پیشنهاد را انتخاب کنید.
                                    </p>
                                    <div class="segmented-control">
                                        <button type="button" 
                                                class="{{ $type === 'normal' ? 'active' : '' }}"
                                                wire:click="$set('type', 'normal')">
                                            <i class="bi bi-shop me-2"></i> فروش عادی
                                        </button>
                                        <button type="button" 
                                                class="{{ $type === 'auction' ? 'active' : '' }}"
                                                wire:click="$set('type', 'auction')">
                                            <i class="bi bi-hammer me-2"></i> فعال‌سازی مزایده
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Ad Type Selector -->
                    <div class="mb-4">
                        <label class="form-label mb-2 d-block fw-semibold" for="panel_ad_type" style="color: #ffffff; font-size: 15px;">
                            <i class="bi bi-tag me-1" style="color: #00f0ff;"></i> نوع آگهی <span class="text-danger">*</span>
                        </label>
                        <select id="panel_ad_type" class="modern-input w-100 @error('ad_type') border-danger @enderror" 
                                wire:model.live="ad_type"
                                style="color: #ffffff !important;">
                            <option value="telegram">گروه تلگرام</option>
                            <option value="instagram">پیج اینستاگرام</option>
                            <option value="website">سایت آماده</option>
                            <option value="domain">دامنه</option>
                            <option value="youtube">کانال یوتیوب</option>
                        </select>
                        @error('ad_type') 
                            <span class="text-danger small d-block mt-2">
                                <i class="bi bi-exclamation-circle me-1"></i> {{ $message }}
                            </span> 
                        @enderror
                    </div>

                    <!-- Title -->
                    <div class="mb-4">
                        <label class="form-label mb-2 d-block fw-semibold" for="title-input" style="color: #ffffff; font-size: 15px;">
                            <i class="bi bi-card-heading me-1" style="color: #00f0ff;"></i> عنوان آگهی <span class="text-danger">*</span>
                        </label>
                        <input type="text" 
                               class="modern-input w-100 @error('title') border-danger @enderror" 
                               placeholder="@if($ad_type === 'telegram') مثال: گروه تلگرام فروش محصولات @elseif($ad_type === 'instagram') مثال: پیج اینستاگرام 120k فالوور @elseif($ad_type === 'website') مثال: سایت فروشگاهی آماده @elseif($ad_type === 'domain') مثال: دامنه کوتاه .com @else مثال: کانال یوتیوب آموزشی @endif" 
                               wire:model="title"
                               wire:key="title-input"
                               id="title-input"
                               style="color: #ffffff !important;">
                        @error('title') 
                            <span class="text-danger small d-block mt-2">
                                <i class="bi bi-exclamation-circle me-1"></i> {{ $message }}
                            </span> 
                        @enderror
                    </div>

                    <!-- Category -->
                    <div class="mb-4">
                        <label class="form-label mb-2 d-block fw-semibold" for="panel_category_id" style="color: #ffffff; font-size: 15px;">
                            <i class="bi bi-tags me-1" style="color: #00f0ff;"></i> دسته‌بندی
                        </label>
                        <div class="modern-select">
                            <select id="panel_category_id" class="modern-input w-100 @error('category_id') border-danger @enderror" wire:model="category_id" style="color: #ffffff !important;">
                                <option value="">انتخاب دسته‌بندی (اختیاری)</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        @error('category_id') 
                            <span class="text-danger small d-block mt-2">
                                <i class="bi bi-exclamation-circle me-1"></i> {{ $message }}
                            </span> 
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="mb-4">
                        <label class="form-label mb-2 d-block fw-semibold" for="description-input" style="color: #ffffff; font-size: 15px;">
                            <i class="bi bi-text-paragraph me-1" style="color: #00f0ff;"></i> توضیحات کامل <span class="text-danger">*</span>
                        </label>
                        <textarea class="modern-input w-100 @error('description') border-danger @enderror" 
                                  rows="6" 
                                  placeholder="توضیحات کامل درباره گروه تلگرام خود را وارد کنید..." 
                                  wire:model="description"
                                  wire:key="description-textarea"
                                  id="description-input"
                                  style="resize: vertical; min-height: 120px; color: #ffffff !important;">{{ $description }}</textarea>
                        @error('description') 
                            <span class="text-danger small d-block mt-2">
                                <i class="bi bi-exclamation-circle me-1"></i> {{ $message }}
                            </span> 
                        @enderror
                    </div>

                    <!-- Telegram Fields (only for telegram ad_type) -->
                    @if($ad_type === 'telegram')
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label mb-2 d-block fw-semibold" style="color: #ffffff; font-size: 15px;">
                                <i class="bi bi-telegram me-1" style="color: #00f0ff;"></i> لینک تلگرام
                            </label>
                            <input type="url" 
                                   class="modern-input w-100 @error('telegram_link') border-danger @enderror" 
                                   placeholder="https://t.me/yourgroup" 
                                   wire:model="telegram_link"
                                   wire:key="telegram-input"
                                   dir="ltr"
                                   id="telegram-input"
                                   style="color: #ffffff !important;">
                            @error('telegram_link') 
                                <span class="text-danger small d-block mt-2">
                                    <i class="bi bi-exclamation-circle me-1"></i> {{ $message }}
                                </span> 
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label mb-2 d-block fw-semibold" style="color: #ffffff; font-size: 15px;">
                                <i class="bi bi-hash me-1" style="color: #00f0ff;"></i> ID گروه یا کانال
                            </label>
                            <input type="text" 
                                   class="modern-input w-100 @error('telegram_id') border-danger @enderror" 
                                   placeholder="@mygroup" 
                                   wire:model="telegram_id"
                                   wire:key="telegram-id-input"
                                   dir="ltr"
                                   id="telegram-id-input"
                                   style="color: #ffffff !important;">
                            @error('telegram_id') 
                                <span class="text-danger small d-block mt-2">
                                    <i class="bi bi-exclamation-circle me-1"></i> {{ $message }}
                                </span> 
                            @enderror
                        </div>
                    </div>

                    <!-- Members (only for telegram) -->
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label mb-2 d-block fw-semibold" style="color: #ffffff; font-size: 15px;">
                                <i class="bi bi-people me-1" style="color: #00f0ff;"></i> تعداد اعضا <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="modern-input w-100 @error('member_count') border-danger @enderror" 
                                   placeholder="مثال: 1000" 
                                   pattern="[0-9]*"
                                   inputmode="numeric"
                                   onkeypress="return event.charCode >= 48 && event.charCode <= 57"
                                   wire:model="member_count"
                                   wire:key="members-input"
                                   min="0"
                                   id="members-input"
                                   style="color: #ffffff !important;">
                            @error('member_count') 
                                <span class="text-danger small d-block mt-2">
                                    <i class="bi bi-exclamation-circle me-1"></i> {{ $message }}
                                </span> 
                            @enderror
                        </div>
                    </div>
                    @endif

                    <!-- Instagram Fields -->
                    @if($ad_type === 'instagram')
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label mb-2 d-block fw-semibold" style="color: #ffffff; font-size: 15px;">
                                <i class="bi bi-people me-1" style="color: #00f0ff;"></i> تعداد فالوور <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="modern-input w-100 @error('instagram_followers') border-danger @enderror" 
                                   placeholder="مثال: 120000" 
                                   pattern="[0-9]*"
                                   inputmode="numeric"
                                   wire:model="instagram_followers"
                                   style="color: #ffffff !important;">
                            @error('instagram_followers') 
                                <span class="text-danger small d-block mt-2">
                                    <i class="bi bi-exclamation-circle me-1"></i> {{ $message }}
                                </span> 
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label mb-2 d-block fw-semibold" style="color: #ffffff; font-size: 15px;">
                                <i class="bi bi-tags me-1" style="color: #00f0ff;"></i> دسته‌بندی <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="modern-input w-100 @error('instagram_category') border-danger @enderror" 
                                   placeholder="مثال: مد و فشن، تکنولوژی" 
                                   wire:model="instagram_category"
                                   style="color: #ffffff !important;">
                            @error('instagram_category') 
                                <span class="text-danger small d-block mt-2">
                                    <i class="bi bi-exclamation-circle me-1"></i> {{ $message }}
                                </span> 
                            @enderror
                        </div>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label mb-2 d-block fw-semibold" style="color: #ffffff; font-size: 15px;">
                                <i class="bi bi-graph-up me-1" style="color: #00f0ff;"></i> نرخ تعامل (درصد)
                            </label>
                            <input type="text" 
                                   class="modern-input w-100 @error('instagram_engagement_rate') border-danger @enderror" 
                                   placeholder="مثال: 3.5" 
                                   wire:model="instagram_engagement_rate"
                                   style="color: #ffffff !important;">
                            @error('instagram_engagement_rate') 
                                <span class="text-danger small d-block mt-2">
                                    <i class="bi bi-exclamation-circle me-1"></i> {{ $message }}
                                </span> 
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <div class="form-check form-switch d-flex align-items-center mt-4">
                                <input class="form-check-input" type="checkbox" wire:model="instagram_monetized" id="instagram_monetized" style="width: 50px; height: 26px;">
                                <label class="form-check-label me-3" for="instagram_monetized" style="color: #ffffff;">
                                    <strong>مونیتایز شده</strong>
                                </label>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Website Fields -->
                    @if($ad_type === 'website')
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label mb-2 d-block fw-semibold" style="color: #ffffff; font-size: 15px;">
                                <i class="bi bi-link-45deg me-1" style="color: #00f0ff;"></i> آدرس سایت <span class="text-danger">*</span>
                            </label>
                            <input type="url" 
                                   class="modern-input w-100 @error('website_url') border-danger @enderror" 
                                   placeholder="https://example.com" 
                                   wire:model="website_url"
                                   dir="ltr"
                                   style="color: #ffffff !important;">
                            @error('website_url') 
                                <span class="text-danger small d-block mt-2">
                                    <i class="bi bi-exclamation-circle me-1"></i> {{ $message }}
                                </span> 
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label mb-2 d-block fw-semibold" style="color: #ffffff; font-size: 15px;">
                                <i class="bi bi-tags me-1" style="color: #00f0ff;"></i> نوع سایت <span class="text-danger">*</span>
                            </label>
                            <select class="modern-input w-100 @error('website_type') border-danger @enderror" 
                                    wire:model="website_type"
                                    style="color: #ffffff !important;">
                                <option value="">انتخاب کنید</option>
                                <option value="store">فروشگاهی</option>
                                <option value="blog">وبلاگ</option>
                                <option value="service">خدماتی</option>
                            </select>
                            @error('website_type') 
                                <span class="text-danger small d-block mt-2">
                                    <i class="bi bi-exclamation-circle me-1"></i> {{ $message }}
                                </span> 
                            @enderror
                        </div>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label mb-2 d-block fw-semibold" style="color: #ffffff; font-size: 15px;">
                                <i class="bi bi-eye me-1" style="color: #00f0ff;"></i> بازدید ماهانه <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="modern-input w-100 @error('website_monthly_visits') border-danger @enderror" 
                                   placeholder="مثال: 50000" 
                                   pattern="[0-9]*"
                                   wire:model="website_monthly_visits"
                                   style="color: #ffffff !important;">
                            @error('website_monthly_visits') 
                                <span class="text-danger small d-block mt-2">
                                    <i class="bi bi-exclamation-circle me-1"></i> {{ $message }}
                                </span> 
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label mb-2 d-block fw-semibold" style="color: #ffffff; font-size: 15px;">
                                <i class="bi bi-cash-coin me-1" style="color: #00f0ff;"></i> درآمد ماهانه (تومان)
                            </label>
                            <input type="text" 
                                   class="modern-input w-100 @error('website_monthly_income') border-danger @enderror" 
                                   placeholder="مثال: 5000000" 
                                   wire:model="website_monthly_income"
                                   dir="ltr"
                                   style="color: #ffffff !important;">
                            @error('website_monthly_income') 
                                <span class="text-danger small d-block mt-2">
                                    <i class="bi bi-exclamation-circle me-1"></i> {{ $message }}
                                </span> 
                            @enderror
                        </div>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label mb-2 d-block fw-semibold" style="color: #ffffff; font-size: 15px;">
                                <i class="bi bi-code-slash me-1" style="color: #00f0ff;"></i> تکنولوژی استفاده شده
                            </label>
                            <input type="text" 
                                   class="modern-input w-100 @error('website_tech') border-danger @enderror" 
                                   placeholder="مثال: WordPress, Laravel, React" 
                                   wire:model="website_tech"
                                   style="color: #ffffff !important;">
                            @error('website_tech') 
                                <span class="text-danger small d-block mt-2">
                                    <i class="bi bi-exclamation-circle me-1"></i> {{ $message }}
                                </span> 
                            @enderror
                        </div>
                    </div>
                    @endif

                    <!-- Domain Fields -->
                    @if($ad_type === 'domain')
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label mb-2 d-block fw-semibold" style="color: #ffffff; font-size: 15px;">
                                <i class="bi bi-globe me-1" style="color: #00f0ff;"></i> نام دامنه <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="modern-input w-100 @error('domain_name') border-danger @enderror" 
                                   placeholder="مثال: example" 
                                   wire:model="domain_name"
                                   dir="ltr"
                                   style="color: #ffffff !important;">
                            @error('domain_name') 
                                <span class="text-danger small d-block mt-2">
                                    <i class="bi bi-exclamation-circle me-1"></i> {{ $message }}
                                </span> 
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label mb-2 d-block fw-semibold" style="color: #ffffff; font-size: 15px;">
                                <i class="bi bi-dot me-1" style="color: #00f0ff;"></i> پسوند <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="modern-input w-100 @error('domain_extension') border-danger @enderror" 
                                   placeholder=".com" 
                                   wire:model="domain_extension"
                                   dir="ltr"
                                   style="color: #ffffff !important;">
                            @error('domain_extension') 
                                <span class="text-danger small d-block mt-2">
                                    <i class="bi bi-exclamation-circle me-1"></i> {{ $message }}
                                </span> 
                            @enderror
                        </div>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label mb-2 d-block fw-semibold" style="color: #ffffff; font-size: 15px;">
                                <i class="bi bi-calendar-x me-1" style="color: #00f0ff;"></i> تاریخ انقضا <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   data-jdp 
                                   class="modern-input w-100 @error('domain_expire_date') border-danger @enderror" 
                                   wire:model.blur="domain_expire_date"
                                   placeholder="انتخاب تاریخ شمسی"
                                   style="color: #ffffff !important;">
                            @error('domain_expire_date') 
                                <span class="text-danger small d-block mt-2">
                                    <i class="bi bi-exclamation-circle me-1"></i> {{ $message }}
                                </span> 
                            @enderror
                        </div>
                    </div>
                    @endif

                    <!-- YouTube Fields -->
                    @if($ad_type === 'youtube')
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label mb-2 d-block fw-semibold" style="color: #ffffff; font-size: 15px;">
                                <i class="bi bi-people me-1" style="color: #00f0ff;"></i> تعداد مشترک <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="modern-input w-100 @error('youtube_subscribers') border-danger @enderror" 
                                   placeholder="مثال: 50000" 
                                   pattern="[0-9]*"
                                   wire:model="youtube_subscribers"
                                   style="color: #ffffff !important;">
                            @error('youtube_subscribers') 
                                <span class="text-danger small d-block mt-2">
                                    <i class="bi bi-exclamation-circle me-1"></i> {{ $message }}
                                </span> 
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label mb-2 d-block fw-semibold" style="color: #ffffff; font-size: 15px;">
                                <i class="bi bi-clock me-1" style="color: #00f0ff;"></i> ساعت تماشا <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="modern-input w-100 @error('youtube_watch_hours') border-danger @enderror" 
                                   placeholder="مثال: 4000" 
                                   pattern="[0-9]*"
                                   wire:model="youtube_watch_hours"
                                   style="color: #ffffff !important;">
                            @error('youtube_watch_hours') 
                                <span class="text-danger small d-block mt-2">
                                    <i class="bi bi-exclamation-circle me-1"></i> {{ $message }}
                                </span> 
                            @enderror
                        </div>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="form-check form-switch d-flex align-items-center mt-4">
                                <input class="form-check-input" type="checkbox" wire:model="youtube_monetized" id="youtube_monetized" style="width: 50px; height: 26px;">
                                <label class="form-check-label me-3" for="youtube_monetized" style="color: #ffffff;">
                                    <strong>مونیتایز شده</strong>
                                </label>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Construction Year -->
                    <div class="row g-3 mt-0">
                        <div class="col-md-6">
                            <label class="form-label mb-2 d-block fw-semibold" style="color: #ffffff; font-size: 15px;">
                                <i class="bi bi-calendar3 me-1" style="color: #00f0ff;"></i> سال ساخت
                            </label>
                            <input type="text" 
                                   class="modern-input w-100 @error('construction_year') border-danger @enderror" 
                                   placeholder="مثال: 1400 یا 2021" 
                                   pattern="[0-9]*"
                                   inputmode="numeric"
                                   onkeypress="return event.charCode >= 48 && event.charCode <= 57"
                                   wire:model="construction_year"
                                   wire:key="construction-year-input"
                                   id="construction-year-input"
                                   style="color: #ffffff !important;">
                            @error('construction_year') 
                                <span class="text-danger small d-block mt-2">
                                    <i class="bi bi-exclamation-circle me-1"></i> {{ $message }}
                                </span> 
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label mb-2 d-block fw-semibold" style="color: #ffffff; font-size: 15px;">
                                <i class="bi bi-calendar-check me-1" style="color: #00f0ff;"></i> نوع تقویم
                            </label>
                            <select class="modern-input w-100 @error('construction_year_calendar') border-danger @enderror" 
                                    wire:model="construction_year_calendar"
                                    style="color: #ffffff !important;">
                                <option value="solar">شمسی</option>
                                <option value="gregorian">میلادی</option>
                            </select>
                            @error('construction_year_calendar') 
                                <span class="text-danger small d-block mt-2">
                                    <i class="bi bi-exclamation-circle me-1"></i> {{ $message }}
                                </span> 
                            @enderror
                        </div>
                    </div>
                </div>
                @endif

                <!-- Step 2: Pricing -->
                @if($currentStep === 2)
                <div class="glass-card p-4 p-md-5 mb-4">
                    <h3 class="fw-bold mb-4" style="color: #ffffff;">
                        <i class="bi bi-cash-coin me-2" style="color: #b026ff;"></i>
                        قیمت‌گذاری
                    </h3>

                    @if($type === 'normal')
                        <!-- Normal Price -->
                        <div class="mb-4">
                            <label class="form-label mb-2 d-block fw-semibold" style="color: #ffffff; font-size: 15px;">
                                <i class="bi bi-cash-coin me-1" style="color: #b026ff;"></i> قیمت فروش (تومان) <span class="text-danger">*</span>
                            </label>
                            <div class="d-flex align-items-center gap-3 mb-3">
                                <input type="text" 
                                       class="modern-input flex-grow-1 @error('price') border-danger @enderror" 
                                       wire:model="price"
                                       wire:key="price-input"
                                       placeholder="مثال: 1000000"
                                       dir="ltr"
                                       style="font-size: 1.25rem; font-weight: 600; color: #ffffff !important;">
                                <span style="color: #9ca3af; font-size: 14px;">تومان</span>
                            </div>
                            @error('price') 
                                <span class="text-danger small d-block mt-2">
                                    <i class="bi bi-exclamation-circle me-1"></i> {{ $message }}
                                </span> 
                            @enderror
                        </div>
                    @else
                        <!-- Auction Base Price -->
                        <div class="mb-4">
                            <label class="form-label mb-2 d-block fw-semibold" style="color: #ffffff; font-size: 15px;">
                                <i class="bi bi-cash-coin me-1" style="color: #b026ff;"></i> قیمت پایه مزایده (تومان) <span class="text-danger">*</span>
                            </label>
                            <div class="d-flex align-items-center gap-3">
                                <input type="text" 
                                       class="modern-input flex-grow-1 @error('base_price') border-danger @enderror" 
                                       wire:model="base_price"
                                       wire:key="base-price-input"
                                       placeholder="مثال: 1000000"
                                       dir="ltr"
                                       style="font-size: 1.25rem; font-weight: 600; color: #ffffff !important;">
                                <span style="color: #9ca3af; font-size: 14px;">تومان</span>
                            </div>
                            @error('base_price') 
                                <span class="text-danger small d-block mt-2">
                                    <i class="bi bi-exclamation-circle me-1"></i> {{ $message }}
                                </span> 
                            @enderror
                        </div>
                    @endif
                </div>
                @endif

                <!-- Step 3: Contact & Details -->
                @if($currentStep === 3)
                <div class="glass-card p-4 p-md-5 mb-4">
                    <h3 class="fw-bold mb-4" style="color: #ffffff;">
                        <i class="bi bi-person-lines-fill me-2" style="color: #ff006e;"></i>
                        تماس و جزئیات
                    </h3>

                    <!-- Contact Visibility -->
                    <div class="mb-4">
                        <div class="glass-card p-4" style="background: rgba(255,255,255,0.03);">
                            <div class="form-check form-switch d-flex align-items-center">
                                <input class="form-check-input" 
                                       type="checkbox" 
                                       wire:model="show_contact" 
                                       id="show_contact"
                                       style="width: 50px; height: 26px; cursor: pointer;">
                                <label class="form-check-label me-3 flex-grow-1" for="show_contact" style="cursor: pointer;">
                                    <strong>نمایش اطلاعات تماس</strong>
                                    <small class="d-block text-muted">اجازه دهید خریداران مستقیماً با شما تماس بگیرند</small>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Step 4: Images -->
                @if($currentStep === 4)
                <div class="glass-card p-4 p-md-5 mb-4">
                    <h3 class="fw-bold mb-4" style="color: #ffffff;">
                        <i class="bi bi-images me-2" style="color: #39ff14;"></i>
                        تصاویر آگهی
                    </h3>

                    <!-- Upload Zone -->
                    <div class="upload-zone mb-4"
                         x-data="{ 
                             isDragging: false,
                             init() {
                                 this.$el.addEventListener('dragover', (e) => {
                                     e.preventDefault();
                                     this.isDragging = true;
                                 });
                                 this.$el.addEventListener('dragleave', (e) => {
                                     e.preventDefault();
                                     this.isDragging = false;
                                 });
                                 this.$el.addEventListener('drop', (e) => {
                                     e.preventDefault();
                                     this.isDragging = false;
                                     const files = Array.from(e.dataTransfer.files).filter(file => file.type.startsWith('image/'));
                                     if (files.length > 0) {
                                         const input = document.getElementById('imageInput');
                                         const dataTransfer = new DataTransfer();
                                         files.forEach(file => dataTransfer.items.add(file));
                                         input.files = dataTransfer.files;
                                         input.dispatchEvent(new Event('change', { bubbles: true }));
                                     }
                                 });
                             }
                         }"
                         :class="{ 'dragover': isDragging }"
                         @click="$refs.imageInput.click()">
                        <div class="d-flex flex-column align-items-center justify-content-center" style="min-height: 200px;">
                            <i class="bi bi-cloud-upload" style="font-size: 4rem; color: rgba(0, 240, 255, 0.5); margin-bottom: 1rem;"></i>
                            <p class="mb-2 fw-semibold" style="color: #ffffff;">
                                برای آپلود تصاویر کلیک کنید یا تصاویر را اینجا بکشید
                            </p>
                            <p class="small mb-3" style="color: #9ca3af;">
                                فرمت‌های مجاز: JPG, PNG, GIF (حداکثر 2MB)
                            </p>
                            <input type="file" 
                                   x-ref="imageInput"
                                   id="imageInput"
                                   class="d-none" 
                                   wire:model="images" 
                                   multiple 
                                   accept="image/*">
                            <button type="button" class="btn btn-modern">
                                <i class="bi bi-plus-circle me-2"></i> انتخاب تصاویر
                            </button>
                        </div>
                    </div>

                    @error('images.*') 
                        <div class="alert alert-danger">
                            <i class="bi bi-exclamation-circle me-2"></i> {{ $message }}
                        </div>
                    @enderror

                    <!-- Image Previews -->
                    @if(!empty($imagePreviews) && count($imagePreviews) > 0)
                        <div class="row g-3">
                            @foreach($imagePreviews as $index => $preview)
                                <div class="col-md-4 col-sm-6">
                                    <div class="glass-card position-relative" style="overflow: hidden;">
                                        <img src="{{ $preview['preview'] }}" 
                                             class="w-100" 
                                             style="height: 200px; object-fit: cover;"
                                             alt="Preview">
                                        <button type="button"
                                                wire:click="removeImage({{ $index }})"
                                                class="btn btn-sm position-absolute top-0 end-0 m-2"
                                                style="background: rgba(255, 0, 0, 0.8); border: none; border-radius: 50%; width: 36px; height: 36px; padding: 0;"
                                                title="حذف تصویر">
                                            <i class="bi bi-x-lg text-white"></i>
                                        </button>
                                        <div class="p-2">
                                            <small class="text-muted d-block text-truncate" title="{{ $preview['name'] }}">
                                                {{ $preview['name'] }}
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-3 glass-card p-3" style="background: rgba(0, 240, 255, 0.1);">
                            <i class="bi bi-info-circle me-2" style="color: #00f0ff;"></i>
                            <strong>{{ count($imagePreviews) }}</strong> تصویر انتخاب شده است
                        </div>
                    @endif
                </div>
                @endif

                <!-- Step 5: Payment -->
                @if($currentStep === 5)
                <div class="glass-card p-4 p-md-5 mb-4">
                    <h3 class="fw-bold mb-4" style="color: #ffffff;">
                        <i class="bi bi-credit-card me-2" style="color: #ffd700;"></i>
                        پرداخت هزینه آگهی
                    </h3>

                    @php
                        $settings = \App\Models\SiteSetting::getSettings();
                        $dailyPrice = $type === 'auction' ? $settings->auction_daily_price : $settings->ad_daily_price;
                        $totalAmount = $dailyPrice * ($payment_days ?? 30);
                    @endphp

                    <div class="glass-card p-4 mb-4" style="background: rgba(255, 215, 0, 0.1); border-color: rgba(255, 215, 0, 0.3);">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="form-label mb-2 d-block fw-semibold" style="color: #ffffff; font-size: 15px;">
                                    <i class="bi bi-calendar-range me-1" style="color: #ffd700;"></i> تعداد روزهای نمایش آگهی
                                </label>
                                <input type="text" 
                                       class="modern-input w-100" 
                                       wire:model.live="payment_days"
                                       pattern="[0-9]*"
                                       inputmode="numeric"
                                       onkeypress="return event.charCode >= 48 && event.charCode <= 57"
                                       wire:key="payment-days-input"
                                       style="color: #ffffff !important;">
                                <small style="color: #9ca3af;" class="d-block mt-2">
                                    حداقل 1 روز و حداکثر 365 روز
                                </small>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex flex-column">
                                    <label class="form-label mb-2 fw-semibold" style="color: #ffffff; font-size: 15px;">
                                        <i class="bi bi-cash-stack me-1" style="color: #ffd700;"></i> مبلغ قابل پرداخت
                                    </label>
                                    <div class="glass-card p-3" style="background: rgba(255, 215, 0, 0.15);">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <span style="color: #9ca3af; font-size: 14px;">قیمت روزانه:</span>
                                            <span style="color: #ffffff; font-weight: 600;">{{ number_format($dailyPrice) }} تومان</span>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between mt-2">
                                            <span style="color: #9ca3af; font-size: 14px;">تعداد روزها:</span>
                                            <span style="color: #ffffff; font-weight: 600;">{{ $payment_days }} روز</span>
                                        </div>
                                        <hr style="border-color: rgba(255,255,255,0.2); margin: 1rem 0;">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <span style="color: #ffffff; font-size: 18px; font-weight: 700;">جمع کل:</span>
                                            <span style="color: #ffd700; font-size: 24px; font-weight: 700;">{{ number_format($totalAmount) }} تومان</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-info glass-card p-4" style="background: rgba(0, 240, 255, 0.1); border-color: rgba(0, 240, 255, 0.3);">
                        <div class="d-flex align-items-start gap-3">
                            <i class="bi bi-info-circle" style="font-size: 1.5rem; color: #00f0ff;"></i>
                            <div>
                                <h6 class="fw-bold mb-2" style="color: #ffffff;">نکات مهم:</h6>
                                <ul style="color: #e5e7eb; margin: 0; padding-right: 1.5rem;">
                                    <li>پس از پرداخت موفق، آگهی شما به مدت {{ $payment_days }} روز فعال خواهد بود.</li>
                                    <li>در صورت عدم پرداخت، آگهی شما غیرفعال باقی می‌ماند.</li>
                                    <li>می‌توانید بعداً از پنل کاربری خود آگهی را تمدید کنید.</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Navigation Buttons -->
                <div class="d-flex justify-content-between gap-3">
                    @if($currentStep > 1)
                    <button type="button" 
                            class="btn btn-modern" 
                            style="background: rgba(255,255,255,0.1); box-shadow: none;"
                            wire:click="previousStep">
                        <i class="bi bi-arrow-right me-2"></i> قبلی
                    </button>
                    @endif
                    <div class="ms-auto"></div>
                    @if($currentStep < 4)
                    <button type="button" 
                            class="btn btn-modern" 
                            wire:click="nextStep">
                        بعدی <i class="bi bi-arrow-left me-2"></i>
                    </button>
                    @endif
                    @if($currentStep === 4)
                    <button type="submit" 
                            class="btn btn-modern" 
                            wire:loading.attr="disabled"
                            wire:target="save">
                        <span wire:loading.remove wire:target="save">
                            <i class="bi bi-check-circle me-2"></i> ثبت آگهی
                        </span>
                        <span wire:loading wire:target="save">
                            <span class="spinner-border spinner-border-sm me-2" role="status"></span>
                            در حال ذخیره...
                        </span>
                    </button>
                    @endif
                    @if($currentStep === 5)
                    <button type="button" 
                            class="btn btn-modern" 
                            wire:click="proceedToPayment"
                            wire:loading.attr="disabled"
                            wire:target="proceedToPayment"
                            style="background: linear-gradient(135deg, #ffd700, #ffed4e); color: #000; font-weight: 700;">
                        <span wire:loading.remove wire:target="proceedToPayment">
                            <i class="bi bi-credit-card me-2"></i> پرداخت و فعال‌سازی آگهی
                        </span>
                        <span wire:loading wire:target="proceedToPayment">
                            <span class="spinner-border spinner-border-sm me-2" role="status"></span>
                            در حال انتقال به درگاه...
                        </span>
                    </button>
                    @endif
                </div>
            </div>

            <!-- Live Preview Sidebar -->
            <div class="col-lg-4">
                <div class="preview-card">
                    <div class="glass-card p-4 sticky-top" style="top: 20px;">
                        <h4 class="fw-bold mb-4" style="color: #ffffff;">
                            <i class="bi bi-eye me-2" style="color: #00f0ff;"></i>
                            پیش‌نمایش آگهی
                        </h4>
                        @include('components.ad-card', [
                            'ad' => $previewAd,
                            'type' => $type,
                            'preview' => true
                        ])
                    </div>
                </div>
            </div>
        </div>
    </form>

    @script
    <script>
        // Initialize jalali datepicker - define as global function
        window.initJalaliDatePicker = function() {
            if (typeof jalaliDatepicker === 'undefined') {
                setTimeout(window.initJalaliDatePicker, 100);
                return;
            }
            
            if (jalaliDatepicker.config) {
                jalaliDatepicker.config.zIndex = 1060;
            }
            
            // Stop previous watch if exists
            try {
                jalaliDatepicker.stopWatch();
            } catch(e) {}
            
            // Start watch to detect new datepicker inputs
            jalaliDatepicker.startWatch();
            
            // Manually initialize any existing datepicker inputs
            document.querySelectorAll('input[data-jdp]').forEach(input => {
                if (!input.hasAttribute('data-jdp-initialized')) {
                    try {
                        jalaliDatepicker.init(input);
                        input.setAttribute('data-jdp-initialized', 'true');
                    } catch(e) {
                        console.log('Datepicker init error:', e);
                    }
                }
            });
            
            setTimeout(() => {
                // Only fix z-index, let datepicker use its own styles
                document.querySelectorAll('[class*="jdp"], [id*="jdp"], .datepicker-plot-area').forEach(el => {
                    el.style.zIndex = '1060';
                });
            }, 100);
        };

        document.addEventListener('livewire:init', () => {
            // Reinitialize datepicker on Livewire updates
            Livewire.hook('morph.updated', ({ el }) => {
                setTimeout(window.initJalaliDatePicker, 200);
            });
            
            // Listen for ad_type changes
            Livewire.on('ad-type-changed', () => {
                setTimeout(() => {
                    window.initJalaliDatePicker();
                }, 300);
            });
        });

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', window.initJalaliDatePicker);
        } else {
            window.initJalaliDatePicker();
        }
    </script>
    @endscript
</div>
