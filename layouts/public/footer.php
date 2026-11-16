<!-- FOOTER (Old UI style converted to PHP) -->
<?php if (!$isAuthPage): ?>
<footer class="vaa-footer" id="contact">
    <div class="container px-4 px-lg-5">
        <div class="row g-5 mb-5">
            <!-- Left Col: Logo & Tagline -->
            <div class="col-lg-4 pe-lg-5">
                <a class="text-white text-decoration-none d-flex align-items-center gap-2 mb-4" href="<?= e(url('home')) ?>">
                    <i class="fa-solid fa-paper-plane text-caramel fs-3"></i>
                    <span class="font-serif fw-bold fs-3 letter-spacing-wide">VAA THÉ</span>
                </a>
                <p class="font-serif fs-4 fst-italic text-caramel mb-4">"Good Tea, Good Mood"</p>
                <p class="small text-white-50 mb-4">
                    Thương hiệu trà sữa cao cấp, kiên định mang đến trải nghiệm trà thanh tao, tối giản và chất lượng nguyên bản.
                </p>
                <div class="d-flex gap-3">
                    <a href="#" class="btn btn-outline-light rounded-circle p-2 d-flex align-items-center justify-content-center global-style-4271b4"><i class="fa-brands fa-facebook-f"></i></a>
                    <a href="#" class="btn btn-outline-light rounded-circle p-2 d-flex align-items-center justify-content-center global-style-4271b4"><i class="fa-brands fa-instagram"></i></a>
                    <a href="#" class="btn btn-outline-light rounded-circle p-2 d-flex align-items-center justify-content-center global-style-4271b4"><i class="fa-brands fa-tiktok"></i></a>
                </div>
            </div>

            <!-- Links 1 -->
            <div class="col-6 col-lg-2 offset-lg-1">
                <h5>Liên kết</h5>
                <ul class="list-unstyled">
                    <li><a href="<?= e(url('home')) ?>">Trang chủ</a></li>
                    <li><a href="<?= e(url('products')) ?>">Sản phẩm</a></li>
                    <li><a href="<?= e(url('stores')) ?>">Cửa hàng</a></li>
                    <li><a href="<?= e(url('about')) ?>">Giới thiệu</a></li>
                    <li><a href="<?= e(url('contact')) ?>">Liên hệ</a></li>
                </ul>
            </div>

            <!-- Links 2 -->
            <div class="col-6 col-lg-2">
                <h5>Hỗ trợ</h5>
                <ul class="list-unstyled">
                    <li><a href="#">Hướng dẫn mua hàng</a></li>
                    <li><a href="#">Chính sách đổi trả</a></li>
                    <li><a href="#">Chính sách bảo mật</a></li>
                    <li><a href="#">Điều khoản sử dụng</a></li>
                    <li><a href="#">FAQ</a></li>
                </ul>
            </div>

            <!-- Contact -->
            <div class="col-lg-3">
                <h5>Liên hệ</h5>
                <ul class="text-white-50 small list-unstyled global-style-35bb81">
                    <li class="mb-2"><i class="fa-solid fa-location-dot me-2 text-caramel"></i> 123 Nguyễn Văn Cừ, Q5, TP.HCM</li>
                    <li class="mb-2"><i class="fa-solid fa-phone me-2 text-caramel"></i> 1900 633 988</li>
                    <li class="mb-2"><i class="fa-solid fa-envelope me-2 text-caramel"></i> hello@vaathe.com</li>
                    <li><i class="fa-regular fa-clock me-2 text-caramel"></i> 08:00 - 22:30 Hằng ngày</li>
                </ul>
            </div>
        </div>

        <div class="pt-4 mt-4 border-top border-secondary border-opacity-25 d-flex flex-column flex-md-row justify-content-between align-items-center text-white-50 small">
            <p class="mb-2 mb-md-0">&copy; 2026 VAA THÉ. All rights reserved.</p>
            <p class="mb-0">Designed for premium taste.</p>
        </div>
    </div>
</footer>
<?php endif; ?>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<!-- Custom JS -->
<?php if ($isAuthPage): ?>
<script src="<?= e(asset('assets/js/auth.js')) ?>"></script>
<?php else: ?>
<script src="<?= e(asset('assets/js/intro.js')) ?>"></script>
<script src="<?= e(asset('assets/js/main.js')) ?>"></script>
<?php endif; ?>

</body>
</html>
