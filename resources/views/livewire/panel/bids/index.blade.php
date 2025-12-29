<div>
    <h2 class="mb-4 fw-bold" style="color: #ffffff;">
        <i class="bi bi-gavel me-2" style="color: #ff006e;"></i>
        پیشنهادهای من
    </h2>

    <div class="glass-card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped mb-0">
                    <thead>
                        <tr>
                            <th>آگهی</th>
                            <th>مبلغ پیشنهادی</th>
                            <th>تاریخ</th>
                            <th>وضعیت</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bids as $bid)
                            <tr>
                                <td>{{ \Illuminate\Support\Str::limit($bid->ad->title, 40) }}</td>
                                <td class="fw-bold">{{ number_format($bid->amount) }} تومان</td>
                                <td class="text-muted">{{ $bid->created_at->format('Y/m/d H:i') }}</td>
                                <td>
                                    @if($bid->ad->current_bid == $bid->amount)
                                        <span class="badge" style="background: rgba(57, 255, 20, 0.2); color: #39ff14; border: 1px solid rgba(57, 255, 20, 0.3);">
                                            <i class="bi bi-check-circle me-1"></i> پیشنهاد برتر
                                        </span>
                                    @else
                                        <span class="badge" style="background: rgba(255, 170, 0, 0.2); color: #ffaa00; border: 1px solid rgba(255, 170, 0, 0.3);">
                                            غیرفعال
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-5 text-muted">
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



