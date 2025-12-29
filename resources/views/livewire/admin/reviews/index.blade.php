<div>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0" style="color: #ffffff;">
            <i class="bi bi-chat-left-text me-2" style="color: #00f0ff;"></i>
            مدیریت نظرات و امتیازها
        </h2>
    </div>

    <!-- فیلترها -->
    <div class="glass-card p-4 mb-4">
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label mb-2 d-block fw-semibold" style="color: #ffffff; font-size: 14px;">
                    <i class="bi bi-funnel me-1" style="color: #00f0ff;"></i> فیلتر وضعیت
                </label>
                <select class="modern-input w-100" wire:model.live="statusFilter" style="color: #ffffff !important;">
                    <option value="all">همه</option>
                    <option value="pending">در انتظار تایید</option>
                    <option value="approved">تایید شده</option>
                    <option value="rejected">رد شده</option>
                </select>
            </div>
            <div class="col-md-8">
                <label class="form-label mb-2 d-block fw-semibold" style="color: #ffffff; font-size: 14px;">
                    <i class="bi bi-search me-1" style="color: #00f0ff;"></i> جستجو
                </label>
                <input type="text" 
                       class="modern-input w-100" 
                       placeholder="جستجو در نام کاربر، عنوان آگهی یا متن نظر..."
                       wire:model.debounce.500ms="searchQuery"
                       style="color: #ffffff !important;">
            </div>
        </div>
    </div>

    <!-- جدول نظرات -->
    <div class="glass-card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>کاربر</th>
                            <th>آگهی</th>
                            <th>امتیاز</th>
                            <th>نظر</th>
                            <th>وضعیت</th>
                            <th>تاریخ</th>
                            <th>عملیات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reviews as $review)
                            <tr>
                                <td>
                                    <div class="fw-bold" style="color: #ffffff;">{{ $review->user->name }}</div>
                                    <small class="text-muted">{{ $review->user->mobile ?? '-' }}</small>
                                </td>
                                <td>
                                    <div style="color: #ffffff;">{{ \Illuminate\Support\Str::limit($review->ad->title, 40) }}</div>
                                </td>
                                <td>
                                    <div class="d-flex gap-1" style="direction: ltr;">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="bi bi-star{{ $i <= $review->rating ? '-fill' : '' }}" 
                                               style="color: {{ $i <= $review->rating ? '#ffd700' : '#9ca3af' }};"></i>
                                        @endfor
                                    </div>
                                    <small class="text-muted d-block mt-1">{{ $review->rating }}/5</small>
                                </td>
                                <td>
                                    @if($review->comment)
                                        <div style="color: #e5e7eb; max-width: 300px;">
                                            {{ \Illuminate\Support\Str::limit($review->comment, 100) }}
                                        </div>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge" style="background: {{ $review->status === 'approved' ? 'rgba(57, 255, 20, 0.2)' : ($review->status === 'pending' ? 'rgba(255, 170, 0, 0.2)' : 'rgba(255, 0, 110, 0.2)') }}; color: {{ $review->status === 'approved' ? '#39ff14' : ($review->status === 'pending' ? '#ffaa00' : '#ff006e') }}; border: 1px solid {{ $review->status === 'approved' ? 'rgba(57, 255, 20, 0.3)' : ($review->status === 'pending' ? 'rgba(255, 170, 0, 0.3)' : 'rgba(255, 0, 110, 0.3)') }};">
                                        {{ $review->status === 'approved' ? 'تایید شده' : ($review->status === 'pending' ? 'در انتظار' : 'رد شده') }}
                                    </span>
                                </td>
                                <td class="text-muted">{{ $review->created_at->format('Y/m/d H:i') }}</td>
                                <td>
                                    <div class="d-flex gap-2">
                                        @if($review->status === 'pending')
                                            <button wire:click="approve({{ $review->id }})" 
                                                    class="btn btn-modern btn-sm" 
                                                    style="background: rgba(57, 255, 20, 0.2); box-shadow: none; padding: 4px 12px;"
                                                    wire:confirm="آیا از تایید این نظر مطمئن هستید؟">
                                                <i class="bi bi-check-circle"></i>
                                            </button>
                                            <button wire:click="reject({{ $review->id }})" 
                                                    class="btn btn-modern btn-sm" 
                                                    style="background: rgba(255, 0, 110, 0.2); box-shadow: none; padding: 4px 12px;"
                                                    wire:confirm="آیا از رد این نظر مطمئن هستید؟">
                                                <i class="bi bi-x-circle"></i>
                                            </button>
                                        @endif
                                        @if($review->comment)
                                            <button type="button" 
                                                    class="btn btn-modern btn-sm" 
                                                    style="background: rgba(0, 240, 255, 0.2); box-shadow: none; padding: 4px 12px;"
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#reviewModal{{ $review->id }}">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                            
                                            <!-- Modal نمایش کامل نظر -->
                                            <div class="modal fade" id="reviewModal{{ $review->id }}" tabindex="-1">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content glass-card border-0" style="background: rgba(17, 17, 24, 0.95);">
                                                        <div class="modal-header border-bottom" style="border-color: rgba(255,255,255,0.1);">
                                                            <h5 class="modal-title fw-bold" style="color: #ffffff;">متن کامل نظر</h5>
                                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body" style="color: #e5e7eb; line-height: 1.8;">
                                                            {{ $review->comment }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">
                                    <i class="bi bi-inbox" style="font-size: 3rem; opacity: 0.5;"></i>
                                    <p class="mt-3 mb-0">نظری یافت نشد</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($reviews->hasPages())
            <div class="mt-3 p-3" style="border-top: 1px solid rgba(255,255,255,0.1);">
                <div class="d-flex justify-content-center">
                    {{ $reviews->links('pagination::bootstrap-5') }}
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
