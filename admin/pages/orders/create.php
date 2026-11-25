<div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
    <h4 class="font-serif fw-bold text-dark mb-0">
        <?php $posBackUrl = (isset($_SESSION['role']) && $_SESSION['role'] === 'staff') ? admin_url('dashboard') : admin_url('orders'); ?>
        <a href="<?= e($posBackUrl) ?>" class="text-muted me-2"><i class="fa-solid fa-arrow-left"></i></a> 
        Tạo đơn hàng mới (POS)
    </h4>
    <div>
        <?php if ($posOpenSession): ?>
            <a href="<?= e(admin_url('shift_sessions', ['action' => 'close'])) ?>" class="btn btn-sm btn-outline-success rounded-pill px-3 py-1">
                <i class="fa-solid fa-cash-register me-1"></i> Ca: <b><?= e($posOpenSession['shift_name'] ?? $posOpenSession['session_code']) ?></b> (Kết ca)
            </a>
        <?php elseif ($posTodayShift): ?>
            <a href="<?= e(admin_url('shift_sessions', ['action' => 'open'])) ?>" class="btn btn-sm btn-outline-forest rounded-pill px-3 py-1 fw-medium">
                <i class="fa-solid fa-business-time me-1"></i> Vào ca hôm nay: <b><?= e($posTodayShift['shift_name']) ?></b> (Mở ca)
            </a>
        <?php else: ?>
            <a href="<?= e(admin_url('shift_sessions', ['action' => 'open'])) ?>" class="btn btn-sm btn-outline-warning text-dark rounded-pill px-3 py-1">
                <i class="fa-solid fa-lock-open me-1"></i> Chưa mở ca (Nhấn để Mở ca)
            </a>
        <?php endif; ?>
    </div>
</div>

<?php if ($errorMsg = get_flash('error')): ?>
<div class="alert alert-danger alert-dismissible fade show rounded-4 border-0 shadow-sm mb-3">
    <i class="fa-solid fa-triangle-exclamation me-2"></i> <?= e($errorMsg) ?>
    <button type="button" class="btn-close shadow-none" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<div class="row g-4">
    <!-- Left: Product List -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-body p-4">
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <input type="text" id="searchInput" class="form-control bg-light border-0 shadow-none" placeholder="Tìm tên sản phẩm..." onkeyup="filterProducts()">
                    </div>
                </div>
                
                <div class="row g-3" id="productGrid" style="max-height: 600px; overflow-y: auto;">
                    <?php foreach ($products as $p): ?>
                    <div class="col-md-4 col-sm-6 product-card" data-name="<?= strtolower(e($p['name'])) ?>">
                        <div class="card h-100 border rounded-3 shadow-none text-center p-3" style="cursor: pointer; transition: all 0.2s;" onmouseover="this.classList.add('shadow-sm')" onmouseout="this.classList.remove('shadow-sm')" onclick="openOptionsModal(<?= (int)$p['id'] ?>, '<?= e(addslashes($p['name'])) ?>', <?= (float)$p['price'] ?>)">
                            <img src="<?= e(product_image($p['image'])) ?>" class="img-fluid rounded-3 mb-2 mx-auto" style="height: 100px; width: 100px; object-fit: cover;" alt="<?= e($p['name']) ?>">
                            <h6 class="fw-bold mb-1 text-truncate" title="<?= e($p['name']) ?>"><?= e($p['name']) ?></h6>
                            <div class="text-forest fw-bold"><?= money($p['price']) ?></div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Right: Cart & Checkout -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-body p-4 d-flex flex-column">
                <h5 class="fw-bold mb-3 border-bottom pb-2 font-serif text-forest">Giỏ hàng</h5>
                
                <div class="flex-grow-1 overflow-y-auto mb-3" style="max-height: 350px; min-height: 200px;">
                    <div id="cartItemsList"></div>
                    <div class="text-center text-muted mt-5" id="emptyCartMsg">Chưa có sản phẩm nào</div>
                </div>

                <div class="border-top pt-3 mb-3">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Tổng cộng:</span>
                        <span class="fw-bold fs-5 text-forest font-monospace" id="totalAmount">0đ</span>
                    </div>
                </div>

                <form action="<?= e(admin_url('orders', ['action' => 'store'])) ?>" method="POST" id="checkoutForm">
                    <input type="hidden" name="items" id="itemsInput" value="[]">
                    
                    <div class="mb-3">
                        <input type="text" name="customer_name" class="form-control bg-light border-0 shadow-none" placeholder="Tên khách hàng (Mặc định: Khách lẻ)">
                    </div>
                    
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <select name="order_type" class="form-select bg-light border-0 shadow-none" onchange="toggleTableSelect(this.value)">
                                <option value="takeaway" <?= empty($preselectedTableId) ? 'selected' : '' ?>>Mang đi</option>
                                <option value="dine_in" <?= !empty($preselectedTableId) ? 'selected' : '' ?>>Tại quán</option>
                            </select>
                        </div>
                        <div class="col-6 <?= !empty($preselectedTableId) ? '' : 'd-none' ?>" id="tableSelectWrapper">
                            <select name="table_id" class="form-select bg-light border-0 shadow-none">
                                <option value="">-- Chọn bàn --</option>
                                <?php foreach ($tables as $t): ?>
                                    <option value="<?= (int)$t['id'] ?>" <?= (!empty($preselectedTableId) && (int)$preselectedTableId === (int)$t['id']) ? 'selected' : '' ?>>
                                        Bàn <?= e($t['table_number']) ?> <?= $t['status'] === 'occupied' ? '(Đang phục vụ)' : '' ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-6" id="paymentWrapper">
                            <select name="payment_method" class="form-select bg-light border-0 shadow-none">
                                <option value="cash">Tiền mặt</option>
                                <option value="bank_transfer">Chuyển khoản</option>
                                <option value="momo">Ví MoMo</option>
                            </select>
                        </div>
                    </div>

                    <button type="button" class="btn btn-forest w-100 py-3 rounded-pill fw-bold" onclick="submitOrder()">
                        <i class="fa-solid fa-receipt me-1"></i> TẠO ĐƠN HÀNG
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tùy Chọn Sản Phẩm (Options Modal) -->
<div class="modal fade" id="productOptionsModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow">
            <div class="modal-header border-bottom-0 pb-0 px-4 pt-4">
                <h5 class="modal-title font-serif fw-bold text-forest" id="modalProductName">Tên sản phẩm</h5>
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body px-4 py-3">
                <div class="mb-3">
                    <label class="form-label text-muted small fw-medium mb-1">Kích cỡ (Size)</label>
                    <div class="d-flex gap-2 flex-wrap">
                        <?php foreach ($sizes as $idx => $s): ?>
                        <div class="form-check form-check-inline m-0">
                            <input class="btn-check size-radio" type="radio" name="modal_size" id="size_<?= (int)$s['id'] ?>" value="<?= (int)$s['id'] ?>" data-name="<?= e($s['name']) ?>" data-price="<?= (float)$s['extra_price'] ?>" <?= $idx === 0 ? 'checked' : '' ?>>
                            <label class="btn btn-sm btn-outline-forest rounded-pill px-3" for="size_<?= (int)$s['id'] ?>">
                                Size <?= e($s['name']) ?> (+<?= money($s['extra_price']) ?>)
                            </label>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="row g-2 mb-3">
                    <div class="col-4">
                        <label class="form-label text-muted small fw-medium mb-1">Nhiệt độ</label>
                        <select id="tempSelect" class="form-select border-secondary border-opacity-25 shadow-none" onchange="toggleIceOption(this.value)">
                            <option value="cold">Lạnh</option>
                            <option value="hot">Nóng</option>
                        </select>
                    </div>
                    <div class="col-4">
                        <label class="form-label text-muted small fw-medium mb-1">Đường</label>
                        <select id="sugarSelect" class="form-select border-secondary border-opacity-25 shadow-none">
                            <option value="100">100%</option>
                            <option value="70">70%</option>
                            <option value="50">50%</option>
                            <option value="30">30%</option>
                            <option value="0">0%</option>
                        </select>
                    </div>
                    <div class="col-4" id="iceWrapper">
                        <label class="form-label text-muted small fw-medium mb-1">Đá</label>
                        <select id="iceSelect" class="form-select border-secondary border-opacity-25 shadow-none">
                            <option value="100">100%</option>
                            <option value="70">70%</option>
                            <option value="50">50%</option>
                            <option value="0">0%</option>
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label text-muted small fw-medium mb-1">Thêm Topping</label>
                    <div class="row g-2" style="max-height: 180px; overflow-y: auto;">
                        <?php foreach ($toppings as $top): ?>
                        <div class="col-6">
                            <div class="form-check p-2 border rounded-3">
                                <input class="form-check-input topping-checkbox ms-0 me-2" type="checkbox" id="top_<?= (int)$top['id'] ?>" value="<?= (int)$top['id'] ?>" data-name="<?= e($top['name']) ?>" data-price="<?= (float)$top['price'] ?>">
                                <label class="form-check-label small" for="top_<?= (int)$top['id'] ?>">
                                    <?= e($top['name']) ?> (+<?= money($top['price']) ?>)
                                </label>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-top-0 px-4 pb-4 pt-0">
                <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Hủy</button>
                <button type="button" class="btn btn-forest rounded-pill px-4" onclick="addCustomizedProduct()">
                    <i class="fa-solid fa-plus me-1"></i> Thêm vào giỏ
                </button>
            </div>
        </div>
    </div>
</div>

<script>
let cart = {};
let currentProduct = null;

function filterProducts() {
    const keyword = document.getElementById('searchInput').value.toLowerCase();
    document.querySelectorAll('.product-card').forEach(card => {
        const name = card.dataset.name;
        if (name.includes(keyword)) {
            card.style.display = '';
        } else {
            card.style.display = 'none';
        }
    });
}

function openOptionsModal(id, name, price) {
    currentProduct = { id, name, price };
    document.getElementById('modalProductName').innerText = name;
    
    // Reset inputs
    document.querySelectorAll('.size-radio')[0].checked = true;
    document.getElementById('tempSelect').value = 'cold';
    document.getElementById('sugarSelect').value = '100';
    document.getElementById('iceSelect').value = '100';
    document.getElementById('iceWrapper').classList.remove('d-none');
    document.querySelectorAll('.topping-checkbox').forEach(cb => cb.checked = false);

    const modal = new bootstrap.Modal(document.getElementById('productOptionsModal'));
    modal.show();
}

function toggleIceOption(temp) {
    const iceWrapper = document.getElementById('iceWrapper');
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
    modal.hide();
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
    let html = '';
    let total = 0;
    let count = 0;

    for (const cartKey in cart) {
        const item = cart[cartKey];
        total += item.price * item.quantity;
        count++;
        
        let tempText = item.temperature === 'hot' ? 'Nóng' : 'Lạnh';
        let iceText = item.temperature === 'hot' ? '0% đá' : `${item.ice_level}% đá`;
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
</script>

