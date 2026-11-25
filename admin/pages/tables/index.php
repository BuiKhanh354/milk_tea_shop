<?php
if ($action === 'show' && !empty($table)) {
    require __DIR__ . '/show.php';
    return;
}
$isStaffRole = isset($_SESSION['role']) && $_SESSION['role'] === 'staff';
?>
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
    <h4 class="font-serif fw-bold text-dark mb-0">Sơ đồ Bàn & Theo dõi phục vụ</h4>
    <div class="d-flex gap-3 text-muted small align-items-center flex-wrap">
        <div><span class="d-inline-block rounded-circle bg-white border border-secondary me-1" style="width:10px;height:10px;"></span> Bàn trống</div>
        <div><span class="d-inline-block rounded-circle bg-primary bg-opacity-25 border border-primary me-1" style="width:10px;height:10px;"></span> Đang có khách</div>
        <div><span class="d-inline-block rounded-circle bg-warning bg-opacity-25 border border-warning me-1" style="width:10px;height:10px;"></span> Đã đặt trước</div>
        <a href="<?= e(admin_url('orders', ['order_type' => 'dine_in'])) ?>" class="btn btn-outline-forest rounded-pill px-3">
            <i class="fa-solid fa-bell-concierge me-1"></i> Đơn tại bàn
        </a>
        <?php if (!$isStaffRole): ?>
        <button type="button" class="btn btn-forest rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#addTableModal">
            <i class="fa-solid fa-plus me-1"></i> Thêm bàn
        </button>
        <?php endif; ?>
    </div>
</div>

<?php if ($errorMsg = get_flash('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show rounded-4 border-0 shadow-sm" role="alert">
        <i class="fa-solid fa-circle-exclamation me-2"></i> <?= e($errorMsg) ?>
        <button type="button" class="btn-close shadow-none" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if ($successMsg = get_flash('success')): ?>
    <div class="alert alert-success alert-dismissible fade show rounded-4 border-0 shadow-sm" role="alert">
        <i class="fa-solid fa-circle-check me-2"></i> <?= e($successMsg) ?>
        <button type="button" class="btn-close shadow-none" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if (!$isStaffRole): ?>
<div class="modal fade" id="addTableModal" tabindex="-1" aria-labelledby="addTableModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow">
            <div class="modal-header border-0 pb-0 px-4 pt-4">
                <h5 class="modal-title font-serif fw-bold text-forest" id="addTableModalLabel">
                    <i class="fa-solid fa-chair me-2"></i>Thêm bàn mới
                </h5>
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Đóng"></button>
            </div>
            <form action="<?= e(admin_url('tables', ['action' => 'store'])) ?>" method="POST">
                <div class="modal-body px-4 py-3">
                    <div class="mb-3">
                        <label for="table_number" class="form-label text-muted small fw-medium mb-1">Số bàn <span class="text-danger">*</span></label>
                        <input type="text" class="form-control shadow-none border-secondary border-opacity-25" id="table_number" name="table_number" placeholder="Ví dụ: B01" required>
                    </div>
                    <div class="mb-3">
                        <label for="capacity" class="form-label text-muted small fw-medium mb-1">Sức chứa (người) <span class="text-danger">*</span></label>
                        <input type="number" class="form-control shadow-none border-secondary border-opacity-25" id="capacity" name="capacity" min="1" value="4" required>
                    </div>
                </div>
                <div class="modal-footer border-0 px-4 pb-4 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-forest rounded-pill px-4">
                        <i class="fa-solid fa-floppy-disk me-1"></i> Lưu bàn
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>

<div class="row g-4">
    <?php if (empty($tables)): ?>
        <div class="col-12 text-center text-muted py-5">
            Chưa có dữ liệu bàn.
        </div>
    <?php else: ?>
        <?php foreach ($tables as $table): ?>
            <?php
            $bgClass = 'bg-white';
            $borderClass = 'border-secondary border-opacity-25';
            $textClass = 'text-dark';
            $icon = 'fa-chair';

            if ($table['status'] == 'occupied') {
                $bgClass = 'bg-primary bg-opacity-10';
                $borderClass = 'border-primary';
                $textClass = 'text-primary';
                $icon = 'fa-users';
            } else if ($table['status'] == 'reserved') {
                $bgClass = 'bg-warning bg-opacity-10';
                $borderClass = 'border-warning';
                $textClass = 'text-warning';
                $icon = 'fa-calendar-check';
            }
            ?>
            <div class="col-6 col-md-3 col-xl-2">
                <div class="card <?= $bgClass ?> <?= $borderClass ?> shadow-sm rounded-4 text-center h-100" style="cursor: pointer; transition: all 0.3s;" onmouseover="this.classList.add('shadow')" onmouseout="this.classList.remove('shadow')" onclick="window.location.href='<?= e(admin_url('tables', ['action' => 'show', 'id' => $table['id']])) ?>'">
                    <div class="card-body p-4 d-flex flex-column justify-content-between">
                        <div>
                            <i class="fa-solid <?= $icon ?> fs-1 <?= $textClass ?> mb-3"></i>
                            <h5 class="fw-bold text-dark mb-1">Bàn <?= e($table['table_number']) ?></h5>
                            <div class="small text-muted mb-2">Sức chứa: <?= (int)$table['capacity'] ?> người</div>
                        </div>
                        <?php if ((int)($table['active_orders_count'] ?? 0) > 0): ?>
                            <div class="mt-2 pt-2 border-top border-primary border-opacity-25">
                                <span class="badge bg-primary text-white rounded-pill px-2 py-1 mb-1">
                                    <?= (int)$table['active_orders_count'] ?> đơn đang phục vụ
                                </span>
                                <div class="small fw-bold text-forest font-monospace">
                                    <?= money($table['active_total_amount']) ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

