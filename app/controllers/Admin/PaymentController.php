<?php
require_once __DIR__ . '/../../models/Payment.php';
require_once __DIR__ . '/../../models/Order.php';

class PaymentController {
    
    public function index() {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header('Location: admin.php?route=orders');
            exit;
        }

        $pageTitle = 'Quản lý thanh toán';
        
        $paymentModel = new Payment();
        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $limit = 10;
        
        $filters = [];
        if (!empty($_GET['search'])) $filters['search'] = trim($_GET['search']);
        if (!empty($_GET['method'])) $filters['method'] = trim($_GET['method']);
        if (!empty($_GET['status'])) $filters['status'] = trim($_GET['status']);
        if (!empty($_GET['start_date'])) $filters['start_date'] = trim($_GET['start_date']);
        if (!empty($_GET['end_date'])) $filters['end_date'] = trim($_GET['end_date']);
        
        $stats = $paymentModel->getStats($filters);
        $paginatedData = $paymentModel->getPaginated($page, $limit, $filters);
        
        $payments = $paginatedData['data'];
        $totalPages = $paginatedData['total_pages'];
        $currentPage = $paginatedData['current_page'];
        $total = $paginatedData['total'];

        ob_start();
        require_once __DIR__ . '/../../views/admin/payments/index.php';
        $content = ob_get_clean();

        require_once __DIR__ . '/../../views/layouts/admin.php';
    }

    public function update_status() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
            // Kiểm tra CSRF nếu có gửi lên
            if (function_exists('verify_csrf') && !verify_csrf()) {
                header('Location: admin.php?route=payments&error=csrf_invalid');
                exit;
            }

            $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
            $status = $_POST['status'] ?? '';
            $transactionCode = !empty($_POST['transaction_code']) ? trim($_POST['transaction_code']) : null;
            
            if ($id && $status) {
                $paymentModel = new Payment();
                if ($paymentModel->updateStatus($id, $status, $transactionCode)) {
                    // Nếu cập nhật thành 'paid', tự động cập nhật trạng thái đơn hàng nếu đơn đang pending
                    if ($status === 'paid') {
                        $payment = $paymentModel->getById($id);
                        if ($payment && !empty($payment['order_id']) && $payment['order_status'] === 'pending') {
                            $orderModel = new Order();
                            $orderModel->updateStatus($payment['order_id'], 'confirmed');
                        }
                    }
                    header('Location: admin.php?route=payments&msg=status_updated');
                    exit;
                }
            }
            header('Location: admin.php?route=payments&error=failed');
            exit;
        }
        header('Location: admin.php?route=payments');
        exit;
    }
}
