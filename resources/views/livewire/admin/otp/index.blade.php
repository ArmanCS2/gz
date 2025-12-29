<div>
    <h2 class="mb-4 fw-bold" style="color: #ffffff;">
        <i class="bi bi-shield-lock me-2" style="color: #00f0ff;"></i>
        لاگ OTP
    </h2>

    <div class="glass-card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>موبایل</th>
                            <th>کد</th>
                            <th>استفاده شده</th>
                            <th>IP</th>
                            <th>تاریخ انقضا</th>
                            <th>تاریخ ایجاد</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $log)
                            <tr>
                                <td>{{ $log->mobile }}</td>
                                <td class="text-muted" style="font-family: monospace; font-weight: 600;">{{ $log->code }}</td>
                                <td>
                                    <span class="badge" style="background: {{ $log->is_used ? 'rgba(57, 255, 20, 0.2)' : 'rgba(255, 170, 0, 0.2)' }}; color: {{ $log->is_used ? '#39ff14' : '#ffaa00' }}; border: 1px solid {{ $log->is_used ? 'rgba(57, 255, 20, 0.3)' : 'rgba(255, 170, 0, 0.3)' }};">
                                        {{ $log->is_used ? 'بله' : 'خیر' }}
                                    </span>
                                </td>
                                <td class="text-muted" style="font-family: monospace;">{{ $log->ip }}</td>
                                <td class="text-muted">{{ $log->expires_at->format('Y/m/d H:i') }}</td>
                                <td class="text-muted">{{ $log->created_at->format('Y/m/d H:i') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="bi bi-inbox" style="font-size: 3rem; opacity: 0.5;"></i>
                                    <p class="mt-3 mb-0">لاگی وجود ندارد</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($logs->hasPages())
            <div class="mt-3 p-3" style="border-top: 1px solid rgba(255,255,255,0.1);">
                <div class="d-flex justify-content-center">
                    {{ $logs->links('pagination::bootstrap-5') }}
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
