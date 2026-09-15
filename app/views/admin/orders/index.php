<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="font-serif fw-bold text-dark mb-0">Quản lý đơn hàng</h4>
    <button class="btn btn-forest fw-medium px-4">
        <i class="fa-solid fa-plus me-2"></i> Tạo đơn hàng
    </button>
</div>

<!-- Filters -->
<div class="card border-0 shadow-sm rounded-3 mb-4">
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-3">
                <input type="text" class="form-control bg-light border-0" placeholder="Tìm mã đơn, khách hàng...">
            </div>
            <div class="col-md-2">
                <select class="form-select bg-light border-0">
                    <option value="">Trạng thái</option>
                    <option value="Pending">Chờ xác nhận</option>
                    <option value="Preparing">Đang chuẩn bị</option>
                    <option value="Completed">Hoàn thành</option>
                </select>
            </div>
            <div class="col-md-2">
                <select class="form-select bg-light border-0">
                    <option value="">Loại đơn</option>
                    <option value="Delivery">Delivery</option>
                    <option value="Takeaway">Takeaway</option>
                    <option value="Dine-in">Dine-in</option>
                </select>
            </div>
            <div class="col-md-3">
                <input type="date" class="form-control bg-light border-0">
            </div>
            <div class="col-md-2">
                <button class="btn btn-outline-forest w-100"><i class="fa-solid fa-filter me-2"></i> Lọc</button>
            </div>
        </div>
    </div>
</div>

<!-- Orders Table -->
<div class="admin-table-card">
    <div class="table-responsive">
        <table class="table mb-0 text-nowrap align-middle">
            <thead>
                <tr>
                    <th class="ps-4">Mã đơn</th>
                    <th>Khách hàng</th>
                    <th>Loại đơn</th>
                    <th>Tổng tiền</th>
                    <th>Thanh toán</th>
                    <th>Trạng thái</th>
                    <th>Ngày tạo</th>
                    <th class="text-end pe-4">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($orders as $order): ?>
                <tr>
                    <td class="ps-4 fw-bold text-forest"><?= $order['code'] ?></td>
                    <td><?= $order['customer'] ?></td>
                    <td><?= $order['type'] ?></td>
                    <td class="fw-bold text-dark"><?= $order['total'] ?></td>
                    <td><span class="badge bg-light text-dark border"><?= $order['payment'] ?></span></td>
                    <td>
                        <?php 
                        $badgeClass = '';
                        $statusText = '';
                        switch($order['status']) {
                            case 'Pending': $badgeClass = 'badge-pending'; $statusText = 'Chờ xác nhận'; break;
                            case 'Confirmed': $badgeClass = 'bg-info bg-opacity-10 text-info'; $statusText = 'Đã xác nhận'; break;
                            case 'Preparing': $badgeClass = 'badge-preparing'; $statusText = 'Đang chuẩn bị'; break;
                            case 'Ready': $badgeClass = 'bg-success bg-opacity-25 text-success'; $statusText = 'Sẵn sàng'; break;
                            case 'Completed': $badgeClass = 'badge-completed'; $statusText = 'Hoàn thành'; break;
                            case 'Cancelled': $badgeClass = 'badge-cancelled'; $statusText = 'Đã hủy'; break;
                            default: $badgeClass = 'bg-secondary text-white'; $statusText = $order['status'];
                        }
                        ?>
                        <span class="badge <?= $badgeClass ?> rounded-pill px-3 py-2 fw-medium"><?= $statusText ?></span>
                    </td>
                    <td class="text-muted"><?= $order['date'] ?></td>
                    <td class="text-end pe-4">
                        <a href="admin.php?route=orders&action=show&id=<?= $order['id'] ?>" class="btn btn-sm btn-light border shadow-none" data-bs-toggle="tooltip" title="Xem chi tiết">
                            <i class="fa-solid fa-eye text-muted"></i>
                        </a>
                        <button class="btn btn-sm btn-light border shadow-none" data-bs-toggle="dropdown" title="Cập nhật trạng thái">
                            <i class="fa-solid fa-ellipsis-vertical text-muted"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">
                            <li><a class="dropdown-item" href="#">Xác nhận đơn</a></li>
                            <li><a class="dropdown-item" href="#">Bắt đầu chuẩn bị</a></li>
                            <li><a class="dropdown-item" href="#">Hoàn thành</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="#">Hủy đơn</a></li>
                        </ul>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <!-- Pagination -->
    <div class="card-footer bg-white border-top py-3 px-4 d-flex align-items-center justify-content-between">
        <span class="text-muted small">Hiển thị 1-6 của 128 đơn hàng</span>
        <nav aria-label="Page navigation">
            <ul class="pagination pagination-sm mb-0">
                <li class="page-item disabled"><a class="page-link" href="#">Trước</a></li>
                <li class="page-item active"><a class="page-link bg-forest border-forest" href="#">1</a></li>
                <li class="page-item"><a class="page-link text-forest" href="#">2</a></li>
                <li class="page-item"><a class="page-link text-forest" href="#">3</a></li>
                <li class="page-item"><a class="page-link text-forest" href="#">Sau</a></li>
            </ul>
        </nav>
    </div>
</div>
