// Global Toast & SweetAlert System for GroohBaz

// Bootstrap Toast Container
if (!document.getElementById('toast-container')) {
    const toastContainer = document.createElement('div');
    toastContainer.id = 'toast-container';
    toastContainer.className = 'toast-container position-fixed top-0 end-0 p-3';
    toastContainer.style.zIndex = '9999';
    document.body.appendChild(toastContainer);
}

// Show Bootstrap Toast
window.showToast = function(message, type = 'info') {
    const toastContainer = document.getElementById('toast-container');
    const toastId = 'toast-' + Date.now();
    
    const bgClass = {
        'success': 'bg-success',
        'error': 'bg-danger',
        'warning': 'bg-warning',
        'info': 'bg-info'
    }[type] || 'bg-info';
    
    // Translate type to Persian
    const typeTranslations = {
        'success': 'موفقیت',
        'error': 'خطا',
        'warning': 'هشدار',
        'info': 'اطلاعات'
    };
    
    const typeLabel = typeTranslations[type] || typeTranslations['info'];
    
    const toastHtml = `
        <div id="${toastId}" class="toast ${bgClass} text-white" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header ${bgClass} text-white">
                <strong class="me-auto">${typeLabel}</strong>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="بستن"></button>
            </div>
            <div class="toast-body">
                ${message}
            </div>
        </div>
    `;
    
    toastContainer.insertAdjacentHTML('beforeend', toastHtml);
    const toastElement = document.getElementById(toastId);
    const toast = new bootstrap.Toast(toastElement, { autohide: true, delay: 5000 });
    toast.show();
    
    toastElement.addEventListener('hidden.bs.toast', () => {
        toastElement.remove();
    });
};

// Show SweetAlert
window.showSwal = function(options) {
    if (typeof Swal === 'undefined') {
        console.error('SweetAlert2 is not loaded');
        return;
    }
    
    const defaultOptions = {
        confirmButtonText: 'تأیید',
        cancelButtonText: 'انصراف',
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33'
    };
    
    return Swal.fire({ ...defaultOptions, ...options });
};

// Livewire Event Listeners
document.addEventListener('livewire:init', () => {
    Livewire.on('showToast', (data) => {
        window.showToast(data[0].message, data[0].type || 'info');
    });
    
    Livewire.on('showSwal', (data) => {
        window.showSwal(data[0]);
    });
});

// Browser Event Listeners
window.addEventListener('show-toast', (event) => {
    window.showToast(event.detail.message, event.detail.type || 'info');
});

window.addEventListener('show-swal', (event) => {
    window.showSwal(event.detail);
});







