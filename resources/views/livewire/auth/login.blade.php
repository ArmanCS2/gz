<!-- Modern Glassmorphism Login Modal -->
<div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content login-modal-content border-0">
            <div class="login-modal-background"></div>
            
            <div class="modal-header border-0 pb-0 position-relative" style="z-index: 2;">
                <h5 class="modal-title login-title" id="loginModalLabel">
                    <i class="bi bi-shield-lock me-2"></i>
                    ورود / ثبت نام
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close" wire:click="resetForm"></button>
            </div>
            
            <div class="modal-body position-relative" style="z-index: 2;">
                
                {{-- STEP 1: MOBILE --}}
                @if($step === 'mobile')
                <form wire:submit.prevent="sendOtp" class="login-form">
                    <div class="mb-4">
                        <div class="floating-label-group login-input-group">
                            <input type="text" 
                                   class="login-input modern-input @error('mobile') is-invalid shake @enderror" 
                                   wire:model.live="mobile"
                                   placeholder=" " 
                                   maxlength="11"
                                   autofocus
                                   id="mobile-input"
                                   dir="ltr"
                                   x-on:input="$el.value = $el.value.replace(/[^0-9]/g,'')">
                            <label class="floating-label login-label" for="mobile-input">
                                <i class="bi bi-phone me-2"></i>
                                شماره موبایل <span class="text-danger">*</span>
                            </label>
                        </div>

                        @error('mobile') 
                        <div class="error-message mt-2">
                            <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                        </div>
                        @enderror
                    </div>
                    
                    <button type="submit" class="btn btn-modern login-submit-btn w-100">
                        <i class="bi bi-send me-2"></i> ارسال کد تایید
                    </button>

                    <div class="text-center mt-3">
                        <small class="text-muted">
                            <i class="bi bi-info-circle me-1"></i>
                            کد تایید به شماره شما ارسال خواهد شد
                        </small>
                    </div>

                    
                    
            
                </form>


                {{-- STEP 2: OTP --}}
                @else
                <form wire:submit.prevent="verifyOtp" class="login-form" id="otp-form">
                    
                    <div class="text-center mb-4 otp-phone-display">
                        <i class="bi bi-phone-vibrate me-2"></i>
                        کد تایید به شماره <strong>{{ $mobile }}</strong> ارسال شد
                    </div>

                    <!-- OTP INPUTS Livewire 3 Safe -->
                    <div class="mb-4">
                        <div class="otp-input-container d-flex justify-content-center gap-3">

                            @foreach([0,1,2,3] as $i)
                                <input type="text"
                                    class="otp-input @error('otp') otp-error @enderror"
                                    maxlength="1"
                                    id="otp-{{ $i }}"
                                    wire:model.live="otp.{{ $i }}"
                                    inputmode="numeric"
                                    x-on:input="
                                        $el.value = $el.value.replace(/[^0-9]/g,'');
                                        if($el.value && {{ $i }} < 3)
                                            document.getElementById('otp-{{ $i+1 }}').focus();
                                    "
                                    autocomplete="off"
                                    style="text-align:center;">
                            @endforeach
                        </div>

                        @error('otp')
                        <div class="error-message mt-3 text-center">
                            <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                        </div>
                        @enderror
                        @error('otp.*')
                        <div class="error-message mt-3 text-center">
                            <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                        </div>
                        @enderror
                    </div>

                    <button type="submit"
                        class="btn btn-modern login-submit-btn w-100">
                        <i class="bi bi-check-circle me-2"></i> تایید و ورود
                    </button>

                    <div class="text-center mt-3">
                        <button type="button" class="btn btn-link text-decoration-none login-back-btn"
                            wire:click="$set('step','mobile')">
                            <i class="bi bi-arrow-right me-1"></i> تغییر شماره موبایل
                        </button>
                    </div>

                </form>
                @endif
            </div>
        </div>
    </div>
</div>


@script
<script>
document.addEventListener('livewire:init', () => {
    // Close modal event
    Livewire.on('closeLoginModal', () => {
        const modalElement = document.getElementById('loginModal');
        if (modalElement) {
            const modal = bootstrap.Modal.getInstance(modalElement);
            if (modal) {
                modal.hide();
            }
        }
    });

    // Auto focus first OTP input when step changes to verify
    Livewire.on('otpSent', () => {
        setTimeout(() => {
            document.getElementById('otp-0')?.focus();
        }, 300);
    });
});

// Handle OTP input changes - فقط auto-focus، بدون auto-submit
document.addEventListener('DOMContentLoaded', function() {
    function setupOtpInputs() {
        const inputs = [
            document.getElementById('otp-0'),
            document.getElementById('otp-1'),
            document.getElementById('otp-2'),
            document.getElementById('otp-3')
        ].filter(Boolean);

        inputs.forEach((input, index) => {
            if (!input) return;

            // Remove existing listeners to prevent duplicates
            const newInput = input.cloneNode(true);
            input.parentNode.replaceChild(newInput, input);

            newInput.addEventListener('input', function(e) {
                const value = e.target.value.replace(/[^0-9]/g, '').slice(0, 1);
                e.target.value = value;

                // فقط auto-focus به input بعدی - بدون auto-submit
                if (value && index < 3) {
                    setTimeout(() => {
                        const next = document.getElementById('otp-' + (index + 1));
                        if (next) next.focus();
                    }, 50);
                }
            });

            // Handle backspace
            newInput.addEventListener('keydown', function(e) {
                if (e.key === 'Backspace' && !e.target.value && index > 0) {
                    e.preventDefault();
                    setTimeout(() => {
                        const prev = document.getElementById('otp-' + (index - 1));
                        if (prev) {
                            prev.focus();
                            prev.value = '';
                        }
                    }, 10);
                }
            });
        });
    }

    setupOtpInputs();

    // Re-setup after Livewire updates
    document.addEventListener('livewire:navigated', setupOtpInputs);
    Livewire.hook('morph.updated', () => {
        setTimeout(setupOtpInputs, 100);
    });
});
</script>
@endscript
