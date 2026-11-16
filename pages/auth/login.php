<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập - VAA THÉ</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= e(asset('assets/css/style.css')) ?>">
    <link rel="stylesheet" href="<?= e(asset('assets/css/auth.css')) ?>">
</head>

<body>
    <div class="auth-wrapper auth-style-cc17ca">
        <!-- Left Side: Brand Visual -->
        <div class="auth-visual">
            <div class="btn-back">
                <i class="fa-solid fa-arrow-left"></i>
                <a href="<?= e(url('home')) ?>">Trang chủ</a>
            </div>

            <div class="auth-image-container">
                <img src="https://images.unsplash.com/photo-1576092768241-dec231879fc3?auto=format&fit=crop&q=80&w=1200"
                    alt="Vaa The Brand Image">
            </div>
        </div>

        <!-- Right Side: Login Form -->
        <div class="auth-form-side">
            <div class="auth-form-container">

                <a href="<?= e(url('home')) ?>" class="auth-logo">
                    <i class="fa-solid fa-leaf text-sage"></i> VAA THÉ
                </a>

                <div class="mb-5">
                    <span class="small-label mb-2 d-inline-block">WELCOME BACK</span>
                    <h2 class="font-serif fw-bold text-forest mb-3">Chào mừng trở lại</h2>
                    <p class="text-muted">Đăng nhập tài khoản Khách hàng, Nhân viên hoặc Quản trị viên.</p>
                </div>

                <?php if (!empty($error)): ?>
                    <div id="general-error" class="alert alert-danger error-msg mb-4 auth-style-19fae4" role="alert">
                        <?= e($error) ?>
                    </div>
                <?php endif; ?>

                <?php if ($successMsg = get_flash('success')): ?>
                    <div class="alert alert-success rounded-4 mb-4 border-0 shadow-sm" role="alert">
                        <i class="fa-solid fa-circle-check me-2"></i><?= e($successMsg) ?>
                    </div>
                <?php endif; ?>

                <form id="login-form" method="POST" action="<?= e(url('login', !empty($redirect_val) ? ['redirect' => $redirect_val] : [])) ?>" novalidate>
                    <input type="hidden" name="redirect" value="<?= e($redirect_val ?? '') ?>">

                    <!-- Email / Username -->
                    <div class="mb-4">
                        <label for="email" class="form-label auth-form-label">Tài khoản / Email / Số điện thoại</label>
                        <input type="text" class="form-control auth-input" id="email" name="email"
                            placeholder="Email, số điện thoại hoặc tên đăng nhập" autocomplete="username" value="<?= e($email_val) ?>" required>
                    </div>

                    <!-- Password -->
                    <div class="mb-4">
                        <label for="password" class="form-label auth-form-label">Mật khẩu</label>
                        <div class="password-wrapper">
                            <input type="password" class="form-control auth-input" id="password" name="password"
                                placeholder="Nhập mật khẩu" autocomplete="current-password" required>
                            <button type="button" class="password-toggle" id="toggle-password"
                                aria-label="Hiện mật khẩu">
                                <i class="fa-regular fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Remember Me & Forgot Password -->
                    <div class="d-flex align-items-center justify-content-between mb-5 flex-wrap gap-2">
                        <div class="form-check mb-0">
                            <input class="form-check-input" type="checkbox" id="rememberMe">
                            <label class="form-check-label text-muted small auth-style-24b531" for="rememberMe">
                                Ghi nhớ đăng nhập
                            </label>
                        </div>
                        <a href="#" class="auth-link small">Quên mật khẩu?</a>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" id="submit-btn" class="btn btn-auth w-100 mb-4">
                        ĐĂNG NHẬP <i class="fa-solid fa-arrow-right arrow-icon"></i>
                    </button>

                    <!-- Register Link -->
                    <div class="text-center mb-4">
                        <span class="text-muted small">Chưa có tài khoản?</span>
                        <a href="<?= e(url('register')) ?>" class="auth-link small ms-1">Đăng ký ngay</a>
                    </div>

                    <!-- Social Login Divider -->
                    <div class="social-login-divider">hoặc</div>

                    <!-- Social Login Button -->
                    <button type="button"
                        class="btn btn-social w-100 d-flex align-items-center justify-content-center gap-2">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/5/53/Google_%22G%22_Logo.svg"
                            alt="Google" width="20" height="20">
                        Tiếp tục với Google
                    </button>

                </form>
            </div>
        </div>

    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Auth Custom JS -->
    <script src="<?= e(asset('assets/js/auth.js')) ?>"></script>
</body>

</html>
