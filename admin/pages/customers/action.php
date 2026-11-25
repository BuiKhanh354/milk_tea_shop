<?php
// admin/pages/customers/action.php — Quản lý khách hàng (OOP)
$action = get_param('action', '');
$customerModel = new Customer();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'toggle_status') {
    $id = (int)post_param('id');
    if ($id) {
        $customerModel->toggleStatus($id);
        set_flash('success', "Đã thay đổi trạng thái tài khoản!");
    }
    redirect(admin_url('customers'));
}

$page = max(1, (int)get_param('page', 1));
$limit = 10;
$search = get_param('search', '');
$status = get_param('status', '');

$result = $customerModel->getPaginatedWithStats($page, $limit, $search, $status);
$total = $result['total'];
$totalPages = $result['total_pages'];
$customers = $result['data'];
$currentPage = $page;
$pageTitle = 'Quản lý khách hàng';
