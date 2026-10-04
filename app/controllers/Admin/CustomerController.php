<?php
require_once __DIR__ . '/../../models/Customer.php';

class CustomerController {
    
    public function index() {
        $pageTitle = 'Quản lý khách hàng';
        
        $customerModel = new Customer();
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 10;
        
        $filters = [];
        if (isset($_GET['search']) && $_GET['search'] !== '') $filters['search'] = $_GET['search'];
        if (isset($_GET['status']) && $_GET['status'] !== '') $filters['status'] = $_GET['status'];
        
        $paginatedData = $customerModel->getPaginated($page, $limit, $filters);
        $customers = $paginatedData['data'];
        $totalPages = $paginatedData['total_pages'];
        $currentPage = $paginatedData['current_page'];

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
