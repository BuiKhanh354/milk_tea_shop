<!-- pages/profile/index.php — Thông tin cá nhân -->
<section class="profile-section py-5">
    <div class="container py-4">
        <div class="card border-0 shadow-sm rounded-4 mx-auto p-4 p-md-5" style="max-width: 600px;">
            <h3 class="font-serif fw-bold text-forest mb-4">Thông Tin Cá Nhân</h3>

            <?php if (!empty($msg)): ?>
                <div class="alert alert-success rounded-3 mb-3"><?= e($msg) ?></div>
            <?php endif; ?>

            <form action="<?= e(url('profile')) ?>" method="POST">
                <div class="mb-3">
                    <label class="form-label small fw-bold text-muted">Họ và tên *</label>
                    <input type="text" name="name" class="form-control" required value="<?= e($customer['name'] ?? '') ?>">
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-bold text-muted">Số điện thoại *</label>
                    <input type="tel" name="phone" class="form-control" required value="<?= e($customer['phone'] ?? '') ?>">
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-bold text-muted">Email</label>
                    <input type="email" name="email" class="form-control" value="<?= e($customer['email'] ?? '') ?>">
                </div>

                <div class="mb-4">
                    <label class="form-label small fw-bold text-muted">Địa chỉ nhận hàng mặc định</label>
                    <textarea name="address" class="form-control" rows="2"><?= e($customer['address'] ?? '') ?></textarea>
                </div>

                <button type="submit" class="btn btn-forest px-4 py-2 rounded-pill fw-bold">Lưu Thay Đổi</button>
            </form>
        </div>
    </div>
</section>

