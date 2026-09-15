<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'Admin Dashboard' ?> - VAA THÉ</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/admin.css">
</head>
<body class="admin-body">

    <!-- Sidebar (Desktop & Offcanvas on Mobile) -->
    <?php include __DIR__ . '/../partials/admin/sidebar.php'; ?>

    <!-- Main Content Wrapper -->
    <div class="admin-main-wrapper">
        
        <!-- Topbar -->
        <?php include __DIR__ . '/../partials/admin/topbar.php'; ?>

        <!-- Content Area -->
        <main class="admin-content p-4">
            <?= $content ?? '' ?>
        </main>
        
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom Admin JS -->
    <script src="assets/js/admin.js"></script>
    
    <!-- Optional: Chart.js if needed in specific views -->
    <?php if (isset($useChart) && $useChart): ?>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <?php endif; ?>
</body>
</html>
