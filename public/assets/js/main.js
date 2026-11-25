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

// 4. Giỏ hàng (pages/cart/index.php)
function updateCartItem(cartId, change) {
    const input = document.getElementById('qty-' + cartId);
    if (!input) return;
    let qty = parseInt(input.value) + change;
    if (qty < 1) qty = 1;

    const formData = new FormData();
    const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    if (csrf) formData.append('csrf_token', csrf);
    formData.append('cart_id', cartId);
    formData.append('item_key', cartId);
    formData.append('quantity', qty);

    fetch('index.php?route=cart&action=update', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) location.reload();
    })
    .catch(() => location.reload());
}

function removeCartItem(cartId) {
    if (!confirm('Bạn có chắc chắn muốn xóa sản phẩm này?')) return;

    const formData = new FormData();
    const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    if (csrf) formData.append('csrf_token', csrf);
    formData.append('cart_id', cartId);
    formData.append('item_key', cartId);

    fetch('index.php?route=cart&action=remove', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) location.reload();
    })
    .catch(() => location.reload());
}

// 5. Danh sách sản phẩm & Yêu thích (pages/products/index.php, pages/yeuthich/index.php)
function toggleFavoriteList(event, productId, el) {
    event.preventDefault();
    event.stopPropagation();

    const container = document.getElementById('products-grid-container');
    const toggleUrl = container?.dataset.toggleUrl || 'index.php?page=yeuthich&action=toggle';
    const loginUrl = container?.dataset.loginUrl || 'index.php?page=login';

    fetch(toggleUrl, {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: new URLSearchParams({product_id: productId})
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'unauthorized') {
            alert('Vui lòng đăng nhập để lưu sản phẩm yêu thích.');
            window.location.href = loginUrl;
            return;
        }
        if (data.status === 'success') {
            const icon = el.querySelector('i');
            if (data.action === 'added') {
                icon.classList.remove('fa-regular');
                icon.classList.add('fa-solid', 'text-danger');
            } else {
                icon.classList.remove('fa-solid', 'text-danger');
                icon.classList.add('fa-regular');
            }
        }
    }).catch(() => alert('Không thể cập nhật danh sách yêu thích. Vui lòng thử lại.'));
}

function toggleFavorite(productId) {
    const container = document.getElementById('favorites-grid');
    const toggleUrl = container?.dataset.toggleUrl || 'index.php?page=yeuthich&action=toggle';
    const loginUrl = container?.dataset.loginUrl || 'index.php?page=login';

    fetch(toggleUrl, {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: 'product_id=' + productId
    })
    .then(response => response.json())
    .then(data => {
        if (data.success && !data.is_favorited) {
            const el = document.getElementById('favorite-' + productId);
            if (el) el.remove();
            if (document.querySelectorAll('.col[id^="favorite-"]').length === 0) {
                location.reload();
            }
        } else if (data.require_login) {
            alert(data.message);
            window.location.href = loginUrl;
        }
    });
}

// 6. Đổi mật khẩu (pages/profile/password.php)
function togglePasswordVisibility(fieldId, btn) {
    const input = document.getElementById(fieldId);
    const icon = btn.querySelector('i');
    if (!input || !icon) return;
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}

document.addEventListener('DOMContentLoaded', function() {
    // Lọc danh mục sản phẩm (pages/products/index.php)
    const categoryLinks = document.querySelectorAll('#category-filter .category-link');
    const categoryInput = document.getElementById('category_input');
    const filterForm = document.getElementById('filter-form');
    if (categoryLinks.length > 0 && categoryInput && filterForm) {
        categoryLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                categoryInput.value = this.getAttribute('data-filter');
                filterForm.submit();
            });
        });
    }

    // Lọc cửa hàng (pages/stores/index.php)
    const searchInput = document.getElementById('store-search-input');
    const statusSelect = document.getElementById('store-status-select');
    const storeCards = document.querySelectorAll('.store-card');
    const storeCount = document.getElementById('store-count');
    if (searchInput && statusSelect && storeCards.length > 0) {
        function filterStores() {
            const query = searchInput.value.trim().toLowerCase();
            const status = statusSelect.value;
            let visibleCount = 0;

            storeCards.forEach(card => {
                const name = (card.getAttribute('data-name') || '').toLowerCase();
                const cardStatus = card.getAttribute('data-status');
                const matchesQuery = query === '' || name.includes(query);
                const matchesStatus = status === 'all' || cardStatus === status;

                if (matchesQuery && matchesStatus) {
                    card.style.display = 'block';
                    visibleCount++;
                } else {
                    card.style.display = 'none';
                }
            });

            if (storeCount) {
                storeCount.textContent = `${visibleCount} cửa hàng`;
            }
        }
        searchInput.addEventListener('input', filterStores);
        statusSelect.addEventListener('change', filterStores);
    }
});
