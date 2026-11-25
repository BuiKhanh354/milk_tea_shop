<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <h4 class="font-serif fw-bold text-dark mb-0">
            <a href="<?= e(admin_url('tables')) ?>" class="text-decoration-none text-muted fs-5 me-2">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            Chi tiết & Phục vụ Bàn <?= e($table['table_number']) ?>
        </h4>
        <a href="<?= e(admin_url('order-create', ['table_id' => (int)$table['id']])) ?>" class="btn btn-forest rounded-pill px-4">
            <i class="fa-solid fa-plus me-2"></i> Gọi món cho bàn này
        </a>
    </div>

    <?php if ($successMsg = get_flash('success')): ?>
        <div class="alert alert-success alert-dismissible fade show rounded-4 border-0 shadow-sm mb-4" role="alert">
            <i class="fa-solid fa-check-circle me-2"></i> <?= e($successMsg) ?>
            <button type="button" class="btn-close shadow-none" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if ($errorMsg = get_flash('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show rounded-4 border-0 shadow-sm mb-4" role="alert">
            <i class="fa-solid fa-triangle-exclamation me-2"></i> <?= e($errorMsg) ?>
            <button type="button" class="btn-close shadow-none" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="row g-4">
        <!-- Cột trái: Thông tin bàn & Cập nhật trạng thái bàn -->
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 rounded-4 mb-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4 font-serif text-forest">Thông tin bàn</h5>
                    <ul class="list-group list-group-flush mb-4">
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span class="text-muted">Mã số bàn</span>
                            <span class="fw-bold fs-5"><?= e($table['table_number']) ?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span class="text-muted">Sức chứa</span>
                            <span class="fw-medium"><?= (int)$table['capacity'] ?> người</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span class="text-muted">Trạng thái hiện tại</span>
                            <?php
                            $statusMap = [
                                'available' => ['Trống', 'bg-success text-white'],
                                'occupied'  => ['Đang phục vụ', 'bg-primary text-white'],
                                'reserved'  => ['Đã đặt trước', 'bg-warning text-dark']
                            ];
                            $statusInfo = $statusMap[$table['status']] ?? [$table['status'], 'bg-secondary'];
                            ?>
                            <span class="badge <?= $statusInfo[1] ?> px-3 py-2 rounded-pill"><?= $statusInfo[0] ?></span>
                        </li>
                    </ul>

                    <h6 class="fw-bold mb-3 font-serif text-forest">Cập nhật trạng thái bàn</h6>
                    <form action="<?= e(admin_url('tables', ['action' => 'update_status'])) ?>" method="POST">
                        <input type="hidden" name="id" value="<?= (int)$table['id'] ?>">
                        <div class="mb-3">
                            <select name="status" class="form-select shadow-none">
                                <option value="available" <?= $table['status'] == 'available' ? 'selected' : '' ?>>Trống (Available)</option>
                                <option value="occupied" <?= $table['status'] == 'occupied' ? 'selected' : '' ?>>Đang phục vụ (Occupied)</option>
                                <option value="reserved" <?= $table['status'] == 'reserved' ? 'selected' : '' ?>>Đã đặt trước (Reserved)</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-outline-forest w-100 py-2 rounded-3 shadow-none">Lưu trạng thái bàn</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Cột phải: Theo dõi đơn đang phục vụ tại bàn -->
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 rounded-4 mb-4">
                <div class="card-header bg-white border-bottom py-3 px-4 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0 font-serif text-forest">
                        <i class="fa-solid fa-bell-concierge me-2 text-caramel"></i> Đơn đang phục vụ tại bàn
                    </h5>
                    <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 py-2 fw-semibold">
                        <?= count($activeOrders ?? []) ?> đơn đang mở
                    </span>
                </div>
                <div class="card-body p-4">
                    <?php if (empty($activeOrders)): ?>
                        <div class="text-center py-4 text-muted">
                            <i class="fa-solid fa-mug-saucer fs-2 mb-2 d-block opacity-50"></i>
                            Hiện chưa có đơn hàng nào đang phục vụ tại bàn <?= e($table['table_number']) ?>.
                        </div>
                    <?php else: ?>
                        <div class="d-flex flex-column gap-4">
                            <?php foreach ($activeOrders as $ao): ?>
                                <?php
                                $oStatusMap = [
                                    'pending'   => ['Chờ xác nhận', 'bg-warning bg-opacity-25 text-dark'],
                                    'confirmed' => ['Đã xác nhận', 'bg-info bg-opacity-10 text-info'],
                                    'preparing' => ['Đang pha chế', 'bg-primary bg-opacity-10 text-primary'],
                                    'ready'     => ['Sẵn sàng mang ra', 'bg-success bg-opacity-25 text-success'],
                                ];
                                $oStatusInfo = $oStatusMap[$ao['status']] ?? [$ao['status'], 'bg-secondary text-white'];
                                $orderCode = '#ORD' . str_pad($ao['id'], 3, '0', STR_PAD_LEFT);
                                ?>
                                <div class="border rounded-4 p-3 bg-light bg-opacity-50">
                                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 pb-2 mb-3 border-bottom">
                                        <div>
                                            <a href="<?= e(admin_url('orders', ['action' => 'show', 'id' => $ao['id']])) ?>" class="fw-bold font-monospace text-forest text-decoration-none fs-5">
                                                <?= e($orderCode) ?>
                                            </a>
                                            <span class="text-muted small ms-2">
                                                <i class="fa-regular fa-clock me-1"></i><?= date('H:i d/m/Y', strtotime($ao['created_at'])) ?>
                                            </span>
                                        </div>
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="badge <?= $oStatusInfo[1] ?> rounded-pill px-3 py-2 fw-medium">
                                                <?= e($oStatusInfo[0]) ?>
                                            </span>
                                        </div>
                                    </div>

                                    <!-- Danh sách món của đơn -->
                                    <div class="table-responsive mb-3">
                                        <table class="table table-sm align-middle mb-0 bg-white rounded-3 overflow-hidden">
                                            <thead class="table-light text-muted small">
                                                <tr>
                                                    <th class="ps-3 py-2">Món</th>
                                                    <th class="text-center py-2">SL</th>
                                                    <th class="text-end pe-3 py-2">Thành tiền</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($ao['items'] as $item): ?>
                                                <tr>
                                                    <td class="ps-3 py-2">
                                                        <div class="fw-semibold text-dark"><?= e($item['product_name']) ?></div>
                                                        <div class="small text-muted">
                                                            <?= !empty($item['size_name']) ? 'Size ' . e($item['size_name']) : '' ?>
                                                            <?= !empty($item['toppings']) ? ' • Topping: ' . e($item['toppings']) : '' ?>
                                                        </div>
                                                    </td>
                                                    <td class="text-center fw-bold"><?= (int)$item['quantity'] ?></td>
                                                    <td class="text-end pe-3 font-monospace"><?= money($item['subtotal']) ?></td>
                                                </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>

                                    <!-- Tổng tiền & Nút cập nhật trạng thái phục vụ -->
                                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                                        <div>
                                            <span class="text-muted small">Tổng cộng:</span>
                                            <span class="fw-bold text-forest fs-5 font-monospace ms-1"><?= money($ao['final_amount']) ?></span>
                                            <span class="badge bg-light text-dark border ms-2"><?= e(format_payment_method($ao['payment_method'])) ?></span>
                                        </div>
                                        <div class="d-flex gap-2 flex-wrap">
                                            <?php if ($ao['status'] === 'pending' || $ao['status'] === 'confirmed'): ?>
                                                <form action="<?= e(admin_url('orders', ['action' => 'update_status'])) ?>" method="POST" class="d-inline">
                                                    <input type="hidden" name="id" value="<?= (int)$ao['id'] ?>">
                                                    <input type="hidden" name="status" value="preparing">
                                                    <input type="hidden" name="redirect" value="table">
                                                    <button type="submit" class="btn btn-sm btn-primary rounded-pill px-3">
                                                        <i class="fa-solid fa-blender me-1"></i> Pha chế
                                                    </button>
                                                </form>
                                            <?php endif; ?>

                                            <?php if ($ao['status'] !== 'ready'): ?>
                                                <form action="<?= e(admin_url('orders', ['action' => 'update_status'])) ?>" method="POST" class="d-inline">
                                                    <input type="hidden" name="id" value="<?= (int)$ao['id'] ?>">
                                                    <input type="hidden" name="status" value="ready">
                                                    <input type="hidden" name="redirect" value="table">
                                                    <button type="submit" class="btn btn-sm btn-info text-white rounded-pill px-3">
                                                        <i class="fa-solid fa-bell me-1"></i> Sẵn sàng
                                                    </button>
                                                </form>
                                            <?php endif; ?>

                                            <form action="<?= e(admin_url('orders', ['action' => 'update_status'])) ?>" method="POST" class="d-inline">
                                                <input type="hidden" name="id" value="<?= (int)$ao['id'] ?>">
                                                <input type="hidden" name="status" value="completed">
                                                <input type="hidden" name="redirect" value="table">
                                                <button type="submit" class="btn btn-sm btn-success rounded-pill px-3">
                                                    <i class="fa-solid fa-check me-1"></i> Hoàn thành & Trả bàn
                                                </button>
                                            </form>

                                            <form action="<?= e(admin_url('orders', ['action' => 'update_status'])) ?>" method="POST" class="d-inline" onsubmit="return confirm('Xác nhận hủy đơn <?= e($orderCode) ?>?');">
                                                <input type="hidden" name="id" value="<?= (int)$ao['id'] ?>">
                                                <input type="hidden" name="status" value="cancelled">
                                                <input type="hidden" name="redirect" value="table">
                                                <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-3">
                                                    <i class="fa-solid fa-xmark me-1"></i> Hủy
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Lịch sử đơn gần đây tại bàn -->
            <?php if (!empty($recentTableOrders)): ?>
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-header bg-white border-bottom py-3 px-4">
                    <h6 class="fw-bold mb-0 font-serif text-forest">Lịch sử phục vụ gần đây tại bàn</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light text-muted small">
                                <tr>
                                    <th class="ps-4 py-2">Mã đơn</th>
                                    <th class="py-2">Khách / Nhân viên</th>
                                    <th class="py-2">Tổng tiền</th>
                                    <th class="py-2">Trạng thái</th>
                                    <th class="pe-4 py-2 text-end">Thời gian</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recentTableOrders as $ro): ?>
                                <tr>
                                    <td class="ps-4 font-monospace fw-bold">
                                        <a href="<?= e(admin_url('orders', ['action' => 'show', 'id' => $ro['id']])) ?>" class="text-decoration-none text-forest">
                                            #ORD<?= str_pad($ro['id'], 3, '0', STR_PAD_LEFT) ?>
                                        </a>
                                    </td>
                                    <td><?= e($ro['customer_name']) ?></td>
                                    <td class="font-monospace fw-semibold"><?= money($ro['final_amount']) ?></td>
                                    <td>
                                        <?php if ($ro['status'] === 'completed'): ?>
                                            <span class="badge bg-success rounded-pill px-2 py-1">Hoàn thành</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger rounded-pill px-2 py-1">Đã hủy</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="pe-4 text-end text-muted small"><?= date('d/m/Y H:i', strtotime($ro['created_at'])) ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
