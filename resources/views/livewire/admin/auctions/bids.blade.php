<div>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0" style="color: #ffffff;">
            <i class="bi bi-gavel me-2" style="color: #ff006e;"></i>
            پیشنهادهای مزایده: {{ \Illuminate\Support\Str::limit($ad->title, 40) }}
        </h2>
        <a href="{{ route('admin.auctions.index') }}" class="btn btn-modern" style="background: rgba(255,255,255,0.1);">
            <i class="bi bi-arrow-right me-2"></i> بازگشت
        </a>
    </div>

    <div class="glass-card mb-4 p-4">
        <div class="row g-3">
            <div class="col-md-3">
                <small class="text-muted d-block mb-1">قیمت پایه</small>
                <div class="fw-bold" style="color: #ffffff; font-size: 1.25rem;">{{ number_format($ad->base_price) }} تومان</div>
            </div>
            <div class="col-md-3">
                <small class="text-muted d-block mb-1">قیمت فعلی</small>
                <div class="fw-bold" style="color: #ffd700; font-size: 1.25rem;">{{ number_format($ad->current_bid ?? $ad->base_price) }} تومان</div>
            </div>
            <div class="col-md-3">
                <small class="text-muted d-block mb-1">تعداد پیشنهادها</small>
                <div class="fw-bold" style="color: #ffffff; font-size: 1.25rem;">{{ $bids->total() }}</div>
            </div>
            <div class="col-md-3">
                <small class="text-muted d-block mb-1">پایان مزایده</small>
                <div class="fw-bold" style="color: #ffffff; font-size: 1rem;">{{ $ad->auction_end_time ? \App\Helpers\DateHelper::toPersianDateTime($ad->auction_end_time) : '-' }}</div>
            </div>
        </div>
    </div>

    <div class="glass-card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>کاربر</th>
                            <th>مبلغ</th>
                            <th>تاریخ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bids as $bid)
                            <tr>
                                <td>{{ $bid->user->name }}</td>
                                <td class="fw-bold">{{ number_format($bid->amount) }} تومان</td>
                                <td class="text-muted">{{ \App\Helpers\DateHelper::toPersianDateTime($bid->created_at) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center py-5 text-muted">
                                    <i class="bi bi-inbox" style="font-size: 3rem; opacity: 0.5;"></i>
                                    <p class="mt-3 mb-0">پیشنهادی وجود ندارد</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($bids->hasPages())
            <div class="mt-3 p-3" style="border-top: 1px solid rgba(255,255,255,0.1);">
                <div class="d-flex justify-content-center">
                    {{ $bids->links('pagination::bootstrap-5') }}
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
