<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập - VAA THÉ Back Office</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/admin.css">
</head>
<body class="bg-ivory d-flex align-items-center justify-content-center" style="min-height: 100vh;">
    
    <div class="card shadow-sm border-0" style="width: 100%; max-width: 400px; border-radius: 16px;">
        <div class="card-body p-5">
            <div class="text-center mb-4">
                <h2 class="font-serif fw-bold text-forest mb-1">VAA THÉ</h2>
                <p class="small text-muted fst-italic">BACK OFFICE</p>
            </div>
            
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger py-2 small" role="alert">
                    <?= $error ?>
                </div>
            <?php endif; ?>

            <form action="admin.php?route=login" method="POST">
                <div class="mb-3">
                    <label class="form-label text-muted small fw-medium">Tài khoản</label>
                    <input type="text" name="username" class="form-control form-control-lg bg-light border-0 shadow-none fs-6" required placeholder="Nhập username...">
                </div>
                <div class="mb-4">
                    <label class="form-label text-muted small fw-medium">Mật khẩu</label>
                    <input type="password" name="password" class="form-control form-control-lg bg-light border-0 shadow-none fs-6" required placeholder="Nhập mật khẩu...">
                </div>
                <button type="submit" class="btn btn-forest w-100 py-2 fw-medium rounded-3">
                    Đăng nhập
                </button>
            </form>
            
            <div class="text-center mt-4">
                <a href="../public/" class="text-decoration-none text-muted small">
                    <i class="fa-solid fa-arrow-left me-1"></i> Quay về trang khách hàng
                </a>
            </div>
        </div>
    </div>

</body>
</html>
