<div>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold" style="color: #ffffff;">
            <i class="bi bi-card-list me-2" style="color: #00f0ff;"></i>
            آگهی‌های من
        </h2>
        <a href="{{ route('panel.ads.create') }}" class="btn btn-modern">
            <i class="bi bi-plus-circle me-2"></i> ایجاد آگهی جدید
        </a>
    </div>

    <div class="glass-card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped mb-0">
                    <thead>
                        <tr>
                            <th>عنوان</th>
                            <th>نوع</th>
                            <th>قیمت</th>
                            <th>وضعیت</th>
                            <th>تاریخ انقضا</th>
                            <th>عملیات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($ads as $ad)
                            <tr>
                                <td>{{ \Illuminate\Support\Str::limit($ad->title, 30) }}</td>
                                <td>
                                    @if($ad->type === 'auction')
                                        <span class="badge" style="background: rgba(255, 0, 110, 0.2); color: #ff006e; border: 1px solid rgba(255, 0, 110, 0.3);">
                                            مزایده
                                        </span>
                                    @else
                                        <span class="badge" style="background: rgba(0, 240, 255, 0.2); color: #00f0ff; border: 1px solid rgba(0, 240, 255, 0.3);">
                                            عادی
                                        </span>
                                    @endif
                                </td>
                                <td class="fw-bold">{{ number_format($ad->price ?? $ad->base_price ?? 0) }} تومان</td>
                                <td>
                                    @if(!$ad->is_active)
                                        <span class="badge" style="background: rgba(255, 0, 110, 0.2); color: #ff006e; border: 1px solid rgba(255, 0, 110, 0.3);">
                                            غیرفعال
                                        </span>
                                    @else
                                        <span class="badge" style="background: {{ $ad->status === 'active' ? 'rgba(57, 255, 20, 0.2)' : ($ad->status === 'pending' ? 'rgba(255, 170, 0, 0.2)' : 'rgba(255, 0, 110, 0.2)') }}; color: {{ $ad->status === 'active' ? '#39ff14' : ($ad->status === 'pending' ? '#ffaa00' : '#ff006e') }}; border: 1px solid {{ $ad->status === 'active' ? 'rgba(57, 255, 20, 0.3)' : ($ad->status === 'pending' ? 'rgba(255, 170, 0, 0.3)' : 'rgba(255, 0, 110, 0.3)') }};">
                                            {{ $ad->status === 'active' ? 'فعال' : ($ad->status === 'pending' ? 'در انتظار' : 'غیرفعال') }}
                                        </span>
                                    @endif
                                </td>
                                <td class="text-muted">{{ $ad->expire_at ? \App\Helpers\DateHelper::toPersianDate($ad->expire_at) : '-' }}</td>
                                <td>
                                    <div class="d-flex gap-2 flex-wrap">
                                        <a href="{{ route('panel.ads.show', $ad) }}" class="btn btn-modern btn-sm" style="background: rgba(0, 240, 255, 0.2); box-shadow: none; padding: 6px 14px;" title="مشاهده">
                                            <i class="bi bi-eye me-1"></i> مشاهده
                                        </a>
                                        <a href="{{ route('panel.ads.edit', $ad) }}" class="btn btn-modern btn-sm" style="background: rgba(255, 170, 0, 0.2); box-shadow: none; padding: 6px 14px;" title="ویرایش">
                                            <i class="bi bi-pencil me-1"></i> ویرایش
                                        </a>
                                        <a href="{{ route('panel.ads.extend', $ad) }}" class="btn btn-modern btn-sm" style="background: rgba(176, 38, 255, 0.2); box-shadow: none; padding: 6px 14px; color: #b026ff;" title="تمدید">
                                            <i class="bi bi-calendar-plus me-1"></i> تمدید
                                        </a>
                                        <button wire:click="delete({{ $ad->id }})" 
                                                wire:confirm="آیا از غیرفعال کردن این آگهی مطمئن هستید؟"
                                                class="btn btn-modern btn-sm" style="background: rgba(255, 0, 110, 0.2); box-shadow: none; padding: 6px 14px;" title="غیرفعال کردن">
                                            <i class="bi bi-x-circle me-1"></i> غیرفعال
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="bi bi-inbox" style="font-size: 3rem; opacity: 0.5;"></i>
                                    <p class="mt-3 mb-0">آگهی‌ای وجود ندارد</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($ads->hasPages())
            <div class="mt-3 p-3" style="border-top: 1px solid rgba(255,255,255,0.1);">
                <div class="d-flex justify-content-center">
                    {{ $ads->links('pagination::bootstrap-5') }}
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

