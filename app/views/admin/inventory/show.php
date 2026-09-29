<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="font-serif fw-bold text-dark mb-0">CHI TIẾT NGUYÊN LIỆU</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 small">
                <li class="breadcrumb-item"><a href="admin.php?route=inventory" class="text-muted text-decoration-none">Kho</a></li>
                <li class="breadcrumb-item active"><?= htmlspecialchars($item['ingredient_name']) ?></li>
            </ol>
        </nav>
    </div>
    <div>
        <a href="admin.php?route=inventory" class="btn btn-light border shadow-none">
            <i class="fa-solid fa-arrow-left me-2"></i> Quay lại
        </a>
    </div>
</div>

<div class="row g-4">
    <!-- Info Column -->
    <div class="col-md-5">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-4 text-forest border-bottom pb-3">Thông tin chung</h5>
                
                <table class="table table-borderless mb-0">
                    <tbody>
                        <tr>
                            <td class="text-muted w-50 pb-3">Tên nguyên liệu:</td>
                            <td class="fw-bold text-dark pb-3"><?= htmlspecialchars($item['ingredient_name']) ?></td>
                        </tr>
                        <tr>
                            <td class="text-muted pb-3">Đơn vị:</td>
                            <td class="pb-3"><span class="badge bg-light text-dark border px-2 py-1"><?= htmlspecialchars($item['unit']) ?></span></td>
                        </tr>
                        <tr>
                            <td class="text-muted pb-3">Tồn kho hiện tại:</td>
                            <td class="pb-3">
                                <span class="fw-bold fs-5 <?= $item['quantity'] <= 0 ? 'text-danger' : ($item['quantity'] <= $item['min_quantity'] ? 'text-warning' : 'text-success') ?>">
                                    <?= floatval($item['quantity']) ?>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-muted pb-3">Mức tối thiểu:</td>
                            <td class="pb-3 fw-medium"><?= floatval($item['min_quantity']) ?></td>
                        </tr>
                        <tr>
                            <td class="text-muted pb-3">Giá nhập tham khảo:</td>
                            <td class="pb-3 fw-medium"><?= number_format($item['price'], 0, ',', '.') ?> đ</td>
                        </tr>
                        <tr>
                            <td class="text-muted pb-3">Trạng thái:</td>
                            <td class="pb-3">
                                <?php if($item['quantity'] > $item['min_quantity']): ?>
                                    <span class="badge bg-success bg-opacity-10 text-success px-2 py-1 rounded-pill">Còn hàng</span>
                                <?php elseif($item['quantity'] > 0 && $item['quantity'] <= $item['min_quantity']): ?>
                                    <span class="badge bg-warning bg-opacity-10 text-warning px-2 py-1 rounded-pill">Sắp hết</span>
                                <?php else: ?>
                                    <span class="badge bg-danger bg-opacity-10 text-danger px-2 py-1 rounded-pill">Hết hàng</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-muted pb-0">Ghi chú:</td>
                            <td class="pb-0 text-muted fst-italic"><?= htmlspecialchars($item['note'] ?? 'Không có') ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- History Column -->
    <div class="col-md-7">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
                    <h5 class="fw-bold mb-0 text-forest">Lịch sử Nhập/Xuất gần đây</h5>
                    <a href="admin.php?route=inventory_history&ingredient_id=<?= $item['id'] ?>" class="text-decoration-none small fw-medium">Xem tất cả</a>
                </div>
                
                <?php if(empty($history)): ?>
                    <div class="text-center py-5 text-muted">
                        <i class="fa-regular fa-clock fs-1 mb-3 text-light"></i>
                        <p class="mb-0">Chưa có lịch sử giao dịch nào.</p>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-3 py-2 fw-medium border-0 rounded-top-left small text-muted">Ngày</th>
                                    <th class="py-2 fw-medium border-0 small text-muted">Loại</th>
                                    <th class="py-2 fw-medium border-0 small text-muted">Số lượng</th>
                                    <th class="pe-3 py-2 fw-medium border-0 rounded-top-right small text-muted">Người tạo</th>
                                </tr>
                            </thead>
                            <tbody class="border-top-0">
                                <?php foreach($history as $tx): ?>
                                <tr>
                                    <td class="ps-3 text-muted small"><?= date('d/m/Y H:i', strtotime($tx['created_at'])) ?></td>
                                    <td>
                                        <?php if($tx['type'] === 'IMPORT'): ?>
                                            <span class="badge bg-success bg-opacity-10 text-success px-2 py-1">NHẬP KHO</span>
                                        <?php elseif($tx['type'] === 'EXPORT'): ?>
                                            <span class="badge bg-danger bg-opacity-10 text-danger px-2 py-1">XUẤT KHO</span>
                                        <?php elseif($tx['type'] === 'USAGE'): ?>
                                            <span class="badge bg-primary bg-opacity-10 text-primary px-2 py-1">TIÊU HAO</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary bg-opacity-10 text-secondary px-2 py-1"><?= $tx['type'] ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="fw-bold <?= in_array($tx['type'], ['EXPORT','USAGE']) ? 'text-danger' : 'text-success' ?>">
                                        <?= in_array($tx['type'], ['EXPORT','USAGE']) ? '-' : '+' ?><?= floatval($tx['quantity']) ?>
                                    </td>
                                    <td class="pe-3 text-muted small"><?= htmlspecialchars($tx['user_name'] ?? 'Hệ thống') ?></td>
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
