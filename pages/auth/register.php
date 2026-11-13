<!-- pages/auth/register.php — Giao diện Đăng ký Khách hàng -->
<section class="auth-section py-5">
    <div class="container py-4">
        <div class="card border-0 shadow-sm rounded-4 mx-auto p-4 p-md-5" style="max-width: 480px;">
            <div class="text-center mb-4">
                <i class="fa-solid fa-paper-plane text-caramel fs-2 mb-2"></i>
                <h3 class="font-serif fw-bold text-forest">Tạo Tài Khoản</h3>
                <p class="text-muted small">Trở thành thành viên để tích luỹ và nhận ưu đãi</p>
            </div>

            <?php if (!empty($error)): ?>
                <div class="alert alert-danger small rounded-3 mb-3"><?= e($error) ?></div>
            <?php endif; ?>

            <form action="<?= e(url('register')) ?>" method="POST">
                <div class="mb-3">
                    <label class="form-label small fw-bold text-muted">Họ và tên *</label>
                    <input type="text" name="name" class="form-control" required placeholder="Nguyễn Văn A">
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-bold text-muted">Số điện thoại *</label>
                    <input type="tel" name="phone" class="form-control" required placeholder="0901234567">
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-bold text-muted">Email (không bắt buộc)</label>
                    <input type="email" name="email" class="form-control" placeholder="email@example.com">
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-bold text-muted">Mật khẩu *</label>
                    <input type="password" name="password" class="form-control" required placeholder="••••••••">
                </div>

                <div class="mb-4">
                    <label class="form-label small fw-bold text-muted">Nhập lại mật khẩu *</label>
                    <input type="password" name="confirm_password" class="form-control" required placeholder="••••••••">
                </div>

                <button type="submit" class="btn btn-forest w-100 py-2 rounded-pill fw-bold mb-3">Đăng Ký Tài Khoản</button>

                <div class="text-center small text-muted">
                    Đã có tài khoản? <a href="<?= e(url('login')) ?>" class="text-forest fw-bold text-decoration-none">Đăng nhập</a>
                </div>
            </form>
        </div>
    </div>
</section>

