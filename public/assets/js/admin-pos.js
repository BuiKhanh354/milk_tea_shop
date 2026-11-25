// public/assets/js/admin-pos.js — Điều khiển giao diện bán hàng tại quầy (POS)
let cart = {};
let currentProduct = null;

function filterProducts() {
    const keyword = (document.getElementById('searchInput')?.value || '').toLowerCase();
    document.querySelectorAll('.product-card').forEach(card => {
        const name = card.dataset.name || '';
        card.style.display = name.includes(keyword) ? '' : 'none';
    });
}

function openOptionsModal(id, name, price) {
    currentProduct = { id, name, price };
    document.getElementById('modalProductName').innerText = name;

    const firstSize = document.querySelectorAll('.size-radio')[0];
    if (firstSize) firstSize.checked = true;
    document.getElementById('tempSelect').value = 'cold';
    document.getElementById('sugarSelect').value = '100';
    document.getElementById('iceSelect').value = '100';
    document.getElementById('iceWrapper').classList.remove('d-none');
    document.querySelectorAll('.topping-checkbox').forEach(cb => { cb.checked = false; });

    const modal = new bootstrap.Modal(document.getElementById('productOptionsModal'));
    modal.show();
}

function toggleIceOption(temp) {
    const iceWrapper = document.getElementById('iceWrapper');
    if (!iceWrapper) return;
    if (temp === 'hot') {
        iceWrapper.classList.add('d-none');
    } else {
        iceWrapper.classList.remove('d-none');
    }
}

function addCustomizedProduct() {
    const selectedSize = document.querySelector('.size-radio:checked');
    const sizeId = selectedSize ? parseInt(selectedSize.value) : 1;
    const sizeName = selectedSize ? selectedSize.dataset.name : 'M';
    const sizePrice = selectedSize ? parseFloat(selectedSize.dataset.price) : 0;

    const tempVal = document.getElementById('tempSelect').value;
    const sugarVal = parseInt(document.getElementById('sugarSelect').value) || 100;
    const iceVal = (tempVal === 'hot') ? 0 : (parseInt(document.getElementById('iceSelect').value) || 100);

    const toppings = [];
    let toppingPrice = 0;
    document.querySelectorAll('.topping-checkbox:checked').forEach(cb => {
        toppings.push({ id: cb.value, name: cb.dataset.name, price: parseFloat(cb.dataset.price) || 0 });
        toppingPrice += parseFloat(cb.dataset.price) || 0;
    });

    const finalPrice = currentProduct.price + sizePrice + toppingPrice;
    const toppingIdsStr = toppings.map(t => t.id).join('-');
    const cartKey = `${currentProduct.id}_${sizeId}_${tempVal}_${sugarVal}_${iceVal}_${toppingIdsStr}`;

    if (cart[cartKey]) {
        cart[cartKey].quantity++;
    } else {
        cart[cartKey] = {
            id: currentProduct.id,
            product_id: currentProduct.id,
            name: currentProduct.name,
            price: finalPrice,
            quantity: 1,
            size_id: sizeId,
            size_name: sizeName,
            temperature: tempVal,
            sugar_level: sugarVal,
            ice_level: iceVal,
            toppings: toppings
        };
    }

    const modal = bootstrap.Modal.getInstance(document.getElementById('productOptionsModal'));
    if (modal) modal.hide();
    renderCart();
}

function updateQty(cartKey, delta) {
    if (!cart[cartKey]) return;
    cart[cartKey].quantity += delta;
    if (cart[cartKey].quantity <= 0) {
        delete cart[cartKey];
    }
    renderCart();
}

function renderCart() {
    const cartEl = document.getElementById('cartItemsList');
    const emptyMsg = document.getElementById('emptyCartMsg');
    if (!cartEl || !emptyMsg) return;

    let html = '';
    let total = 0;
    let count = 0;

    for (const cartKey in cart) {
        const item = cart[cartKey];
        total += item.price * item.quantity;
        count++;

        const tempText = item.temperature === 'hot' ? 'Nóng' : 'Lạnh';
        const iceText = item.temperature === 'hot' ? '0% đá' : `${item.ice_level}% đá`;
        let optionsText = `Size ${item.size_name} | ${tempText} | ${item.sugar_level}% đường | ${iceText}`;
        if (item.toppings.length > 0) {
            optionsText += ` + ${item.toppings.length} Topping`;
        }

        html += `
            <div class="d-flex justify-content-between align-items-center mb-2 p-2 border rounded-3 bg-light">
                <div class="text-truncate me-2" style="max-width: 170px;">
                    <div class="fw-medium small">${item.name}</div>
                    <div class="text-muted small text-truncate" style="font-size: 0.75rem;">${optionsText}</div>
                    <div class="text-forest small fw-bold">${new Intl.NumberFormat('vi-VN').format(item.price)}đ</div>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <button type="button" class="btn btn-sm btn-outline-secondary px-2 py-0" onclick="updateQty('${cartKey}', -1)">-</button>
                    <span class="fw-bold" style="min-width: 16px; text-align: center;">${item.quantity}</span>
                    <button type="button" class="btn btn-sm btn-outline-secondary px-2 py-0" onclick="updateQty('${cartKey}', 1)">+</button>
                </div>
            </div>
        `;
    }

    if (count > 0) {
        emptyMsg.style.display = 'none';
        cartEl.innerHTML = html;
    } else {
        cartEl.innerHTML = '';
        emptyMsg.style.display = 'block';
    }

    document.getElementById('totalAmount').innerText = new Intl.NumberFormat('vi-VN').format(total) + 'đ';
}

function toggleTableSelect(type) {
    const wrapper = document.getElementById('tableSelectWrapper');
    if (!wrapper) return;
    if (type === 'dine_in') {
        wrapper.classList.remove('d-none');
    } else {
        wrapper.classList.add('d-none');
    }
}

function submitOrder() {
    const items = [];
    for (const id in cart) {
        items.push({
            product_id: cart[id].product_id || cart[id].id,
            price: cart[id].price,
            quantity: cart[id].quantity,
            size_id: cart[id].size_id,
            temperature: cart[id].temperature,
            sugar_level: cart[id].sugar_level,
            ice_level: cart[id].ice_level,
            toppings: cart[id].toppings
        });
    }

    if (items.length === 0) {
        alert('Giỏ hàng trống! Vui lòng chọn ít nhất một sản phẩm.');
        return;
    }

    document.getElementById('itemsInput').value = JSON.stringify(items);
    document.getElementById('checkoutForm').submit();
}
