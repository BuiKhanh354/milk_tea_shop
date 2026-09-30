<?php
class SettingController {
    public function index() {
        $pageTitle = 'Cài đặt hệ thống';
        
        ob_start();
        require_once __DIR__ . '/../../views/admin/settings/index.php';
        $content = ob_get_clean();

        require_once __DIR__ . '/../../views/layouts/admin.php';
    }
}
