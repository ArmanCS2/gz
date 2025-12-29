<div>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0" style="color: #ffffff;">
            <i class="bi bi-grid-3x3-gap me-2" style="color: #00f0ff;"></i>
            مدیریت دسته‌بندی‌ها
        </h2>
        <button wire:click="openCreateModal" class="btn btn-modern">
            <i class="bi bi-plus-circle me-2"></i> ایجاد دسته‌بندی جدید
        </button>
    </div>

    <div class="glass-card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>نام</th>
                            <th>آیکون</th>
                            <th>ترتیب</th>
                            <th>وضعیت</th>
                            <th>تعداد آگهی</th>
                            <th>عملیات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categories as $category)
                            <tr>
                                <td>
                                    <div>
                                        <strong style="color: #ffffff;">{{ $category->name }}</strong>
                                        @if($category->description)
                                            <br><small class="text-muted">{{ Str::limit($category->description, 50) }}</small>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <i class="bi {{ $category->icon ?? 'bi-grid-3x3-gap' }}" style="font-size: 1.5rem; color: #00f0ff;"></i>
                                </td>
                                <td>{{ $category->order }}</td>
                                <td>
                                    <span class="badge" style="background: {{ $category->is_active ? 'rgba(57, 255, 20, 0.2)' : 'rgba(255, 0, 110, 0.2)' }}; color: {{ $category->is_active ? '#39ff14' : '#ff006e' }}; border: 1px solid {{ $category->is_active ? 'rgba(57, 255, 20, 0.3)' : 'rgba(255, 0, 110, 0.3)' }};">
                                        {{ $category->is_active ? 'فعال' : 'غیرفعال' }}
                                    </span>
                                </td>
                                <td>{{ $category->ads()->count() }}</td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <button wire:click="openEditModal({{ $category->id }})" 
                                                class="btn btn-modern btn-sm" 
                                                style="background: rgba(255, 170, 0, 0.2); box-shadow: none; padding: 4px 12px;">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <button wire:click="delete({{ $category->id }})" 
                                                wire:confirm="آیا از حذف این دسته‌بندی مطمئن هستید؟"
                                                class="btn btn-modern btn-sm" 
                                                style="background: rgba(255, 0, 110, 0.2); box-shadow: none; padding: 4px 12px;">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="bi bi-inbox" style="font-size: 3rem; opacity: 0.5;"></i>
                                    <p class="mt-3 mb-0">دسته‌بندی‌ای وجود ندارد</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($categories->hasPages())
            <div class="mt-3 p-3" style="border-top: 1px solid rgba(255,255,255,0.1);">
                <div class="d-flex justify-content-center">
                    {{ $categories->links('pagination::bootstrap-5') }}
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="categoryModal" tabindex="-1" wire:ignore.self wire:key="category-modal-{{ $category_id ?? 'new' }}">
        <div class="modal-dialog modal-lg">
            <div class="modal-content glass-card border-0" style="background: rgba(17, 17, 24, 0.95);">
                <div class="modal-header border-bottom" style="border-color: rgba(255,255,255,0.1);">
                    <h5 class="modal-title fw-bold" style="color: #ffffff;">{{ $category_id ? 'ویرایش دسته‌بندی' : 'ایجاد دسته‌بندی جدید' }}</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" wire:click="closeModal"></button>
                </div>
                <div class="modal-body">
                    <form wire:submit.prevent="save">
                        <div class="mb-3">
                            <label class="form-label mb-2 d-block fw-semibold" style="color: #ffffff; font-size: 14px;">
                                <i class="bi bi-card-heading me-1" style="color: #00f0ff;"></i> نام دسته‌بندی <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="modern-input w-100" wire:model="name" style="color: #ffffff !important;" placeholder="مثال: فناوری">
                            @error('name') <span class="text-danger small d-block mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label mb-2 d-block fw-semibold" style="color: #ffffff; font-size: 14px;">
                                <i class="bi bi-file-text me-1" style="color: #00f0ff;"></i> توضیحات
                            </label>
                            <textarea class="modern-input w-100" rows="4" wire:model="description" style="color: #ffffff !important;" placeholder="توضیحات دسته‌بندی"></textarea>
                            @error('description') <span class="text-danger small d-block mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label mb-2 d-block fw-semibold" style="color: #ffffff; font-size: 14px;">
                                    <i class="bi bi-image me-1" style="color: #00f0ff;"></i> آیکون (Bootstrap Icons)
                                </label>
                                <input type="text" class="modern-input w-100" wire:model="icon" style="color: #ffffff !important;" placeholder="مثال: bi-laptop">
                                @error('icon') <span class="text-danger small d-block mt-1">{{ $message }}</span> @enderror
                                <small class="text-muted d-block mt-1">نام کلاس آیکون Bootstrap Icons را وارد کنید</small>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label mb-2 d-block fw-semibold" style="color: #ffffff; font-size: 14px;">
                                    <i class="bi bi-sort-numeric-down me-1" style="color: #00f0ff;"></i> ترتیب نمایش
                                </label>
                                <input type="number" class="modern-input w-100" wire:model="order" style="color: #ffffff !important;" placeholder="0" min="0">
                                @error('order') <span class="text-danger small d-block mt-1">{{ $message }}</span> @enderror
                            </div>
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
                if (modalId === 'categoryModal') {
                    const modalElement = document.getElementById('categoryModal');
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
                if (modalId === 'categoryModal') {
                    const modalElement = document.getElementById('categoryModal');
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
            const modalElement = document.getElementById('categoryModal');
            if (modalElement) {
                const handleModalHidden = () => {
                    @this.call('closeModal');
                };
                modalElement.addEventListener('hidden.bs.modal', handleModalHidden);
            }
        });
    </script>
</div>

