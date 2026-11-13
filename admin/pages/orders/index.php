<!-- admin/pages/orders/index.php -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="font-serif fw-bold text-forest mb-0">Quản Lý Đơn Hàng</h3>
</div>

<div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
    <div class="table-responsive">
        <table class="table align-middle">
            <thead class="text-muted small border-bottom">
                <tr>
                    <th>Mã Đơn</th>
                    <th>Khách Hàng</th>
                    <th>Số Điện Thoại</th>
                    <th>Thời Gian</th>
                    <th>Tổng Tiền</th>
                    <th>Trạng Thái</th>
                    <th>Cập Nhật</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $ord): ?>
                <tr>
                    <td class="fw-bold text-forest">#<?= (int)$ord['id'] ?></td>
                    <td><?= e($ord['customer_name'] ?? 'Khách vãng lai') ?></td>
                    <td><?= e($ord['customer_phone'] ?? '-') ?></td>
                    <td class="text-muted small"><?= date('d/m/Y H:i', strtotime($ord['created_at'])) ?></td>
                    <td class="fw-bold text-caramel"><?= money($ord['final_amount']) ?></td>
                    <td>
                        <span class="badge <?= $ord['status'] === 'completed' ? 'bg-success' : ($ord['status'] === 'cancelled' ? 'bg-danger' : 'bg-warning text-dark') ?>">
                            <?= e($ord['status']) ?>
                        </span>
                    </td>
                    <td>
                        <form action="<?= e(admin_url('orders', ['action' => 'update-status', 'id' => $ord['id']])) ?>" method="POST" class="d-flex gap-1">
                            <select name="status" class="form-select form-select-sm" style="width: 130px;">
                                <option value="pending" <?= $ord['status'] === 'pending' ? 'selected' : '' ?>>Chờ xử lý</option>
                                <option value="confirmed" <?= $ord['status'] === 'confirmed' ? 'selected' : '' ?>>Đã xác nhận</option>
                                <option value="preparing" <?= $ord['status'] === 'preparing' ? 'selected' : '' ?>>Đang pha</option>
                                <option value="completed" <?= $ord['status'] === 'completed' ? 'selected' : '' ?>>Hoàn thành</option>
                                <option value="cancelled" <?= $ord['status'] === 'cancelled' ? 'selected' : '' ?>>Đã huỷ</option>
                            </select>
                            <button type="submit" class="btn btn-sm btn-outline-forest">Lưu</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

