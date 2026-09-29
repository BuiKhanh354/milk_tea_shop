<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đổi mật khẩu - VAA THÉ</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .form-control {
            border: none;
            border-bottom: 1px solid var(--vaa-sage);
            border-radius: 0;
            padding: 0.75rem 0;
            font-family: var(--font-sans);
            color: var(--vaa-forest);
            background-color: transparent;
        }

        .form-control:focus {
            box-shadow: none;
            border-color: var(--vaa-caramel);
            background-color: transparent;
        }

        .form-label {
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: var(--vaa-caramel);
            font-weight: 600;
        }

        .password-toggle {
            position: absolute;
            right: 0;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--vaa-sage);
            cursor: pointer;
        }
    </style>
</head>

<body class="bg-ivory">

    <!-- Header tối giản -->
    <?php include '../app/views/partials/account-header.php'; ?>

    <main class="container py-5" style="min-height: calc(100vh - 70px);">
        <div class="row g-4">
            <!-- Sidebar (Cột Trái) -->
            <div class="col-lg-3">
                <?php include '../app/views/partials/account-sidebar.php'; ?>
            </div>

            <!-- Content (Cột Phải) -->
            <div class="col-lg-9">
                <div class="bg-white p-4 p-md-5 border border-sage border-opacity-25 shadow-sm h-100 fade-up visible">

                    <div class="mb-5 pb-3 border-bottom border-light">
                        <span class="small-label d-block mb-1">SECURITY</span>
                        <h3 class="font-serif fw-bold text-forest mb-0">Đổi mật khẩu</h3>
                    </div>

                    <form class="mx-auto" style="max-width: 500px;" method="POST" action="index.php?route=password">
                        <div class="row g-4">
                            <div class="col-12 position-relative">
                                <label class="form-label">Mật khẩu hiện tại</label>
                                <input type="password" class="form-control pe-5" name="current_password">
                                <button type="button" class="password-toggle"><i class="fa-regular fa-eye"></i></button>
                            </div>

                            <div class="col-12 position-relative mt-4">
                                <label class="form-label">Mật khẩu mới</label>
                                <input type="password" class="form-control pe-5" name="new_password">
                                <button type="button" class="password-toggle"><i class="fa-regular fa-eye"></i></button>
                            </div>

                            <div class="col-12 position-relative">
                                <label class="form-label">Xác nhận mật khẩu mới</label>
                                <input type="password" class="form-control pe-5" name="confirm_password">
                                <button type="button" class="password-toggle"><i class="fa-regular fa-eye"></i></button>
                            </div>

                            <div class="col-12 mt-5 text-center">
                                <button type="submit" class="btn btn-caramel px-5 py-2 w-100">CẬP NHẬT MẬT KHẨU</button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/main.js"></script>
</body>

</html>
