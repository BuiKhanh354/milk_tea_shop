document.addEventListener('DOMContentLoaded', () => {
    // Tooltips initialization
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    if (tooltipTriggerList.length > 0) {
        const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));
    }
});

// 3. Tự động đính kèm CSRF Token vào tất cả các yêu cầu fetch POST/PUT/DELETE
if (typeof window.fetch === 'function') {
    const _originalFetch = window.fetch;
    window.fetch = function(url, options = {}) {
        const method = (options.method || 'GET').toUpperCase();
        if (['POST', 'PUT', 'DELETE', 'PATCH'].includes(method)) {
            const tokenMeta = document.querySelector('meta[name="csrf-token"]');
            const token = tokenMeta ? tokenMeta.getAttribute('content') : '';
            if (token) {
                options.headers = options.headers || {};
                if (options.headers instanceof Headers) {
                    if (!options.headers.has('X-CSRF-TOKEN')) options.headers.append('X-CSRF-TOKEN', token);
                } else if (Array.isArray(options.headers)) {
                    options.headers.push(['X-CSRF-TOKEN', token]);
                } else {
                    if (!options.headers['X-CSRF-TOKEN']) options.headers['X-CSRF-TOKEN'] = token;
                }
            }
        }
        return _originalFetch.call(this, url, options);
    };
}
