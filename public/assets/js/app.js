// GroohBaz - Custom JavaScript
// CDN-only implementation

(function() {
    'use strict';

    // Initialize on DOM ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

    // Search filter visual feedback
    document.addEventListener('livewire:init', () => {
        if (typeof Livewire !== 'undefined') {
            Livewire.hook('morph.updated', () => {
                updateFilterVisuals();
            });
        }
    });

    function updateFilterVisuals() {
        // Add visual feedback to active filters
        document.querySelectorAll('.modern-input, .modern-select select, .segmented-control button').forEach(element => {
            if (element.value && element.value !== '' && element.value !== 'all') {
                element.classList.add('filter-active');
            } else {
                element.classList.remove('filter-active');
            }
        });
    }

    function init() {
        initCounterAnimations();
        initFloatingLabels();
        initRangeSliders();
        initImageUpload();
    }

    // Counter Animation
    function initCounterAnimations() {
        const counters = document.querySelectorAll('.counter');
        counters.forEach(counter => {
            const target = parseInt(counter.textContent.replace(/[^0-9]/g, ''));
            if (isNaN(target)) return;
            
            let current = 0;
            const increment = target / 50;
            const timer = setInterval(() => {
                current += increment;
                if (current >= target) {
                    counter.textContent = counter.textContent.replace(/[\d,]+/, formatNumber(target));
                    clearInterval(timer);
                } else {
                    counter.textContent = counter.textContent.replace(/[\d,]+/, formatNumber(Math.floor(current)));
                }
            }, 30);
        });
    }

    function formatNumber(num) {
        return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }

    // Floating Labels
    function initFloatingLabels() {
        const inputs = document.querySelectorAll('.floating-label-group input, .floating-label-group textarea');
        inputs.forEach(input => {
            if (input.value) {
                input.classList.add('has-value');
            }
            input.addEventListener('input', function() {
                if (this.value) {
                    this.classList.add('has-value');
                } else {
                    this.classList.remove('has-value');
                }
            });
        });
    }

    // Range Sliders
    function initRangeSliders() {
        const sliders = document.querySelectorAll('.range-slider');
        sliders.forEach(slider => {
            slider.addEventListener('input', function() {
                const value = this.value;
                const display = this.nextElementSibling;
                if (display && display.classList.contains('range-value')) {
                    display.textContent = formatNumber(value) + ' تومان';
                }
            });
        });
    }

    // Image Upload with Preview
    function initImageUpload() {
        const uploadZones = document.querySelectorAll('.upload-zone');
        uploadZones.forEach(zone => {
            const input = zone.querySelector('input[type="file"]');
            if (!input) return;

            zone.addEventListener('dragover', (e) => {
                e.preventDefault();
                zone.classList.add('dragover');
            });

            zone.addEventListener('dragleave', () => {
                zone.classList.remove('dragover');
            });

            zone.addEventListener('drop', (e) => {
                e.preventDefault();
                zone.classList.remove('dragover');
                const files = e.dataTransfer.files;
                if (files.length > 0) {
                    input.files = files;
                    input.dispatchEvent(new Event('change', { bubbles: true }));
                }
            });
        });
    }

    // Toast Notification Helper
    window.showToast = function(message, type = 'info') {
        if (typeof Toastify !== 'undefined') {
            Toastify({
                text: message,
                duration: 5000,
                gravity: "top",
                position: "left",
                backgroundColor: type === 'success' ? '#39ff14' : 
                                type === 'error' ? '#ff006e' : 
                                type === 'warning' ? '#ffaa00' : '#00f0ff',
                stopOnFocus: true,
            }).showToast();
        } else if (typeof bootstrap !== 'undefined') {
            // Fallback to Bootstrap Toast
            const toastContainer = document.getElementById('toast-container') || createToastContainer();
            const toastId = 'toast-' + Date.now();
            const bgClass = {
                'success': 'bg-success',
                'error': 'bg-danger',
                'warning': 'bg-warning',
                'info': 'bg-info'
            }[type] || 'bg-info';

            const toastHtml = `
                <div id="${toastId}" class="toast ${bgClass} text-white" role="alert">
                    <div class="toast-header ${bgClass} text-white">
                        <strong class="me-auto">${type.charAt(0).toUpperCase() + type.slice(1)}</strong>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>
                    </div>
                    <div class="toast-body">${message}</div>
                </div>
            `;

            toastContainer.insertAdjacentHTML('beforeend', toastHtml);
            const toastElement = document.getElementById(toastId);
            const toast = new bootstrap.Toast(toastElement, { autohide: true, delay: 5000 });
            toast.show();

            toastElement.addEventListener('hidden.bs.toast', () => {
                toastElement.remove();
            });
        }
    };

    function createToastContainer() {
        const container = document.createElement('div');
        container.id = 'toast-container';
        container.className = 'toast-container position-fixed top-0 end-0 p-3';
        container.style.zIndex = '9999';
        document.body.appendChild(container);
        return container;
    }

    // SweetAlert Helper with Dark Theme
    window.showSwal = function(options) {
        if (typeof Swal !== 'undefined') {
            const defaultOptions = {
                confirmButtonText: 'تأیید',
                cancelButtonText: 'انصراف',
                confirmButtonColor: '#00f0ff',
                cancelButtonColor: '#ff006e',
                background: '#111118',
                color: '#ffffff',
                backdrop: 'rgba(0, 0, 0, 0.8)',
                customClass: {
                    popup: 'swal-dark-popup',
                    title: 'swal-dark-title',
                    content: 'swal-dark-content',
                    confirmButton: 'swal-dark-confirm',
                    cancelButton: 'swal-dark-cancel'
                },
                buttonsStyling: true,
                allowOutsideClick: true,
                allowEscapeKey: true
            };
            
            const mergedOptions = { ...defaultOptions, ...options };
            
            // Handle onConfirm callback
            if (options && options.onConfirm) {
                const originalOnConfirm = options.onConfirm;
                mergedOptions.then = function(result) {
                    if (result.isConfirmed && originalOnConfirm) {
                        originalOnConfirm();
                    }
                };
                delete mergedOptions.onConfirm;
            }
            
            return Swal.fire(mergedOptions);
        }
    };

    // Livewire Event Listeners
    document.addEventListener('livewire:init', () => {
        if (typeof Livewire !== 'undefined') {
            Livewire.on('showToast', (data) => {
                window.showToast(data[0].message, data[0].type || 'info');
            });

            Livewire.on('showSwal', (data) => {
                const options = data[0] || {};
                window.showSwal(options);
            });

            // Close login modal event
            Livewire.on('closeLoginModal', () => {
                const modalElement = document.getElementById('loginModal');
                if (modalElement) {
                    const modal = bootstrap.Modal.getInstance(modalElement);
                    if (modal) {
                        modal.hide();
                    } else {
                        const bsModal = new bootstrap.Modal(modalElement);
                        bsModal.hide();
                    }
                }
            });
        }
    });

    // Browser Event Listeners
    window.addEventListener('show-toast', (event) => {
        window.showToast(event.detail.message, event.detail.type || 'info');
    });

    window.addEventListener('show-swal', (event) => {
        window.showSwal(event.detail);
    });

    // Modal Scrollable Helper
    function makeModalsScrollable() {
        document.querySelectorAll('.modal-dialog').forEach(dialog => {
            if (!dialog.classList.contains('modal-dialog-scrollable')) {
                dialog.classList.add('modal-dialog-scrollable');
            }
        });
    }

    makeModalsScrollable();

    const observer = new MutationObserver(function(mutations) {
        makeModalsScrollable();
    });

    observer.observe(document.body, {
        childList: true,
        subtree: true
    });

    document.addEventListener('livewire:init', () => {
        makeModalsScrollable();
        if (typeof Livewire !== 'undefined') {
            Livewire.hook('morph.updated', () => {
                setTimeout(makeModalsScrollable, 50);
            });
        }
    });

    document.addEventListener('show.bs.modal', function(e) {
        setTimeout(() => {
            const dialog = e.target.querySelector('.modal-dialog');
            if (dialog && !dialog.classList.contains('modal-dialog-scrollable')) {
                dialog.classList.add('modal-dialog-scrollable');
            }
        }, 10);
    });

    // Login Modal Enhancements
    initLoginModal();

    // Blog-specific enhancements
    initBlogFeatures();
})();

// Blog Features
function initBlogFeatures() {
    // Lazy loading images
    if ('loading' in HTMLImageElement.prototype) {
        const images = document.querySelectorAll('img[data-src]');
        images.forEach(img => {
            img.src = img.dataset.src;
            img.addEventListener('load', function() {
                this.classList.add('loaded');
            });
        });
    } else {
        // Fallback for browsers that don't support lazy loading
        const script = document.createElement('script');
        script.src = 'https://cdn.jsdelivr.net/npm/lazysizes@5/lazysizes.min.js';
        document.body.appendChild(script);
    }

    // Reading progress bar
    const readingProgress = document.createElement('div');
    readingProgress.className = 'reading-progress';
    document.body.appendChild(readingProgress);

    function updateReadingProgress() {
        const windowHeight = window.innerHeight;
        const documentHeight = document.documentElement.scrollHeight;
        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        const scrollableHeight = documentHeight - windowHeight;
        const progress = (scrollTop / scrollableHeight) * 100;
        readingProgress.style.width = Math.min(progress, 100) + '%';
    }

    window.addEventListener('scroll', updateReadingProgress);
    window.addEventListener('resize', updateReadingProgress);
    updateReadingProgress();

    // Copy link functionality
    document.querySelectorAll('[onclick*="navigator.clipboard"]').forEach(btn => {
        btn.addEventListener('click', function() {
            const url = this.getAttribute('data-url') || window.location.href;
            if (navigator.clipboard) {
                navigator.clipboard.writeText(url).then(() => {
                    if (typeof showToast !== 'undefined') {
                        showToast('لینک کپی شد!', 'success');
                    }
                });
            }
        });
    });
}

// Login Modal Initialization
function initLoginModal() {
    // Animate modal on show
    const loginModal = document.getElementById('loginModal');
    if (loginModal) {
        loginModal.addEventListener('show.bs.modal', function() {
            const modalContent = this.querySelector('.login-modal-content');
            if (modalContent) {
                modalContent.style.opacity = '0';
                modalContent.style.transform = 'scale(0.9) translateY(20px)';
                
                setTimeout(() => {
                    modalContent.style.transition = 'all 0.3s cubic-bezier(0.4, 0, 0.2, 1)';
                    modalContent.style.opacity = '1';
                    modalContent.style.transform = 'scale(1) translateY(0)';
                }, 10);
            }
        });

        // Reset form on hide
        loginModal.addEventListener('hidden.bs.modal', function() {
            const form = this.querySelector('form');
            if (form) {
                form.reset();
            }
            
            // Reset OTP inputs
            const otpInputs = this.querySelectorAll('.otp-input');
            otpInputs.forEach(input => {
                input.value = '';
            });
        });
    }

    // Auto-focus mobile input when modal opens
    document.addEventListener('livewire:init', () => {
        if (typeof Livewire !== 'undefined') {
            Livewire.on('loginModalOpened', () => {
                setTimeout(() => {
                    const mobileInput = document.getElementById('mobile-input');
                    if (mobileInput) {
                        mobileInput.focus();
                    }
                }, 300);
            });
        }
    });

    // OTP Input Auto-focus and Paste Support
    document.addEventListener('DOMContentLoaded', function() {
        const otpContainer = document.querySelector('.otp-input-container');
        if (otpContainer) {
            const inputs = otpContainer.querySelectorAll('.otp-input');
            
            // Handle paste
            otpContainer.addEventListener('paste', function(e) {
                e.preventDefault();
                const pastedData = (e.clipboardData || window.clipboardData).getData('text').replace(/[^0-9]/g, '');
                
                if (pastedData.length === 4) {
                    inputs.forEach((input, index) => {
                        if (pastedData[index]) {
                            input.value = pastedData[index];
                            input.dispatchEvent(new Event('input', { bubbles: true }));
                        }
                    });
                    inputs[3]?.focus();
                }
            });

            // Auto-focus next on input
            inputs.forEach((input, index) => {
                input.addEventListener('input', function(e) {
                    const value = e.target.value.replace(/[^0-9]/g, '');
                if (value && index < inputs.length - 1) {
                    inputs[index + 1]?.focus();
                } else if (value && index === inputs.length - 1) {
                    // Last input filled, trigger submit if all filled
                    const allFilled = Array.from(inputs).every(input => input.value);
                    if (allFilled) {
                        updateOtp();
                    }
                }
                });

                input.addEventListener('keydown', function(e) {
                    if (e.key === 'Backspace' && !e.target.value && index > 0) {
                        inputs[index - 1]?.focus();
                    }
                });
            });
        }
    });

    // Mobile input formatting
    const mobileInput = document.getElementById('mobile-input');
    if (mobileInput) {
        mobileInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/[^0-9]/g, '');
            if (value.length > 11) {
                value = value.substring(0, 11);
            }
            e.target.value = value;
            
            // Add glow effect when valid
            if (value.length === 11 && /^09\d{9}$/.test(value)) {
                e.target.style.boxShadow = '0 0 15px rgba(57, 255, 20, 0.3)';
            } else {
                e.target.style.boxShadow = '';
            }
        });
    }
}
