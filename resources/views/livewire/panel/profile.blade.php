<div>
    <h2 class="mb-4 fw-bold" style="color: #ffffff;">
        <i class="bi bi-person me-2" style="color: #00f0ff;"></i>
        پروفایل
    </h2>

    <div class="glass-card">
        <div class="card-body p-4">
            <form wire:submit.prevent="save">
                <div class="mb-4">
                    <label class="filter-label mb-2 d-block" for="name-input" style="color: #e5e7eb;">
                        <i class="bi bi-person me-1"></i> نام
                    </label>
                    <div class="floating-label-group">
                        <input type="text" 
                               class="modern-input @error('name') border-danger @enderror" 
                               placeholder=" " 
                               wire:model.blur="name"
                               id="name-input">
                        <label class="floating-label" for="name-input">نام شما</label>
                    </div>
                    @error('name') 
                        <span class="text-danger small d-block mt-2">
                            <i class="bi bi-exclamation-circle me-1"></i> {{ $message }}
                        </span> 
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="filter-label mb-2 d-block" for="mobile-input" style="color: #e5e7eb;">
                        <i class="bi bi-phone me-1"></i> شماره موبایل
                    </label>
                    <div class="floating-label-group">
                        <input type="text" 
                               id="mobile-input"
                               class="modern-input" 
                               value="{{ auth()->user()->mobile }}" 
                               disabled
                               aria-label="شماره موبایل (غیرقابل تغییر)"
                               style="opacity: 0.6; cursor: not-allowed;">
                        <label class="floating-label" for="mobile-input" style="opacity: 0.6;">شماره موبایل (غیرقابل تغییر)</label>
                    </div>
                </div>

                <button type="submit" class="btn btn-modern">
                    <i class="bi bi-check-circle me-2"></i> ذخیره تغییرات
                </button>
            </form>
        </div>
    </div>
</div>



