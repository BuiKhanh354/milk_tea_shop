// 1. Intersection Observer for fade-up animations
const fadeElements = document.querySelectorAll('.fade-up');

const observerOptions = {
    root: null,
    rootMargin: '0px',
    threshold: 0.15
};

const observer = new IntersectionObserver((entries, observer) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.classList.add('visible');
            observer.unobserve(entry.target);
        }
    });
}, observerOptions);

fadeElements.forEach(el => {
    observer.observe(el);
});

// 2. Navbar shadow on scroll
const navbar = document.querySelector('.vaa-header');

if (navbar) {
    window.addEventListener('scroll', () => {
        if (window.scrollY > 10) {
            navbar.style.boxShadow = '0 2px 10px rgba(0,0,0,0.05)';
        } else {
            navbar.style.boxShadow = 'none';
        }
    });
}

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
