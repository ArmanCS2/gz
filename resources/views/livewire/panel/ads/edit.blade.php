<div>
    <h2 class="mb-4">ویرایش آگهی</h2>

    <form wire:submit.prevent="save">
        <div class="card mb-3">
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">
                        <i class="bi bi-card-heading"></i> عنوان آگهی <span class="text-danger">*</span>
                    </label>
                    <input type="text" class="form-control @error('title') is-invalid @enderror" wire:model="title" placeholder="عنوان آگهی خود را وارد کنید">
                    @error('title') <span class="text-danger small d-block mt-1"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">
                        <i class="bi bi-tag"></i> نوع آگهی <span class="text-danger">*</span>
                    </label>
                    <select class="form-control @error('ad_type') is-invalid @enderror" wire:model.live="ad_type">
                        <option value="telegram">گروه تلگرام</option>
                        <option value="instagram">پیج اینستاگرام</option>
                        <option value="website">سایت آماده</option>
                        <option value="domain">دامنه</option>
                        <option value="youtube">کانال یوتیوب</option>
                    </select>
                    @error('ad_type') <span class="text-danger small d-block mt-1"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">
                        <i class="bi bi-tags"></i> دسته‌بندی
                    </label>
                    <select class="form-control @error('category_id') is-invalid @enderror" wire:model="category_id">
                        <option value="">انتخاب دسته‌بندی (اختیاری)</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id') <span class="text-danger small d-block mt-1"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">
                        <i class="bi bi-text-paragraph"></i> توضیحات <span class="text-danger">*</span>
                    </label>
                    <textarea class="form-control @error('description') is-invalid @enderror" rows="6" wire:model="description" placeholder="توضیحات کامل آگهی را اینجا وارد کنید..."></textarea>
                    @error('description') <span class="text-danger small d-block mt-1"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span> @enderror
                </div>

                <!-- Telegram Fields (only for telegram ad_type) -->
                @if($ad_type === 'telegram')
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">
                            <i class="bi bi-telegram"></i> لینک تلگرام
                        </label>
                        <input type="url" class="form-control @error('telegram_link') is-invalid @enderror" wire:model="telegram_link" placeholder="https://t.me/..." dir="ltr">
                        @error('telegram_link') <span class="text-danger small d-block mt-1"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">
                            <i class="bi bi-hash"></i> ID گروه یا کانال
                        </label>
                        <input type="text" class="form-control @error('telegram_id') is-invalid @enderror" wire:model="telegram_id" placeholder="@mygroup" dir="ltr">
                        @error('telegram_id') <span class="text-danger small d-block mt-1"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">
                            <i class="bi bi-people"></i> تعداد اعضا <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control @error('member_count') is-invalid @enderror" wire:model="member_count" pattern="[0-9]*" inputmode="numeric" onkeypress="return event.charCode >= 48 && event.charCode <= 57" placeholder="0">
                        @error('member_count') <span class="text-danger small d-block mt-1"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span> @enderror
                    </div>
                </div>
                @endif

                <!-- Instagram Fields -->
                @if($ad_type === 'instagram')
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">
                            <i class="bi bi-people"></i> تعداد فالوور <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control @error('instagram_followers') is-invalid @enderror" wire:model="instagram_followers" pattern="[0-9]*" placeholder="مثال: 120000">
                        @error('instagram_followers') <span class="text-danger small d-block mt-1"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">
                            <i class="bi bi-tags"></i> دسته‌بندی <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control @error('instagram_category') is-invalid @enderror" wire:model="instagram_category" placeholder="مثال: مد و فشن">
                        @error('instagram_category') <span class="text-danger small d-block mt-1"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">
                            <i class="bi bi-graph-up"></i> نرخ تعامل (درصد)
                        </label>
                        <input type="text" class="form-control @error('instagram_engagement_rate') is-invalid @enderror" wire:model="instagram_engagement_rate" placeholder="مثال: 3.5">
                        @error('instagram_engagement_rate') <span class="text-danger small d-block mt-1"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="form-check form-switch mt-4">
                            <input class="form-check-input" type="checkbox" wire:model="instagram_monetized" id="instagram_monetized">
                            <label class="form-check-label" for="instagram_monetized">مونیتایز شده</label>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Website Fields -->
                @if($ad_type === 'website')
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">
                            <i class="bi bi-link-45deg"></i> آدرس سایت <span class="text-danger">*</span>
                        </label>
                        <input type="url" class="form-control @error('website_url') is-invalid @enderror" wire:model="website_url" placeholder="https://example.com" dir="ltr">
                        @error('website_url') <span class="text-danger small d-block mt-1"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">
                            <i class="bi bi-tags"></i> نوع سایت <span class="text-danger">*</span>
                        </label>
                        <select class="form-control @error('website_type') is-invalid @enderror" wire:model="website_type">
                            <option value="">انتخاب کنید</option>
                            <option value="store">فروشگاهی</option>
                            <option value="blog">وبلاگ</option>
                            <option value="service">خدماتی</option>
                        </select>
                        @error('website_type') <span class="text-danger small d-block mt-1"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">
                            <i class="bi bi-eye"></i> بازدید ماهانه <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control @error('website_monthly_visits') is-invalid @enderror" wire:model="website_monthly_visits" pattern="[0-9]*" placeholder="مثال: 50000">
                        @error('website_monthly_visits') <span class="text-danger small d-block mt-1"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">
                            <i class="bi bi-cash-coin"></i> درآمد ماهانه (تومان)
                        </label>
                        <input type="text" class="form-control @error('website_monthly_income') is-invalid @enderror" wire:model="website_monthly_income" placeholder="مثال: 5000000" dir="ltr">
                        @error('website_monthly_income') <span class="text-danger small d-block mt-1"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label class="form-label">
                            <i class="bi bi-code-slash"></i> تکنولوژی استفاده شده
                        </label>
                        <input type="text" class="form-control @error('website_tech') is-invalid @enderror" wire:model="website_tech" placeholder="مثال: WordPress, Laravel">
                        @error('website_tech') <span class="text-danger small d-block mt-1"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span> @enderror
                    </div>
                </div>
                @endif

                <!-- Domain Fields -->
                @if($ad_type === 'domain')
                <div class="row">
                    <div class="col-md-8 mb-3">
                        <label class="form-label">
                            <i class="bi bi-globe"></i> نام دامنه <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control @error('domain_name') is-invalid @enderror" wire:model="domain_name" placeholder="example" dir="ltr">
                        @error('domain_name') <span class="text-danger small d-block mt-1"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">
                            <i class="bi bi-dot"></i> پسوند <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control @error('domain_extension') is-invalid @enderror" wire:model="domain_extension" placeholder=".com" dir="ltr">
                        @error('domain_extension') <span class="text-danger small d-block mt-1"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">
                            <i class="bi bi-calendar-x"></i> تاریخ انقضا <span class="text-danger">*</span>
                        </label>
                        <input type="text" 
                               data-jdp 
                               class="form-control @error('domain_expire_date') is-invalid @enderror" 
                               wire:model.blur="domain_expire_date"
                               placeholder="انتخاب تاریخ شمسی">
                        @error('domain_expire_date') <span class="text-danger small d-block mt-1"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span> @enderror
                    </div>
                </div>
                @endif

                <!-- YouTube Fields -->
                @if($ad_type === 'youtube')
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">
                            <i class="bi bi-people"></i> تعداد مشترک <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control @error('youtube_subscribers') is-invalid @enderror" wire:model="youtube_subscribers" pattern="[0-9]*" placeholder="مثال: 50000">
                        @error('youtube_subscribers') <span class="text-danger small d-block mt-1"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">
                            <i class="bi bi-clock"></i> ساعت تماشا <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control @error('youtube_watch_hours') is-invalid @enderror" wire:model="youtube_watch_hours" pattern="[0-9]*" placeholder="مثال: 4000">
                        @error('youtube_watch_hours') <span class="text-danger small d-block mt-1"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="form-check form-switch mt-4">
                            <input class="form-check-input" type="checkbox" wire:model="youtube_monetized" id="youtube_monetized">
                            <label class="form-check-label" for="youtube_monetized">مونیتایز شده</label>
                        </div>
                    </div>
                </div>
                @endif

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">
                            <i class="bi bi-calendar3"></i> سال ساخت
                        </label>
                        <input type="text" class="form-control @error('construction_year') is-invalid @enderror" wire:model="construction_year" pattern="[0-9]*" inputmode="numeric" onkeypress="return event.charCode >= 48 && event.charCode <= 57" placeholder="مثال: 1400 یا 2021">
                        @error('construction_year') <span class="text-danger small d-block mt-1"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">
                            <i class="bi bi-calendar-check"></i> نوع تقویم
                        </label>
                        <select class="form-control @error('construction_year_calendar') is-invalid @enderror" wire:model="construction_year_calendar">
                            <option value="solar">شمسی</option>
                            <option value="gregorian">میلادی</option>
                        </select>
                        @error('construction_year_calendar') <span class="text-danger small d-block mt-1"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span> @enderror
                    </div>
                </div>

                @if($ad->type === 'normal')
                    <div class="mb-3">
                        <label class="form-label">
                            <i class="bi bi-cash-coin"></i> قیمت (تومان)
                        </label>
                        <div class="input-group">
                            <input type="text" class="form-control @error('price') is-invalid @enderror" wire:model="price" placeholder="مثال: 1000000" dir="ltr">
                            <span class="input-group-text">تومان</span>
                        </div>
                        @error('price') <span class="text-danger small d-block mt-1"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span> @enderror
                    </div>
                @else
                    <div class="mb-3">
                        <label class="form-label">
                            <i class="bi bi-cash-coin"></i> قیمت پایه (تومان)
                        </label>
                        <div class="input-group">
                            <input type="text" class="form-control @error('base_price') is-invalid @enderror" wire:model="base_price" placeholder="مثال: 1000000" dir="ltr">
                            <span class="input-group-text">تومان</span>
                        </div>
                        @error('base_price') <span class="text-danger small d-block mt-1"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span> @enderror
                    </div>
                @endif

                <div class="mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" wire:model="show_contact" id="show_contact">
                        <label class="form-check-label" for="show_contact">نمایش اطلاعات تماس</label>
                    </div>
                </div>

                <!-- Existing Images -->
                @if(!empty($existing_images) && count($existing_images) > 0)
                    <div class="mb-4">
                        <label class="form-label mb-3 d-block">تصاویر موجود</label>
                        <div class="row g-3">
                            @foreach($existing_images as $image)
                                <div class="col-md-4 col-sm-6">
                                    <div class="card position-relative">
                                        <img 
                                            src="{{ asset($image->image) }}" 
                                            class="card-img-top" 
                                            style="height: 200px; object-fit: cover;"
                                            alt="Ad Image"
                                        >
                                        <button 
                                            type="button"
                                            wire:click="removeExistingImage({{ $image->id }})"
                                            wire:confirm="آیا از حذف این تصویر مطمئن هستید؟"
                                            class="btn btn-sm btn-danger position-absolute top-0 end-0 m-2"
                                            style="border-radius: 50%; width: 32px; height: 32px; padding: 0;"
                                        >
                                            <i class="bi bi-x-lg"></i>
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Add New Images -->
                <div class="mb-3">
                    <label class="form-label mb-3 d-block">افزودن تصاویر جدید</label>
                    
                    <!-- Drag & Drop Zone -->
                    <div 
                        class="border-2 border-dashed rounded p-4 text-center mb-3" 
                        style="border-color: #dee2e6; min-height: 200px;"
                        x-data="{ 
                            isDragging: false,
                            init() {
                                this.$el.addEventListener('dragover', (e) => {
                                    e.preventDefault();
                                    this.isDragging = true;
                                    this.$el.style.borderColor = '#0d6efd';
                                    this.$el.style.backgroundColor = '#f0f8ff';
                                });
                                this.$el.addEventListener('dragleave', (e) => {
                                    e.preventDefault();
                                    this.isDragging = false;
                                    this.$el.style.borderColor = '#dee2e6';
                                    this.$el.style.backgroundColor = '';
                                });
                                this.$el.addEventListener('drop', (e) => {
                                    e.preventDefault();
                                    this.isDragging = false;
                                    this.$el.style.borderColor = '#dee2e6';
                                    this.$el.style.backgroundColor = '';
                                    
                                    const files = Array.from(e.dataTransfer.files).filter(file => file.type.startsWith('image/'));
                                    if (files.length > 0) {
                                        const input = document.getElementById('editImageInput');
                                        const dataTransfer = new DataTransfer();
                                        files.forEach(file => dataTransfer.items.add(file));
                                        input.files = dataTransfer.files;
                                        input.dispatchEvent(new Event('change', { bubbles: true }));
                                    }
                                });
                            }
                        }"
                    >
                        <div class="d-flex flex-column align-items-center justify-content-center" style="min-height: 150px;">
                            <i class="bi bi-cloud-upload fs-1 text-muted mb-3"></i>
                            <p class="mb-2">
                                <strong>برای آپلود تصاویر کلیک کنید</strong> یا تصاویر را اینجا بکشید
                            </p>
                            <p class="text-muted small mb-3">
                                فرمت‌های مجاز: JPG, PNG, GIF (حداکثر 2MB)
                            </p>
                            <input 
                                type="file" 
                                id="editImageInput"
                                class="d-none" 
                                wire:model="images" 
                                multiple 
                                accept="image/*"
                            >
                            <label for="editImageInput" class="btn btn-primary">
                                <i class="bi bi-plus-circle"></i> انتخاب تصاویر
                            </label>
                        </div>
                    </div>

                    @error('images.*') 
                        <div class="alert alert-danger">
                            {{ $message }}
                        </div>
                    @enderror

                    <!-- Image Previews -->
                    @if(!empty($imagePreviews) && count($imagePreviews) > 0)
                        <div class="row g-3 mt-3">
                            @foreach($imagePreviews as $index => $preview)
                                <div class="col-md-4 col-sm-6">
                                    <div class="card position-relative shadow-sm">
                                        <img 
                                            src="{{ $preview['preview'] }}" 
                                            class="card-img-top" 
                                                style="height: 200px; object-fit: cover;"
                                                alt="Preview"
                                            >
                                            <div class="card-body p-2">
                                                <small class="text-muted d-block text-truncate" title="{{ $preview['name'] }}">
                                                    {{ $preview['name'] }}
                                                </small>
                                            </div>
                                            <button 
                                                type="button"
                                                wire:click="removeImage({{ $index }})"
                                                class="btn btn-sm btn-danger position-absolute top-0 end-0 m-2"
                                                style="border-radius: 50%; width: 32px; height: 32px; padding: 0;"
                                                title="حذف تصویر"
                                            >
                                                <i class="bi bi-x-lg"></i>
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        @if(!empty($imagePreviews) && count($imagePreviews) > 0)
                        <div class="mt-3 alert alert-info">
                            <i class="bi bi-info-circle"></i> 
                            <strong>{{ count($imagePreviews) }}</strong> تصویر جدید انتخاب شده است
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="mt-4 d-flex gap-2">
            <button type="submit" class="btn btn-primary btn-lg" wire:loading.attr="disabled" wire:target="save">
                <span wire:loading.remove wire:target="save">
                    <i class="bi bi-check-circle"></i> ذخیره تغییرات
                </span>
                <span wire:loading wire:target="save">
                    <span class="spinner-border spinner-border-sm me-2" role="status"></span>
                    در حال ذخیره...
                </span>
            </button>
            <a href="{{ route('panel.ads.show', $ad) }}" class="btn btn-secondary btn-lg">
                <i class="bi bi-x-circle"></i> انصراف
            </a>
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

