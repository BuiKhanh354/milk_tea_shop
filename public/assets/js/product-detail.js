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
    
    // Toast Notification for Add to Cart
    if (btnAddToCart && toastElement) {
        const cartToast = new bootstrap.Toast(toastElement);
        btnAddToCart.addEventListener('click', () => {
            // Hiển thị toast
            cartToast.show();
            // Cập nhật số lượng giỏ hàng trên navbar (nếu có id="cart-badge")
            const cartBadge = document.getElementById('cart-badge');
            if(cartBadge) {
                let currentCount = parseInt(cartBadge.textContent) || 0;
                cartBadge.textContent = currentCount + parseInt(inputQty.value);
            }
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
