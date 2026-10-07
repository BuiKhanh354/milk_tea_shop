<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="font-serif fw-bold text-dark mb-0">
        <a href="admin.php?route=orders" class="text-muted me-2"><i class="fa-solid fa-arrow-left"></i></a> 
        Tạo đơn hàng mới (POS)
    </h4>
</div>

<?php if (isset($_GET['error'])): ?>
<div class="alert alert-danger">
    Vui lòng kiểm tra lại thông tin đơn hàng. Giỏ hàng không được để trống.
</div>
<?php endif; ?>

<div class="row g-4">
    <!-- Left: Product List -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-body p-4">
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <input type="text" id="searchInput" class="form-control bg-light border-0" placeholder="Tìm tên sản phẩm..." onkeyup="filterProducts()">
                    </div>
                </div>
                
                <div class="row g-3 admin-style-d31d38" id="productGrid" >
                    <?php foreach ($products as $p): ?>
                    <div class="col-md-4 col-sm-6 product-card" data-name="<?= strtolower($p['name']) ?>">
                        <div class="card h-100 border rounded-3 shadow-none text-center p-3 admin-style-58aba5"  onclick="openOptionsModal(<?= $p['id'] ?>, '<?= htmlspecialchars($p['name'], ENT_QUOTES) ?>', <?= $p['price'] ?>)">
                            <img src="<?= $p['image'] ?>" class="img-fluid rounded-3 mb-2 mx-auto" style="height: 100px; object-fit: cover;" alt="<?= $p['name'] ?>">
                            <h6 class="fw-bold mb-1 text-truncate" title="<?= $p['name'] ?>"><?= $p['name'] ?></h6>
                            <div class="text-forest fw-medium"><?= number_format($p['price'], 0, ',', '.') ?>đ</div>
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
                <h5 class="fw-bold mb-3 border-bottom pb-2">Giỏ hàng</h5>
                
                <div class="flex-grow-1 overflow-y-auto mb-3 admin-style-a0b01d" >
                    <div id="cartItemsList"></div>
                    <div class="text-center text-muted mt-5" id="emptyCartMsg">Chưa có sản phẩm nào</div>
                </div>

                <div class="border-top pt-3 mb-3">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Tổng cộng:</span>
                        <span class="fw-bold fs-5 text-forest" id="totalAmount">0đ</span>
                    </div>
                </div>

                <form action="admin.php?route=orders&action=store" method="POST" id="checkoutForm">
                    <input type="hidden" name="items" id="itemsInput" value="[]">
                    
                    <div class="mb-3">
                        <input type="text" name="customer_name" class="form-control bg-light border-0" placeholder="Tên khách hàng (Mặc định: Khách lẻ)">
                    </div>
                    
                    <?php 
                        $selectedTable = isset($_GET['table_id']) ? (int)$_GET['table_id'] : 0; 
                    ?>
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <select name="order_type" id="orderTypeSelect" class="form-select bg-light border-0" onchange="toggleTableSelect(this.value)">
                                <option value="takeaway" <?= $selectedTable == 0 ? 'selected' : '' ?>>Mang đi (Takeaway)</option>
                                <option value="dine_in" <?= $selectedTable > 0 ? 'selected' : '' ?>>Tại quán (Dine-in)</option>
                                <option value="delivery">Giao hàng (Delivery)</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <select name="payment_method" class="form-select bg-light border-0">
                                <option value="cash">Tiền mặt</option>
                                <option value="bank_transfer">Chuyển khoản</option>
                                <option value="momo">Ví MoMo</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3 <?= $selectedTable > 0 ? '' : 'd-none' ?>" id="tableSelectWrapper">
                        <select name="table_id" class="form-select bg-light border-0">
                            <option value="0">-- Chọn bàn --</option>
                            <?php foreach($tables as $t): ?>
                                <?php if($t['status'] === 'available' || $t['id'] == $selectedTable): ?>
                                    <option value="<?= $t['id'] ?>" <?= $t['id'] == $selectedTable ? 'selected' : '' ?>>Bàn <?= $t['table_number'] ?> (Sức chứa: <?= $t['capacity'] ?>)</option>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <button type="button" class="btn btn-forest w-100 py-2 fw-medium rounded-3" onclick="submitOrder()">
                        TẠO ĐƠN
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Chọn Size & Topping -->
<div class="modal fade" id="productOptionsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title fw-bold" id="modalProductName">Tên sản phẩm</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-forest fw-bold fs-5 mb-3" id="modalProductPrice">0đ</div>
                
                <h6 class="fw-bold mb-2">Chọn Size</h6>
                <select id="sizeSelect" class="form-select bg-light border-0 mb-4">
                    <?php foreach ($sizes as $s): ?>
                        <option value="<?= $s['id'] ?>" data-name="<?= $s['name'] ?>" data-price="<?= $s['extra_price'] ?>">
                            <?= $s['name'] ?> 
                            <?= $s['extra_price'] > 0 ? '(+ ' . number_format($s['extra_price'], 0, ',', '.') . 'đ)' : '' ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <h6 class="fw-bold mb-2">Thêm Topping</h6>
                <div class="row g-2 mb-3">
                    <?php foreach ($toppings as $t): ?>
                    <div class="col-6">
                        <div class="form-check p-2 border rounded bg-light m-0">
                            <input class="form-check-input ms-1 topping-checkbox" type="checkbox" value="<?= $t['id'] ?>" data-name="<?= $t['name'] ?>" data-price="<?= $t['price'] ?>" id="top_<?= $t['id'] ?>">
                            <label class="form-check-label w-100 ms-2 admin-style-58aba5"  for="top_<?= $t['id'] ?>">
                                <span class="d-block small fw-medium"><?= $t['name'] ?></span>
                                <span class="d-block small text-forest">+<?= number_format($t['price'], 0, ',', '.') ?>đ</span>
                            </label>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="modal-footer border-top-0 pt-0">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Hủy</button>
                <button type="button" class="btn btn-forest px-4" onclick="addWithOptionsToCart()">Thêm vào giỏ</button>
            </div>
        </div>
    </div>
</div>

<script>
    let cart = {};

    function filterProducts() {
        const query = document.getElementById('searchInput').value.toLowerCase();
        document.querySelectorAll('.product-card').forEach(card => {
            const name = card.getAttribute('data-name');
            card.style.display = name.includes(query) ? '' : 'none';
        });
    }

    let currentProduct = null;

    function openOptionsModal(id, name, basePrice) {
        currentProduct = { id, name, price: basePrice };
        
        document.getElementById('modalProductName').innerText = name;
        document.getElementById('modalProductPrice').innerText = new Intl.NumberFormat('vi-VN').format(basePrice) + 'đ';
        
        // Reset form
        document.getElementById('sizeSelect').selectedIndex = 0;
        document.querySelectorAll('.topping-checkbox').forEach(cb => cb.checked = false);
        
        const modal = new bootstrap.Modal(document.getElementById('productOptionsModal'));
        modal.show();
    }

    function addWithOptionsToCart() {
        if (!currentProduct) return;
        
        const sizeSelect = document.getElementById('sizeSelect');
        const sizeId = sizeSelect.value;
        const sizeName = sizeSelect.options[sizeSelect.selectedIndex].dataset.name;
        const sizePrice = parseFloat(sizeSelect.options[sizeSelect.selectedIndex].dataset.price) || 0;
        
        const toppings = [];
        let toppingPrice = 0;
        document.querySelectorAll('.topping-checkbox:checked').forEach(cb => {
            toppings.push({ id: cb.value, name: cb.dataset.name, price: parseFloat(cb.dataset.price) || 0 });
            toppingPrice += parseFloat(cb.dataset.price) || 0;
        });
        
        const finalPrice = currentProduct.price + sizePrice + toppingPrice;
        const toppingIdsStr = toppings.map(t => t.id).join('-');
        const cartKey = `${currentProduct.id}_${sizeId}_${toppingIdsStr}`;
        
        if (cart[cartKey]) {
            cart[cartKey].quantity++;
        } else {
            cart[cartKey] = { 
                id: currentProduct.id, 
                name: currentProduct.name, 
                price: finalPrice, 
                quantity: 1, 
                size_id: sizeId,
                size_name: sizeName, 
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
            
            let optionsText = `Size ${item.size_name}`;
            if (item.toppings.length > 0) {
                optionsText += ` + ${item.toppings.length} Topping`;
            }

            html += `
                <div class="d-flex justify-content-between align-items-center mb-2 p-2 border rounded-2 bg-light">
                    <div class="text-truncate me-2 admin-style-7ecc66" >
                        <div class="fw-medium small">${item.name}</div>
                        <div class="text-muted small admin-style-198265" >${optionsText}</div>
                        <div class="text-forest small">${new Intl.NumberFormat('vi-VN').format(item.price)}đ</div>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <button type="button" class="btn btn-sm btn-outline-secondary px-2 py-0" onclick="updateQty('${cartKey}', -1)">-</button>
                        <span class="fw-bold admin-style-193ae6" >${item.quantity}</span>
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
                product_id: cart[id].id,
                price: cart[id].price,
                quantity: cart[id].quantity,
                size_id: cart[id].size_id,
                toppings: cart[id].toppings
            });
        }
        
        if (items.length === 0) {
            alert('Giỏ hàng trống!');
            return;
        }

        document.getElementById('itemsInput').value = JSON.stringify(items);
        document.getElementById('checkoutForm').submit();
    }
</script>
