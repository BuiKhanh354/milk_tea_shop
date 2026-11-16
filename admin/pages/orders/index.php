<?php
if ($action === 'create' || get_param('page') === 'order-create') {
    require __DIR__ . '/create.php';
    return;
}
if ($action === 'show' || $action === 'detail' || get_param('page') === 'order-detail') {
    require __DIR__ . '/show.php';
    return;
}
?>
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <h4 class="font-serif fw-bold text-dark mb-0">Quản lý đơn hàng</h4>
    <a href="<?= e(admin_url('orders', ['action' => 'create'])) ?>" class="btn btn-forest rounded-pill fw-medium px-4">
        <i class="fa-solid fa-plus me-2"></i> Tạo đơn hàng (POS)
    </a>
</div>

<?php if ($successMsg = get_flash('success')): ?>
    <div class="alert alert-success alert-dismissible fade show rounded-4 border-0 shadow-sm mb-4">
        <i class="fa-solid fa-circle-check me-2"></i> <?= e($successMsg) ?>
        <button type="button" class="btn-close shadow-none" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if ($errorMsg = get_flash('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show rounded-4 border-0 shadow-sm mb-4">
        <i class="fa-solid fa-triangle-exclamation me-2"></i> <?= e($errorMsg) ?>
        <button type="button" class="btn-close shadow-none" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<!-- Filters -->
<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-body p-3">
        <form action="<?= e(admin_url('orders')) ?>" method="GET">
            <input type="hidden" name="page" value="orders">
            <div class="row g-3">
                <div class="col-md-5">
                    <input type="text" name="search" class="form-control bg-light border-0 shadow-none" placeholder="Tìm mã đơn, khách hàng..." value="<?= e(get_param('search')) ?>">
                </div>
                <div class="col-md-4">
                    <select name="status" class="form-select bg-light border-0 shadow-none">
                        <option value="">Tất cả trạng thái</option>
                        <option value="pending" <?= get_param('status') === 'pending' ? 'selected' : '' ?>>Chờ xác nhận</option>
                        <option value="confirmed" <?= get_param('status') === 'confirmed' ? 'selected' : '' ?>>Đã xác nhận</option>
                        <option value="preparing" <?= get_param('status') === 'preparing' ? 'selected' : '' ?>>Đang chuẩn bị</option>
                        <option value="ready" <?= get_param('status') === 'ready' ? 'selected' : '' ?>>Sẵn sàng</option>
                        <option value="completed" <?= get_param('status') === 'completed' ? 'selected' : '' ?>>Hoàn thành</option>
                        <option value="cancelled" <?= get_param('status') === 'cancelled' ? 'selected' : '' ?>>Đã hủy</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-outline-forest w-100 rounded-3"><i class="fa-solid fa-filter me-2"></i> Lọc</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Orders Table -->
<div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table mb-0 text-nowrap align-middle table-hover">
                <thead class="bg-light text-muted">
                    <tr>
                        <th class="ps-4 py-3 fw-medium border-0">Mã đơn</th>
                        <th class="py-3 fw-medium border-0">Khách hàng</th>
                        <th class="py-3 fw-medium border-0">Loại đơn</th>
                        <th class="py-3 fw-medium border-0">Tổng tiền</th>
                        <th class="py-3 fw-medium border-0">Thanh toán</th>
                        <th class="py-3 fw-medium border-0">Trạng thái</th>
                        <th class="py-3 fw-medium border-0">Ngày tạo</th>
                        <th class="text-end pe-4 py-3 fw-medium border-0">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="border-top-0">
                    <?php if (empty($orders)): ?>
                        <tr><td colspan="8" class="text-center py-5 text-muted">Không tìm thấy đơn hàng nào.</td></tr>
                    <?php else: ?>
                        <?php foreach ($orders as $order): ?>
                        <tr>
                            <td class="ps-4 fw-bold font-monospace text-forest">
                                <a href="<?= e(admin_url('orders', ['action' => 'show', 'id' => $order['id']])) ?>" class="text-decoration-none text-forest">
                                    <?= e($order['code']) ?>
                                </a>
                            </td>
                            <td><div class="fw-bold text-dark"><?= e($order['customer']) ?></div></td>
                            <td><span class="badge bg-light text-dark border px-2 py-1"><?= e($order['type']) ?></span></td>
                            <td class="fw-bold text-dark font-monospace"><?= e($order['total']) ?></td>
                            <td><span class="badge bg-light text-dark border px-2 py-1"><?= e($order['payment']) ?></span></td>
                            <td>
                                <?php 
                                $badgeClass = '';
                                $statusText = '';
                                switch (strtolower($order['status'])) {
                                    case 'pending':   $badgeClass = 'bg-warning bg-opacity-25 text-dark'; $statusText = 'Chờ xác nhận'; break;
                                    case 'confirmed': $badgeClass = 'bg-info bg-opacity-10 text-info'; $statusText = 'Đã xác nhận'; break;
                                    case 'preparing': $badgeClass = 'bg-primary bg-opacity-10 text-primary'; $statusText = 'Đang chuẩn bị'; break;
                                    case 'ready':     $badgeClass = 'bg-success bg-opacity-25 text-success'; $statusText = 'Sẵn sàng'; break;
                                    case 'completed': $badgeClass = 'bg-success text-white'; $statusText = 'Hoàn thành'; break;
                                    case 'cancelled': $badgeClass = 'bg-danger text-white'; $statusText = 'Đã hủy'; break;
                                    default:          $badgeClass = 'bg-secondary text-white'; $statusText = $order['status'];
                                }
                                ?>
                                <span class="badge <?= $badgeClass ?> rounded-pill px-3 py-2 fw-medium"><?= $statusText ?></span>
                            </td>
                            <td class="text-muted small"><?= e($order['date']) ?></td>
                            <td class="text-end pe-4">
                                <div class="d-inline-flex gap-1 align-items-center">
                                    <a href="<?= e(admin_url('orders', ['action' => 'show', 'id' => $order['id']])) ?>" class="btn btn-sm btn-light border shadow-none" title="Xem chi tiết">
                                        <i class="fa-solid fa-eye text-muted"></i>
                                    </a>
                                    <div class="dropdown d-inline-block">
                                        <button class="btn btn-sm btn-light border shadow-none dropdown-toggle" data-bs-toggle="dropdown" title="Cập nhật trạng thái">
                                            <i class="fa-solid fa-ellipsis-vertical text-muted"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">
                                            <li>
                                                <form action="<?= e(admin_url('orders', ['action' => 'update_status'])) ?>" method="POST">
                                                    <input type="hidden" name="id" value="<?= (int)$order['id'] ?>">
                                                    <input type="hidden" name="status" value="confirmed">
                                                    <input type="hidden" name="redirect" value="orders">
                                                    <button type="submit" class="dropdown-item">Xác nhận đơn</button>
                                                </form>
                                            </li>
                                            <li>
                                                <form action="<?= e(admin_url('orders', ['action' => 'update_status'])) ?>" method="POST">
                                                    <input type="hidden" name="id" value="<?= (int)$order['id'] ?>">
                                                    <input type="hidden" name="status" value="preparing">
                                                    <input type="hidden" name="redirect" value="orders">
                                                    <button type="submit" class="dropdown-item">Bắt đầu pha chế</button>
                                                </form>
                                            </li>
                                            <li>
                                                <form action="<?= e(admin_url('orders', ['action' => 'update_status'])) ?>" method="POST">
                                                    <input type="hidden" name="id" value="<?= (int)$order['id'] ?>">
                                                    <input type="hidden" name="status" value="ready">
                                                    <input type="hidden" name="redirect" value="orders">
                                                    <button type="submit" class="dropdown-item">Báo sẵn sàng</button>
                                                </form>
                                            </li>
                                            <li>
                                                <form action="<?= e(admin_url('orders', ['action' => 'update_status'])) ?>" method="POST">
                                                    <input type="hidden" name="id" value="<?= (int)$order['id'] ?>">
                                                    <input type="hidden" name="status" value="completed">
                                                    <input type="hidden" name="redirect" value="orders">
                                                    <button type="submit" class="dropdown-item text-success">Hoàn thành</button>
                                                </form>
                                            </li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <form action="<?= e(admin_url('orders', ['action' => 'update_status'])) ?>" method="POST" onsubmit="return confirm('Bạn có chắc muốn hủy đơn này?');">
                                                    <input type="hidden" name="id" value="<?= (int)$order['id'] ?>">
                                                    <input type="hidden" name="status" value="cancelled">
                                                    <input type="hidden" name="redirect" value="orders">
                                                    <button type="submit" class="dropdown-item text-danger">Hủy đơn</button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Phân trang -->
        <?php if (isset($totalPages) && $totalPages > 1): ?>
        <?php 
            $queryString = '';
            if (get_param('search') !== '') $queryString .= '&search=' . urlencode(get_param('search'));
            if (get_param('status') !== '') $queryString .= '&status=' . urlencode(get_param('status'));
            $curP = $orderPage ?? 1;
        ?>
        <div class="card-footer bg-white border-top py-3 px-4 d-flex align-items-center justify-content-between">
            <span class="text-muted small">Hiển thị trang <?= $curP ?> của <?= $totalPages ?> (Tổng <?= $total ?> đơn)</span>
            <nav aria-label="Page navigation">
                <ul class="pagination pagination-sm mb-0">
                    <li class="page-item <?= $curP <= 1 ? 'disabled' : '' ?>">
                        <a class="page-link <?= $curP <= 1 ? '' : 'text-forest' ?>" href="<?= $curP > 1 ? admin_url('orders') . $queryString . '&p=' . ($curP - 1) : '#' ?>">Trước</a>
                    </li>
                    
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <li class="page-item <?= $i === $curP ? 'active' : '' ?>">
                            <a class="page-link <?= $i === $curP ? 'bg-forest border-forest text-white' : 'text-forest' ?>" href="<?= admin_url('orders') . $queryString . '&p=' . $i ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>
                    
                    <li class="page-item <?= $curP >= $totalPages ? 'disabled' : '' ?>">
                        <a class="page-link <?= $curP >= $totalPages ? '' : 'text-forest' ?>" href="<?= $curP < $totalPages ? admin_url('orders') . $queryString . '&p=' . ($curP + 1) : '#' ?>">Sau</a>
                    </li>
                </ul>
            </nav>
        </div>
        <?php endif; ?>
    </div>
</div>
