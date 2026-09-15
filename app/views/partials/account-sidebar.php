<?php
// Lấy tên file hiện tại để xác định menu đang active
$current_page = basename($_SERVER['PHP_SELF']);
?>

<div class="account-sidebar bg-white p-4 border border-sage border-opacity-25 shadow-sm rounded-0 h-100">
    <h5 class="font-serif fw-bold text-forest mb-4 pb-3 border-bottom border-sage border-opacity-25">MY ACCOUNT</h5>
    
    <!-- Mobile Toggle (Visible only on small screens) -->
    <div class="d-md-none mb-3">
        <button class="btn btn-outline-forest w-100 text-start d-flex justify-content-between align-items-center" type="button" data-bs-toggle="collapse" data-bs-target="#accountMenuCollapse" aria-expanded="false" aria-controls="accountMenuCollapse">
            <span>Menu Tài Khoản</span>
            <i class="fa-solid fa-chevron-down"></i>
        </button>
    </div>

    <!-- Menu List -->
    <div class="collapse d-md-block" id="accountMenuCollapse">
        <ul class="nav flex-column account-nav gap-2">
            <li class="nav-item">
                <a class="nav-link px-3 py-2 text-dark rounded-0 transition-fast <?= $current_page === 'profile.php' ? 'active bg-sage bg-opacity-25 text-forest fw-bold border-start border-3 border-caramel' : 'hover-sage border-start border-3 border-transparent' ?>" href="profile.php">
                    <i class="fa-regular fa-id-card me-2 text-caramel"></i> Thông tin cá nhân
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link px-3 py-2 text-dark rounded-0 transition-fast <?= $current_page === 'orders.php' ? 'active bg-sage bg-opacity-25 text-forest fw-bold border-start border-3 border-caramel' : 'hover-sage border-start border-3 border-transparent' ?>" href="orders.php">
                    <i class="fa-solid fa-clock-rotate-left me-2 text-caramel"></i> Đơn đã đặt
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link px-3 py-2 text-dark rounded-0 transition-fast <?= $current_page === 'favorites.php' ? 'active bg-sage bg-opacity-25 text-forest fw-bold border-start border-3 border-caramel' : 'hover-sage border-start border-3 border-transparent' ?>" href="favorites.php">
                    <i class="fa-regular fa-heart me-2 text-caramel"></i> Sản phẩm yêu thích
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link px-3 py-2 text-dark rounded-0 transition-fast <?= $current_page === 'password.php' ? 'active bg-sage bg-opacity-25 text-forest fw-bold border-start border-3 border-caramel' : 'hover-sage border-start border-3 border-transparent' ?>" href="password.php">
                    <i class="fa-solid fa-lock me-2 text-caramel"></i> Đổi mật khẩu
                </a>
            </li>
        </ul>
        
        <hr class="border-sage my-4 opacity-25">
        
        <ul class="nav flex-column account-nav">
            <li class="nav-item">
                <!-- Nút Logout -->
                <a class="nav-link px-3 py-2 text-danger hover-sage transition-fast border-start border-3 border-transparent" href="#" data-bs-toggle="modal" data-bs-target="#logoutModal">
                    <i class="fa-solid fa-arrow-right-from-bracket me-2"></i> Đăng xuất
                </a>
            </li>
        </ul>
    </div>
</div>

<!-- Logout Confirmation Modal -->
<div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-0 border-0 shadow">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title font-serif fw-bold text-forest" id="logoutModalLabel">Xác nhận Đăng xuất</h5>
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center py-4">
                <i class="fa-solid fa-arrow-right-from-bracket fs-1 text-sage mb-3"></i>
                <p class="mb-0 fs-5">Bạn có chắc chắn muốn đăng xuất?</p>
            </div>
            <div class="modal-footer border-top-0 justify-content-center pb-4 pt-0 gap-2">
                <button type="button" class="btn btn-outline-forest px-4" data-bs-dismiss="modal">HỦY</button>
                <a href="logout.php" class="btn btn-caramel px-4 border-0">ĐĂNG XUẤT</a>
            </div>
        </div>
    </div>
</div>
