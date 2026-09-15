<?php
session_start();
require_once __DIR__ . '/../app/models/Product.php';
$productModel = new Product();
$productsList = $productModel->getAll();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sản Phẩm - VAA THÉ</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/products.css">
</head>
<body>

    <!-- Nạp components trực tiếp bằng PHP -->
    <?php include '../app/views/partials/navbar_old.php'; ?>
    
    <?php include '../app/views/customer/products/index.php'; ?>
    
    <?php include '../app/views/layouts/footer.html'; ?>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const navLinks = document.querySelectorAll('.nav-link');
            navLinks.forEach(link => {
                if (link.textContent.trim() === 'Sản phẩm') {
                    link.classList.add('active');
                }
            });
            
            // Cập nhật link liên hệ trong Footer nếu cần
            const footerLinks = document.querySelectorAll('.vaa-footer a');
            footerLinks.forEach(link => {
                if (link.textContent.trim() === 'Trang chủ') {
                    link.href = 'index.php';
                }
            });
        });
    </script>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/main.js"></script>
</body>
</html>
