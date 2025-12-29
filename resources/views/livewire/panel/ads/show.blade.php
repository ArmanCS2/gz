<div class="container-fluid px-3 px-md-5">
    <h2 class="mb-4 fw-bold" style="color: #ffffff;">{{ $ad->title }}</h2>

    <div class="glass-card p-4 mb-4">
        <div class="row g-3">
            <div class="col-md-4">
                <div>
                    <small class="text-muted d-block mb-1">وضعیت</small>
                    <span class="badge" style="background: {{ $ad->status === 'active' ? 'rgba(57, 255, 20, 0.2)' : ($ad->status === 'pending' ? 'rgba(255, 170, 0, 0.2)' : 'rgba(255, 0, 110, 0.2)') }}; color: {{ $ad->status === 'active' ? '#39ff14' : ($ad->status === 'pending' ? '#ffaa00' : '#ff006e') }}; border: 1px solid {{ $ad->status === 'active' ? 'rgba(57, 255, 20, 0.3)' : ($ad->status === 'pending' ? 'rgba(255, 170, 0, 0.3)' : 'rgba(255, 0, 110, 0.3)') }}; padding: 6px 16px;">
                        {{ $ad->status === 'active' ? 'فعال' : ($ad->status === 'pending' ? 'در انتظار' : 'غیرفعال') }}
                    </span>
                </div>
            </div>
            <div class="col-md-4">
                <div>
                    <small class="text-muted d-block mb-1">نوع آگهی</small>
                    <div style="color: #ffffff; font-weight: 600;">
                        {{ $ad->type === 'auction' ? 'مزایده' : 'فروش عادی' }}
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div>
                    <small class="text-muted d-block mb-1">تاریخ انقضا</small>
                    <div style="color: #e5e7eb;">
                        {{ $ad->expire_at ? \App\Helpers\DateHelper::toPersianDateTime($ad->expire_at) : '-' }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="glass-card p-4 mb-4">
        <h5 class="fw-bold mb-3" style="color: #ffffff;">
            <i class="bi bi-file-text me-2" style="color: #00f0ff;"></i>
            توضیحات
        </h5>
        <div style="color: #e5e7eb; line-height: 1.8;">{{ $ad->description }}</div>
    </div>

    @if($ad->type === 'auction')
        <div class="glass-card p-4 mb-3" style="background: rgba(255, 0, 110, 0.1); border-color: rgba(255, 0, 110, 0.3);">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="fw-bold mb-2" style="color: #ffffff;">
                        <i class="bi bi-gavel me-2" style="color: #ff006e;"></i>
                        مدیریت مزایده
                    </h5>
                    <p class="mb-0" style="color: #e5e7eb;">
                        تعداد پیشنهادها: <strong style="color: #ff006e;">{{ $ad->bids->count() }}</strong>
                        @if($ad->current_bid)
                            | قیمت فعلی: <strong style="color: #00f0ff;">{{ number_format($ad->current_bid) }} تومان</strong>
                        @endif
                    </p>
                </div>
                <a href="{{ route('panel.ads.bids', $ad) }}" class="btn btn-modern" style="background: linear-gradient(135deg, #ff006e, #b026ff);">
                    <i class="bi bi-list-ul me-2"></i> مشاهده پیشنهادها
                </a>
            </div>
        </div>
    @endif

    <div class="d-flex gap-2">
        <a href="{{ route('panel.ads.edit', $ad) }}" class="btn btn-modern btn-sm" style="background: rgba(255,255,255,0.1); box-shadow: none;">
            <i class="bi bi-pencil me-2"></i> ویرایش
        </a>
        <a href="{{ route('panel.ads.extend', $ad) }}" class="btn btn-modern btn-sm">
            <i class="bi bi-calendar-plus me-2"></i> تمدید
        </a>
        <a href="{{ route('panel.ads.index') }}" class="btn btn-modern btn-sm" style="background: rgba(255,255,255,0.1); box-shadow: none;">
            <i class="bi bi-arrow-right me-2"></i> بازگشت
        </a>
    </div>
</div>



