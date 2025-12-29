<div>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0" style="color: #ffffff;">
            <i class="bi bi-pencil me-2" style="color: #00f0ff;"></i>
            ویرایش آگهی
        </h2>
        <a href="{{ route('admin.ads.index') }}" class="btn btn-modern btn-sm" style="background: rgba(255,255,255,0.1); box-shadow: none;">
            <i class="bi bi-arrow-right me-1"></i> بازگشت
        </a>
    </div>

    <div class="glass-card p-4">
        <form wire:submit.prevent="save">
            @include('livewire.admin.ads._form-fields')

            <div class="d-flex justify-content-end gap-3 mt-4 pt-3 border-top" style="border-color: rgba(255,255,255,0.1);">
                <a href="{{ route('admin.ads.index') }}" class="btn btn-modern" style="background: rgba(255,255,255,0.1);">انصراف</a>
                <button type="submit" class="btn btn-modern">ذخیره</button>
            </div>
        </form>
    </div>

    @script
    <script>
        // Initialize jalali datepicker
        let watchStarted = false;
        window.initJalaliDatePicker = function(scope = document) {
            if (typeof jalaliDatepicker === 'undefined') {
                setTimeout(() => window.initJalaliDatePicker(scope), 100);
                return;
            }
            
            if (jalaliDatepicker.config) {
                jalaliDatepicker.config.zIndex = 9999;
            }
            
            if (!watchStarted) {
                try {
                    jalaliDatepicker.startWatch();
                    watchStarted = true;
                } catch(e) {
                    console.log('Datepicker watch error:', e);
                }
            }
            
            const inputs = scope.querySelectorAll ? scope.querySelectorAll('input[data-jdp]') : [];
            inputs.forEach(input => {
                if (!input.hasAttribute('data-jdp-initialized')) {
                    try {
                        jalaliDatepicker.init(input);
                        input.setAttribute('data-jdp-initialized', 'true');
                    } catch(e) {
                        console.log('Datepicker init error:', e);
                    }
                }
            });
        };
        
        function waitForJalaliDatepicker(scope = document) {
            if (typeof jalaliDatepicker !== 'undefined') {
                window.initJalaliDatePicker(scope);
            } else {
                setTimeout(() => waitForJalaliDatepicker(scope), 100);
            }
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', () => {
                waitForJalaliDatepicker();
            });
        } else {
            waitForJalaliDatepicker();
        }

        document.addEventListener('livewire:init', () => {
            Livewire.hook('morph.updated', ({ el }) => {
                setTimeout(() => {
                    waitForJalaliDatepicker();
                }, 300);
            });
            
            Livewire.on('ad-type-changed', () => {
                setTimeout(() => {
                    waitForJalaliDatepicker();
                }, 400);
            });
        });
    </script>
    @endscript
</div>
