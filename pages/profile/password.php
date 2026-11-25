<?php
// pages/profile/password.php — Đổi mật khẩu tài khoản khách hàng chuẩn gốc VAA THÉ
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?= csrf_meta() ?>
    <title><?= e($title ?? 'Đổi mật khẩu') ?> - VAA THÉ</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= e(asset('assets/css/style.css')) ?>">
    <link rel="stylesheet" href="<?= e(asset('assets/css/global.css')) ?>">
    <link rel="stylesheet" href="<?= e(asset('assets/css/account.css')) ?>">
    <style>
        .form-control {
            border: none;
            border-bottom: 1px solid var(--c-sage, #aab79f);
            border-radius: 0;
            padding: 0.75rem 0;
            font-family: var(--font-sans, inherit);
            color: var(--c-forest, #263A30);
            background-color: transparent;
        }

        .form-control:focus {
            box-shadow: none;
            border-color: var(--c-caramel, #C68A4C);
            background-color: transparent;
        }

        .form-label {
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: var(--c-caramel, #C68A4C);
            font-weight: 600;
        }

        .password-toggle {
            position: absolute;
            right: 0;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--c-sage, #aab79f);
            cursor: pointer;
        }
    </style>
</head>

<body class="bg-ivory">

    <!-- Header tối giản -->
    <?php include __DIR__ . '/../../layouts/account/header.php'; ?>

    <main class="container py-5 account-style-a6ee44">
        <div class="row g-4">
            <!-- Sidebar (Cột Trái) -->
            <div class="col-lg-3">
                <?php include __DIR__ . '/../../layouts/account/sidebar.php'; ?>
            </div>

            <!-- Content (Cột Phải) -->
            <div class="col-lg-9">
                <div class="bg-white p-4 p-md-5 border border-sage border-opacity-25 shadow-sm h-100 fade-up visible">

                    <div class="mb-5 pb-3 border-bottom border-light">
                        <span class="small-label d-block mb-1">SECURITY</span>
                        <h3 class="font-serif fw-bold text-forest mb-0">Đổi mật khẩu</h3>
                        <p class="text-muted small mt-1 mb-0">Bảo mật tài khoản của bạn bằng mật khẩu mạnh</p>
                    </div>

                    <?php if (!empty($success_msg)): ?>
                        <div class="alert alert-success border-0 rounded-0 bg-sage bg-opacity-25 text-forest">
                            <i class="fa-solid fa-check-circle me-2"></i> <?= e($success_msg) ?>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($error_msg)): ?>
                        <div class="alert alert-danger border-0 rounded-0">
                            <i class="fa-solid fa-triangle-exclamation me-2"></i> <?= e($error_msg) ?>
                        </div>
                    <?php endif; ?>

                    <form class="mx-auto account-style-d99434" method="POST" action="<?= e(url('password')) ?>">
                        <?= csrf_field() ?>
                        <input type="hidden" name="change_password" value="1">
                        <div class="row g-4">
                            <div class="col-12 position-relative">
                                <label class="form-label">Mật khẩu hiện tại</label>
                                <input type="password" class="form-control pe-5" id="current_password" name="current_password" required>
                                <button type="button" class="password-toggle" onclick="togglePasswordVisibility('current_password', this)">
                                    <i class="fa-regular fa-eye"></i>
                                </button>
                            </div>

                            <div class="col-12 position-relative mt-4">
                                <label class="form-label">Mật khẩu mới</label>
                                <input type="password" class="form-control pe-5" id="new_password" name="new_password" required minlength="6">
                                <button type="button" class="password-toggle" onclick="togglePasswordVisibility('new_password', this)">
                                    <i class="fa-regular fa-eye"></i>
                                </button>
                            </div>

                            <div class="col-12 position-relative">
                                <label class="form-label">Xác nhận mật khẩu mới</label>
                                <input type="password" class="form-control pe-5" id="confirm_password" name="confirm_password" required minlength="6">
                                <button type="button" class="password-toggle" onclick="togglePasswordVisibility('confirm_password', this)">
                                    <i class="fa-regular fa-eye"></i>
                                </button>
                            </div>

                            <div class="col-12 mt-5 text-center">
                                <button type="submit" class="btn btn-caramel px-5 py-2 w-100 fw-bold tracking-wide">CẬP NHẬT MẬT KHẨU</button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= e(asset('assets/js/main.js')) ?>"></script>
</body>

</html>
