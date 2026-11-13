<!-- pages/auth/login.php — Giao diện Đăng nhập Khách hàng -->
<section class="auth-section py-5">
    <div class="container py-4">
        <div class="card border-0 shadow-sm rounded-4 mx-auto p-4 p-md-5" style="max-width: 450px;">
            <div class="text-center mb-4">
                <i class="fa-solid fa-paper-plane text-caramel fs-2 mb-2"></i>
                <h3 class="font-serif fw-bold text-forest">Đăng Nhập</h3>
                <p class="text-muted small">Chào mừng bạn trở lại với VAA THÉ</p>
            </div>

            <?php if (!empty($error)): ?>
                <div class="alert alert-danger small rounded-3 mb-3"><?= e($error) ?></div>
            <?php endif; ?>

            <form action="<?= e(url('login')) ?>" method="POST">
                <div class="mb-3">
                    <label class="form-label small fw-bold text-muted">Số điện thoại hoặc Email</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="fa-regular fa-user text-muted"></i></span>
                        <input type="text" name="login" class="form-control border-start-0" required placeholder="0901234567 hoặc email...">
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label small fw-bold text-muted">Mật khẩu</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-lock text-muted"></i></span>
                        <input type="password" name="password" class="form-control border-start-0" required placeholder="••••••••">
                    </div>
                </div>

                <button type="submit" class="btn btn-forest w-100 py-2 rounded-pill fw-bold mb-3">Đăng Nhập</button>

                <div class="text-center small text-muted">
                    Chưa có tài khoản? <a href="<?= e(url('register')) ?>" class="text-forest fw-bold text-decoration-none">Đăng ký ngay</a>
                </div>
            </form>
        </div>
    </div>
</section>

