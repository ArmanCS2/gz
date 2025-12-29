<div>
    <h2 class="mb-4 fw-bold" style="color: #ffffff;">
        <i class="bi bi-speedometer2 me-2" style="color: #00f0ff;"></i>
        داشبورد مدیریت
    </h2>

    <!-- Stats Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-3 col-6">
            <div class="glass-card p-4 text-center" style="border: 1px solid rgba(0, 240, 255, 0.2);">
                <div class="mb-3">
                    <i class="bi bi-people" style="font-size: 2.5rem; color: #00f0ff;"></i>
                </div>
                <h5 class="mb-2" style="color: #9ca3af; font-size: 0.9rem;">کاربران</h5>
                <h2 class="mb-0 fw-bold" style="color: #ffffff; font-size: 2rem;">{{ number_format($stats['users_count']) }}</h2>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="glass-card p-4 text-center" style="border: 1px solid rgba(57, 255, 20, 0.2);">
                <div class="mb-3">
                    <i class="bi bi-check-circle" style="font-size: 2.5rem; color: #39ff14;"></i>
                </div>
                <h5 class="mb-2" style="color: #9ca3af; font-size: 0.9rem;">آگهی‌های فعال</h5>
                <h2 class="mb-0 fw-bold" style="color: #ffffff; font-size: 2rem;">{{ number_format($stats['active_ads']) }}</h2>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="glass-card p-4 text-center" style="border: 1px solid rgba(255, 170, 0, 0.2);">
                <div class="mb-3">
                    <i class="bi bi-clock-history" style="font-size: 2.5rem; color: #ffaa00;"></i>
                </div>
                <h5 class="mb-2" style="color: #9ca3af; font-size: 0.9rem;">در انتظار تایید</h5>
                <h2 class="mb-0 fw-bold" style="color: #ffffff; font-size: 2rem;">{{ number_format($stats['pending_ads']) }}</h2>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="glass-card p-4 text-center" style="border: 1px solid rgba(255, 215, 0, 0.2);">
                <div class="mb-3">
                    <i class="bi bi-cash-stack" style="font-size: 2.5rem; color: #ffd700;"></i>
                </div>
                <h5 class="mb-2" style="color: #9ca3af; font-size: 0.9rem;">مجموع پرداخت‌ها</h5>
                <h2 class="mb-0 fw-bold" style="color: #ffffff; font-size: 1.5rem;">{{ number_format($stats['total_payments']) }} <small style="font-size: 0.7em;">تومان</small></h2>
            </div>
        </div>
    </div>

    <!-- Additional Stats -->
    <div class="row g-4 mb-4">
        <div class="col-md-6">
            <div class="glass-card p-4 text-center" style="border: 1px solid rgba(176, 38, 255, 0.2);">
                <div class="mb-3">
                    <i class="bi bi-card-list" style="font-size: 2.5rem; color: #b026ff;"></i>
                </div>
                <h5 class="mb-2" style="color: #9ca3af; font-size: 0.9rem;">کل آگهی‌ها</h5>
                <h2 class="mb-0 fw-bold" style="color: #ffffff; font-size: 2rem;">{{ number_format($stats['ads_count']) }}</h2>
            </div>
        </div>
        <div class="col-md-6">
            <div class="glass-card p-4 text-center" style="border: 1px solid rgba(255, 0, 110, 0.2);">
                <div class="mb-3">
                    <i class="bi bi-gavel" style="font-size: 2.5rem; color: #ff006e;"></i>
                </div>
                <h5 class="mb-2" style="color: #9ca3af; font-size: 0.9rem;">کل پیشنهادها</h5>
                <h2 class="mb-0 fw-bold" style="color: #ffffff; font-size: 2rem;">{{ number_format($stats['bids_count']) }}</h2>
            </div>
        </div>
    </div>

    <!-- Recent Data Tables -->
    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="glass-card">
                <div class="p-4 border-bottom" style="border-color: rgba(255,255,255,0.1);">
                    <h5 class="mb-0 fw-bold" style="color: #ffffff;">
                        <i class="bi bi-clock-history me-2" style="color: #00f0ff;"></i>
                        آگهی‌های اخیر
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>عنوان</th>
                                    <th>وضعیت</th>
                                    <th>عملیات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recent_ads as $ad)
                                    <tr>
                                        <td>{{ \Illuminate\Support\Str::limit($ad->title, 30) }}</td>
                                        <td>
                                            <span class="badge" style="background: {{ $ad->status === 'active' ? 'rgba(57, 255, 20, 0.2)' : 'rgba(255, 170, 0, 0.2)' }}; color: {{ $ad->status === 'active' ? '#39ff14' : '#ffaa00' }}; border: 1px solid {{ $ad->status === 'active' ? 'rgba(57, 255, 20, 0.3)' : 'rgba(255, 170, 0, 0.3)' }};">
                                                {{ $ad->status === 'active' ? 'فعال' : 'در انتظار' }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.ads.edit', $ad) }}" class="btn btn-modern btn-sm" style="background: rgba(0, 240, 255, 0.2); box-shadow: none; padding: 4px 12px;">
                                                <i class="bi bi-pencil"></i> ویرایش
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="glass-card">
                <div class="p-4 border-bottom" style="border-color: rgba(255,255,255,0.1);">
                    <h5 class="mb-0 fw-bold" style="color: #ffffff;">
                        <i class="bi bi-credit-card me-2" style="color: #ffd700;"></i>
                        پرداخت‌های اخیر
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>مبلغ</th>
                                    <th>کاربر</th>
                                    <th>تاریخ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recent_payments as $payment)
                                    <tr>
                                        <td class="fw-bold">{{ number_format($payment->amount) }} تومان</td>
                                        <td>{{ $payment->user->name }}</td>
                                        <td class="text-muted">{{ $payment->created_at->format('Y/m/d') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


