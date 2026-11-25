<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div class="d-flex align-items-center gap-3">
        <?php $orderBackUrl = (isset($_SESSION['role']) && $_SESSION['role'] === 'staff') ? admin_url('dashboard') : admin_url('orders'); ?>
        <a href="<?= e($orderBackUrl) ?>" class="btn btn-light border shadow-none rounded-pill">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <h4 class="font-serif fw-bold text-dark mb-0">Chi tiết đơn hàng #ORD<?= str_pad($order['id'], 3, '0', STR_PAD_LEFT) ?></h4>
    </div>
    
    <!-- Nút hành động cập nhật trạng thái -->
    <div class="d-flex gap-2">
        <?php if ($order['status'] !== 'completed' && $order['status'] !== 'cancelled'): ?>
            <form action="<?= e(admin_url('orders', ['action' => 'update_status'])) ?>" method="POST">
                <input type="hidden" name="id" value="<?= (int)$order['id'] ?>">
                <?php if ($order['status'] === 'pending'): ?>
                    <input type="hidden" name="status" value="confirmed">
                    <button type="submit" class="btn btn-info text-white fw-medium px-4 rounded-pill">Xác nhận đơn</button>
                <?php elseif ($order['status'] === 'confirmed'): ?>
                    <input type="hidden" name="status" value="preparing">
                    <button type="submit" class="btn btn-primary fw-medium px-4 rounded-pill">Bắt đầu pha chế</button>
                <?php elseif ($order['status'] === 'preparing'): ?>
                    <input type="hidden" name="status" value="ready">
                    <button type="submit" class="btn btn-warning text-dark fw-medium px-4 rounded-pill">Báo sẵn sàng</button>
                <?php elseif ($order['status'] === 'ready'): ?>
                    <input type="hidden" name="status" value="completed">
                    <button type="submit" class="btn btn-success fw-medium px-4 rounded-pill">Hoàn thành đơn</button>
                <?php endif; ?>
            </form>
            <form action="<?= e(admin_url('orders', ['action' => 'update_status'])) ?>" method="POST" onsubmit="return confirm('Xác nhận hủy đơn hàng này?');">
                <input type="hidden" name="id" value="<?= (int)$order['id'] ?>">
                <input type="hidden" name="status" value="cancelled">
                <button type="submit" class="btn btn-outline-danger fw-medium px-4 rounded-pill">Hủy đơn</button>
            </form>
        <?php endif; ?>
    </div>
</div>

<?php if ($successMsg = get_flash('success')): ?>
    <div class="alert alert-success alert-dismissible fade show rounded-4 border-0 shadow-sm mb-4">
        <i class="fa-solid fa-circle-check me-2"></i> <?= e($successMsg) ?>
        <button type="button" class="btn-close shadow-none" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="row g-4">
    <!-- Cột trái: Thông tin khách hàng & trạng thái -->
    <div class="col-12 col-xl-4">
        <!-- Trạng thái -->
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body p-4 text-center">
                <h6 class="text-muted fw-bold mb-3 text-uppercase">Trạng thái hiện tại</h6>
                <?php 
                $badgeClass = '';
                $statusText = '';
                switch ($order['status']) {
                    case 'pending':   $badgeClass = 'bg-warning bg-opacity-25 text-dark'; $statusText = 'Chờ xác nhận'; break;
                    case 'confirmed': $badgeClass = 'bg-info bg-opacity-10 text-info'; $statusText = 'Đã xác nhận'; break;
                    case 'preparing': $badgeClass = 'bg-primary bg-opacity-10 text-primary'; $statusText = 'Đang chuẩn bị'; break;
                    case 'ready':     $badgeClass = 'bg-success bg-opacity-25 text-success'; $statusText = 'Sẵn sàng'; break;
                    case 'completed': $badgeClass = 'bg-success text-white'; $statusText = 'Hoàn thành'; break;
                    case 'cancelled': $badgeClass = 'bg-danger text-white'; $statusText = 'Đã hủy'; break;
                    default:          $badgeClass = 'bg-secondary text-white'; $statusText = ucfirst($order['status']);
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
                        <h6 class="fw-bold mb-0 text-dark"><?= e($order['customer_name'] ?? $order['user_name'] ?? 'Khách vãng lai') ?></h6>
                        <span class="text-muted small">Khách hàng</span>
                    </div>
                </div>

                <div class="mb-3">
                    <div class="text-muted small mb-1">Số điện thoại</div>
                    <div class="fw-medium text-dark"><i class="fa-solid fa-phone me-2 text-forest"></i><?= e($order['customer_phone'] ?? $order['user_phone'] ?? 'Chưa cập nhật') ?></div>
                </div>

                <div class="mb-3">
                    <div class="text-muted small mb-1">Địa chỉ giao hàng</div>
                    <div class="fw-medium text-dark"><i class="fa-solid fa-location-dot me-2 text-forest"></i><?= e($order['customer_address'] ?? 'Tại quầy / Mang đi') ?></div>
                </div>

                <div class="mb-3">
                    <div class="text-muted small mb-1">Hình thức phục vụ</div>
                    <div class="fw-medium text-dark">
                        <span class="badge bg-light text-dark border px-3 py-1">
                            <?= ucfirst(str_replace('_', ' ', $order['order_type'])) ?>
                            <?= !empty($order['table_number']) ? ' - Bàn ' . e($order['table_number']) : '' ?>
                        </span>
                    </div>
                </div>

                <div>
                    <div class="text-muted small mb-1">Ghi chú của khách</div>
                    <div class="fw-medium text-warning bg-warning bg-opacity-10 p-2 rounded"><?= e($order['note'] ?: 'Không có ghi chú') ?></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Cột phải: Hóa đơn -->
    <div class="col-12 col-xl-8">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4">
                <h6 class="text-muted fw-bold mb-4 text-uppercase font-serif text-forest">Chi tiết hóa đơn</h6>
                
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
                            <?php foreach ($order['items'] as $item): ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="<?= e(product_image($item['product_image'])) ?>" alt="" class="rounded-3 me-3 object-fit-cover" width="44" height="44">
                                        <div class="fw-bold text-dark"><?= e($item['product_name']) ?></div>
                                    </div>
                                </td>
                                <td>
                                    <div class="small text-muted">
                                        Size: <?= e($item['size_name'] ?? 'M') ?><br>
                                        <?php if (!empty($item['toppings'])): ?>
                                        Topping: <?= e($item['toppings']) ?><br>
                                        <?php endif; ?>
                                        Đường: <?= (int)$item['sugar_level'] ?>% | Đá: <?= (int)$item['ice_level'] ?>%
                                    </div>
                                </td>
                                <td class="fw-medium"><?= money($item['unit_price']) ?></td>
                                <td class="fw-bold text-forest">x<?= (int)$item['quantity'] ?></td>
                                <td class="text-end fw-bold text-dark font-monospace"><?= money($item['subtotal']) ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="row justify-content-end">
                    <div class="col-md-6 col-lg-5">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Phương thức thanh toán:</span>
                            <span class="fw-medium text-dark"><?= format_payment_method($order['payment_method']) ?></span>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-muted">Tổng tiền hàng:</span>
                            <span class="fw-medium text-dark"><?= money($order['total_amount']) ?></span>
                        </div>
                        <div class="d-flex justify-content-between border-top pt-3">
                            <span class="fw-bold text-dark fs-5">TỔNG CỘNG:</span>
                            <span class="fw-bold text-forest fs-4"><?= money($order['final_amount']) ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

