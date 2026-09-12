// KOI THÉ INSPIRED INTERACTIVE JAVASCRIPT
document.addEventListener('DOMContentLoaded', () => {
  initCategoryFilter();
  initCustomizerModal();
  initCartDrawer();
  initMobileMenu();
});

// 1. Category Filtering
function initCategoryFilter() {
  const filterTabs = document.querySelectorAll('.filter-tab');
  const productItems = document.querySelectorAll('.product-item');

  if (!filterTabs.length) return;

  filterTabs.forEach(tab => {
    tab.addEventListener('click', () => {
      filterTabs.forEach(t => {
        t.classList.remove('btn-gold', 'active-tab');
        t.classList.add('bg-white', 'text-gray-700');
      });
      tab.classList.add('btn-gold', 'active-tab');
      tab.classList.remove('bg-white', 'text-gray-700');

      const cat = tab.getAttribute('data-category');
      productItems.forEach(item => {
        if (cat === 'all' || item.getAttribute('data-category') === cat) {
          item.style.display = 'flex';
          item.classList.add('animate-fade-in');
        } else {
          item.style.display = 'none';
        }
      });
    });
  });
}

// 2. Customizer Popup Modal
let selectedProduct = { name: '', basePrice: 0, sugar: '70%', ice: 'Chuẩn 100%', toppings: [] };

function openCustomizer(name, price) {
  selectedProduct = { name, basePrice: price, sugar: '70%', ice: 'Chuẩn 100%', toppings: [] };
  const modal = document.getElementById('customizer-modal');
  const nameEl = document.getElementById('modal-item-name');
  const priceEl = document.getElementById('modal-item-price');

  if (nameEl) nameEl.innerText = name;
  if (priceEl) priceEl.innerText = price.toLocaleString('vi-VN') + ' VNĐ';

  if (modal) modal.classList.remove('hidden');
}

function closeCustomizer() {
  const modal = document.getElementById('customizer-modal');
  if (modal) modal.classList.add('hidden');
}

function initCustomizerModal() {
  // Option selection for Sugar & Ice
  const sugarBtns = document.querySelectorAll('.sugar-btn');
  sugarBtns.forEach(btn => {
    btn.addEventListener('click', () => {
      sugarBtns.forEach(b => b.classList.remove('border-[var(--koi-gold)]', 'bg-amber-50', 'text-[var(--koi-gold)]', 'font-bold'));
      btn.classList.add('border-[var(--koi-gold)]', 'bg-amber-50', 'text-[var(--koi-gold)]', 'font-bold');
      selectedProduct.sugar = btn.innerText;
    });
  });

  const iceBtns = document.querySelectorAll('.ice-btn');
  iceBtns.forEach(btn => {
    btn.addEventListener('click', () => {
      iceBtns.forEach(b => b.classList.remove('border-[var(--koi-gold)]', 'bg-amber-50', 'text-[var(--koi-gold)]', 'font-bold'));
      btn.classList.add('border-[var(--koi-gold)]', 'bg-amber-50', 'text-[var(--koi-gold)]', 'font-bold');
      selectedProduct.ice = btn.innerText;
    });
  });
}

function addToCartSuccess() {
  closeCustomizer();
  const badge = document.getElementById('cart-badge');
  if (badge) {
    const count = parseInt(badge.innerText || '0') + 1;
    badge.innerText = count;
  }
  openCartDrawer();
}

// 3. Cart Drawer Toggle
function initCartDrawer() {
  const cartDrawer = document.getElementById('cart-drawer');
  const openBtn = document.getElementById('open-cart-btn');
  const closeBtn = document.getElementById('close-cart-btn');

  if (openBtn && cartDrawer) {
    openBtn.addEventListener('click', openCartDrawer);
  }
  if (closeBtn && cartDrawer) {
    closeBtn.addEventListener('click', closeCartDrawer);
  }
}

function openCartDrawer() {
  const cartDrawer = document.getElementById('cart-drawer');
  if (cartDrawer) {
    cartDrawer.classList.remove('drawer-closed');
    cartDrawer.classList.add('drawer-open');
  }
}

function closeCartDrawer() {
  const cartDrawer = document.getElementById('cart-drawer');
  if (cartDrawer) {
    cartDrawer.classList.remove('drawer-open');
    cartDrawer.classList.add('drawer-closed');
  }
}

// 4. Mobile Menu Drawer
function initMobileMenu() {
  const mobileBtn = document.getElementById('mobile-menu-toggle');
  const mobileNav = document.getElementById('mobile-nav-drawer');

  if (mobileBtn && mobileNav) {
    mobileBtn.addEventListener('click', () => {
      mobileNav.classList.toggle('hidden');
    });
  }
}
