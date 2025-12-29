<div class="container-fluid px-3 px-md-5">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb" style="background: rgba(255,255,255,0.05); padding: 12px 20px; border-radius: 12px;">
            <li class="breadcrumb-item"><a href="{{ route('panel.ads.index') }}" style="color: #00f0ff; text-decoration: none;">آگهی‌های من</a></li>
            <li class="breadcrumb-item"><a href="{{ route('panel.ads.show', $ad) }}" style="color: #00f0ff; text-decoration: none;">جزئیات آگهی</a></li>
            <li class="breadcrumb-item active" style="color: #ffffff;">تمدید آگهی</li>
        </ol>
    </nav>

    <div class="mb-4">
        <h2 class="fw-bold mb-2" style="color: #ffffff;">
            <i class="bi bi-calendar-plus me-2" style="color: #b026ff;"></i>
            تمدید آگهی: {{ $ad->title }}
        </h2>
        <p class="text-muted mb-0">تمدید زمان نمایش آگهی خود را انتخاب کنید</p>
    </div>

    <div class="row g-4">
        <!-- Main Form -->
        <div class="col-lg-8">
            <div class="glass-card p-4 p-md-5">
                <h4 class="fw-bold mb-4" style="color: #ffffff;">
                    <i class="bi bi-info-circle me-2" style="color: #b026ff;"></i>
                    اطلاعات تمدید
                </h4>

                <!-- Days Input -->
                <div class="mb-4">
                    <label class="form-label mb-2 d-block fw-semibold" style="color: #ffffff; font-size: 15px;">
                        <i class="bi bi-calendar-range me-1" style="color: #b026ff;"></i> تعداد روزهای تمدید <span class="text-danger">*</span>
                    </label>
                    <input type="text" 
                           class="modern-input w-100" 
                           wire:model.live="days"
                           pattern="[0-9]*"
                           inputmode="numeric"
                           onkeypress="return event.charCode >= 48 && event.charCode <= 57"
                           placeholder="مثال: 30"
                           style="font-size: 1.1rem; font-weight: 600; color: #ffffff !important;">
                    <small class="text-muted d-block mt-2">
                        <i class="bi bi-info-circle me-1"></i>
                        حداقل 1 روز و حداکثر 365 روز
                    </small>
                </div>

                <!-- Price Details -->
                <div class="glass-card p-4 mb-4" style="background: rgba(176, 38, 255, 0.1); border-color: rgba(176, 38, 255, 0.3);">
                    <h5 class="fw-bold mb-3" style="color: #ffffff;">
                        <i class="bi bi-calculator me-2" style="color: #b026ff;"></i>
                        محاسبه مبلغ
                    </h5>
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span style="color: #e5e7eb; font-size: 14px;">قیمت روزانه:</span>
                                <span style="color: #ffffff; font-weight: 700; font-size: 1.1rem;">{{ number_format($dailyPrice) }} تومان</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span style="color: #e5e7eb; font-size: 14px;">تعداد روزها:</span>
                                <span style="color: #ffffff; font-weight: 700; font-size: 1.1rem;">{{ $days }} روز</span>
                            </div>
                        </div>
                        <div class="col-12">
                            <hr style="border-color: rgba(255,255,255,0.2); margin: 1rem 0;">
                            <div class="d-flex justify-content-between align-items-center">
                                <span style="color: #ffffff; font-size: 18px; font-weight: 700;">مبلغ قابل پرداخت:</span>
                                <span style="color: #b026ff; font-size: 24px; font-weight: 800; text-shadow: 0 0 15px rgba(176, 38, 255, 0.5);">{{ number_format($amount) }} تومان</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Info Alert -->
                <div class="alert alert-info glass-card p-4" style="background: rgba(0, 240, 255, 0.1); border-color: rgba(0, 240, 255, 0.3);">
                    <div class="d-flex align-items-start gap-3">
                        <i class="bi bi-info-circle" style="font-size: 1.5rem; color: #00f0ff; flex-shrink: 0;"></i>
                        <div>
                            <h6 class="fw-bold mb-2" style="color: #ffffff;">نکات مهم:</h6>
                            <ul style="color: #e5e7eb; margin: 0; padding-right: 1.5rem;">
                                <li>پس از پرداخت موفق، آگهی شما به مدت {{ $days }} روز دیگر تمدید خواهد شد.</li>
                                <li>در صورت وجود تاریخ انقضا، به تاریخ فعلی اضافه می‌شود.</li>
                                <li>می‌توانید در آینده مجدداً آگهی را تمدید کنید.</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="d-flex gap-3 justify-content-end mt-4">
                    <a href="{{ route('panel.ads.show', $ad) }}" class="btn btn-modern" style="background: rgba(255,255,255,0.1); box-shadow: none;">
                        <i class="bi bi-x-circle me-2"></i> انصراف
                    </a>
                    <button wire:click="pay" 
                            wire:loading.attr="disabled"
                            wire:target="pay"
                            class="btn btn-modern" 
                            style="background: linear-gradient(135deg, #b026ff, #ff006e); box-shadow: 0 4px 15px rgba(176, 38, 255, 0.4);">
                        <span wire:loading.remove wire:target="pay">
                            <i class="bi bi-credit-card me-2"></i> پرداخت و تمدید
                        </span>
                        <span wire:loading wire:target="pay">
                            <span class="spinner-border spinner-border-sm me-2" role="status"></span>
                            در حال انتقال به درگاه...
                        </span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Sidebar Info -->
        <div class="col-lg-4">
            <div class="glass-card p-4 sticky-top" style="top: 20px;">
                <h5 class="fw-bold mb-4" style="color: #ffffff;">
                    <i class="bi bi-file-earmark-text me-2" style="color: #00f0ff;"></i>
                    اطلاعات آگهی
                </h5>
                
                <div class="mb-3">
                    <small class="text-muted d-block mb-1">عنوان</small>
                    <div style="color: #ffffff; font-weight: 600;">{{ $ad->title }}</div>
                </div>

                <div class="mb-3">
                    <small class="text-muted d-block mb-1">نوع آگهی</small>
                    <span class="badge" style="background: {{ $ad->type === 'auction' ? 'rgba(255, 0, 110, 0.2)' : 'rgba(0, 240, 255, 0.2)' }}; color: {{ $ad->type === 'auction' ? '#ff006e' : '#00f0ff' }}; border: 1px solid {{ $ad->type === 'auction' ? 'rgba(255, 0, 110, 0.3)' : 'rgba(0, 240, 255, 0.3)' }}; padding: 6px 12px;">
                        {{ $ad->type === 'auction' ? 'مزایده' : 'فروش عادی' }}
                    </span>
                </div>

                <div class="mb-3">
                    <small class="text-muted d-block mb-1">وضعیت فعلی</small>
                    <span class="badge" style="background: {{ $ad->status === 'active' ? 'rgba(57, 255, 20, 0.2)' : ($ad->status === 'pending' ? 'rgba(255, 170, 0, 0.2)' : 'rgba(255, 0, 110, 0.2)') }}; color: {{ $ad->status === 'active' ? '#39ff14' : ($ad->status === 'pending' ? '#ffaa00' : '#ff006e') }}; border: 1px solid {{ $ad->status === 'active' ? 'rgba(57, 255, 20, 0.3)' : ($ad->status === 'pending' ? 'rgba(255, 170, 0, 0.3)' : 'rgba(255, 0, 110, 0.3)') }}; padding: 6px 12px;">
                        {{ $ad->status === 'active' ? 'فعال' : ($ad->status === 'pending' ? 'در انتظار' : 'غیرفعال') }}
                    </span>
                </div>

                @if($ad->expire_at && $ad->expire_at->isFuture())
                <div class="mb-3">
                    <small class="text-muted d-block mb-1">تاریخ انقضای فعلی</small>
                    <div style="color: #ffffff; font-weight: 600;">
                        {{ $ad->expire_at->format('Y/m/d H:i') }}
                    </div>
                    <small class="text-muted d-block mt-1">بعد از تمدید: {{ $newExpireAt->format('Y/m/d H:i') }}</small>
                </div>
                @else
                <div class="mb-3">
                    <small class="text-muted d-block mb-1">تاریخ انقضا</small>
                    <div style="color: #e5e7eb;">تعیین نشده / منقضی شده</div>
                    <small class="text-muted d-block mt-1">بعد از پرداخت: {{ $newExpireAt->format('Y/m/d H:i') }}</small>
                </div>
                @endif

                <div class="mt-4 pt-3" style="border-top: 1px solid rgba(255,255,255,0.1);">
                    <a href="{{ route('panel.ads.show', $ad) }}" class="btn btn-modern btn-sm w-100" style="background: rgba(0, 240, 255, 0.1); box-shadow: none;">
                        <i class="bi bi-arrow-left me-2"></i> بازگشت به جزئیات
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
