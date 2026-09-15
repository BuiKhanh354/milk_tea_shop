<?php
// Lấy thông tin từ session (Giả định sau này sẽ query full từ DB)
$customer_name = $_SESSION['customer_name'] ?? 'Khách hàng';
$customer_email = $_SESSION['customer_email'] ?? '';
$customer = [
    'name' => $customer_name,
    'email' => $customer_email,
    'phone' => 'Chưa cập nhật',
    'address' => 'Chưa cập nhật',
    'avatar' => strtoupper(substr($customer_name, 0, 1))
];

$summary = [
    'orders' => 12,
    'favorites' => 4,
    'completed' => 10
];

$recent_orders = [
    [
        'id' => 'VT001',
        'date' => '15/09/2026',
        'items' => 3,
        'total' => '165.000đ',
        'status' => 'Preparing'
    ],
    [
        'id' => 'VT002',
        'date' => '10/09/2026',
        'items' => 1,
        'total' => '55.000đ',
        'status' => 'Completed'
    ]
];
?>

<div class="account-page bg-cream py-5" style="min-height: calc(100vh - 300px); padding-top: 120px !important;">
    <div class="container">
        <div class="row gy-4">
            <!-- Sidebar -->
            <div class="col-lg-3">
                <?php require_once __DIR__ . '/../../partials/account-sidebar.php'; ?>
            </div>

            <!-- Main Content -->
            <div class="col-lg-9">
                <!-- Welcome Section -->
                <div class="account-header-bg text-ivory p-4 p-lg-5 mb-4 position-relative overflow-hidden">
                    <div class="position-relative z-1">
                        <h2 class="font-serif fw-bold mb-2">Welcome back, <?= $customer['name'] ?></h2>
                        <p class="mb-0 text-cream opacity-75">Manage your account and keep track of your tea moments.</p>
                    </div>
                    <!-- Decorative Icon -->
                    <i class="fa-solid fa-leaf position-absolute text-sage opacity-25" style="font-size: 8rem; right: -20px; bottom: -20px; transform: rotate(-15deg);"></i>
                </div>

                <!-- Profile Card & Summary -->
                <div class="row gy-4 mb-5">
                    <!-- Profile Card -->
                    <div class="col-md-5">
                        <div class="card border-0 rounded-0 shadow-sm h-100">
                            <div class="card-body p-4 text-center">
                                <div class="avatar-wrapper mx-auto mb-3">
                                    <?= $customer['avatar'] ?>
                                </div>
                                <h5 class="font-serif fw-bold mb-1"><?= $customer['name'] ?></h5>
                                <p class="text-muted small mb-3"><?= $customer['email'] ?></p>
                                
                                <div class="text-start mt-4">
                                    <p class="mb-2 fs-6"><i class="fa-solid fa-phone text-sage me-2 w-20px text-center"></i> <?= $customer['phone'] ?></p>
                                    <p class="mb-3 fs-6"><i class="fa-solid fa-location-dot text-sage me-2 w-20px text-center"></i> <?= $customer['address'] ?></p>
                                </div>
                                
                                <a href="<?= BASE_URL ?>/profile/edit" class="btn btn-outline-forest w-100 mt-2">EDIT PROFILE</a>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Summary Cards -->
                    <div class="col-md-7">
                        <div class="row g-3 h-100">
                            <div class="col-sm-6">
                                <div class="card stat-card rounded-0 h-100">
                                    <div class="card-body p-4 text-center d-flex flex-column justify-content-center align-items-center">
                                        <div class="stat-icon-wrapper mb-3 text-forest fs-4">
                                            <i class="fa-solid fa-bag-shopping"></i>
                                        </div>
                                        <h3 class="font-serif fw-bold text-forest mb-1"><?= $summary['orders'] ?></h3>
                                        <p class="text-muted small text-uppercase tracking-wide mb-0">Total Orders</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="card stat-card rounded-0 h-100">
                                    <div class="card-body p-4 text-center d-flex flex-column justify-content-center align-items-center">
                                        <div class="stat-icon-wrapper mb-3 text-forest fs-4">
                                            <i class="fa-regular fa-heart"></i>
                                        </div>
                                        <h3 class="font-serif fw-bold text-forest mb-1"><?= $summary['favorites'] ?></h3>
                                        <p class="text-muted small text-uppercase tracking-wide mb-0">Favorites</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 mt-3">
                                <div class="card stat-card rounded-0 bg-forest text-ivory border-0 h-100">
                                    <div class="card-body p-4 d-flex align-items-center justify-content-between">
                                        <div>
                                            <p class="small text-cream text-uppercase tracking-wide mb-1">Completed Orders</p>
                                            <h3 class="font-serif fw-bold mb-0"><?= $summary['completed'] ?></h3>
                                        </div>
                                        <i class="fa-solid fa-check-circle text-sage fs-1 opacity-50"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Orders -->
                <div class="card border-0 rounded-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-bottom border-sage border-opacity-25 p-4 d-flex justify-content-between align-items-center">
                        <h5 class="font-serif fw-bold text-forest mb-0">RECENT ORDERS</h5>
                        <a href="<?= BASE_URL ?>/orders" class="text-sage text-decoration-none hover-forest transition-fast small fw-medium">VIEW ALL <i class="fa-solid fa-arrow-right ms-1"></i></a>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light text-muted small text-uppercase">
                                    <tr>
                                        <th class="ps-4 fw-medium py-3">Order ID</th>
                                        <th class="fw-medium py-3">Date</th>
                                        <th class="fw-medium py-3">Items</th>
                                        <th class="fw-medium py-3">Total</th>
                                        <th class="fw-medium py-3">Status</th>
                                        <th class="pe-4 text-end fw-medium py-3">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($recent_orders as $order): ?>
                                    <tr>
                                        <td class="ps-4 fw-medium text-dark">#<?= $order['id'] ?></td>
                                        <td class="text-muted"><?= $order['date'] ?></td>
                                        <td class="text-muted"><?= $order['items'] ?> items</td>
                                        <td class="fw-semibold text-caramel"><?= $order['total'] ?></td>
                                        <td>
                                            <?php
                                                $badgeClass = 'bg-secondary';
                                                if ($order['status'] === 'Completed') $badgeClass = 'bg-success';
                                                else if ($order['status'] === 'Preparing') $badgeClass = 'bg-warning text-dark';
                                                else if ($order['status'] === 'Pending') $badgeClass = 'bg-info text-dark';
                                            ?>
                                            <span class="badge <?= $badgeClass ?> rounded-pill fw-normal px-2 py-1"><?= $order['status'] ?></span>
                                        </td>
                                        <td class="pe-4 text-end">
                                            <a href="<?= BASE_URL ?>/orders/<?= $order['id'] ?>" class="btn btn-sm btn-outline-forest">VIEW</a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
