<div>
    <h2 class="mb-4 fw-bold" style="color: #ffffff;">
        <i class="bi bi-speedometer2 me-2" style="color: #00f0ff;"></i>
        داشبورد
    </h2>

    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <a href="{{ route('panel.ads.index') }}" class="text-decoration-none">
                <div class="glass-card p-4" style="background: rgba(0, 240, 255, 0.1); border-color: rgba(0, 240, 255, 0.3); cursor: pointer; transition: all 0.3s;" onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 8px 25px rgba(0, 240, 255, 0.3)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <i class="bi bi-card-list" style="font-size: 2rem; color: #00f0ff;"></i>
                    </div>
                    <h5 class="mb-2" style="color: #e5e7eb; font-size: 0.9rem; font-weight: 500;">آگهی‌های من</h5>
                    <h2 class="mb-0 fw-bold" style="color: #ffffff; font-size: 2rem;">{{ $stats['ads_count'] }}</h2>
                    <small class="text-muted d-block mt-2" style="color: rgba(255,255,255,0.5);">کلیک برای مشاهده</small>
                </div>
            </a>
        </div>
        <div class="col-md-3 mb-3">
            <a href="{{ route('panel.ads.index') }}" class="text-decoration-none">
                <div class="glass-card p-4" style="background: rgba(57, 255, 20, 0.1); border-color: rgba(57, 255, 20, 0.3); cursor: pointer; transition: all 0.3s;" onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 8px 25px rgba(57, 255, 20, 0.3)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <i class="bi bi-check-circle" style="font-size: 2rem; color: #39ff14;"></i>
                    </div>
                    <h5 class="mb-2" style="color: #e5e7eb; font-size: 0.9rem; font-weight: 500;">آگهی‌های فعال</h5>
                    <h2 class="mb-0 fw-bold" style="color: #ffffff; font-size: 2rem;">{{ $stats['active_ads'] }}</h2>
                    <small class="text-muted d-block mt-2" style="color: rgba(255,255,255,0.5);">کلیک برای مشاهده</small>
                </div>
            </a>
        </div>
        <div class="col-md-3 mb-3">
            <a href="{{ route('panel.payments.index') }}" class="text-decoration-none">
                <div class="glass-card p-4" style="background: rgba(176, 38, 255, 0.1); border-color: rgba(176, 38, 255, 0.3); cursor: pointer; transition: all 0.3s;" onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 8px 25px rgba(176, 38, 255, 0.3)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <i class="bi bi-credit-card" style="font-size: 2rem; color: #b026ff;"></i>
                    </div>
                    <h5 class="mb-2" style="color: #e5e7eb; font-size: 0.9rem; font-weight: 500;">پرداخت‌ها</h5>
                    <h2 class="mb-0 fw-bold" style="color: #ffffff; font-size: 1.5rem;">{{ number_format($stats['total_payments']) }} <small style="font-size: 0.7rem;">تومان</small></h2>
                    <small class="text-muted d-block mt-2" style="color: rgba(255,255,255,0.5);">کلیک برای مشاهده</small>
                </div>
            </a>
        </div>
        <div class="col-md-3 mb-3">
            <a href="{{ route('panel.bids.index') }}" class="text-decoration-none">
                <div class="glass-card p-4" style="background: rgba(255, 0, 110, 0.1); border-color: rgba(255, 0, 110, 0.3); cursor: pointer; transition: all 0.3s;" onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 8px 25px rgba(255, 0, 110, 0.3)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <i class="bi bi-gavel" style="font-size: 2rem; color: #ff006e;"></i>
                    </div>
                    <h5 class="mb-2" style="color: #e5e7eb; font-size: 0.9rem; font-weight: 500;">پیشنهادهای من</h5>
                    <h2 class="mb-0 fw-bold" style="color: #ffffff; font-size: 2rem;">{{ $stats['bids_count'] }}</h2>
                    <small class="text-muted d-block mt-2" style="color: rgba(255,255,255,0.5);">کلیک برای مشاهده</small>
                </div>
            </a>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <a href="{{ route('panel.ads.create') }}" class="text-decoration-none">
                <div class="glass-card p-4 text-center" style="background: rgba(0, 240, 255, 0.1); border-color: rgba(0, 240, 255, 0.3); cursor: pointer; transition: all 0.3s;" onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 8px 25px rgba(0, 240, 255, 0.3)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                    <i class="bi bi-plus-circle" style="font-size: 3rem; color: #00f0ff; margin-bottom: 1rem;"></i>
                    <h5 class="mb-0 fw-bold" style="color: #ffffff;">ایجاد آگهی جدید</h5>
                </div>
            </a>
        </div>
        <div class="col-md-4 mb-3">
            <a href="{{ route('panel.profile') }}" class="text-decoration-none">
                <div class="glass-card p-4 text-center" style="background: rgba(176, 38, 255, 0.1); border-color: rgba(176, 38, 255, 0.3); cursor: pointer; transition: all 0.3s;" onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 8px 25px rgba(176, 38, 255, 0.3)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                    <i class="bi bi-person-circle" style="font-size: 3rem; color: #b026ff; margin-bottom: 1rem;"></i>
                    <h5 class="mb-0 fw-bold" style="color: #ffffff;">پروفایل</h5>
                </div>
            </a>
        </div>
        <div class="col-md-4 mb-3">
            <a href="{{ route('panel.payments.index') }}" class="text-decoration-none">
                <div class="glass-card p-4 text-center" style="background: rgba(57, 255, 20, 0.1); border-color: rgba(57, 255, 20, 0.3); cursor: pointer; transition: all 0.3s;" onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 8px 25px rgba(57, 255, 20, 0.3)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                    <i class="bi bi-wallet2" style="font-size: 3rem; color: #39ff14; margin-bottom: 1rem;"></i>
                    <h5 class="mb-0 fw-bold" style="color: #ffffff;">مدیریت پرداخت‌ها</h5>
                </div>
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="glass-card">
                <div class="card-header">
                    <h5 class="mb-0 fw-bold" style="color: #ffffff;">
                        <i class="bi bi-clock-history me-2" style="color: #00f0ff;"></i>
                        آگهی‌های اخیر
                    </h5>
                </div>
                <div class="card-body">
                    @forelse($recent_ads as $ad)
                        <div class="d-flex justify-content-between align-items-center mb-3 pb-3" style="border-bottom: 1px solid rgba(255,255,255,0.1);">
                            <div>
                                <strong style="color: #ffffff;">{{ \Illuminate\Support\Str::limit($ad->title, 40) }}</strong>
                                <br>
                                <small class="text-muted">
                                    <span class="badge" style="background: {{ $ad->status === 'active' ? 'rgba(57, 255, 20, 0.2)' : ($ad->status === 'pending' ? 'rgba(255, 170, 0, 0.2)' : 'rgba(255, 0, 110, 0.2)') }}; color: {{ $ad->status === 'active' ? '#39ff14' : ($ad->status === 'pending' ? '#ffaa00' : '#ff006e') }}; border: 1px solid {{ $ad->status === 'active' ? 'rgba(57, 255, 20, 0.3)' : ($ad->status === 'pending' ? 'rgba(255, 170, 0, 0.3)' : 'rgba(255, 0, 110, 0.3)') }};">
                                        {{ $ad->status === 'active' ? 'فعال' : ($ad->status === 'pending' ? 'در انتظار' : 'غیرفعال') }}
                                    </span>
                                </small>
                            </div>
                            <a href="{{ route('panel.ads.show', $ad) }}" class="btn btn-modern btn-sm">
                                <i class="bi bi-eye me-1"></i> مشاهده
                            </a>
                        </div>
                    @empty
                        <p class="text-muted text-center py-4">آگهی‌ای وجود ندارد</p>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="glass-card">
                <div class="card-header">
                    <h5 class="mb-0 fw-bold" style="color: #ffffff;">
                        <i class="bi bi-credit-card me-2" style="color: #b026ff;"></i>
                        پرداخت‌های اخیر
                    </h5>
                </div>
                <div class="card-body">
                    @forelse($recent_payments as $payment)
                        <div class="d-flex justify-content-between align-items-center mb-3 pb-3" style="border-bottom: 1px solid rgba(255,255,255,0.1);">
                            <div>
                                <strong style="color: #ffffff; font-size: 1.1rem;">{{ number_format($payment->amount) }} تومان</strong>
                                <br>
                                <small class="text-muted">{{ \App\Helpers\DateHelper::diffForHumans($payment->created_at) }}</small>
                            </div>
                            <span class="badge" style="background: {{ $payment->status === 'paid' ? 'rgba(57, 255, 20, 0.2)' : 'rgba(255, 170, 0, 0.2)' }}; color: {{ $payment->status === 'paid' ? '#39ff14' : '#ffaa00' }}; border: 1px solid {{ $payment->status === 'paid' ? 'rgba(57, 255, 20, 0.3)' : 'rgba(255, 170, 0, 0.3)' }}; padding: 6px 12px;">
                                {{ $payment->status === 'paid' ? 'پرداخت شده' : 'در انتظار' }}
                            </span>
                        </div>
                    @empty
                        <p class="text-muted text-center py-4">پرداختی وجود ندارد</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>



