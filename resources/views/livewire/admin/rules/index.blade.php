<div>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0" style="color: #ffffff;">
            <i class="bi bi-file-text me-2" style="color: #00f0ff;"></i>
            مدیریت قوانین سایت
        </h2>
        <button wire:click="openCreateModal" class="btn btn-modern">
            <i class="bi bi-plus-circle me-2"></i> ایجاد قانون جدید
        </button>
    </div>

    <div class="glass-card">
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
                        @forelse($rules as $rule)
                            <tr>
                                <td>{{ $rule->title }}</td>
                                <td>
                                    <span class="badge" style="background: {{ $rule->is_active ? 'rgba(57, 255, 20, 0.2)' : 'rgba(255, 0, 110, 0.2)' }}; color: {{ $rule->is_active ? '#39ff14' : '#ff006e' }}; border: 1px solid {{ $rule->is_active ? 'rgba(57, 255, 20, 0.3)' : 'rgba(255, 0, 110, 0.3)' }};">
                                        {{ $rule->is_active ? 'فعال' : 'غیرفعال' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <button wire:click="openEditModal({{ $rule->id }})" 
                                                class="btn btn-modern btn-sm" 
                                                style="background: rgba(255, 170, 0, 0.2); box-shadow: none; padding: 4px 12px;">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <button wire:click="delete({{ $rule->id }})" 
                                                wire:confirm="آیا از حذف این قانون مطمئن هستید؟"
                                                class="btn btn-modern btn-sm" 
                                                style="background: rgba(255, 0, 110, 0.2); box-shadow: none; padding: 4px 12px;">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center py-5 text-muted">
                                    <i class="bi bi-inbox" style="font-size: 3rem; opacity: 0.5;"></i>
                                    <p class="mt-3 mb-0">قانونی وجود ندارد</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($rules->hasPages())
            <div class="mt-3 p-3" style="border-top: 1px solid rgba(255,255,255,0.1);">
                <div class="d-flex justify-content-center">
                    {{ $rules->links('pagination::bootstrap-5') }}
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="ruleModal" tabindex="-1" wire:ignore.self wire:key="rule-modal-{{ $rule_id ?? 'new' }}">
        <div class="modal-dialog modal-lg">
            <div class="modal-content glass-card border-0" style="background: rgba(17, 17, 24, 0.95);">
                <div class="modal-header border-bottom" style="border-color: rgba(255,255,255,0.1);">
                    <h5 class="modal-title fw-bold" style="color: #ffffff;">{{ $rule_id ? 'ویرایش قانون' : 'ایجاد قانون جدید' }}</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" wire:click="closeModal"></button>
                </div>
                <div class="modal-body">
                    <form wire:submit.prevent="save">
                        <div class="mb-3">
                            <label class="form-label mb-2 d-block fw-semibold" style="color: #ffffff; font-size: 14px;">
                                <i class="bi bi-card-heading me-1" style="color: #00f0ff;"></i> عنوان
                            </label>
                            <input type="text" class="modern-input w-100" wire:model="title" style="color: #ffffff !important;">
                            @error('title') <span class="text-danger small d-block mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label mb-2 d-block fw-semibold" style="color: #ffffff; font-size: 14px;">
                                <i class="bi bi-file-text me-1" style="color: #00f0ff;"></i> محتوا
                            </label>
                            <textarea class="modern-input w-100" rows="8" wire:model="content" style="color: #ffffff !important;"></textarea>
                            @error('content') <span class="text-danger small d-block mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-3">
                            <div class="glass-card p-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" wire:model="is_active" id="is_active">
                                    <label class="form-check-label" for="is_active" style="color: #ffffff;">فعال</label>
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
                if (modalId === 'ruleModal') {
                    const modalElement = document.getElementById('ruleModal');
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
                if (modalId === 'ruleModal') {
                    const modalElement = document.getElementById('ruleModal');
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
            const modalElement = document.getElementById('ruleModal');
            if (modalElement) {
                const handleModalHidden = () => {
                    @this.call('closeModal');
                };
                modalElement.addEventListener('hidden.bs.modal', handleModalHidden);
            }
        });
    </script>
</div>
