<div>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0" style="color: #ffffff;">
            <i class="bi bi-credit-card me-2" style="color: #ffd700;"></i>
            پرداخت‌های آگهی: {{ \Illuminate\Support\Str::limit($ad->title, 40) }}
        </h2>
        <a href="{{ route('admin.ads.index') }}" class="btn btn-modern" style="background: rgba(255,255,255,0.1);">
            <i class="bi bi-arrow-right me-2"></i> بازگشت
        </a>
    </div>

    <div class="glass-card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>کاربر</th>
                            <th>مبلغ</th>
                            <th>تعداد روز</th>
                            <th>وضعیت</th>
                            <th>تاریخ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payments as $payment)
                            <tr>
                                <td>{{ $payment->user->name }}</td>
                                <td class="fw-bold">{{ number_format($payment->amount) }} تومان</td>
                                <td class="text-muted">{{ $payment->days }} روز</td>
                                <td>
                                    <span class="badge" style="background: {{ $payment->status === 'paid' ? 'rgba(57, 255, 20, 0.2)' : ($payment->status === 'pending' ? 'rgba(255, 170, 0, 0.2)' : 'rgba(255, 0, 110, 0.2)') }}; color: {{ $payment->status === 'paid' ? '#39ff14' : ($payment->status === 'pending' ? '#ffaa00' : '#ff006e') }}; border: 1px solid {{ $payment->status === 'paid' ? 'rgba(57, 255, 20, 0.3)' : ($payment->status === 'pending' ? 'rgba(255, 170, 0, 0.3)' : 'rgba(255, 0, 110, 0.3)') }};">
                                        {{ $payment->status === 'paid' ? 'پرداخت شده' : ($payment->status === 'pending' ? 'در انتظار' : 'ناموفق') }}
                                    </span>
                                </td>
                                <td class="text-muted">{{ \App\Helpers\DateHelper::toPersianDateTime($payment->created_at) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <i class="bi bi-inbox" style="font-size: 3rem; opacity: 0.5;"></i>
                                    <p class="mt-3 mb-0">پرداختی وجود ندارد</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($payments->hasPages())
            <div class="mt-3 p-3" style="border-top: 1px solid rgba(255,255,255,0.1);">
                <div class="d-flex justify-content-center">
                    {{ $payments->links('pagination::bootstrap-5') }}
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
