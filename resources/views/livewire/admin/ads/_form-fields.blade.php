{{-- Form Fields Partial for Admin Ads Create/Edit --}}
<div class="row g-3 mb-3">
    <div class="col-md-4">
        <label class="form-label mb-2 d-block fw-semibold" for="admin_user_id" style="color: #ffffff; font-size: 14px;">
            <i class="bi bi-person me-1" style="color: #00f0ff;"></i> کاربر
        </label>
        <select id="admin_user_id" class="modern-input w-100" wire:model="user_id" style="color: #ffffff !important;">
            <option value="">انتخاب کاربر</option>
            @foreach($users as $user)
                <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->mobile }})</option>
            @endforeach
        </select>
        @error('user_id') <span class="text-danger small d-block mt-1">{{ $message }}</span> @enderror
    </div>
    <div class="col-md-4">
        <label class="form-label mb-2 d-block fw-semibold" for="admin_category_id" style="color: #ffffff; font-size: 14px;">
            <i class="bi bi-grid-3x3-gap me-1" style="color: #00f0ff;"></i> دسته‌بندی
        </label>
        <select id="admin_category_id" class="modern-input w-100" wire:model="category_id" style="color: #ffffff !important;">
            <option value="">انتخاب دسته‌بندی (اختیاری)</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
            @endforeach
        </select>
        @error('category_id') <span class="text-danger small d-block mt-1">{{ $message }}</span> @enderror
    </div>
    <div class="col-md-4">
        <label class="form-label mb-2 d-block fw-semibold" for="admin_ad_type" style="color: #ffffff; font-size: 14px;">
            <i class="bi bi-tag me-1" style="color: #00f0ff;"></i> نوع آگهی <span class="text-danger">*</span>
        </label>
        <select id="admin_ad_type" class="modern-input w-100" wire:model.live="ad_type" style="color: #ffffff !important;">
            <option value="telegram">گروه تلگرام</option>
            <option value="instagram">پیج اینستاگرام</option>
            <option value="website">سایت آماده</option>
            <option value="domain">دامنه</option>
            <option value="youtube">کانال یوتیوب</option>
        </select>
        @error('ad_type') <span class="text-danger small d-block mt-1">{{ $message }}</span> @enderror
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-md-6">
        <label class="form-label mb-2 d-block fw-semibold" for="admin_type" style="color: #ffffff; font-size: 14px;">
            <i class="bi bi-tags me-1" style="color: #00f0ff;"></i> نوع فروش
        </label>
        <select id="admin_type" class="modern-input w-100" wire:model="type" style="color: #ffffff !important;">
            <option value="normal">عادی</option>
            <option value="auction">مزایده</option>
        </select>
        @error('type') <span class="text-danger small d-block mt-1">{{ $message }}</span> @enderror
    </div>
</div>

<div class="mb-3">
    <label class="form-label mb-2 d-block fw-semibold" for="admin_title" style="color: #ffffff; font-size: 14px;">
        <i class="bi bi-card-heading me-1" style="color: #00f0ff;"></i> عنوان
    </label>
    <input type="text" id="admin_title" class="modern-input w-100" wire:model="title" style="color: #ffffff !important;">
    @error('title') <span class="text-danger small d-block mt-1">{{ $message }}</span> @enderror
</div>

<div class="mb-3">
    <label class="form-label mb-2 d-block fw-semibold" for="admin_description" style="color: #ffffff; font-size: 14px;">
        <i class="bi bi-file-text me-1" style="color: #00f0ff;"></i> توضیحات
    </label>
    <textarea id="admin_description" class="modern-input w-100" rows="4" wire:model="description" style="color: #ffffff !important;"></textarea>
    @error('description') <span class="text-danger small d-block mt-1">{{ $message }}</span> @enderror
</div>

<!-- Telegram Fields -->
@if($ad_type === 'telegram')
<div class="row g-3 mb-3">
    <div class="col-md-6">
        <label class="form-label mb-2 d-block fw-semibold" for="admin_telegram_link" style="color: #ffffff; font-size: 14px;">
            <i class="bi bi-telegram me-1" style="color: #00f0ff;"></i> لینک تلگرام
        </label>
        <input type="url" id="admin_telegram_link" class="modern-input w-100" wire:model="telegram_link" style="color: #ffffff !important;">
        @error('telegram_link') <span class="text-danger small d-block mt-1">{{ $message }}</span> @enderror
    </div>
    <div class="col-md-6">
        <label class="form-label mb-2 d-block fw-semibold" for="admin_telegram_id" style="color: #ffffff; font-size: 14px;">
            <i class="bi bi-hash me-1" style="color: #00f0ff;"></i> ID گروه یا کانال
        </label>
        <input type="text" id="admin_telegram_id" class="modern-input w-100" wire:model="telegram_id" placeholder="مثال: @mygroup یا -1001234567890" dir="ltr" style="color: #ffffff !important;">
        @error('telegram_id') <span class="text-danger small d-block mt-1">{{ $message }}</span> @enderror
    </div>
</div>
<div class="row g-3 mb-3">
    <div class="col-md-6">
        <label class="form-label mb-2 d-block fw-semibold" for="admin_member_count" style="color: #ffffff; font-size: 14px;">
            <i class="bi bi-people me-1" style="color: #00f0ff;"></i> تعداد اعضا <span class="text-danger">*</span>
        </label>
        <input type="text" id="admin_member_count" class="modern-input w-100" wire:model="member_count" pattern="[0-9]*" inputmode="numeric" onkeypress="return event.charCode >= 48 && event.charCode <= 57" style="color: #ffffff !important;">
        @error('member_count') <span class="text-danger small d-block mt-1">{{ $message }}</span> @enderror
    </div>
</div>
@endif

<!-- Instagram Fields -->
@if($ad_type === 'instagram')
<div class="row g-3 mb-3">
    <div class="col-md-6">
        <label class="form-label mb-2 d-block fw-semibold" for="admin_instagram_followers" style="color: #ffffff; font-size: 14px;">
            <i class="bi bi-people me-1" style="color: #00f0ff;"></i> تعداد فالوور <span class="text-danger">*</span>
        </label>
        <input type="text" id="admin_instagram_followers" class="modern-input w-100" wire:model="instagram_followers" pattern="[0-9]*" style="color: #ffffff !important;">
        @error('instagram_followers') <span class="text-danger small d-block mt-1">{{ $message }}</span> @enderror
    </div>
    <div class="col-md-6">
        <label class="form-label mb-2 d-block fw-semibold" for="admin_instagram_category" style="color: #ffffff; font-size: 14px;">
            <i class="bi bi-tags me-1" style="color: #00f0ff;"></i> دسته‌بندی <span class="text-danger">*</span>
        </label>
        <input type="text" id="admin_instagram_category" class="modern-input w-100" wire:model="instagram_category" style="color: #ffffff !important;">
        @error('instagram_category') <span class="text-danger small d-block mt-1">{{ $message }}</span> @enderror
    </div>
</div>
<div class="row g-3 mb-3">
    <div class="col-md-6">
        <label class="form-label mb-2 d-block fw-semibold" for="admin_instagram_engagement_rate" style="color: #ffffff; font-size: 14px;">
            <i class="bi bi-graph-up me-1" style="color: #00f0ff;"></i> نرخ تعامل (درصد)
        </label>
        <input type="text" id="admin_instagram_engagement_rate" class="modern-input w-100" wire:model="instagram_engagement_rate" style="color: #ffffff !important;">
        @error('instagram_engagement_rate') <span class="text-danger small d-block mt-1">{{ $message }}</span> @enderror
    </div>
    <div class="col-md-6">
        <div class="form-check form-switch mt-4">
            <input class="form-check-input" type="checkbox" wire:model="instagram_monetized" id="admin_instagram_monetized">
            <label class="form-check-label" for="admin_instagram_monetized" style="color: #ffffff;">مونیتایز شده</label>
        </div>
    </div>
</div>
@endif

<!-- Website Fields -->
@if($ad_type === 'website')
<div class="row g-3 mb-3">
    <div class="col-md-6">
        <label class="form-label mb-2 d-block fw-semibold" for="admin_website_url" style="color: #ffffff; font-size: 14px;">
            <i class="bi bi-link-45deg me-1" style="color: #00f0ff;"></i> آدرس سایت <span class="text-danger">*</span>
        </label>
        <input type="url" id="admin_website_url" class="modern-input w-100" wire:model="website_url" dir="ltr" style="color: #ffffff !important;">
        @error('website_url') <span class="text-danger small d-block mt-1">{{ $message }}</span> @enderror
    </div>
    <div class="col-md-6">
        <label class="form-label mb-2 d-block fw-semibold" for="admin_website_type" style="color: #ffffff; font-size: 14px;">
            <i class="bi bi-tags me-1" style="color: #00f0ff;"></i> نوع سایت <span class="text-danger">*</span>
        </label>
        <select id="admin_website_type" class="modern-input w-100" wire:model="website_type" style="color: #ffffff !important;">
            <option value="">انتخاب کنید</option>
            <option value="store">فروشگاهی</option>
            <option value="blog">وبلاگ</option>
            <option value="service">خدماتی</option>
        </select>
        @error('website_type') <span class="text-danger small d-block mt-1">{{ $message }}</span> @enderror
    </div>
</div>
<div class="row g-3 mb-3">
    <div class="col-md-6">
        <label class="form-label mb-2 d-block fw-semibold" for="admin_website_monthly_visits" style="color: #ffffff; font-size: 14px;">
            <i class="bi bi-eye me-1" style="color: #00f0ff;"></i> بازدید ماهانه <span class="text-danger">*</span>
        </label>
        <input type="text" id="admin_website_monthly_visits" class="modern-input w-100" wire:model="website_monthly_visits" pattern="[0-9]*" style="color: #ffffff !important;">
        @error('website_monthly_visits') <span class="text-danger small d-block mt-1">{{ $message }}</span> @enderror
    </div>
    <div class="col-md-6">
        <label class="form-label mb-2 d-block fw-semibold" for="admin_website_monthly_income" style="color: #ffffff; font-size: 14px;">
            <i class="bi bi-cash-coin me-1" style="color: #00f0ff;"></i> درآمد ماهانه (تومان)
        </label>
        <input type="text" id="admin_website_monthly_income" class="modern-input w-100" wire:model="website_monthly_income" dir="ltr" style="color: #ffffff !important;">
        @error('website_monthly_income') <span class="text-danger small d-block mt-1">{{ $message }}</span> @enderror
    </div>
</div>
<div class="row g-3 mb-3">
    <div class="col-md-12">
        <label class="form-label mb-2 d-block fw-semibold" for="admin_website_tech" style="color: #ffffff; font-size: 14px;">
            <i class="bi bi-code-slash me-1" style="color: #00f0ff;"></i> تکنولوژی استفاده شده
        </label>
        <input type="text" id="admin_website_tech" class="modern-input w-100" wire:model="website_tech" style="color: #ffffff !important;">
        @error('website_tech') <span class="text-danger small d-block mt-1">{{ $message }}</span> @enderror
    </div>
</div>
@endif

<!-- Domain Fields -->
@if($ad_type === 'domain')
<div class="row g-3 mb-3">
    <div class="col-md-8">
        <label class="form-label mb-2 d-block fw-semibold" for="admin_domain_name" style="color: #ffffff; font-size: 14px;">
            <i class="bi bi-globe me-1" style="color: #00f0ff;"></i> نام دامنه <span class="text-danger">*</span>
        </label>
        <input type="text" id="admin_domain_name" class="modern-input w-100" wire:model="domain_name" dir="ltr" style="color: #ffffff !important;">
        @error('domain_name') <span class="text-danger small d-block mt-1">{{ $message }}</span> @enderror
    </div>
    <div class="col-md-4">
        <label class="form-label mb-2 d-block fw-semibold" for="admin_domain_extension" style="color: #ffffff; font-size: 14px;">
            <i class="bi bi-dot me-1" style="color: #00f0ff;"></i> پسوند <span class="text-danger">*</span>
        </label>
        <input type="text" id="admin_domain_extension" class="modern-input w-100" wire:model="domain_extension" dir="ltr" style="color: #ffffff !important;">
        @error('domain_extension') <span class="text-danger small d-block mt-1">{{ $message }}</span> @enderror
    </div>
</div>
<div class="row g-3 mb-3">
    <div class="col-md-6">
        <label class="form-label mb-2 d-block fw-semibold" for="admin_domain_expire_date" style="color: #ffffff; font-size: 14px;">
            <i class="bi bi-calendar-x me-1" style="color: #00f0ff;"></i> تاریخ انقضا <span class="text-danger">*</span>
        </label>
        <input type="text" id="admin_domain_expire_date" data-jdp class="modern-input w-100" wire:model.blur="domain_expire_date" placeholder="انتخاب تاریخ شمسی" style="color: #ffffff !important;">
        @error('domain_expire_date') <span class="text-danger small d-block mt-1">{{ $message }}</span> @enderror
    </div>
</div>
@endif

<!-- YouTube Fields -->
@if($ad_type === 'youtube')
<div class="row g-3 mb-3">
    <div class="col-md-6">
        <label class="form-label mb-2 d-block fw-semibold" for="admin_youtube_subscribers" style="color: #ffffff; font-size: 14px;">
            <i class="bi bi-people me-1" style="color: #00f0ff;"></i> تعداد مشترک <span class="text-danger">*</span>
        </label>
        <input type="text" id="admin_youtube_subscribers" class="modern-input w-100" wire:model="youtube_subscribers" pattern="[0-9]*" style="color: #ffffff !important;">
        @error('youtube_subscribers') <span class="text-danger small d-block mt-1">{{ $message }}</span> @enderror
    </div>
    <div class="col-md-6">
        <label class="form-label mb-2 d-block fw-semibold" for="admin_youtube_watch_hours" style="color: #ffffff; font-size: 14px;">
            <i class="bi bi-clock me-1" style="color: #00f0ff;"></i> ساعت تماشا <span class="text-danger">*</span>
        </label>
        <input type="text" id="admin_youtube_watch_hours" class="modern-input w-100" wire:model="youtube_watch_hours" pattern="[0-9]*" style="color: #ffffff !important;">
        @error('youtube_watch_hours') <span class="text-danger small d-block mt-1">{{ $message }}</span> @enderror
    </div>
</div>
<div class="row g-3 mb-3">
    <div class="col-md-6">
        <div class="form-check form-switch mt-4">
            <input class="form-check-input" type="checkbox" wire:model="youtube_monetized" id="admin_youtube_monetized">
            <label class="form-check-label" for="admin_youtube_monetized" style="color: #ffffff;">مونیتایز شده</label>
        </div>
    </div>
</div>
@endif

<div class="row g-3 mb-3">
    <div class="col-md-6">
        <label class="form-label mb-2 d-block fw-semibold" for="admin_construction_year" style="color: #ffffff; font-size: 14px;">
            <i class="bi bi-calendar3 me-1" style="color: #00f0ff;"></i> سال ساخت
        </label>
        <input type="text" id="admin_construction_year" class="modern-input w-100" wire:model="construction_year" pattern="[0-9]*" inputmode="numeric" onkeypress="return event.charCode >= 48 && event.charCode <= 57" placeholder="مثال: 1400 یا 2021" style="color: #ffffff !important;">
        @error('construction_year') <span class="text-danger small d-block mt-1">{{ $message }}</span> @enderror
    </div>
    <div class="col-md-6">
        <label class="form-label mb-2 d-block fw-semibold" for="admin_construction_year_calendar" style="color: #ffffff; font-size: 14px;">
            <i class="bi bi-calendar-check me-1" style="color: #00f0ff;"></i> نوع تقویم
        </label>
        <select id="admin_construction_year_calendar" class="modern-input w-100" wire:model="construction_year_calendar" style="color: #ffffff !important;">
            <option value="solar">شمسی</option>
            <option value="gregorian">میلادی</option>
        </select>
        @error('construction_year_calendar') <span class="text-danger small d-block mt-1">{{ $message }}</span> @enderror
    </div>
</div>

<div class="row g-3 mb-3">
    @if($type === 'normal')
        <div class="col-md-6">
            <label class="form-label mb-2 d-block fw-semibold" for="admin_price" style="color: #ffffff; font-size: 14px;">
                <i class="bi bi-cash-coin me-1" style="color: #00f0ff;"></i> قیمت (تومان)
            </label>
            <input type="text" id="admin_price" class="modern-input w-100" wire:model="price" pattern="[0-9]*\.?[0-9]*" inputmode="decimal" onkeypress="return (event.charCode >= 48 && event.charCode <= 57) || event.charCode === 46" style="color: #ffffff !important;">
            @error('price') <span class="text-danger small d-block mt-1">{{ $message }}</span> @enderror
        </div>
    @else
        <div class="col-md-4">
            <label class="form-label mb-2 d-block fw-semibold" for="admin_base_price" style="color: #ffffff; font-size: 14px;">
                <i class="bi bi-cash-coin me-1" style="color: #00f0ff;"></i> قیمت پایه (تومان)
            </label>
            <input type="text" id="admin_base_price" class="modern-input w-100" wire:model="base_price" pattern="[0-9]*\.?[0-9]*" inputmode="decimal" onkeypress="return (event.charCode >= 48 && event.charCode <= 57) || event.charCode === 46" style="color: #ffffff !important;">
            @error('base_price') <span class="text-danger small d-block mt-1">{{ $message }}</span> @enderror
        </div>
        <div class="col-md-4">
            <label class="form-label mb-2 d-block fw-semibold" for="admin_current_bid" style="color: #ffffff; font-size: 14px;">
                <i class="bi bi-arrow-up-circle me-1" style="color: #00f0ff;"></i> قیمت فعلی (تومان)
            </label>
            <input type="text" id="admin_current_bid" class="modern-input w-100" wire:model="current_bid" pattern="[0-9]*\.?[0-9]*" inputmode="decimal" onkeypress="return (event.charCode >= 48 && event.charCode <= 57) || event.charCode === 46" style="color: #ffffff !important;">
            @error('current_bid') <span class="text-danger small d-block mt-1">{{ $message }}</span> @enderror
        </div>
        <div class="col-md-4">
            <label class="form-label mb-2 d-block fw-semibold" for="admin_auction_end_time" style="color: #ffffff; font-size: 14px;">
                <i class="bi bi-calendar3 me-1" style="color: #00f0ff;"></i> پایان مزایده
            </label>
            <input type="text" id="admin_auction_end_time" data-jdp class="modern-input w-100" wire:model.blur="auction_end_time" placeholder="انتخاب تاریخ شمسی" style="color: #ffffff !important;">
            @error('auction_end_time') <span class="text-danger small d-block mt-1">{{ $message }}</span> @enderror
        </div>
    @endif
</div>

<!-- Existing Images -->
@if(isset($existing_images) && !empty($existing_images) && count($existing_images) > 0)
    <div class="mb-4">
        <label class="form-label mb-3 d-block fw-semibold" style="color: #ffffff; font-size: 14px;">
            <i class="bi bi-images me-1" style="color: #00f0ff;"></i> تصاویر موجود
        </label>
        <div class="row g-3">
            @foreach($existing_images as $index => $image)
                @php
                    $imagePath = is_object($image) ? $image->image : ($image['image'] ?? '');
                @endphp
                <div class="col-md-3 col-sm-4">
                    <div class="glass-card position-relative p-2">
                        <img src="{{ asset($imagePath) }}" class="w-100 rounded" style="height: 150px; object-fit: cover;">
                        <button type="button" wire:click="removeImage({{ $index }})" wire:confirm="آیا از حذف این تصویر مطمئن هستید؟" class="btn btn-sm position-absolute top-0 end-0 m-2" style="background: rgba(255, 0, 110, 0.8); border-radius: 50%; width: 32px; height: 32px; padding: 0; border: none;">
                            <i class="bi bi-x-lg text-white"></i>
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif

<!-- Add New Images -->
<div class="mb-3">
    <label class="form-label mb-3 d-block fw-semibold" style="color: #ffffff; font-size: 14px;">
        <i class="bi bi-cloud-upload me-1" style="color: #00f0ff;"></i> {{ isset($existing_images) && !empty($existing_images) ? 'افزودن تصاویر جدید' : 'تصاویر آگهی' }}
    </label>
    
    <div class="border-2 border-dashed rounded p-4 text-center mb-3 glass-card" style="border-color: rgba(255,255,255,0.2); min-height: 150px;">
        <i class="bi bi-cloud-upload" style="font-size: 3rem; color: rgba(255,255,255,0.3);"></i>
        <p class="mb-2 mt-3" style="color: #ffffff;">
            <strong>برای آپلود تصاویر کلیک کنید</strong>
        </p>
        <p class="text-muted small mb-3">فرمت‌های مجاز: JPG, PNG, GIF (حداکثر 2MB)</p>
        <input type="file" id="adminImageInput" class="d-none" wire:model="images" multiple accept="image/*">
        <label for="adminImageInput" class="btn btn-modern btn-sm">
            <i class="bi bi-plus-circle"></i> انتخاب تصاویر
        </label>
    </div>

    @error('images.*') 
        <div class="alert alert-danger">
            {{ $message }}
        </div>
    @enderror

    <!-- Image Previews -->
    @if(!empty($imagePreviews) && count($imagePreviews) > 0)
        <div class="row g-3 mt-2">
            @foreach($imagePreviews as $index => $preview)
                <div class="col-md-3 col-sm-4">
                    <div class="glass-card position-relative p-2">
                        <img src="{{ $preview['preview'] }}" class="w-100 rounded" style="height: 150px; object-fit: cover;" alt="Preview">
                        <button type="button" wire:click="removeNewImage({{ $index }})" class="btn btn-sm position-absolute top-0 end-0 m-2" style="background: rgba(255, 0, 110, 0.8); border-radius: 50%; width: 32px; height: 32px; padding: 0; border: none;">
                            <i class="bi bi-x-lg text-white"></i>
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

<div class="row g-3 mb-3">
    <div class="col-md-4">
        <label class="form-label mb-2 d-block fw-semibold" for="admin_status" style="color: #ffffff; font-size: 14px;">
            <i class="bi bi-info-circle me-1" style="color: #00f0ff;"></i> وضعیت
        </label>
        <select id="admin_status" class="modern-input w-100" wire:model="status" style="color: #ffffff !important;">
            <option value="pending">در انتظار</option>
            <option value="active">فعال</option>
            <option value="rejected">رد شده</option>
            <option value="expired">منقضی شده</option>
            <option value="sold">فروخته شده</option>
        </select>
        @error('status') <span class="text-danger small d-block mt-1">{{ $message }}</span> @enderror
    </div>
    <div class="col-md-4">
        <label class="form-label mb-2 d-block fw-semibold" for="admin_expire_at" style="color: #ffffff; font-size: 14px;">
            <i class="bi bi-calendar-x me-1" style="color: #00f0ff;"></i> تاریخ انقضا
        </label>
        <input type="text" id="admin_expire_at" data-jdp class="modern-input w-100" wire:model.blur="expire_at" placeholder="انتخاب تاریخ شمسی" style="color: #ffffff !important;">
        @error('expire_at') <span class="text-danger small d-block mt-1">{{ $message }}</span> @enderror
    </div>
    <div class="col-md-4">
        <label class="form-label mb-2 d-block fw-semibold" style="color: #ffffff; font-size: 14px;">تنظیمات</label>
        <div class="glass-card p-3">
            <div class="form-check mb-2">
                <input class="form-check-input" type="checkbox" wire:model="is_active" id="is_active">
                <label class="form-check-label" for="is_active" style="color: #ffffff;">فعال</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" wire:model="show_contact" id="show_contact">
                <label class="form-check-label" for="show_contact" style="color: #ffffff;">نمایش اطلاعات تماس</label>
            </div>
        </div>
    </div>
</div>

