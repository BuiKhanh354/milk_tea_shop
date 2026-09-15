<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="font-serif fw-bold text-dark mb-0">Danh sách Khách hàng</h4>
</div>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="bg-light text-muted">
                    <tr>
                        <th class="ps-4 py-3 fw-medium border-0 rounded-top-left">Khách hàng</th>
                        <th class="py-3 fw-medium border-0">Liên hệ</th>
                        <th class="py-3 fw-medium border-0">Tổng đơn</th>
                        <th class="py-3 fw-medium border-0">Tổng chi tiêu</th>
                        <th class="py-3 fw-medium border-0">Ngày đăng ký</th>
                        <th class="pe-4 py-3 fw-medium border-0 text-end rounded-top-right">Trạng thái</th>
                    </tr>
                </thead>
                <tbody class="border-top-0">
                    <?php if(empty($customers)): ?>
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">Chưa có khách hàng nào.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach($customers as $customer): ?>
                        <tr>
                            <td class="ps-4">
                                <div class="fw-bold text-dark"><?= $customer['full_name'] ?></div>
                            </td>
                            <td>
                                <div class="small text-muted mb-1"><i class="fa-solid fa-phone me-1"></i> <?= $customer['phone'] ?></div>
                                <div class="small text-muted"><i class="fa-solid fa-envelope me-1"></i> <?= $customer['email'] ?></div>
                            </td>
                            <td>
                                <span class="badge bg-forest bg-opacity-10 text-white rounded-pill px-3 py-2"><?= $customer['total_orders'] ?> đơn</span>
                            </td>
                            <td class="fw-bold text-dark"><?= number_format($customer['total_spent'], 0, ',', '.') ?> ₫</td>
                            <td class="text-muted small"><?= date('d/m/Y', strtotime($customer['created_at'])) ?></td>
                            <td class="text-end pe-4">
                                <div class="d-flex justify-content-end align-items-center gap-2">
                                    <?php if($customer['status'] == 1): ?>
                                        <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill mb-0 me-2"><i class="fa-solid fa-check me-1"></i> Hoạt động</span>
                                        <form action="admin.php?route=customers&action=toggle_status" method="POST" class="m-0" onsubmit="return confirm('Bạn muốn khóa tài khoản này?');">
                                            <input type="hidden" name="id" value="<?= $customer['id'] ?>">
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Khóa tài khoản"><i class="fa-solid fa-lock"></i></button>
                                        </form>
                                    <?php else: ?>
                                        <span class="badge bg-danger bg-opacity-10 text-danger px-3 py-2 rounded-pill mb-0 me-2"><i class="fa-solid fa-lock me-1"></i> Đã khóa</span>
                                        <form action="admin.php?route=customers&action=toggle_status" method="POST" class="m-0" onsubmit="return confirm('Bạn muốn mở khóa tài khoản này?');">
                                            <input type="hidden" name="id" value="<?= $customer['id'] ?>">
                                            <button type="submit" class="btn btn-sm btn-outline-success" title="Mở khóa tài khoản"><i class="fa-solid fa-unlock"></i></button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
