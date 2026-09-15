<?php
require_once __DIR__ . '/../../models/Table.php';

class TableController {
    
    public function index() {
        $pageTitle = 'Quản lý Bàn';
        
        $tableModel = new TableModel();
        $tables = $tableModel->getAll();

        ob_start();
        require_once __DIR__ . '/../../views/admin/tables/index.php';
        $content = ob_get_clean();

        require_once __DIR__ . '/../../views/layouts/admin.php';
    }
}
