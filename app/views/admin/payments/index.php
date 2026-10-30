<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="font-serif fw-bold text-dark mb-1">Quản lý thanh toán & Giao dịch</h4>
        <p class="text-muted small mb-0">Theo dõi dòng tiền, phương thức thanh toán và trạng thái đối soát giao dịch</p>
    </div>
    <div class="d-flex gap-2">
        <a href="admin.php?route=orders" class="btn btn-outline-forest fw-medium px-3">
            <i class="fa-solid fa-receipt me-2"></i> Xem đơn hàng
        </a>
        <a href="admin.php?route=shift_sessions" class="btn btn-forest fw-medium px-3">
            <i class="fa-solid fa-cash-register me-2"></i> Bàn giao ca
        </a>
    </div>
</div>

<?php if (isset($_GET['msg'])): ?>
    <div class="alert alert-success alert-dismissible fade show rounded-4 border-0 shadow-sm mb-4">
        <i class="fa-solid fa-circle-check me-2"></i>
        <?= $_GET['msg'] === 'status_updated' ? 'Cập nhật trạng thái thanh toán thành công!' : 'Thao tác thành công!' ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if (isset($_GET['error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show rounded-4 border-0 shadow-sm mb-4">
        <i class="fa-solid fa-circle-exclamation me-2"></i>
        <?= $_GET['error'] === 'csrf_invalid' ? 'Mã bảo mật CSRF không hợp lệ, vui lòng thử lại!' : 'Thao tác thất bại, vui lòng kiểm tra lại.' ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<!-- 4 Thẻ KPI thống kê thanh toán -->
<div class="row g-3 mb-4">
    <!-- Tổng đã thanh toán -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-muted small fw-medium">Tổng thực thu (Đã thanh toán)</span>
                    <div class="rounded-3 bg-success bg-opacity-10 text-success p-2">
                        <i class="fa-solid fa-sack-dollar fs-5"></i>
                    </div>
                </div>
                <h4 class="fw-bold text-dark mb-1"><?= number_format($stats['total_paid_amount'], 0, ',', '.') ?> ₫</h4>
                <div class="text-muted small">
                    <span class="text-success fw-medium"><i class="fa-solid fa-check-double me-1"></i><?= number_format($stats['total_transactions']) ?></span> tổng giao dịch
                </div>
            </div>
        </div>
    </div>

    <!-- Tiền mặt -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-muted small fw-medium">Tiền mặt tại quầy</span>
                    <div class="rounded-3 bg-forest bg-opacity-10 text-forest p-2">
                        <i class="fa-solid fa-money-bill-wave fs-5 text-light"></i>
                    </div>
                </div>
                <h4 class="fw-bold text-forest mb-1"><?= number_format($stats['cash_paid_amount'], 0, ',', '.') ?> ₫</h4>
                <div class="text-muted small">Tiền mặt bàn giao vào két thu ngân</div>
            </div>
        </div>
    </div>

    <!-- Chuyển khoản / Ví MoMo -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-muted small fw-medium">Chuyển khoản & Ví điện tử</span>
                    <div class="rounded-3 bg-primary bg-opacity-10 text-primary p-2">
                        <i class="fa-solid fa-qrcode fs-5"></i>
                    </div>
                </div>
                <h4 class="fw-bold text-primary mb-1"><?= number_format($stats['online_paid_amount'], 0, ',', '.') ?> ₫</h4>
                <div class="text-muted small">Ngân hàng & ví MoMo tự động</div>
            </div>
        </div>
    </div>

    <!-- Đang chờ xử lý -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-muted small fw-medium">Chờ thanh toán (Pending)</span>
                    <div class="rounded-3 bg-warning bg-opacity-10 text-warning p-2">
                        <i class="fa-solid fa-clock fs-5"></i>
                    </div>
                </div>
                <h4 class="fw-bold text-warning mb-1"><?= number_format($stats['pending_amount'], 0, ',', '.') ?> ₫</h4>
                <div class="text-muted small">
                    <span class="fw-bold text-dark"><?= (int)$stats['pending_count'] ?></span> giao dịch chưa hoàn tất
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bộ lọc tìm kiếm nâng cao -->
<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-body p-3 p-md-4">
        <form action="admin.php" method="GET" class="row g-3 align-items-end">
            <input type="hidden" name="route" value="payments">
            
            <div class="col-12 col-md-4 col-lg-3">
                <label class="form-label text-muted small fw-medium mb-1">Tìm kiếm</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-0"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                    <input type="text" name="search" class="form-control bg-light border-0 shadow-none" placeholder="Mã đơn #ORD, mã GD, khách..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
                </div>
            </div>

            <div class="col-6 col-md-4 col-lg-2">
                <label class="form-label text-muted small fw-medium mb-1">Phương thức</label>
                <select name="method" class="form-select bg-light border-0 shadow-none">
                    <option value="">-- Tất cả --</option>
                    <option value="cash" <?= (isset($_GET['method']) && strtolower($_GET['method']) === 'cash') ? 'selected' : '' ?>>Tiền mặt</option>
                    <option value="bank_transfer" <?= (isset($_GET['method']) && strtolower($_GET['method']) === 'bank_transfer') ? 'selected' : '' ?>>Chuyển khoản</option>
                    <option value="momo" <?= (isset($_GET['method']) && strtolower($_GET['method']) === 'momo') ? 'selected' : '' ?>>Ví MoMo</option>
                </select>
            </div>

            <div class="col-6 col-md-4 col-lg-2">
                <label class="form-label text-muted small fw-medium mb-1">Trạng thái</label>
                <select name="status" class="form-select bg-light border-0 shadow-none">
                    <option value="">-- Tất cả --</option>
                    <option value="paid" <?= (isset($_GET['status']) && $_GET['status'] === 'paid') ? 'selected' : '' ?>>Đã thanh toán (Paid)</option>
                    <option value="pending" <?= (isset($_GET['status']) && $_GET['status'] === 'pending') ? 'selected' : '' ?>>Chờ thanh toán (Pending)</option>
                    <option value="failed" <?= (isset($_GET['status']) && $_GET['status'] === 'failed') ? 'selected' : '' ?>>Thất bại (Failed)</option>
                </select>
            </div>

            <div class="col-6 col-md-3 col-lg-2">
                <label class="form-label text-muted small fw-medium mb-1">Từ ngày</label>
                <input type="date" name="start_date" class="form-control bg-light border-0 shadow-none" value="<?= htmlspecialchars($_GET['start_date'] ?? '') ?>">
            </div>

            <div class="col-6 col-md-3 col-lg-2">
                <label class="form-label text-muted small fw-medium mb-1">Đến ngày</label>
                <input type="date" name="end_date" class="form-control bg-light border-0 shadow-none" value="<?= htmlspecialchars($_GET['end_date'] ?? '') ?>">
            </div>

            <div class="col-12 col-md-6 col-lg-1 d-flex gap-2">
                <button type="submit" class="btn btn-forest w-100 fw-medium shadow-none">
                    <i class="fa-solid fa-filter me-1"></i> Lọc
                </button>
                <?php if (!empty($_GET['search']) || !empty($_GET['method']) || !empty($_GET['status']) || !empty($_GET['start_date']) || !empty($_GET['end_date'])): ?>
                <a href="admin.php?route=payments" class="btn btn-light border shadow-none" title="Xóa lọc">
                    <i class="fa-solid fa-rotate-left"></i>
                </a>
                <?php endif; ?>
            </div>
        </form>
    </div>
</div>

<!-- Bảng danh sách thanh toán -->
<div class="admin-table-card">
    <div class="table-responsive">
        <table class="table mb-0 text-nowrap align-middle">
            <thead class="table-light">
                <tr>
                    <th class="ps-4">Mã GD</th>
                    <th>Đơn hàng</th>
                    <th>Khách hàng</th>
                    <th>Phương thức</th>
                    <th>Số tiền</th>
                    <th>Trạng thái</th>
                    <th>Mã tham chiếu</th>
                    <th>Thời gian</th>
                    <th class="text-end pe-4">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($payments)): ?>
                    <tr>
                        <td colspan="9" class="text-center py-5 text-muted">
                            <i class="fa-solid fa-receipt fs-1 d-block mb-3 opacity-25"></i>
                            Không tìm thấy giao dịch thanh toán nào phù hợp.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($payments as $p): ?>
                    <tr>
                        <!-- Mã GD -->
                        <td class="ps-4 fw-bold text-muted">
                            #PAY<?= str_pad($p['id'], 3, '0', STR_PAD_LEFT) ?>
                        </td>

                        <!-- Đơn hàng -->
                        <td>
                            <a href="admin.php?route=orders&action=show&id=<?= $p['order_id'] ?>" class="fw-bold text-forest text-decoration-none">
                                #ORD<?= str_pad($p['order_id'], 3, '0', STR_PAD_LEFT) ?>
                            </a>
                            <?php 
                            $typeBadge = '';
                            $typeText = '';
                            switch($p['order_type']) {
                                case 'dine_in': $typeBadge = 'bg-info bg-opacity-10 text-info'; $typeText = 'Tại bàn'; break;
                                case 'delivery': $typeBadge = 'bg-primary bg-opacity-10 text-primary'; $typeText = 'Giao hàng'; break;
                                default: $typeBadge = 'bg-secondary bg-opacity-10 text-secondary'; $typeText = 'Mang đi';
                            }
                            ?>
                            <span class="badge <?= $typeBadge ?> rounded-pill ms-1 px-2 small"><?= $typeText ?></span>
                        </td>

                        <!-- Khách hàng -->
                        <td>
                            <span class="fw-medium text-dark d-block"><?= htmlspecialchars($p['customer_name']) ?></span>
                            <?php if (!empty($p['customer_phone'])): ?>
                                <small class="text-muted"><i class="fa-solid fa-phone me-1 small"></i><?= htmlspecialchars($p['customer_phone']) ?></small>
                            <?php endif; ?>
                        </td>

                        <!-- Phương thức thanh toán -->
                        <td>
                            <?php
                            $mKey = strtolower($p['method'] ?? '');
                            $mIcon = 'fa-money-bill-wave';
                            $mClass = 'border bg-light text-dark';
                            if ($mKey === 'cash' || $mKey === 'tiền mặt') {
                                $mIcon = 'fa-money-bill-wave text-success';
                            } elseif ($mKey === 'bank_transfer' || $mKey === 'chuyển khoản' || $mKey === 'bank') {
                                $mIcon = 'fa-building-columns text-primary';
                            } elseif ($mKey === 'momo' || $mKey === 'ví momo') {
                                $mIcon = 'fa-wallet text-danger';
                            }
                            ?>
                            <span class="badge <?= $mClass ?> px-3 py-2 fw-medium rounded-pill">
                                <i class="fa-solid <?= $mIcon ?> me-1"></i>
                                <?= function_exists('format_payment_method') ? format_payment_method($p['method']) : ($p['method'] ?: 'Tiền mặt') ?>
                            </span>
                        </td>

                        <!-- Số tiền -->
                        <td>
                            <span class="fw-bold fs-6 text-dark"><?= number_format($p['amount'], 0, ',', '.') ?> ₫</span>
                        </td>

                        <!-- Trạng thái -->
                        <td>
                            <?php 
                            $statusBadge = 'bg-secondary';
                            $statusTitle = $p['status'];
                            switch($p['status']) {
                                case 'paid':
                                    $statusBadge = 'bg-success bg-opacity-15 text-light border border-success border-opacity-25';
                                    $statusTitle = 'Đã thanh toán';
                                    break;
                                case 'pending':
                                    $statusBadge = 'bg-warning bg-opacity-15 text-dark border border-warning border-opacity-25';
                                    $statusTitle = 'Chờ thanh toán';
                                    break;
                                case 'failed':
                                    $statusBadge = 'bg-danger bg-opacity-15 text-light border border-danger border-opacity-25';
                                    $statusTitle = 'Thất bại';
                                    break;
                            }
                            ?>
                            <span class="badge <?= $statusBadge ?> rounded-pill px-3 py-2 fw-semibold">
                                <?= $statusTitle ?>
                            </span>
                        </td>

                        <!-- Mã tham chiếu / Ngân hàng -->
                        <td>
                            <?php if (!empty($p['transaction_code'])): ?>
                                <code class="text-dark bg-light px-2 py-1 rounded small"><?= htmlspecialchars($p['transaction_code']) ?></code>
                            <?php else: ?>
                                <span class="text-muted small">---</span>
                            <?php endif; ?>
                        </td>

                        <!-- Thời gian -->
                        <td>
                            <div class="small fw-medium text-dark"><?= date('d/m/Y H:i', strtotime($p['created_at'])) ?></div>
                            <?php if (!empty($p['paid_at'])): ?>
                                <small class="text-success d-block"><i class="fa-solid fa-check me-1"></i><?= date('H:i d/m', strtotime($p['paid_at'])) ?></small>
                            <?php endif; ?>
                        </td>

                        <!-- Thao tác -->
                        <td class="text-end pe-4">
                            <div class="d-inline-flex gap-1">
                                <a href="admin.php?route=orders&action=show&id=<?= $p['order_id'] ?>" class="btn btn-sm btn-light border shadow-none" title="Xem chi tiết đơn hàng">
                                    <i class="fa-solid fa-eye text-muted"></i>
                                </a>
                                <button type="button" class="btn btn-sm btn-outline-forest shadow-none" data-bs-toggle="modal" data-bs-target="#updateModal<?= $p['id'] ?>" title="Cập nhật trạng thái">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </button>
                            </div>
                        </td>
                    </tr>

                    <!-- Modal cập nhật trạng thái thanh toán -->
                    <div class="modal fade" id="updateModal<?= $p['id'] ?>" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-sm">
                            <div class="modal-content border-0 rounded-4 shadow">
                                <form action="admin.php?route=payments&action=update_status" method="POST">
                                    <?php if (function_exists('csrf_field')) echo csrf_field(); ?>
                                    <input type="hidden" name="id" value="<?= $p['id'] ?>">

                                    <div class="modal-header border-bottom-0 pb-0">
                                        <h5 class="modal-title fw-bold">Cập nhật thanh toán</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3 p-3 bg-light rounded-3 text-center">
                                            <div class="text-muted small">Đơn hàng #ORD<?= str_pad($p['order_id'], 3, '0', STR_PAD_LEFT) ?></div>
                                            <div class="fs-5 fw-bold text-forest my-1"><?= number_format($p['amount'], 0, ',', '.') ?> ₫</div>
                                            <div class="small text-muted">
                                                PT: <strong><?= function_exists('format_payment_method') ? format_payment_method($p['method']) : $p['method'] ?></strong>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label text-muted small fw-medium">Trạng thái thanh toán</label>
                                            <select name="status" class="form-select bg-light border-0 shadow-none" required>
                                                <option value="paid" <?= $p['status'] === 'paid' ? 'selected' : '' ?>>Đã thanh toán (Paid)</option>
                                                <option value="pending" <?= $p['status'] === 'pending' ? 'selected' : '' ?>>Chờ thanh toán (Pending)</option>
                                                <option value="failed" <?= $p['status'] === 'failed' ? 'selected' : '' ?>>Thất bại (Failed)</option>
                                            </select>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label text-muted small fw-medium">Mã tham chiếu / Giao dịch (nếu có)</label>
                                            <input type="text" name="transaction_code" class="form-control bg-light border-0 shadow-none" placeholder="VD: FT241024..., MOMO..." value="<?= htmlspecialchars($p['transaction_code'] ?? '') ?>">
                                        </div>
                                    </div>
                                    <div class="modal-footer border-top-0 pt-0">
                                        <button type="button" class="btn btn-light shadow-none" data-bs-dismiss="modal">Đóng</button>
                                        <button type="submit" class="btn btn-forest px-4 shadow-none">Lưu thay đổi</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Phân trang -->
    <?php if ($totalPages > 1): ?>
    <div class="card-footer bg-white border-0 py-3">
        <nav aria-label="Payments pagination">
            <ul class="pagination justify-content-center mb-0">
                <?php 
                $queryParams = $_GET;
                unset($queryParams['page']);
                $baseQuery = http_build_query($queryParams);
                $queryPrefix = $baseQuery ? 'admin.php?' . $baseQuery . '&page=' : 'admin.php?route=payments&page=';
                ?>
                <!-- Trang trước -->
                <li class="page-item <?= ($currentPage <= 1) ? 'disabled' : '' ?>">
                    <a class="page-link shadow-none" href="<?= $queryPrefix . ($currentPage - 1) ?>">
                        <i class="fa-solid fa-chevron-left small"></i>
                    </a>
                </li>

                <!-- Danh sách trang -->
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <li class="page-item <?= ($i === $currentPage) ? 'active' : '' ?>">
                        <a class="page-link shadow-none" href="<?= $queryPrefix . $i ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>

                <!-- Trang sau -->
                <li class="page-item <?= ($currentPage >= $totalPages) ? 'disabled' : '' ?>">
                    <a class="page-link shadow-none" href="<?= $queryPrefix . ($currentPage + 1) ?>">
                        <i class="fa-solid fa-chevron-right small"></i>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
    <?php endif; ?>
</div>
