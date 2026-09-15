<?php
session_start();
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VAA THÉ - Premium Milk Tea</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/intro.css">
</head>

<body>

    <!-- INTRO SCREEN -->
    <div id="intro-video-wrapper" class="intro-video-wrapper">
        <!-- Overlay UI -->
        <div class="intro-overlay">
            <div class="intro-brand">
                <span>G</span><span>o</span><span>o</span><span>d</span><span>&nbsp;</span>
                <span>T</span><span>e</span><span>a</span><span>,</span><span>&nbsp;</span>
                <span>G</span><span>o</span><span>o</span><span>d</span><span>&nbsp;</span>
                <span>M</span><span>o</span><span>o</span><span>d</span>
            </div>
            <button id="btn-skip-intro" class="btn-skip-intro" aria-label="Skip intro">
                SKIP <i class="fa-solid fa-arrow-right arrow-icon"></i>
            </button>
        </div>

        <video id="intro-video" class="intro-video" autoplay muted playsinline preload="auto">
            <source src="assets/videos/upscaled-video.mp4" type="video/mp4">
            Trình duyệt của bạn không hỗ trợ thẻ video.
        </video>
    </div>

    <!-- Nạp components trực tiếp bằng PHP -->
    <?php include '../app/views/partials/navbar_old.php'; ?>
    
    <?php include '../app/views/customer/home/index.html'; ?>
    
    <?php include '../app/views/layouts/footer.html'; ?>

    <!-- Active Menu JS -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const navLinks = document.querySelectorAll('.nav-link');
            navLinks.forEach(link => {
                if (link.textContent.trim() === 'Trang chủ') {
                    link.classList.add('active');
                }
            });
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/intro.js"></script>
    <script src="assets/js/main.js"></script>
</body>

</html>
