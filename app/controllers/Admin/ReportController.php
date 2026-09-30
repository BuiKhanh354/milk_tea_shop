<?php
class ReportController {
    public function index() {
        $pageTitle = 'Báo cáo & Thống kê';
        
        ob_start();
        require_once __DIR__ . '/../../views/admin/reports/index.php';
        $content = ob_get_clean();

        require_once __DIR__ . '/../../views/layouts/admin.php';
    }
}
