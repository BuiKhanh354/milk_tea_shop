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
        inputQty.value = qty + 1;
        calculatePrice();
    });
    
    inputQty.addEventListener('change', () => {
        let qty = parseInt(inputQty.value);
        if (isNaN(qty) || qty < 1) {
            inputQty.value = 1;
        }
        calculatePrice();
    });
    
    // Event Listeners for Options
    sizeRadios.forEach(radio => {
        radio.addEventListener('change', calculatePrice);
    });
    
    toppingCheckboxes.forEach(cb => {
        cb.addEventListener('change', calculatePrice);
    });
    
    // Initial Calculation
    calculatePrice();
    
    // Add to Cart
    if (btnAddToCart && toastElement) {
        const cartToast = new bootstrap.Toast(toastElement);
        btnAddToCart.addEventListener('click', () => {
            const productId = new URLSearchParams(window.location.search).get('id') || document.querySelector('input[name="product_id"]')?.value || 1;
            const productName = document.querySelector('.product-title')?.textContent || document.querySelector('h1')?.textContent || '';
            const productImage = document.getElementById('product-img-main')?.src || '';
            const basePrice = parseInt(basePriceEl.dataset.price);
            
            let size = '';
            let sizePrice = 0;
            sizeRadios.forEach(radio => {
                if(radio.checked) {
                    size = radio.value;
                    sizePrice = parseInt(radio.dataset.price);
                }
            });

            let sugar = '';
            document.querySelectorAll('input[name="sugar"]').forEach(r => {
                if(r.checked) sugar = r.value;
            });

            let ice = '';
            document.querySelectorAll('input[name="ice"]').forEach(r => {
                if(r.checked) ice = r.value;
            });

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
            formData.append('base_price', basePrice);
            formData.append('size', size);
            formData.append('size_price', sizePrice);
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
                    cartToast.show();
                    const cartBadge = document.getElementById('cart-badge');
                    if(cartBadge) {
                        cartBadge.textContent = data.cart_count;
                        cartBadge.classList.remove('d-none');
                    }
                }
            });
        });
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
