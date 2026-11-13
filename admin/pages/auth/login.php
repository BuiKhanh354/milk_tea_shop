<!-- admin/pages/auth/login.php -->
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Nhập Quản Trị | VAA THÉ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= e(asset('assets/css/style.css')) ?>">
</head>
<body class="bg-light d-flex align-items-center justify-content-center min-vh-100">
    <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5 bg-white" style="width: 100%; max-width: 420px;">
        <div class="text-center mb-4">
            <h3 class="font-serif fw-bold text-forest">VAA THÉ · Admin</h3>
            <p class="text-muted small">Hệ thống quản lý bán hàng</p>
        </div>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger small rounded-3 mb-3"><?= e($error) ?></div>
        <?php endif; ?>

        <form action="<?= e(admin_url('login')) ?>" method="POST">
            <div class="mb-3">
                <label class="form-label small fw-bold text-muted">Tên đăng nhập</label>
                <input type="text" name="username" class="form-control" required placeholder="admin">
            </div>

            <div class="mb-4">
                <label class="form-label small fw-bold text-muted">Mật khẩu</label>
                <input type="password" name="password" class="form-control" required placeholder="••••••••">
            </div>

            <button type="submit" class="btn btn-forest w-100 py-2 rounded-pill fw-bold">Đăng Nhập</button>
        </form>
    </div>
</body>
</html>

