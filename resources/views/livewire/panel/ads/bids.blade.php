<div class="container-fluid px-3 px-md-5" wire:poll.5s>
    <!-- Header -->
    <div class="mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="fw-bold mb-2" style="color: #ffffff;">
                    <i class="bi bi-gavel me-2" style="color: #ff006e;"></i>
                    پیشنهادهای مزایده
                </h2>
                <p class="text-muted mb-0">آگهی: <strong style="color: #e5e7eb;">{{ $ad->title }}</strong></p>
            </div>
            <a href="{{ route('panel.ads.show', $ad) }}" class="btn btn-modern btn-sm">
                <i class="bi bi-arrow-right me-2"></i> بازگشت به آگهی
            </a>
        </div>
    </div>

    <!-- Auction Info -->
    <div class="glass-card p-4 mb-4">
        <div class="row g-4">
            <div class="col-md-3">
                <div class="text-center">
                    <small class="text-muted d-block mb-2">قیمت پایه</small>
                    <div class="ad-card-price" style="font-size: 1.25rem;">
                        {{ number_format($ad->base_price ?? 0) }} <small>تومان</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="text-center">
                    <small class="text-muted d-block mb-2">قیمت فعلی</small>
                    <div class="ad-card-price" style="font-size: 1.25rem;">
                        {{ number_format($ad->current_bid ?? $ad->base_price ?? 0) }} <small>تومان</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="text-center">
                    <small class="text-muted d-block mb-2">تعداد پیشنهاد</small>
                    <div class="ad-card-bid" style="font-size: 1.5rem;">
                        {{ $bids->total() }}
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="text-center">
                    <small class="text-muted d-block mb-2">پایان مزایده</small>
                    <div style="color: #ff006e; font-weight: 600;">
                        {{ $ad->auction_end_time ? \App\Helpers\DateHelper::diffForHumans($ad->auction_end_time) : '-' }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bids List -->
    <div class="glass-card p-4">
        <h4 class="fw-bold mb-4" style="color: #ffffff;">
            <i class="bi bi-list-ul me-2" style="color: #00f0ff;"></i>
            لیست پیشنهادها
        </h4>

        @if($bids->count() > 0)
            <div class="table-responsive">
                <table class="table table-borderless" style="color: #ffffff;">
                    <thead>
                        <tr style="border-bottom: 1px solid rgba(255,255,255,0.1);">
                            <th style="color: #e5e7eb; font-weight: 600;">رتبه</th>
                            <th style="color: #e5e7eb; font-weight: 600;">کاربر</th>
                            <th style="color: #e5e7eb; font-weight: 600;">مبلغ پیشنهادی</th>
                            <th style="color: #e5e7eb; font-weight: 600;">زمان پیشنهاد</th>
                            <th style="color: #e5e7eb; font-weight: 600;">اطلاعات تماس</th>
                            <th style="color: #e5e7eb; font-weight: 600;">عملیات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($bids as $index => $bid)
                            <tr style="border-bottom: 1px solid rgba(255,255,255,0.05);" 
                                class="{{ $ad->current_bid == $bid->amount ? 'bg-success bg-opacity-10' : '' }}">
                                <td>
                                    <span class="badge" style="background: linear-gradient(135deg, #00f0ff, #b026ff); padding: 6px 12px;">
                                        #{{ $bids->firstItem() + $index }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="bi bi-person-circle" style="font-size: 1.5rem; color: #00f0ff;"></i>
                                        <div>
                                            <div style="color: #ffffff; font-weight: 600;">{{ $bid->user->name }}</div>
                                            <small class="text-muted">{{ $bid->user->mobile }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="ad-card-price" style="font-size: 1.1rem;">
                                        {{ number_format($bid->amount) }} <small>تومان</small>
                                    </div>
                                    @if($ad->current_bid == $bid->amount)
                                        <span class="badge" style="background: rgba(57, 255, 20, 0.2); color: #39ff14; border: 1px solid rgba(57, 255, 20, 0.3); margin-top: 4px;">
                                            <i class="bi bi-check-circle me-1"></i> پیشنهاد برتر
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <div style="color: #e5e7eb;">
                                        <div>{{ \App\Helpers\DateHelper::toPersianDate($bid->created_at) }}</div>
                                        <small class="text-muted">{{ \App\Helpers\DateHelper::toPersian($bid->created_at, 'H:i') }}</small>
                                    </div>
                                </td>
                                <td>
                                    @if($ad->show_contact || $bid->user->show_contact ?? false)
                                        <div style="color: #e5e7eb;">
                                            <small>{{ $bid->user->mobile }}</small>
                                        </div>
                                    @else
                                        <small class="text-muted">مخفی</small>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        @if($ad->current_bid != $bid->amount)
                                            <button type="button" 
                                                    class="btn btn-sm btn-modern" 
                                                    style="background: rgba(57, 255, 20, 0.2); color: #39ff14; border: 1px solid rgba(57, 255, 20, 0.3); padding: 6px 12px;"
                                                    wire:click="acceptBid({{ $bid->id }})"
                                                    wire:confirm="آیا می‌خواهید این پیشنهاد را بپذیرید؟">
                                                <i class="bi bi-check-circle me-1"></i> پذیرش
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $bids->links('pagination::bootstrap-5') }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="bi bi-inbox" style="font-size: 4rem; color: rgba(255,255,255,0.2);"></i>
                <p class="mt-3" style="color: #e5e7eb;">هنوز پیشنهادی ثبت نشده است</p>
            </div>
        @endif
    </div>
</div>
