<?php
session_start();
if (!isset($_SESSION['customer_id'])) {
    header("Location: login.php");
    exit;
}

$favorites = []; // Trống để hiển thị empty state
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sản phẩm yêu thích - VAA THÉ</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
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
                        <span class="small-label d-block mb-1">FAVORITES</span>
                        <h3 class="font-serif fw-bold text-forest mb-0">Sản phẩm yêu thích</h3>
                    </div>

                    <?php if(empty($favorites)): ?>
                        <div class="text-center py-5">
                            <i class="fa-regular fa-heart fs-1 text-muted opacity-25 mb-3"></i>
                            <h4 class="font-serif text-forest">Bạn chưa có sản phẩm yêu thích</h4>
                            <p class="text-muted">Hãy thêm những món trà yêu thích vào đây để dễ dàng đặt lại.</p>
                            <a href="products.php" class="btn btn-caramel mt-3">KHÁM PHÁ MENU</a>
                        </div>
                    <?php else: ?>
                        <!-- Product grid will go here -->
                    <?php endif; ?>
                    
                </div>
            </div>
        </div>
    </main>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/main.js"></script>
</body>
</html>
