document.addEventListener('DOMContentLoaded', function() {
    // DOM Elements
    const basePriceEl = document.getElementById('base-price');
    const unitPriceEl = document.getElementById('unit-price');
    const totalPriceEl = document.getElementById('total-price');
    
    const sizeRadios = document.querySelectorAll('input[name="size"]');
    const toppingCheckboxes = document.querySelectorAll('input[name="toppings[]"]');
    
    const btnMinus = document.getElementById('btn-minus');
    const btnPlus = document.getElementById('btn-plus');
    const inputQty = document.getElementById('input-qty');
    
    const btnAddToCart = document.getElementById('btn-add-cart');
    const btnFavorite = document.getElementById('btn-favorite');
    const toastElement = document.getElementById('cart-toast');
    
    // Base Price value
    const basePrice = parseInt(basePriceEl.dataset.price);
    
    // Format Currency
    const formatCurrency = (amount) => {
        return amount.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".") + 'đ';
    };
    
    // Calculate Prices
    const calculatePrice = () => {
        let sizeExtra = 0;
        let toppingsExtra = 0;
        
        // Get selected size
        sizeRadios.forEach(radio => {
            if (radio.checked) {
                sizeExtra = parseInt(radio.dataset.price);
            }
        });
        
        // Get selected toppings
        toppingCheckboxes.forEach(cb => {
            if (cb.checked) {
                toppingsExtra += parseInt(cb.dataset.price);
                cb.closest('.topping-item').classList.add('selected');
            } else {
                cb.closest('.topping-item').classList.remove('selected');
            }
        });
        
        // Unit Price
        const unitPrice = basePrice + sizeExtra + toppingsExtra;
        unitPriceEl.textContent = formatCurrency(unitPrice);
        
        // Total Price
        const qty = parseInt(inputQty.value);
        const totalPrice = unitPrice * qty;
        totalPriceEl.textContent = formatCurrency(totalPrice);
    };

    // Stock constraint & badge update
    const stockBadgeContainer = document.getElementById('stock-badge-container');
    const btnBuyNow = document.getElementById('btn-buy-now');

    const updateStockDisplay = () => {
        const checkedSize = document.querySelector('input[name="size"]:checked');
        if (!checkedSize || !stockBadgeContainer) return;

        const stockAttr = checkedSize.dataset.stock;
        if (stockAttr === undefined || stockAttr === '') {
            // Chưa có công thức hoặc không giới hạn
            return;
        }

        const stock = parseInt(stockAttr);
        if (isNaN(stock)) return;

        if (stock <= 0) {
            stockBadgeContainer.innerHTML = '<span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-3 py-2 rounded-pill small fw-bold"><i class="fa-solid fa-ban me-1"></i> Tạm hết hàng</span>';
            if (btnAddToCart) {
                btnAddToCart.disabled = true;
                btnAddToCart.textContent = 'HẾT HÀNG';
                btnAddToCart.classList.add('opacity-50');
            }
            if (btnBuyNow) {
                btnBuyNow.disabled = true;
                btnBuyNow.textContent = 'HẾT HÀNG';
                btnBuyNow.classList.add('opacity-50');
            }
            inputQty.value = 0;
            inputQty.disabled = true;
        } else if (stock <= 10) {
            stockBadgeContainer.innerHTML = `<span class="badge bg-warning bg-opacity-15 text-dark border border-warning border-opacity-50 px-3 py-2 rounded-pill small fw-bold"> Sắp hết (Còn ${stock} ly)</span>`;
            if (btnAddToCart) {
                btnAddToCart.disabled = false;
                btnAddToCart.textContent = 'THÊM VÀO GIỎ';
                btnAddToCart.classList.remove('opacity-50');
            }
            if (btnBuyNow) {
                btnBuyNow.disabled = false;
                btnBuyNow.textContent = 'MUA NGAY';
                btnBuyNow.classList.remove('opacity-50');
            }
            inputQty.disabled = false;
            let currentQty = parseInt(inputQty.value);
            if (currentQty <= 0) inputQty.value = 1;
            if (currentQty > stock) inputQty.value = stock;
        } else {
            stockBadgeContainer.innerHTML = `<span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-3 py-2 rounded-pill small fw-bold"><i class="fa-solid fa-circle-check me-1"></i> Còn ${stock} ly</span>`;
            if (btnAddToCart) {
                btnAddToCart.disabled = false;
                btnAddToCart.textContent = 'THÊM VÀO GIỎ';
                btnAddToCart.classList.remove('opacity-50');
            }
            if (btnBuyNow) {
                btnBuyNow.disabled = false;
                btnBuyNow.textContent = 'MUA NGAY';
                btnBuyNow.classList.remove('opacity-50');
            }
            inputQty.disabled = false;
            let currentQty = parseInt(inputQty.value);
            if (currentQty <= 0) inputQty.value = 1;
            if (currentQty > stock) inputQty.value = stock;
        }
    };
    
    // Quantity Controls
    btnMinus.addEventListener('click', () => {
        let qty = parseInt(inputQty.value);
        if (qty > 1) {
            inputQty.value = qty - 1;
            calculatePrice();
        }
    });
    
    btnPlus.addEventListener('click', () => {
        let qty = parseInt(inputQty.value);
        const checkedSize = document.querySelector('input[name="size"]:checked');
        const stockAttr = checkedSize ? checkedSize.dataset.stock : '';
        const maxStock = (stockAttr !== undefined && stockAttr !== '') ? parseInt(stockAttr) : Infinity;

        if (qty < maxStock) {
            inputQty.value = qty + 1;
            calculatePrice();
        } else if (!isNaN(maxStock) && maxStock !== Infinity) {
            alert('Rất tiếc, số lượng nguyên liệu chỉ đủ để phục vụ tối đa ' + maxStock + ' ly!');
        }
    });
    
    inputQty.addEventListener('change', () => {
        let qty = parseInt(inputQty.value);
        const checkedSize = document.querySelector('input[name="size"]:checked');
        const stockAttr = checkedSize ? checkedSize.dataset.stock : '';
        const maxStock = (stockAttr !== undefined && stockAttr !== '') ? parseInt(stockAttr) : Infinity;

        if (isNaN(qty) || qty < 1) {
            inputQty.value = (maxStock <= 0) ? 0 : 1;
        } else if (qty > maxStock) {
            inputQty.value = maxStock;
            alert('Số lượng đã được điều chỉnh về mức tối đa có thể phục vụ (' + maxStock + ' ly).');
        }
        calculatePrice();
    });
    
    // Temperature Radio Toggle
    const tempRadios = document.querySelectorAll('input[name="temperature"]');
    const iceSection = document.getElementById('ice-section');
    tempRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            if (iceSection) {
                if (this.value === 'hot') {
                    iceSection.style.display = 'none';
                } else {
                    iceSection.style.display = 'block';
                }
            }
        });
    });

    // Event Listeners for Options
    sizeRadios.forEach(radio => {
        radio.addEventListener('change', () => {
            calculatePrice();
            updateStockDisplay();
        });
    });
    
    toppingCheckboxes.forEach(cb => {
        cb.addEventListener('change', calculatePrice);
    });
    
    // Initial Calculation & Stock Display
    calculatePrice();
    updateStockDisplay();
    
    // Process Cart Action (Add or Buy Now)
    const processCartAction = (redirect = false) => {
        const productId = new URLSearchParams(window.location.search).get('id') || document.querySelector('input[name="product_id"]')?.value || 1;
        const productName = document.querySelector('.product-title')?.textContent || document.querySelector('h1')?.textContent || '';
        const productImage = document.getElementById('product-img-main')?.src || '';
        const basePriceVal = parseInt(basePriceEl.dataset.price);
        
        let size = '';
        let sizePrice = 0;
        sizeRadios.forEach(radio => {
            if(radio.checked) {
                size = radio.value;
                sizePrice = parseInt(radio.dataset.price);
            }
        });

        let temperature = 'cold';
        tempRadios.forEach(r => {
            if(r.checked) temperature = r.value;
        });

        let sugar = '';
        document.querySelectorAll('input[name="sugar"]').forEach(r => {
            if(r.checked) sugar = r.value;
        });

        let ice = '';
        if (temperature === 'cold') {
            document.querySelectorAll('input[name="ice"]').forEach(r => {
                if(r.checked) ice = r.value;
            });
        }

        let toppings = [];
        toppingCheckboxes.forEach(cb => {
            if(cb.checked) {
                toppings.push({
                    id: cb.value,
                    name: cb.nextElementSibling.textContent.trim(),
                    price: parseInt(cb.dataset.price)
                });
            }
        });

        let formData = new FormData();
        formData.append('product_id', productId);
        formData.append('product_name', productName);
        formData.append('product_image', productImage);
        formData.append('base_price', basePriceVal);
        formData.append('size', size);
        formData.append('size_price', sizePrice);
        formData.append('temperature', temperature);
        formData.append('sugar', sugar);
        formData.append('ice', ice);
        formData.append('quantity', inputQty.value);
        
        toppings.forEach((t, i) => {
            formData.append(`toppings[${i}][id]`, t.id);
            formData.append(`toppings[${i}][name]`, t.name);
            formData.append(`toppings[${i}][price]`, t.price);
        });

        fetch('index.php?route=cart&action=add', {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if(data.success) {
                if(redirect) {
                    window.location.href = 'index.php?route=checkout';
                } else {
                    if (toastElement) {
                        const cartToast = new bootstrap.Toast(toastElement);
                        cartToast.show();
                    }
                    const cartBadge = document.getElementById('cart-badge');
                    if(cartBadge) {
                        cartBadge.textContent = data.cart_count;
                        cartBadge.classList.remove('d-none');
                    }
                }
            }
        });
    };

    // Add to Cart
    if (btnAddToCart) {
        btnAddToCart.addEventListener('click', () => processCartAction(false));
    }

    // Buy Now
    if (btnBuyNow) {
        btnBuyNow.addEventListener('click', () => processCartAction(true));
    }
    
    // Favorite Toggle
    if (btnFavorite) {
        btnFavorite.addEventListener('click', (e) => {
            e.preventDefault();
            const icon = btnFavorite.querySelector('i');
            if (icon.classList.contains('fa-regular')) {
                icon.classList.remove('fa-regular');
                icon.classList.add('fa-solid');
                btnFavorite.classList.add('active');
            } else {
                icon.classList.remove('fa-solid');
                icon.classList.add('fa-regular');
                btnFavorite.classList.remove('active');
            }
        });
    }
});
