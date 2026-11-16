<?php
// admin/pages/customers/action.php — Quản lý khách hàng
$action = get_param('action', '');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'toggle_status') {
    $id = (int)post_param('id');
    if ($id) {
        db()->execute("UPDATE customers SET status = CASE WHEN status = 1 THEN 0 ELSE 1 END WHERE id = ?", [$id]);
        set_flash('success', "Đã thay đổi trạng thái tài khoản!");
    }
    redirect(admin_url('customers'));
}

$page = max(1, (int)get_param('page', 1));
$limit = 10;
$offset = ($page - 1) * $limit;

$search = get_param('search', '');
$status = get_param('status', '');

$conditions = [];
$params = [];

if ($search !== '') {
    $conditions[] = "(c.full_name LIKE ? OR c.email LIKE ? OR c.phone LIKE ?)";
    $like = '%' . $search . '%';
    $params[] = $like;
    $params[] = $like;
    $params[] = $like;
}

if ($status !== '') {
    $conditions[] = "c.status = ?";
    $params[] = (int)$status;
}

$whereClause = !empty($conditions) ? "WHERE " . implode(" AND ", $conditions) : "";

$countRow = db()->fetchOne("SELECT COUNT(*) as total FROM customers c $whereClause", $params);
$total = (int)($countRow['total'] ?? 0);
$totalPages = ceil($total / $limit);

$listParams = array_merge($params, [$limit, $offset]);
$customers = db()->fetchAll("SELECT c.*, 
       COUNT(o.id) as total_orders, 
       COALESCE(SUM(CASE WHEN o.status = 'completed' THEN o.final_amount ELSE 0 END), 0) as total_spent
FROM customers c
LEFT JOIN orders o ON c.id = o.customer_id
$whereClause
GROUP BY c.id
ORDER BY c.created_at DESC
LIMIT ? OFFSET ?", $listParams);

$currentPage = $page;
$pageTitle = 'Quản lý khách hàng';
