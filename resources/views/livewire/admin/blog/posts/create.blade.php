<div>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0" style="color: #ffffff;">
            <i class="bi bi-plus-circle me-2" style="color: #00f0ff;"></i>
            ایجاد مقاله جدید
        </h2>
        <a href="{{ route('admin.blog.posts.index') }}" class="btn btn-modern btn-sm" style="background: rgba(255,255,255,0.1); box-shadow: none;">
            <i class="bi bi-arrow-right me-1"></i> بازگشت
        </a>
    </div>

    <form wire:submit.prevent="save">
        <div class="row">
            <div class="col-lg-8">
                <div class="glass-card p-4 mb-4">
                    <div class="mb-3">
                        <label class="form-label text-white" for="post_title">عنوان مقاله *</label>
                        <input type="text" id="post_title" wire:model="title" class="form-control modern-input" required>
                        @error('title') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-white" for="post_slug">Slug (آدرس URL)</label>
                        <input type="text" id="post_slug" wire:model="slug" class="form-control modern-input">
                        @error('slug') <span class="text-danger small">{{ $message }}</span> @enderror
                        <small class="text-white-50">اگر خالی باشد، به صورت خودکار از عنوان ساخته می‌شود</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-white" for="post_excerpt">خلاصه مقاله</label>
                        <textarea id="post_excerpt" wire:model="excerpt" class="form-control modern-input" rows="3"></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-white" for="post_content">محتوای مقاله *</label>
                        <textarea id="post_content" wire:model="content" class="form-control modern-input" rows="15" required></textarea>
                        @error('content') <span class="text-danger small">{{ $message }}</span> @enderror
                        <small class="text-white-50">می‌توانید از HTML استفاده کنید</small>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="glass-card p-4 mb-4">
                    <h5 class="text-white mb-4">تنظیمات</h5>

                    <div class="mb-3">
                        <label class="form-label text-white" for="post_category_id">دسته‌بندی</label>
                        <select id="post_category_id" wire:model="category_id" class="form-select modern-input">
                            <option value="">بدون دسته‌بندی</option>
                            @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-white" for="post_status">وضعیت</label>
                        <select id="post_status" wire:model="status" class="form-select modern-input">
                            <option value="draft">پیش‌نویس</option>
                            <option value="published">منتشر شده</option>
                            <option value="scheduled">زمان‌بندی شده</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-white">تاریخ انتشار</label>
                        <div class="row g-2">
                            <div class="col-8">
                                <label class="visually-hidden" for="post_published_date">تاریخ انتشار</label>
                                <input type="text" id="post_published_date" data-jdp class="form-control modern-input" wire:model.blur="published_date" placeholder="انتخاب تاریخ شمسی">
                            </div>
                            <div class="col-4">
                                <label class="visually-hidden" for="post_published_time">زمان انتشار</label>
                                <input type="time" id="post_published_time" class="form-control modern-input" wire:model="published_time" placeholder="زمان">
                            </div>
                        </div>
                        <small class="text-white-50">برای زمان‌بندی استفاده می‌شود</small>
                    </div>

                    <div class="mb-3 form-check">
                        <input type="checkbox" wire:model="is_featured" class="form-check-input" id="is_featured">
                        <label class="form-check-label text-white" for="is_featured">
                            مقاله ویژه
                        </label>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-white" for="post_selected_tags">برچسب‌ها</label>
                        <select id="post_selected_tags" wire:model="selected_tags" class="form-select modern-input" multiple size="5">
                            @foreach($tags as $tag)
                            <option value="{{ $tag->id }}">{{ $tag->name }}</option>
                            @endforeach
                        </select>
                        <small class="text-white-50">برای انتخاب چندتایی، Ctrl را نگه دارید</small>
                    </div>
                </div>

                <div class="glass-card p-4 mb-4">
                    <h5 class="text-white mb-4">تصویر شاخص</h5>
                    <label class="form-label text-white" for="post_banner_image">تصویر شاخص</label>
                    <input type="file" id="post_banner_image" wire:model="banner_image" class="form-control modern-input mb-3" accept="image/*">
                    @if($banner_image_preview)
                    <img src="{{ $banner_image_preview }}" alt="Preview" class="w-100 rounded" style="max-height: 200px; object-fit: cover;">
                    @endif
                </div>

                <div class="glass-card p-4">
                    <h5 class="text-white mb-4">SEO</h5>
                    <div class="mb-3">
                        <label class="form-label text-white" for="post_seo_title">عنوان SEO</label>
                        <input type="text" id="post_seo_title" wire:model="seo_title" class="form-control modern-input">
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-white" for="post_seo_description">توضیحات SEO</label>
                        <textarea id="post_seo_description" wire:model="seo_description" class="form-control modern-input" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-white" for="post_seo_keywords">کلمات کلیدی SEO</label>
                        <input type="text" id="post_seo_keywords" wire:model="seo_keywords" class="form-control modern-input" placeholder="کلمه1, کلمه2, کلمه3">
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex gap-2 justify-content-end">
            <a href="{{ route('admin.blog.posts.index') }}" class="btn btn-modern btn-sm" style="background: rgba(255,255,255,0.1); box-shadow: none;">
                انصراف
            </a>
            <button type="submit" class="btn btn-modern">
                <i class="bi bi-check-circle me-2"></i> ذخیره مقاله
            </button>
        </div>
    </form>
</div>

@script
<script>
    function initJalaliDatePicker() {
        if (typeof jalaliDatepicker !== 'undefined') {
            if (jalaliDatepicker.config) {
                jalaliDatepicker.config.zIndex = 1060;
            }
            jalaliDatepicker.stopWatch();
            jalaliDatepicker.startWatch();
            
            // Manually initialize any existing datepicker inputs
            document.querySelectorAll('input[data-jdp]').forEach(input => {
                if (!input.hasAttribute('data-jdp-initialized')) {
                    try {
                        jalaliDatepicker.init(input);
                        input.setAttribute('data-jdp-initialized', 'true');
                    } catch (e) {
                        console.log('Datepicker init error:', e);
                    }
                }
            });
        } else {
            setTimeout(initJalaliDatePicker, 100);
        }
    }
    
    document.addEventListener('DOMContentLoaded', initJalaliDatePicker);
    if (document.readyState !== 'loading') {
        initJalaliDatePicker();
    }
    
    // Reinitialize on Livewire updates
    document.addEventListener('livewire:init', () => {
        Livewire.hook('morph.updated', () => {
            setTimeout(initJalaliDatePicker, 200);
        });
    });
</script>
@endscript






