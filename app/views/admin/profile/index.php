<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="font-serif fw-bold text-dark mb-0">Hồ sơ cá nhân</h4>
</div>

<?php if (isset($_GET['msg']) && $_GET['msg'] === 'success'): ?>
<div class="alert alert-success alert-dismissible fade show" role="alert">
    Cập nhật thông tin thành công!
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
<?php endif; ?>

<?php if (isset($_GET['error'])): ?>
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    Đã có lỗi xảy ra. Vui lòng thử lại.
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
<?php endif; ?>

<div class="row">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4">
                <form action="admin.php?route=profile&action=update" method="POST">
                    <div class="mb-3">
                        <label class="form-label fw-medium text-muted small">Tên đăng nhập (Username)</label>
                        <input type="text" name="username" class="form-control bg-light border-0" value="<?= htmlspecialchars($user['username']) ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-medium text-muted small">Họ và tên</label>
                        <input type="text" name="full_name" class="form-control bg-light border-0" value="<?= htmlspecialchars($user['full_name']) ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-medium text-muted small">Số điện thoại</label>
                        <input type="text" name="phone" class="form-control bg-light border-0" value="<?= htmlspecialchars($user['phone'] ?? '') ?>">
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label fw-medium text-muted small">Email</label>
                        <input type="email" name="email" class="form-control bg-light border-0" value="<?= htmlspecialchars($user['email'] ?? '') ?>">
                    </div>
                    
                    <hr class="mb-4">
                    
                    <div class="mb-4">
                        <label class="form-label fw-medium text-muted small">Mật khẩu mới (Bỏ trống nếu không đổi)</label>
                        <input type="password" name="password" class="form-control bg-light border-0" placeholder="Nhập mật khẩu mới...">
                    </div>
                    
                    <button type="submit" class="btn btn-forest px-4">Lưu thay đổi</button>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <!-- Optional profile info block -->
        <div class="card border-0 shadow-sm rounded-4 bg-light text-center p-5">
            <img src="https://ui-avatars.com/api/?name=<?= urlencode($user['full_name']) ?>&background=263A30&color=fff&size=128" alt="User" class="rounded-circle mb-3 mx-auto" width="128" height="128">
            <h4 class="fw-bold"><?= htmlspecialchars($user['full_name']) ?></h4>
            <p class="text-muted mb-0">Vai trò: <span class="badge bg-secondary"><?= ucfirst($user['role']) ?></span></p>
        </div>
    </div>
</div>
