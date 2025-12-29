<div>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0" style="color: #ffffff;">
            <i class="bi bi-card-list me-2" style="color: #00f0ff;"></i>
            مدیریت آگهی‌ها
        </h2>
        <a href="{{ route('admin.ads.create') }}" class="btn btn-modern">
            <i class="bi bi-plus-circle me-2"></i> ایجاد آگهی جدید
        </a>
    </div>

    <div class="glass-card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>عنوان</th>
                            <th>کاربر</th>
                            <th>نوع</th>
                            <th>وضعیت</th>
                            <th>عملیات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($ads as $ad)
                            <tr>
                                <td>{{ \Illuminate\Support\Str::limit($ad->title, 30) }}</td>
                                <td class="text-muted">{{ $ad->user->name ?? '-' }}</td>
                                <td>
                                    @php
                                        $adType = $ad->ad_type ?? 'telegram';
                                        $adTypeLabels = [
                                            'telegram' => 'گروه تلگرام',
                                            'instagram' => 'پیج اینستاگرام',
                                            'website' => 'سایت آماده',
                                            'domain' => 'دامنه',
                                            'youtube' => 'کانال یوتیوب',
                                        ];
                                    @endphp
                                    <div class="d-flex flex-column gap-1">
                                        <span class="badge" style="background: {{ $ad->type === 'auction' ? 'rgba(255, 0, 110, 0.2)' : 'rgba(0, 240, 255, 0.2)' }}; color: {{ $ad->type === 'auction' ? '#ff006e' : '#00f0ff' }}; border: 1px solid {{ $ad->type === 'auction' ? 'rgba(255, 0, 110, 0.3)' : 'rgba(0, 240, 255, 0.3)' }}; font-size: 11px;">
                                            {{ $ad->type === 'auction' ? 'مزایده' : 'عادی' }}
                                        </span>
                                        @if($adType !== 'telegram')
                                        <span class="badge" style="background: rgba(176, 38, 255, 0.2); color: #b026ff; border: 1px solid rgba(176, 38, 255, 0.3); font-size: 11px;">
                                            {{ $adTypeLabels[$adType] ?? 'گروه تلگرام' }}
                                        </span>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <span class="badge" style="background: {{ $ad->status === 'active' ? 'rgba(57, 255, 20, 0.2)' : ($ad->status === 'pending' ? 'rgba(255, 170, 0, 0.2)' : 'rgba(255, 0, 110, 0.2)') }}; color: {{ $ad->status === 'active' ? '#39ff14' : ($ad->status === 'pending' ? '#ffaa00' : '#ff006e') }}; border: 1px solid {{ $ad->status === 'active' ? 'rgba(57, 255, 20, 0.3)' : ($ad->status === 'pending' ? 'rgba(255, 170, 0, 0.3)' : 'rgba(255, 0, 110, 0.3)') }};">
                                        {{ $ad->status === 'active' ? 'فعال' : ($ad->status === 'pending' ? 'در انتظار' : 'رد شده') }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('admin.ads.edit', $ad->id) }}" 
                                                class="btn btn-modern btn-sm" 
                                                style="background: rgba(0, 240, 255, 0.2); box-shadow: none; padding: 4px 12px;"
                                                title="ویرایش">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        @if($ad->status === 'pending')
                                            <button wire:click="approve({{ $ad->id }})" 
                                                    class="btn btn-modern btn-sm" 
                                                    style="background: rgba(57, 255, 20, 0.2); box-shadow: none; padding: 4px 12px;"
                                                    title="تایید">
                                                <i class="bi bi-check-circle"></i>
                                            </button>
                                            <button wire:click="reject({{ $ad->id }})" 
                                                    class="btn btn-modern btn-sm" 
                                                    style="background: rgba(255, 0, 110, 0.2); box-shadow: none; padding: 4px 12px;"
                                                    title="رد">
                                                <i class="bi bi-x-circle"></i>
                                            </button>
                                        @endif
                                        <button wire:click="delete({{ $ad->id }})" 
                                                wire:confirm="آیا از حذف این آگهی مطمئن هستید؟"
                                                class="btn btn-modern btn-sm" 
                                                style="background: rgba(255, 0, 110, 0.2); box-shadow: none; padding: 4px 12px;"
                                                title="حذف">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
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
