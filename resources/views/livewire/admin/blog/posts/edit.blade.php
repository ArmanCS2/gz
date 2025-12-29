<div>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0" style="color: #ffffff;">
            <i class="bi bi-pencil me-2" style="color: #00f0ff;"></i>
            ویرایش مقاله
        </h2>
        <a href="{{ route('admin.blog.posts.index') }}" class="btn btn-modern btn-sm" style="background: rgba(255,255,255,0.1); box-shadow: none;">
            <i class="bi bi-arrow-right me-1"></i> بازگشت
        </a>
    </div>

    <form wire:submit.prevent="update">
        <div class="row">
            <div class="col-lg-8">
                <div class="glass-card p-4 mb-4">
                    <div class="mb-3">
                        <label class="form-label text-white">عنوان مقاله *</label>
                        <input type="text" wire:model="title" class="form-control modern-input" required>
                        @error('title') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-white">Slug (آدرس URL) *</label>
                        <input type="text" wire:model="slug" class="form-control modern-input" required>
                        @error('slug') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-white">خلاصه مقاله</label>
                        <textarea wire:model="excerpt" class="form-control modern-input" rows="3"></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-white">محتوای مقاله *</label>
                        <textarea wire:model="content" class="form-control modern-input" rows="15" required></textarea>
                        @error('content') <span class="text-danger small">{{ $message }}</span> @enderror
                        <small class="text-white-50">می‌توانید از HTML استفاده کنید</small>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="glass-card p-4 mb-4">
                    <h5 class="text-white mb-4">تنظیمات</h5>

                    <div class="mb-3">
                        <label class="form-label text-white">دسته‌بندی</label>
                        <select wire:model="category_id" class="form-select modern-input">
                            <option value="">بدون دسته‌بندی</option>
                            @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-white">وضعیت</label>
                        <select wire:model="status" class="form-select modern-input">
                            <option value="draft">پیش‌نویس</option>
                            <option value="published">منتشر شده</option>
                            <option value="scheduled">زمان‌بندی شده</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-white">تاریخ انتشار</label>
                        <div class="row g-2">
                            <div class="col-8">
                                <input type="text" data-jdp class="form-control modern-input" wire:model.blur="published_date" placeholder="انتخاب تاریخ شمسی">
                            </div>
                            <div class="col-4">
                                <input type="time" class="form-control modern-input" wire:model="published_time" placeholder="زمان">
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
                        <label class="form-label text-white">برچسب‌ها</label>
                        <select wire:model="selected_tags" class="form-select modern-input" multiple size="5">
                            @foreach($tags as $tag)
                            <option value="{{ $tag->id }}" {{ in_array($tag->id, $selected_tags) ? 'selected' : '' }}>{{ $tag->name }}</option>
                            @endforeach
                        </select>
                        <small class="text-white-50">برای انتخاب چندتایی، Ctrl را نگه دارید</small>
                    </div>
                </div>

                <div class="glass-card p-4 mb-4">
                    <h5 class="text-white mb-4">تصویر شاخص</h5>
                    @if($banner_image_preview && !$banner_image)
                    <img src="{{ $banner_image_preview }}" alt="Current" class="w-100 rounded mb-3" style="max-height: 200px; object-fit: cover;">
                    @endif
                    <input type="file" wire:model="banner_image" class="form-control modern-input mb-3" accept="image/*">
                    @if($banner_image)
                    <img src="{{ $banner_image->temporaryUrl() }}" alt="Preview" class="w-100 rounded" style="max-height: 200px; object-fit: cover;">
                    @endif
                </div>

                <div class="glass-card p-4">
                    <h5 class="text-white mb-4">SEO</h5>
                    <div class="mb-3">
                        <label class="form-label text-white">عنوان SEO</label>
                        <input type="text" wire:model="seo_title" class="form-control modern-input">
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-white">توضیحات SEO</label>
                        <textarea wire:model="seo_description" class="form-control modern-input" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-white">کلمات کلیدی SEO</label>
                        <input type="text" wire:model="seo_keywords" class="form-control modern-input" placeholder="کلمه1, کلمه2, کلمه3">
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex gap-2 justify-content-end">
            <a href="{{ route('admin.blog.posts.index') }}" class="btn btn-modern btn-sm" style="background: rgba(255,255,255,0.1); box-shadow: none;">
                انصراف
            </a>
            <button type="submit" class="btn btn-modern">
                <i class="bi bi-check-circle me-2"></i> به‌روزرسانی مقاله
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






