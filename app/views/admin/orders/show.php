<div class="d-flex justify-content-between align-items-center mb-4">
    <div class="d-flex align-items-center gap-3">
        <a href="admin.php?route=orders" class="btn btn-light border shadow-none">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <h4 class="font-serif fw-bold text-dark mb-0">Chi tiết đơn hàng #ORD<?= str_pad($order['id'], 3, '0', STR_PAD_LEFT) ?></h4>
    </div>
    
    <!-- Nút hành động cập nhật trạng thái -->
    <div class="d-flex gap-2">
        <?php if($order['status'] !== 'completed' && $order['status'] !== 'cancelled'): ?>
            <form action="admin.php?route=orders&action=update_status" method="POST">
                <input type="hidden" name="id" value="<?= $order['id'] ?>">
                <?php if($order['status'] === 'pending'): ?>
                    <input type="hidden" name="status" value="confirmed">
                    <button type="submit" class="btn btn-info text-white fw-medium px-4">Xác nhận đơn</button>
                <?php elseif($order['status'] === 'confirmed'): ?>
                    <input type="hidden" name="status" value="preparing">
                    <button type="submit" class="btn btn-primary fw-medium px-4">Bắt đầu pha chế</button>
                <?php elseif($order['status'] === 'preparing'): ?>
                    <input type="hidden" name="status" value="ready">
                    <button type="submit" class="btn btn-warning fw-medium px-4">Báo sẵn sàng</button>
                <?php elseif($order['status'] === 'ready'): ?>
                    <input type="hidden" name="status" value="completed">
                    <button type="submit" class="btn btn-success fw-medium px-4">Hoàn thành đơn</button>
                <?php endif; ?>
            </form>
            <form action="admin.php?route=orders&action=update_status" method="POST" onsubmit="return confirm('Xác nhận hủy đơn hàng này?');">
                <input type="hidden" name="id" value="<?= $order['id'] ?>">
                <input type="hidden" name="status" value="cancelled">
                <button type="submit" class="btn btn-outline-danger fw-medium px-4">Hủy đơn</button>
            </form>
        <?php endif; ?>
    </div>
</div>

<div class="row g-4">
    <!-- Cột trái: Thông tin -->
    <div class="col-12 col-xl-4">
        <!-- Trạng thái -->
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body p-4 text-center">
                <h6 class="text-muted fw-bold mb-3 text-uppercase">Trạng thái hiện tại</h6>
                <?php 
                $badgeClass = '';
                $statusText = '';
                switch($order['status']) {
                    case 'pending': $badgeClass = 'badge-pending'; $statusText = 'Chờ xác nhận'; break;
                    case 'confirmed': $badgeClass = 'bg-info bg-opacity-10 text-info'; $statusText = 'Đã xác nhận'; break;
                    case 'preparing': $badgeClass = 'badge-preparing'; $statusText = 'Đang chuẩn bị'; break;
                    case 'ready': $badgeClass = 'bg-success bg-opacity-25 text-success'; $statusText = 'Sẵn sàng'; break;
                    case 'completed': $badgeClass = 'badge-completed'; $statusText = 'Hoàn thành'; break;
                    case 'cancelled': $badgeClass = 'badge-cancelled'; $statusText = 'Đã hủy'; break;
                }
                ?>
                <span class="badge <?= $badgeClass ?> rounded-pill px-4 py-2 fs-5 fw-medium w-100"><?= $statusText ?></span>
                <div class="mt-3 text-muted small">
                    <i class="fa-regular fa-clock me-1"></i> Ngày đặt: <?= date('d/m/Y H:i', strtotime($order['created_at'])) ?>
                </div>
            </div>
        </div>

        <!-- Khách hàng -->
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body p-4">
                <h6 class="text-muted fw-bold mb-4 text-uppercase">Thông tin khách hàng</h6>
                
                <div class="d-flex align-items-center mb-4">
                    <div class="bg-forest bg-opacity-10 text-forest rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px;">
                        <i class="fa-solid fa-user fs-5"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-0 text-dark"><?= $order['customer_name'] ?? $order['user_name'] ?? 'Khách vãng lai' ?></h6>
                        <span class="text-muted small">Khách hàng</span>
                    </div>
                </div>

                <div class="mb-3">
                    <div class="text-muted small mb-1">Số điện thoại</div>
                    <div class="fw-medium text-dark"><?= $order['customer_phone'] ?? $order['user_phone'] ?? 'Không có' ?></div>
                </div>
                <div class="mb-3">
                    <div class="text-muted small mb-1">Địa chỉ giao hàng</div>
                    <div class="fw-medium text-dark"><?= $order['customer_address'] ?? 'Tại quán' ?></div>
                </div>
                <div>
                    <div class="text-muted small mb-1">Ghi chú của khách</div>
                    <div class="fw-medium text-warning bg-warning bg-opacity-10 p-2 rounded"><?= $order['note'] ? $order['note'] : 'Không có ghi chú' ?></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Cột phải: Hóa đơn -->
    <div class="col-12 col-xl-8">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4">
                <h6 class="text-muted fw-bold mb-4 text-uppercase">Chi tiết hóa đơn</h6>
                
                <div class="table-responsive mb-4">
                    <table class="table mb-0 align-middle">
                        <thead class="bg-light text-muted">
                            <tr>
                                <th class="border-0 rounded-start">Sản phẩm</th>
                                <th class="border-0">Tùy chọn</th>
                                <th class="border-0">Giá</th>
                                <th class="border-0">SL</th>
                                <th class="border-0 text-end rounded-end">Thành tiền</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($order['items'] as $item): ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <?php if($item['product_image']): ?>
                                            <img src="assets/images/products/<?= $item['product_image'] ?>" alt="" class="rounded me-3" width="40" height="40" style="object-fit: cover;">
                                        <?php else: ?>
                                            <div class="bg-light rounded me-3 d-flex align-items-center justify-content-center text-muted" style="width: 40px; height: 40px;">
                                                <i class="fa-solid fa-cup-togo"></i>
                                            </div>
                                        <?php endif; ?>
                                        <div class="fw-bold text-dark"><?= $item['product_name'] ?></div>
                                    </div>
                                </td>
                                <td>
                                    <div class="small text-muted">
                                        Size: <?= $item['size_name'] ?? 'M' ?><br>
                                        Đường: <?= $item['sugar_level'] ?>% | Đá: <?= $item['ice_level'] ?>%
                                    </div>
                                </td>
                                <td class="fw-medium"><?= number_format($item['unit_price'], 0, ',', '.') ?> ₫</td>
                                <td class="fw-bold text-forest">x<?= $item['quantity'] ?></td>
                                <td class="text-end fw-bold text-dark"><?= number_format($item['subtotal'], 0, ',', '.') ?> ₫</td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="row justify-content-end">
                    <div class="col-md-6 col-lg-5">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Phương thức thanh toán:</span>
                            <span class="fw-medium text-dark"><?= $order['payment_method'] ?></span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Tổng tiền hàng:</span>
                            <span class="fw-medium text-dark"><?= number_format($order['total_amount'], 0, ',', '.') ?> ₫</span>
                        </div>
                        <div class="d-flex justify-content-between mb-3 text-success">
                            <span>Giảm giá:</span>
                            <span class="fw-medium">-<?= number_format($order['discount'], 0, ',', '.') ?> ₫</span>
                        </div>
                        <div class="d-flex justify-content-between border-top pt-3">
                            <span class="fw-bold text-dark fs-5">TỔNG CỘNG:</span>
                            <span class="fw-bold text-forest fs-4"><?= number_format($order['final_amount'], 0, ',', '.') ?> ₫</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
