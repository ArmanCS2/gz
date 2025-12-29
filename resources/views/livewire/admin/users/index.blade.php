<div>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0" style="color: #ffffff;">
            <i class="bi bi-people me-2" style="color: #00f0ff;"></i>
            مدیریت کاربران
        </h2>
        <button wire:click="openCreateModal" class="btn btn-modern">
            <i class="bi bi-plus-circle me-2"></i> ایجاد کاربر جدید
        </button>
    </div>

    <div class="glass-card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>نام</th>
                            <th>موبایل</th>
                            <th>وضعیت</th>
                            <th>عملیات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            <tr>
                                <td>{{ $user->name }}</td>
                                <td class="text-muted">{{ $user->mobile }}</td>
                                <td>
                                    <span class="badge" style="background: {{ $user->is_admin ? 'rgba(255, 0, 110, 0.2)' : ($user->is_verified ? 'rgba(57, 255, 20, 0.2)' : 'rgba(255, 170, 0, 0.2)') }}; color: {{ $user->is_admin ? '#ff006e' : ($user->is_verified ? '#39ff14' : '#ffaa00') }}; border: 1px solid {{ $user->is_admin ? 'rgba(255, 0, 110, 0.3)' : ($user->is_verified ? 'rgba(57, 255, 20, 0.3)' : 'rgba(255, 170, 0, 0.3)') }};">
                                        {{ $user->is_admin ? 'مدیر' : ($user->is_verified ? 'تایید شده' : 'تایید نشده') }}
                                    </span>
                                </td>
                                <td>
                                    <button wire:click="openEditModal({{ $user->id }})" 
                                            class="btn btn-modern btn-sm" 
                                            style="background: rgba(255, 170, 0, 0.2); box-shadow: none; padding: 4px 12px;">
                                        <i class="bi bi-pencil"></i> ویرایش
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-5 text-muted">
                                    <i class="bi bi-inbox" style="font-size: 3rem; opacity: 0.5;"></i>
                                    <p class="mt-3 mb-0">کاربری وجود ندارد</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($users->hasPages())
            <div class="mt-3 p-3" style="border-top: 1px solid rgba(255,255,255,0.1);">
                <div class="d-flex justify-content-center">
                    {{ $users->links('pagination::bootstrap-5') }}
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="userModal" tabindex="-1" wire:ignore.self wire:key="user-modal-{{ $user_id ?? 'new' }}">
        <div class="modal-dialog">
            <div class="modal-content glass-card border-0" style="background: rgba(17, 17, 24, 0.95);">
                <div class="modal-header border-bottom" style="border-color: rgba(255,255,255,0.1);">
                    <h5 class="modal-title fw-bold" style="color: #ffffff;">{{ $user_id ? 'ویرایش کاربر' : 'ایجاد کاربر جدید' }}</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" wire:click="closeModal"></button>
                </div>
                <div class="modal-body">
                    <form wire:submit.prevent="save">
                        <div class="mb-3">
                            <label class="form-label mb-2 d-block fw-semibold" style="color: #ffffff; font-size: 14px;">
                                <i class="bi bi-person me-1" style="color: #00f0ff;"></i> نام
                            </label>
                            <input type="text" class="modern-input w-100" wire:model="name" style="color: #ffffff !important;">
                            @error('name') <span class="text-danger small d-block mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label mb-2 d-block fw-semibold" style="color: #ffffff; font-size: 14px;">
                                <i class="bi bi-phone me-1" style="color: #00f0ff;"></i> شماره موبایل
                            </label>
                            <input type="text" class="modern-input w-100" wire:model="mobile" style="color: #ffffff !important;">
                            @error('mobile') <span class="text-danger small d-block mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-3">
                            <div class="glass-card p-3">
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" wire:model="is_admin" id="is_admin">
                                    <label class="form-check-label" for="is_admin" style="color: #ffffff;">مدیر</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" wire:model="is_verified" id="is_verified">
                                    <label class="form-check-label" for="is_verified" style="color: #ffffff;">تایید شده</label>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer border-top" style="border-color: rgba(255,255,255,0.1);">
                            <button type="button" class="btn btn-modern" style="background: rgba(255,255,255,0.1);" data-bs-dismiss="modal" wire:click="closeModal">انصراف</button>
                            <button type="submit" class="btn btn-modern">ذخیره</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('livewire:init', () => {
            // Listen for Livewire events to open/close modal
            Livewire.on('openModal', (data) => {
                const modalId = data[0]?.modalId || data.modalId;
                if (modalId === 'userModal') {
                    const modalElement = document.getElementById('userModal');
                    if (modalElement) {
                        let modal = bootstrap.Modal.getInstance(modalElement);
                        if (!modal) {
                            modal = new bootstrap.Modal(modalElement);
                        }
                        modal.show();
                    }
                }
            });

            Livewire.on('closeModal', (data) => {
                const modalId = data[0]?.modalId || data.modalId;
                if (modalId === 'userModal') {
                    const modalElement = document.getElementById('userModal');
                    if (modalElement) {
                        let modal = bootstrap.Modal.getInstance(modalElement);
                        if (!modal) {
                            modal = new bootstrap.Modal(modalElement);
                        }
                        modal.hide();
                    }
                }
            });

            // Handle Bootstrap modal hidden event to sync with Livewire
            const modalElement = document.getElementById('userModal');
            if (modalElement) {
                const handleModalHidden = () => {
                    @this.call('closeModal');
                };
                modalElement.addEventListener('hidden.bs.modal', handleModalHidden);
            }
        });
    </script>
</div>
