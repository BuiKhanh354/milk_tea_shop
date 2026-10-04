<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h3 mb-0 text-gray-800">
            <a href="admin.php?route=tables" class="text-decoration-none text-muted fs-5 me-2">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            Chi tiết Bàn <?= $table['table_number'] ?>
        </h2>
    </div>

    <div class="row">
        <div class="col-lg-6">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4">Thông tin bàn</h5>
                    <ul class="list-group list-group-flush mb-4">
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span class="text-muted">Mã số bàn</span>
                            <span class="fw-medium"><?= $table['table_number'] ?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span class="text-muted">Sức chứa</span>
                            <span class="fw-medium"><?= $table['capacity'] ?> người</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span class="text-muted">Trạng thái hiện tại</span>
                            <?php
                            $statusMap = [
                                'available' => ['Trống', 'bg-success text-white'],
                                'occupied' => ['Đang phục vụ', 'bg-danger text-white'],
                                'reserved' => ['Đã đặt trước', 'bg-warning text-dark']
                            ];
                            $statusInfo = $statusMap[$table['status']] ?? [$table['status'], 'bg-secondary'];
                            ?>
                            <span class="badge <?= $statusInfo[1] ?> px-3 py-2 rounded-pill"><?= $statusInfo[0] ?></span>
                        </li>
                    </ul>

                    <h5 class="fw-bold mb-3">Cập nhật trạng thái</h5>
                    <form action="admin.php?route=tables&action=update_status" method="POST">
                        <input type="hidden" name="id" value="<?= $table['id'] ?>">
                        <div class="mb-3">
                            <select name="status" class="form-select form-select-lg shadow-none">
                                <option value="available" <?= $table['status'] == 'available' ? 'selected' : '' ?>>Trống (Available)</option>
                                <option value="occupied" <?= $table['status'] == 'occupied' ? 'selected' : '' ?>>Đang phục vụ (Occupied)</option>
                                <option value="reserved" <?= $table['status'] == 'reserved' ? 'selected' : '' ?>>Đã đặt trước (Reserved)</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 py-2 rounded-3 shadow-none mb-3">Lưu thay đổi</button>
                    </form>
                    
                    <a href="admin.php?route=orders&action=create&table_id=<?= $table['id'] ?>" class="btn btn-success w-100 py-2 rounded-3 shadow-none">
                        <i class="fa-solid fa-plus me-1"></i> Tạo đơn cho bàn này
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
