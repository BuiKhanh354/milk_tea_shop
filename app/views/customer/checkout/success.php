<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'Đặt hàng thành công - RTea' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="bg-light">

<?php include __DIR__ . '/../../partials/navbar_old.php'; ?>

<div class="container-fluid bg-light py-5 min-vh-100 d-flex align-items-center">
    <div class="container text-center">
        <div class="card border-0 shadow-sm rounded-4 mx-auto global-style-4d1e5c" >
            <div class="card-body p-5">
                <div class="mb-4">
                    <i class="fa-solid fa-circle-check text-success global-style-7eebeb" ></i>
                </div>
                <h2 class="font-serif fw-bold text-dark mb-3">Đặt hàng thành công!</h2>
                <p class="text-muted mb-4 fs-5">
                    Cảm ơn bạn đã tin tưởng và ủng hộ RTea.<br>
                    Đơn hàng của bạn đang được xử lý và sẽ sớm được giao đến bạn.
                </p>
                <div class="d-flex justify-content-center gap-3 mt-4">
                    <a href="index.php?route=products" class="btn btn-outline-forest py-2 px-4 rounded-pill fw-bold">
                        TIẾP TỤC MUA SẮM
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../../layouts/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
