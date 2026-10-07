<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sản phẩm không tồn tại - VAA THÉ</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="bg-ivory d-flex flex-column min-vh-100">

    <!-- Header -->
    <?php include __DIR__ . '/../partials/navbar_old.php'; ?>
    
    <!-- Error Content -->
    <main class="container text-center py-5 my-auto fade-up visible">
        <div class="py-5">
            <h1 class="font-serif fw-bold text-forest mb-4 global-style-5afc73" >Oops! Trang này đi đâu mất rồi?</h1>
            <p class="fs-4 text-muted font-serif fst-italic mb-5">"Xin lỗi, chúng tôi không tìm thấy nội dung bạn đang tìm kiếm."</p>
            <a href="index.php" class="btn btn-caramel px-5 py-3 fw-bold tracking-wide">QUAY LẠI TRANG CHỦ</a>
        </div>
    </main>

    <!-- Footer -->
     <div class="mt-auto">
        <?php include __DIR__ . '/../layouts/footer.php'; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/main.js"></script>
</body>
</html>
