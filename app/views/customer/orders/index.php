<?php
$orders = [
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
    ],
    [
        'id' => 'VT003',
        'date' => '05/09/2026',
        'items' => 2,
        'total' => '110.000đ',
        'status' => 'Cancelled'
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
                <div class="card border-0 rounded-0 shadow-sm h-100">
                    <div class="card-header bg-white border-bottom border-sage border-opacity-25 p-4 d-flex flex-wrap justify-content-between align-items-center gap-3">
                        <h4 class="font-serif fw-bold text-forest mb-0">MY ORDERS</h4>
                        
                        <!-- Filters -->
                        <div class="dropdown">
                            <button class="btn btn-outline-forest btn-sm dropdown-toggle rounded-0" type="button" id="orderFilter" data-bs-toggle="dropdown" aria-expanded="false">
                                Filter: All
                            </button>
                            <ul class="dropdown-menu rounded-0 shadow-sm border-sage" aria-labelledby="orderFilter">
                                <li><a class="dropdown-item" href="#">All</a></li>
                                <li><a class="dropdown-item" href="#">Pending</a></li>
                                <li><a class="dropdown-item" href="#">Preparing</a></li>
                                <li><a class="dropdown-item" href="#">Completed</a></li>
                                <li><a class="dropdown-item text-danger" href="#">Cancelled</a></li>
                            </ul>
                        </div>
                    </div>
                    
                    <div class="card-body p-0">
                        <?php if (empty($orders)): ?>
                            <?php 
                                $empty_icon = 'fa-solid fa-receipt';
                                $empty_title = 'NO ORDERS YET';
                                $empty_message = 'Your tea journey hasn\'t started yet. Explore our menu to find your perfect drink.';
                                $empty_btn_text = 'EXPLORE MENU';
                                $empty_btn_link = '/products';
                                require __DIR__ . '/../../partials/empty-state.php'; 
                            ?>
                        <?php else: ?>
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
                                        <?php foreach ($orders as $order): ?>
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
                                                    else if ($order['status'] === 'Cancelled') $badgeClass = 'bg-danger';
                                                ?>
                                                <span class="badge <?= $badgeClass ?> rounded-pill fw-normal px-2 py-1"><?= $order['status'] ?></span>
                                            </td>
                                            <td class="pe-4 text-end">
                                                <a href="<?= BASE_URL ?>/orders/<?= $order['id'] ?>" class="btn btn-sm btn-outline-forest px-3">VIEW DETAILS</a>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
