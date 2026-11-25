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

// 4. Quản lý Danh mục (admin/pages/categories/index.php)
function fillEditModal(id, name, desc) {
    const idEl = document.getElementById('edit_cat_id');
    const nameEl = document.getElementById('edit_cat_name');
    const descEl = document.getElementById('edit_cat_desc');
    if (idEl) idEl.value = id;
    if (nameEl) nameEl.value = name;
    if (descEl) descEl.value = desc;
}

// 5. Nhập / Xuất kho (admin/pages/inventory/import.php & export.php)
let currentMaxQty = 0;
function updateUnit() {
    const select = document.getElementById('inventory_id');
    const display = document.getElementById('unit_display');
    if (!select || !display) return;
    if (select.selectedIndex > 0) {
        const option = select.options[select.selectedIndex];
        display.textContent = option.getAttribute('data-unit') || '--';
        const maxAttr = option.getAttribute('data-qty');
        if (maxAttr !== null) {
            currentMaxQty = parseFloat(maxAttr) || 0;
            validateQuantity();
        }
    } else {
        display.textContent = '--';
        currentMaxQty = 0;
    }
}

function validateQuantity() {
    const qtyInput = document.getElementById('quantity');
    const errorMsg = document.getElementById('qty_error');
    const btnSubmit = document.getElementById('btnSubmit');
    if (!qtyInput || !errorMsg || !btnSubmit) return;
    const val = parseFloat(qtyInput.value || 0);

    if (val > currentMaxQty) {
        errorMsg.style.display = 'block';
        qtyInput.classList.add('is-invalid');
        btnSubmit.disabled = true;
    } else {
        errorMsg.style.display = 'none';
        qtyInput.classList.remove('is-invalid');
        btnSubmit.disabled = false;
    }
}

// 6. Tùy chọn đường / đá (admin/pages/options/index.php)
function openOptionModal(type, id = '', name = '', value = '', isDefault = false, status = true) {
    const form = document.getElementById('optionForm');
    if (!form) return;
    form.action = id ? (form.dataset.updateUrl || '') : (form.dataset.storeUrl || '');
    document.getElementById('optionModalTitle').innerText = id ? 'Sửa tùy chọn' : 'Thêm tùy chọn';
    document.getElementById('option_id').value = id;
    document.getElementById('option_type').value = type;
    document.getElementById('option_name').value = name;
    document.getElementById('option_value').value = value;
    document.getElementById('option_is_default').checked = isDefault;
    document.getElementById('option_status').checked = status;
}

// 7. Công thức nguyên liệu sản phẩm (admin/pages/product_ingredients/edit.php)
function updateRowUnit(select) {
    const row = select.closest('.ingredient-row');
    const label = row ? row.querySelector('.unit-label') : null;
    if (!label) return;
    if (select.selectedIndex > 0) {
        label.textContent = select.options[select.selectedIndex].getAttribute('data-unit') || '--';
    } else {
        label.textContent = '--';
    }
}

function addIngredientRow(sizeId) {
    const container = document.getElementById('list-size-' + sizeId);
    const template = document.getElementById('ingredientRowTemplate');
    if (!container || !template) return;
    const clone = template.content.cloneNode(true);
    const select = clone.querySelector('select');
    const input = clone.querySelector('input');
    select.name = `recipes[${sizeId}][ingredient_id][]`;
    input.name = `recipes[${sizeId}][quantity][]`;
    container.appendChild(clone);
}

// 8. Mở / Kết ca làm việc (admin/pages/shift_sessions/open.php & close.php)
function calcCashDiff(input) {
    let rawVal = input.value.replace(/[^0-9]/g, '');
    if (rawVal === '') rawVal = '0';
    const numVal = parseInt(rawVal);
    input.value = new Intl.NumberFormat('vi-VN').format(numVal);
}

function formatCurrencyInput(input) {
    const value = input.value.replace(/[^0-9]/g, '');
    if (value === '') { input.value = '0'; return; }
    input.value = new Intl.NumberFormat('vi-VN').format(parseInt(value));
}

function setCash(amount) {
    const input = document.getElementById('initialCashInput');
    if (input) {
        input.value = new Intl.NumberFormat('vi-VN').format(amount);
    }
}

// 9. Quản lý Topping & Kích cỡ (admin/pages/toppings/index.php)
function openToppingModal(id = '', name = '', price = '', status = true) {
    const form = document.getElementById('toppingForm');
    if (!form) return;
    form.action = id ? (form.dataset.updateUrl || '') : (form.dataset.storeUrl || '');
    document.getElementById('toppingModalTitle').innerText = id ? 'Sửa Topping' : 'Thêm Topping';
    document.getElementById('topping_id').value = id;
    document.getElementById('topping_name').value = name;
    document.getElementById('topping_price').value = price;
    document.getElementById('topping_status').checked = status;
}

function openSizeModal(id = '', name = '', extra_price = '', status = true) {
    const form = document.getElementById('sizeForm');
    if (!form) return;
    form.action = id ? (form.dataset.updateUrl || '') : (form.dataset.storeUrl || '');
    document.getElementById('sizeModalTitle').innerText = id ? 'Sửa Size' : 'Thêm Size';
    document.getElementById('size_id').value = id;
    document.getElementById('size_name').value = name;
    document.getElementById('size_extra_price').value = extra_price;
    document.getElementById('size_status').checked = status;
}

// 10. Khởi tạo các sự kiện tự động theo trang
document.addEventListener('DOMContentLoaded', () => {
    // Biểu đồ doanh thu Dashboard (admin/pages/dashboard/index.php)
    const revCtx = document.getElementById('revenueChart');
    if (revCtx && typeof Chart !== 'undefined') {
        new Chart(revCtx, {
            type: 'line',
            data: {
                labels: ['T2', 'T3', 'T4', 'T5', 'T6', 'T7', 'CN'],
                datasets: [{
                    label: 'Doanh thu (Triệu VNĐ)',
                    data: [15, 12, 19, 14, 22, 28, 35],
                    borderColor: '#263A30',
                    backgroundColor: 'rgba(38, 58, 48, 0.1)',
                    borderWidth: 2,
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, grid: { borderDash: [2, 4], color: '#f0f0f0' } },
                    x: { grid: { display: false } }
                }
            }
        });
    }

    // Kiểm tra số lượng xuất kho (admin/pages/inventory/export.php)
    const exportQtyInput = document.getElementById('quantity');
    if (exportQtyInput && document.getElementById('qty_error')) {
        exportQtyInput.addEventListener('input', validateQuantity);
    }

    // Tính chênh lệch kiểm kê kho realtime (admin/pages/inventory_audits/create.php)
    document.querySelectorAll('.audit-actual-input').forEach(function (input) {
        input.addEventListener('input', function () {
            const sysQty = parseFloat(this.dataset.system || '0');
            const actualQty = parseFloat(this.value || '0');
            const diff = actualQty - sysQty;
            const target = document.getElementById(this.dataset.target);
            if (!target) return;

            target.textContent = (diff > 0 ? '+' : '') + diff.toFixed(2);
            target.classList.remove('text-danger', 'text-success');
            if (diff < -0.001) {
                target.classList.add('text-danger');
            } else if (diff > 0.001) {
                target.classList.add('text-success');
            }
        });
    });
});
