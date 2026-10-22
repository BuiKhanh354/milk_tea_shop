<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="font-serif fw-bold text-dark mb-0">Sơ đồ Bàn</h4>
    <div class="d-flex gap-3 text-muted small">
        <div><span class="d-inline-block rounded-circle bg-white border border-secondary me-1 admin-style-ebc508"></span> Bàn trống</div>
        <div><span class="d-inline-block rounded-circle bg-primary bg-opacity-25 border border-primary me-1 admin-style-ebc508"></span> Đang có khách</div>
        <div><span class="d-inline-block rounded-circle bg-warning bg-opacity-25 border border-warning me-1 admin-style-ebc508"></span> Đã đặt trước</div>
        <button type="button"
            class="btn btn-caramel rounded-pill px-3"
            data-bs-toggle="modal"
            data-bs-target="#addTableModal">
            <i class="fa-solid fa-plus me-1"></i> Thêm bàn
        </button>
    </div>
</div>

<?php if (isset($_GET['error']) && $_GET['error'] === 'duplicate'): ?>
    <div class="alert alert-danger alert-dismissible fade show rounded-3" role="alert">
        <i class="fa-solid fa-circle-exclamation me-2"></i>
        Bàn <strong><?= htmlspecialchars($_GET['table'] ?? '') ?></strong> đã tồn tại! Vui lòng chọn số bàn khác.
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if (isset($_GET['msg']) && $_GET['msg'] === 'created'): ?>
    <div class="alert alert-success alert-dismissible fade show rounded-3" role="alert">
        <i class="fa-solid fa-circle-check me-2"></i>
        Thêm bàn mới thành công!
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="modal fade" id="addTableModal" tabindex="-1"
    aria-labelledby="addTableModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow">

            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold" id="addTableModalLabel">
                    <i class="fa-solid fa-chair me-2"></i>Thêm bàn mới
                </h5>
                <button type="button" class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Đóng"></button>
            </div>

            <form action="admin.php?route=tables&action=store"
                method="POST">

                <div class="modal-body">
                    <div class="mb-3">
                        <label for="table_number" class="form-label">
                            Số bàn
                        </label>
                        <input type="text"
                            class="form-control"
                            id="table_number"
                            name="table_number"
                            min="1"
                            placeholder="Ví dụ: B01"
                            required>
                    </div>

                    <div class="mb-3">
                        <label for="capacity" class="form-label">
                            Sức chứa (người)
                        </label>
                        <input type="number"
                            class="form-control"
                            id="capacity"
                            name="capacity"
                            min="1"
                            value="4"
                            required>
                    </div>
                </div>

                <div class="modal-footer border-0">
                    <button type="button"
                        class="btn btn-light rounded-pill"
                        data-bs-dismiss="modal">
                        Hủy
                    </button>

                    <button type="submit"
                        class="btn btn-caramel rounded-pill px-4">
                        <i class="fa-solid fa-floppy-disk me-1"></i>
                        Lưu bàn
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>


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
                <div class="card <?= $bgClass ?> <?= $borderClass ?> shadow-sm rounded-4 text-center h-100" style="cursor: pointer; transition: all 0.3s;" onmouseover="this.classList.add('shadow')" onmouseout="this.classList.remove('shadow')" onclick="window.location.href='admin.php?route=tables&action=show&id=<?= $table['id'] ?>'">
                    <div class="card-body p-4">
                        <i class="fa-solid <?= $icon ?> fs-1 <?= $textClass ?> mb-3"></i>
                        <h5 class="fw-bold text-dark mb-1">Bàn <?= $table['table_number'] ?></h5>
                        <div class="small text-muted mb-2">Sức chứa: <?= $table['capacity'] ?> người</div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>