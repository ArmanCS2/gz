<div>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>تنظیمات سایت</h2>
        <button wire:click="openModal()" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#settingsModal">
            <i class="bi bi-pencil-square"></i> ویرایش تنظیمات
        </button>
    </div>

    <div class="card">
        <div class="card-body">
            <dl class="row">
                <dt class="col-sm-3">نام سایت:</dt>
                <dd class="col-sm-9">{{ $settings->site_name }}</dd>

                <dt class="col-sm-3">لوگو سایت:</dt>
                <dd class="col-sm-9">
                    @if($settings->logo)
                        <img src="{{ asset($settings->logo) }}" alt="لوگو" style="max-height: 60px; max-width: 200px;">
                    @else
                        <span class="text-muted">تعریف نشده</span>
                    @endif
                </dd>

                <dt class="col-sm-3">قیمت روزانه آگهی عادی:</dt>
                <dd class="col-sm-9">{{ number_format($settings->ad_daily_price) }} تومان</dd>

                <dt class="col-sm-3">قیمت روزانه مزایده:</dt>
                <dd class="col-sm-9">{{ number_format($settings->auction_daily_price) }} تومان</dd>

                <dt class="col-sm-3">Merchant ID زرین‌پال:</dt>
                <dd class="col-sm-9">{{ $settings->zarinpal_merchant_id ?: 'تعریف نشده' }}</dd>

                <dt class="col-sm-3">نام کاربری MeliPayamak:</dt>
                <dd class="col-sm-9">{{ $settings->melipayamak_username ?: 'تعریف نشده' }}</dd>

                <dt class="col-sm-3">رمز عبور MeliPayamak:</dt>
                <dd class="col-sm-9">{{ $settings->melipayamak_password ? '••••••••' : 'تعریف نشده' }}</dd>

                <dt class="col-sm-3">شماره خط MeliPayamak:</dt>
                <dd class="col-sm-9">{{ $settings->melipayamak_from_number ?: 'تعریف نشده' }}</dd>

                <dt class="col-sm-3">تایید خودکار آگهی‌ها:</dt>
                <dd class="col-sm-9">
                    <span class="badge bg-{{ $settings->ad_auto_approve ? 'success' : 'danger' }}">
                        {{ $settings->ad_auto_approve ? 'فعال' : 'غیرفعال' }}
                    </span>
                </dd>

                <dt class="col-sm-3">آگهی فعال:</dt>
                <dd class="col-sm-9">{{ number_format($settings->active_ads ?? 0) }}</dd>

                <dt class="col-sm-3">کل اعضا:</dt>
                <dd class="col-sm-9">{{ number_format($settings->total_members ?? 0) }}</dd>

                <dt class="col-sm-3">کاربر فعال:</dt>
                <dd class="col-sm-9">{{ number_format($settings->active_users ?? 0) }}</dd>

                <dt class="col-sm-3">معامله موفق:</dt>
                <dd class="col-sm-9">{{ number_format($settings->successful_deals ?? 0) }}</dd>

                <dt class="col-sm-3">درصد رضایت:</dt>
                <dd class="col-sm-9">{{ $settings->satisfaction_percent ?? 100 }}٪</dd>

                <dt class="col-sm-3">امتیاز کلی:</dt>
                <dd class="col-sm-9">{{ number_format($settings->rating ?? 4.9, 1) }}</dd>
            </dl>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="settingsModal" tabindex="-1" wire:ignore.self>
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">ویرایش تنظیمات سایت</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" wire:click="closeModal"></button>
                </div>
                <div class="modal-body">
                    <form wire:submit.prevent="save">
                        <div class="mb-3">
                            <label class="form-label">نام سایت</label>
                            <input type="text" class="form-control" wire:model="site_name" placeholder="{{ $settings->site_name }}">
                            <small class="text-muted">مقدار فعلی: <strong>{{ $settings->site_name }}</strong></small>
                            @error('site_name') <span class="text-danger small d-block">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">لوگو سایت</label>
                            @if($logoPreview)
                                <div class="mb-2">
                                    <img src="{{ $logoPreview }}" alt="پیش‌نمایش لوگو" style="max-height: 80px; max-width: 200px; border-radius: 8px; border: 1px solid rgba(255,255,255,0.1);">
                                </div>
                            @elseif($settings->logo)
                                <div class="mb-2">
                                    <img src="{{ asset($settings->logo) }}" alt="لوگو فعلی" style="max-height: 80px; max-width: 200px; border-radius: 8px; border: 1px solid rgba(255,255,255,0.1);">
                                </div>
                            @endif
                            <input type="file" class="form-control" wire:model="logo" accept="image/*">
                            <small class="text-muted">حداکثر 2 مگابایت - فرمت‌های مجاز: JPG, PNG, GIF</small>
                            @error('logo') <span class="text-danger small d-block">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">قیمت روزانه آگهی عادی (تومان)</label>
                            <input type="text" class="form-control" wire:model="ad_daily_price" pattern="[0-9]*\.?[0-9]*" inputmode="decimal" onkeypress="return (event.charCode >= 48 && event.charCode <= 57) || event.charCode === 46" placeholder="{{ number_format($settings->ad_daily_price) }}">
                            <small class="text-muted">مقدار فعلی: <strong>{{ number_format($settings->ad_daily_price) }} تومان</strong></small>
                            @error('ad_daily_price') <span class="text-danger small d-block">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">قیمت روزانه مزایده (تومان)</label>
                            <input type="text" class="form-control" wire:model="auction_daily_price" pattern="[0-9]*\.?[0-9]*" inputmode="decimal" onkeypress="return (event.charCode >= 48 && event.charCode <= 57) || event.charCode === 46" placeholder="{{ number_format($settings->auction_daily_price) }}">
                            <small class="text-muted">مقدار فعلی: <strong>{{ number_format($settings->auction_daily_price) }} تومان</strong></small>
                            @error('auction_daily_price') <span class="text-danger small d-block">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Merchant ID زرین‌پال</label>
                            <input type="text" class="form-control" wire:model="zarinpal_merchant_id" placeholder="{{ $settings->zarinpal_merchant_id ?: 'مثال: 71c705f8-bd37-11e6-aa0c-000c295eb8fc' }}">
                            @if($settings->zarinpal_merchant_id)
                                <small class="text-muted">مقدار فعلی: <strong>{{ $settings->zarinpal_merchant_id }}</strong></small>
                            @else
                                <small class="text-muted">مقدار فعلی: <strong class="text-muted">تعریف نشده</strong></small>
                            @endif
                            @error('zarinpal_merchant_id') <span class="text-danger small d-block">{{ $message }}</span> @enderror
                        </div>

                        <hr class="my-4">
                        <h6 class="mb-3"><i class="bi bi-send"></i> تنظیمات MeliPayamak (ارسال پیامک)</h6>

                        <div class="mb-3">
                            <label class="form-label">نام کاربری MeliPayamak</label>
                            <input type="text" class="form-control" wire:model="melipayamak_username" placeholder="{{ $settings->melipayamak_username ?: 'مثال: 09223618018' }}">
                            @if($settings->melipayamak_username)
                                <small class="text-muted">مقدار فعلی: <strong>{{ $settings->melipayamak_username }}</strong></small>
                            @else
                                <small class="text-muted">مقدار فعلی: <strong class="text-muted">تعریف نشده</strong></small>
                            @endif
                            @error('melipayamak_username') <span class="text-danger small d-block">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">رمز عبور MeliPayamak</label>
                            <input type="password" class="form-control" wire:model="melipayamak_password" placeholder="رمز عبور پنل MeliPayamak">
                            @if($settings->melipayamak_password)
                                <small class="text-muted">مقدار فعلی: <strong>••••••••</strong> (برای تغییر، مقدار جدید وارد کنید)</small>
                            @else
                                <small class="text-muted">مقدار فعلی: <strong class="text-muted">تعریف نشده</strong></small>
                            @endif
                            @error('melipayamak_password') <span class="text-danger small d-block">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">شماره خط ارسال کننده</label>
                            <input type="text" class="form-control" wire:model="melipayamak_from_number" placeholder="{{ $settings->melipayamak_from_number ?: 'مثال: 50004001' }}">
                            @if($settings->melipayamak_from_number)
                                <small class="text-muted">مقدار فعلی: <strong>{{ $settings->melipayamak_from_number }}</strong> | شماره خطی که پیامک‌ها از آن ارسال می‌شوند</small>
                            @else
                                <small class="text-muted">مقدار فعلی: <strong class="text-muted">تعریف نشده</strong> | شماره خطی که پیامک‌ها از آن ارسال می‌شوند</small>
                            @endif
                            @error('melipayamak_from_number') <span class="text-danger small d-block">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" wire:model="ad_auto_approve" id="ad_auto_approve">
                                <label class="form-check-label" for="ad_auto_approve">
                                    تایید خودکار آگهی‌ها
                                </label>
                            </div>
                            <small class="text-muted">وضعیت فعلی: <strong class="{{ $settings->ad_auto_approve ? 'text-success' : 'text-danger' }}">{{ $settings->ad_auto_approve ? 'فعال' : 'غیرفعال' }}</strong></small>
                        </div>

                        <hr class="my-4">
                        <h6 class="mb-3"><i class="bi bi-bar-chart"></i> آمار صفحه اصلی</h6>

                        <div class="mb-3">
                            <label class="form-label">آگهی فعال</label>
                            <input type="number" class="form-control" wire:model="active_ads" min="0" placeholder="{{ number_format($settings->active_ads ?? 0) }}">
                            <small class="text-muted">مقدار فعلی: <strong>{{ number_format($settings->active_ads ?? 0) }}</strong></small>
                            @error('active_ads') <span class="text-danger small d-block">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">کل اعضا</label>
                            <input type="number" class="form-control" wire:model="total_members" min="0" placeholder="{{ number_format($settings->total_members ?? 0) }}">
                            <small class="text-muted">مقدار فعلی: <strong>{{ number_format($settings->total_members ?? 0) }}</strong></small>
                            @error('total_members') <span class="text-danger small d-block">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">کاربر فعال</label>
                            <input type="number" class="form-control" wire:model="active_users" min="0" placeholder="{{ number_format($settings->active_users ?? 0) }}">
                            <small class="text-muted">مقدار فعلی: <strong>{{ number_format($settings->active_users ?? 0) }}</strong></small>
                            @error('active_users') <span class="text-danger small d-block">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">معامله موفق</label>
                            <input type="number" class="form-control" wire:model="successful_deals" min="0" placeholder="{{ number_format($settings->successful_deals ?? 0) }}">
                            <small class="text-muted">مقدار فعلی: <strong>{{ number_format($settings->successful_deals ?? 0) }}</strong></small>
                            @error('successful_deals') <span class="text-danger small d-block">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">درصد رضایت (0-100)</label>
                            <input type="number" class="form-control" wire:model="satisfaction_percent" min="0" max="100" placeholder="{{ $settings->satisfaction_percent ?? 100 }}">
                            <small class="text-muted">مقدار فعلی: <strong>{{ $settings->satisfaction_percent ?? 100 }}٪</strong></small>
                            @error('satisfaction_percent') <span class="text-danger small d-block">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">امتیاز کلی (0-5)</label>
                            <input type="number" class="form-control" wire:model="rating" min="0" max="5" step="0.1" placeholder="{{ number_format($settings->rating ?? 4.9, 1) }}">
                            <small class="text-muted">مقدار فعلی: <strong>{{ number_format($settings->rating ?? 4.9, 1) }}</strong></small>
                            @error('rating') <span class="text-danger small d-block">{{ $message }}</span> @enderror
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" wire:click="closeModal">انصراف</button>
                            <button type="submit" class="btn btn-primary">ذخیره</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('closeModal', () => {
                const modalElement = document.getElementById('settingsModal');
                if (modalElement) {
                    let modal = bootstrap.Modal.getInstance(modalElement);
                    if (!modal) {
                        modal = new bootstrap.Modal(modalElement);
                    }
                    modal.hide();
                }
            });
        });

        // Show modal when showModal becomes true
        Livewire.hook('morph.updated', ({ el, component }) => {
            if (component.showModal) {
                const modalElement = document.getElementById('settingsModal');
                if (modalElement) {
                    let modal = bootstrap.Modal.getInstance(modalElement);
                    if (!modal) {
                        modal = new bootstrap.Modal(modalElement);
                    }
                    modal.show();
                    
                    // Handle modal close
                    const handleModalHidden = () => {
                        @this.call('closeModal');
                    };
                    
                    // Remove existing listener to prevent duplicates
                    modalElement.removeEventListener('hidden.bs.modal', handleModalHidden);
                    
                    // Add listener
                    modalElement.addEventListener('hidden.bs.modal', handleModalHidden);
                }
            }
        });
    </script>
</div>

