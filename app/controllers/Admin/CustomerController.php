<?php
require_once __DIR__ . '/../../models/Customer.php';

class CustomerController {
    
    public function index() {
        $pageTitle = 'Quản lý khách hàng';
        
        $customerModel = new Customer();
        $customers = $customerModel->getAll();

        ob_start();
        require_once __DIR__ . '/../../views/admin/customers/index.php';
        $content = ob_get_clean();

        require_once __DIR__ . '/../../views/layouts/admin.php';
    }

    public function toggle_status() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
            $customerModel = new Customer();
            $customerModel->toggleStatus($_POST['id']);
        }
        header('Location: admin.php?route=customers');
        exit;
    }
}
