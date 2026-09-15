<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký - VAA THÉ</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/auth.css">
</head>

<body>
    <div class="auth-wrapper" style="animation: fadeInWrapper 0.8s ease forwards; opacity: 0;">
        <!-- Left Side: Brand Visual -->
        <div class="auth-visual">
            <div class="btn-back">
                <i class="fa-solid fa-arrow-left"></i>
                <a href="index.html">Trang chủ</a>
            </div>

            <!-- <div class="auth-brand-top">
                <h3 class="font-serif fw-bold text-forest mb-1">VAA THÉ</h3>
                <p class="text-caramel fst-italic font-serif fs-5 mb-0">GOOD TEA, GOOD MOOD</p>
            </div> -->

            <div class="auth-image-container">
                <!-- Using a different tea image for variety -->
                <img src="https://images.unsplash.com/photo-1558160074-4d7d8bdf4256?auto=format&fit=crop&q=80&w=1200"
                    alt="Vaa The Brand Image">
            </div>
        </div>
        <?php
            require_once'./app/config/database.php';

            if($_SERVER['REQUEST_METHOD'] === 'POST') {
                $name = $_POST['name'];
                $email = $_POST['email'];
                $password = $_POST['password'];
                $confirm_password = $_POST['confirm_password'];

                // echo $name ."<br>";
                // echo $email ."<br>";
                // echo $password ."<br>";
                // echo $confirm_password ."<br>";
            }
        ?>
        <!-- Right Side: Register Form -->
        <div class="auth-form-side">
            <div class="auth-form-container">

                <a href="index.html" class="auth-logo">
                    <i class="fa-solid fa-leaf text-sage"></i> VAA THÉ
                </a>

                <div class="mb-5">
                    <span class="small-label mb-2 d-inline-block">JOIN US</span>
                    <h2 class="font-serif fw-bold text-forest mb-3">Tạo tài khoản mới</h2>
                    <p class="text-muted">Đăng ký để nhận những ưu đãi đặc biệt và trải nghiệm mua sắm tuyệt vời cùng VAA THÉ.</p>
                </div>

                <div id="general-error" class="alert alert-danger error-msg mb-4" role="alert" style="display: none;">
                </div>

                <form id="register-form" method="POST" action="register.php" novalidate>

                    <!-- Full Name -->
                    <div class="mb-4">
                        <label for="fullname" class="form-label auth-form-label">Họ và tên</label>
                        <input type="text" class="form-control auth-input" id="fullname" name="name"
                            placeholder="Nhập họ và tên của bạn" autocomplete="name" required>
                        <div id="fullname-error" class="error-msg"></div>
                    </div>

                    <!-- Email -->
                    <div class="mb-4">
                        <label for="email" class="form-label auth-form-label">Email</label>
                        <input type="email" class="form-control auth-input" id="email" name="email"
                            placeholder="Nhập địa chỉ email" autocomplete="email" required>
                        <div id="email-error" class="error-msg"></div>
                    </div>

                    <!-- Password -->
                    <div class="mb-4">
                        <label for="password" class="form-label auth-form-label">Mật khẩu</label>
                        <div class="password-wrapper">
                            <input type="password" class="form-control auth-input" id="password" name="password"
                                placeholder="Tạo mật khẩu (ít nhất 6 ký tự)" autocomplete="new_password" required>
                            <button type="button" class="password-toggle" aria-label="Hiện mật khẩu">
                                <i class="fa-regular fa-eye"></i>
                            </button>
                        </div>
                        <div id="password-error" class="error-msg"></div>
                    </div>

                    <!-- Confirm Password -->
                    <div class="mb-5">
                        <label for="confirm-password" class="form-label auth-form-label">Xác nhận mật khẩu</label>
                        <div class="password-wrapper">
                            <input type="password" class="form-control auth-input" id="confirm-password" name="confirm_password"
                                placeholder="Nhập lại mật khẩu" autocomplete="new-password" required>
                            <button type="button" class="password-toggle" aria-label="Hiện mật khẩu">
                                <i class="fa-regular fa-eye"></i>
                            </button>
                        </div>
                        <div id="confirm-password-error" class="error-msg"></div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" id="submit-btn" class="btn btn-auth w-100 mb-4">
                        ĐĂNG KÝ TÀI KHOẢN <i class="fa-solid fa-arrow-right arrow-icon"></i>
                    </button>

                    <!-- Login Link -->
                    <div class="text-center mb-4">
                        <span class="text-muted small">Đã có tài khoản?</span>
                        <a href="login.html" class="auth-link small ms-1">Đăng nhập ngay</a>
                    </div>

                    <!-- Social Login Divider -->
                    <div class="social-login-divider">hoặc</div>

                    <!-- Social Login Button -->
                    <button type="button"
                        class="btn btn-social w-100 d-flex align-items-center justify-content-center gap-2">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/5/53/Google_%22G%22_Logo.svg"
                            alt="Google" width="20" height="20">
                        Đăng ký với Google
                    </button>

                </form>
            </div>
        </div>

    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Auth Custom JS -->
    <script src="assets/js/auth.js"></script>
</body>

</html>