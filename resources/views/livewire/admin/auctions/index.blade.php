<div>
    <h2 class="mb-4 fw-bold" style="color: #ffffff;">
        <i class="bi bi-hammer me-2" style="color: #ff006e;"></i>
        مدیریت مزایده‌ها
    </h2>

    <div class="glass-card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>عنوان</th>
                            <th>کاربر</th>
                            <th>قیمت فعلی</th>
                            <th>پایان مزایده</th>
                            <th>وضعیت</th>
                            <th>عملیات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($auctions as $auction)
                            <tr>
                                <td>{{ \Illuminate\Support\Str::limit($auction->title, 30) }}</td>
                                <td class="text-muted">{{ $auction->user->name }}</td>
                                <td class="fw-bold">{{ number_format($auction->current_bid ?? $auction->base_price) }} تومان</td>
                                <td class="text-muted">{{ $auction->auction_end_time ? \App\Helpers\DateHelper::toPersianDateTime($auction->auction_end_time) : '-' }}</td>
                                <td>
                                    <span class="badge" style="background: {{ $auction->status === 'active' ? 'rgba(57, 255, 20, 0.2)' : 'rgba(255, 0, 110, 0.2)' }}; color: {{ $auction->status === 'active' ? '#39ff14' : '#ff006e' }}; border: 1px solid {{ $auction->status === 'active' ? 'rgba(57, 255, 20, 0.3)' : 'rgba(255, 0, 110, 0.3)' }};">
                                        {{ $auction->status === 'active' ? 'فعال' : 'بسته شده' }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.auctions.bids', $auction) }}" class="btn btn-modern btn-sm" style="background: rgba(0, 240, 255, 0.2); box-shadow: none; padding: 4px 12px;">
                                        <i class="bi bi-eye"></i> مشاهده پیشنهادها
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="bi bi-inbox" style="font-size: 3rem; opacity: 0.5;"></i>
                                    <p class="mt-3 mb-0">مزایده‌ای وجود ندارد</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($auctions->hasPages())
            <div class="mt-3 p-3" style="border-top: 1px solid rgba(255,255,255,0.1);">
                <div class="d-flex justify-content-center">
                    {{ $auctions->links('pagination::bootstrap-5') }}
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
