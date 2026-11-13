</div><!-- end .main-content-wrapper -->

<!-- FOOTER -->
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
                    <a href="#" class="btn btn-outline-light rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;"><i class="fa-brands fa-facebook-f"></i></a>
                    <a href="#" class="btn btn-outline-light rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;"><i class="fa-brands fa-instagram"></i></a>
                    <a href="#" class="btn btn-outline-light rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;"><i class="fa-brands fa-tiktok"></i></a>
                </div>
            </div>

            <!-- Links 1 -->
            <div class="col-6 col-lg-2 offset-lg-1">
                <h5>Liên kết</h5>
                <ul class="list-unstyled">
                    <li><a href="<?= e(url('home')) ?>" class="text-white-50 text-decoration-none">Trang chủ</a></li>
                    <li><a href="<?= e(url('products')) ?>" class="text-white-50 text-decoration-none">Sản phẩm</a></li>
                    <li><a href="<?= e(url('stores')) ?>" class="text-white-50 text-decoration-none">Cửa hàng</a></li>
                    <li><a href="<?= e(url('about')) ?>" class="text-white-50 text-decoration-none">Giới thiệu</a></li>
                    <li><a href="<?= e(url('contact')) ?>" class="text-white-50 text-decoration-none">Liên hệ</a></li>
                </ul>
            </div>

            <!-- Links 2 -->
            <div class="col-6 col-lg-2">
                <h5>Hỗ trợ</h5>
                <ul class="list-unstyled">
                    <li><a href="#" class="text-white-50 text-decoration-none">Hướng dẫn mua hàng</a></li>
                    <li><a href="#" class="text-white-50 text-decoration-none">Chính sách đổi trả</a></li>
                    <li><a href="#" class="text-white-50 text-decoration-none">Chính sách bảo mật</a></li>
                    <li><a href="#" class="text-white-50 text-decoration-none">Điều khoản sử dụng</a></li>
                </ul>
            </div>

            <!-- Contact -->
            <div class="col-lg-3">
                <h5>Liên hệ</h5>
                <ul class="text-white-50 small list-unstyled">
                    <li class="mb-2"><i class="fa-solid fa-location-dot me-2 text-caramel"></i> 123 Nguyễn Văn Cừ, Q5, TP.HCM</li>
                    <li class="mb-2"><i class="fa-solid fa-phone me-2 text-caramel"></i> 090 123 4567</li>
                    <li class="mb-2"><i class="fa-solid fa-envelope me-2 text-caramel"></i> contact@vaathe.vn</li>
                    <li><i class="fa-solid fa-clock me-2 text-caramel"></i> 08:00 - 22:30 mỗi ngày</li>
                </ul>
            </div>
        </div>

        <div class="border-top border-secondary pt-4 text-center text-white-50 small">
            &copy; <?= date('Y') ?> VAA THÉ. All rights reserved.
        </div>
    </div>
</footer>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= e(asset('assets/js/main.js')) ?>"></script>
</body>
</html>

