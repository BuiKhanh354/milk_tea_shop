<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'VAA THÉ - Premium Milk Tea' ?></title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/global.css">
    <?php if(isset($cssFiles)) foreach($cssFiles as $css): ?>
        <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/<?= $css ?>.css">
    <?php endforeach; ?>
</head>
<body>
    
    <!-- Navbar -->
    <?php require_once __DIR__ . '/../partials/navbar.php'; ?>

    <!-- Main Content -->
    <main class="main-content">
        <?php 
            if(isset($viewPath) && file_exists($viewPath)) {
                require_once $viewPath;
            } else {
                echo "<div class='container mt-5 text-center'>View path not found: $viewPath</div>";
            }
        ?>
    </main>

    <!-- Footer -->
    <?php require_once __DIR__ . '/../partials/footer.php'; ?>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JS -->
    <script src="<?= BASE_URL ?>/assets/js/global.js"></script>
    <?php if(isset($jsFiles)) foreach($jsFiles as $js): ?>
        <script src="<?= BASE_URL ?>/assets/js/<?= $js ?>.js"></script>
    <?php endforeach; ?>
</body>
</html>
