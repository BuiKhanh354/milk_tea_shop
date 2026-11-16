<?php
// admin/pages/profile/index.php — Giao diện thông tin tài khoản cá nhân chuẩn gốc
?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="font-serif fw-bold text-dark mb-1">
            <i class="fa-regular fa-user text-forest me-2"></i>Hồ sơ cá nhân
        </h4>
        <p class="text-muted small mb-0">Xem và cập nhật thông tin tài khoản quản trị viên / nhân viên</p>
    </div>
</div>

<?php if ($successMsg = get_flash('success')): ?>
    <div class="alert alert-success alert-dismissible fade show rounded-4 mb-4 border-0 shadow-sm" role="alert">
        <i class="fa-solid fa-circle-check me-2"></i><?= e($successMsg) ?>
        <button type="button" class="btn-close shadow-none" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if ($errorMsg = get_flash('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show rounded-4 mb-4 border-0 shadow-sm" role="alert">
        <i class="fa-solid fa-triangle-exclamation me-2"></i><?= e($errorMsg) ?>
        <button type="button" class="btn-close shadow-none" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="row g-4">
    <div class="col-md-7">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4">
                <form action="<?= e(admin_url('profile', ['action' => 'update'])) ?>" method="POST">
                    <?= csrf_field() ?>
                    <div class="mb-3">
                        <label class="form-label fw-medium text-muted small">Tên đăng nhập (Username)</label>
                        <input type="text" name="username" class="form-control bg-light border-0" value="<?= e($user['username']) ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-medium text-muted small">Họ và tên</label>
                        <input type="text" name="full_name" class="form-control bg-light border-0" value="<?= e($user['full_name']) ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-medium text-muted small">Số điện thoại</label>
                        <input type="text" name="phone" class="form-control bg-light border-0" value="<?= e($user['phone'] ?? '') ?>">
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label fw-medium text-muted small">Email</label>
                        <input type="email" name="email" class="form-control bg-light border-0" value="<?= e($user['email'] ?? '') ?>">
                    </div>
                    
                    <hr class="my-4">
                    
                    <div class="mb-4">
                        <label class="form-label fw-medium text-muted small">Mật khẩu mới (Bỏ trống nếu không muốn đổi)</label>
                        <input type="password" name="password" class="form-control bg-light border-0" placeholder="Nhập mật khẩu mới...">
                    </div>
                    
                    <button type="submit" class="btn btn-forest px-4 rounded-pill shadow-sm">
                        <i class="fa-solid fa-floppy-disk me-2"></i>Lưu thay đổi
                    </button>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-5">
        <div class="card border-0 shadow-sm rounded-4 bg-light text-center p-5">
            <img src="https://ui-avatars.com/api/?name=<?= urlencode($user['full_name']) ?>&background=263A30&color=fff&size=128" alt="User" class="rounded-circle mb-3 mx-auto shadow-sm" width="128" height="128">
            <h4 class="fw-bold mb-1"><?= e($user['full_name']) ?></h4>
            <p class="text-muted small mb-3">@<?= e($user['username']) ?></p>
            <div>
                <span class="badge bg-forest px-3 py-2 rounded-pill"><?= ucfirst(e($user['role'] ?? 'staff')) ?></span>
            </div>
        </div>
    </div>
</div>

