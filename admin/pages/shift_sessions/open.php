<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h4 class="font-serif fw-bold text-dark mb-1">
            <a href="<?= e(admin_url('shift_sessions')) ?>" class="text-muted me-2"><i class="fa-solid fa-arrow-left"></i></a>
            Mở Ca làm việc mới
        </h4>
        <p class="text-muted small mb-0">
            Hôm nay: <strong class="text-dark"><?= date('d/m/Y') ?></strong> | Giờ hiện tại: <strong class="text-forest"><?= date('H:i') ?></strong>
        </p>
    </div>
</div>

<?php if ($errorMsg = get_flash('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show rounded-4 border-0 shadow-sm" role="alert">
        <i class="fa-solid fa-triangle-exclamation me-2"></i> <?= e($errorMsg) ?>
        <button type="button" class="btn-close shadow-none" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<form action="<?= e(admin_url('shift_sessions', ['action' => 'storeOpen'])) ?>" method="POST" id="openShiftForm">
    <div class="row g-4">
        <!-- Cột trái: Thông tin ca & Tiền két ban đầu -->
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold text-dark mb-3 border-bottom pb-2 font-serif text-forest">
                        <i class="fa-solid fa-business-time me-2"></i>1. Ca làm việc & Tiền két
                    </h5>
                    
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted mb-1">Ca làm việc <span class="text-danger">*</span></label>
                        <select name="shift_id" class="form-select border-secondary border-opacity-25 shadow-none" required>
                            <option value="">-- Chọn ca làm việc --</option>
                            <?php foreach ($shifts as $sh): ?>
                                <option value="<?= (int)$sh['id'] ?>"><?= e($sh['name']) ?> (<?= substr($sh['start_time'], 0, 5) ?> - <?= substr($sh['end_time'], 0, 5) ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted mb-1">Tiền mặt ban đầu trong két (VNĐ) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-secondary border-opacity-25"><i class="fa-solid fa-money-bill-wave text-success"></i></span>
                            <input type="text" name="initial_cash" id="initialCashInput" class="form-control form-control-lg border-secondary border-opacity-25 shadow-none font-monospace fw-bold" value="500.000" onkeyup="formatCurrencyInput(this)" required>
                            <span class="input-group-text bg-light border-secondary border-opacity-25">VNĐ</span>
                        </div>
                        <div class="d-flex gap-2 mt-2">
                            <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-2 py-0" onclick="setCash(0)">0đ</button>
                            <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-2 py-0" onclick="setCash(500000)">500k</button>
                            <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-2 py-0" onclick="setCash(1000000)">1 Triệu</button>
                            <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-2 py-0" onclick="setCash(2000000)">2 Triệu</button>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label small fw-bold text-muted mb-1">Ghi chú lúc nhận ca</label>
                        <textarea name="note" class="form-control border-secondary border-opacity-25 shadow-none" rows="3" placeholder="Tình trạng két, máy móc hoặc dặn dò..."></textarea>
                    </div>

                    <button type="submit" class="btn btn-forest w-100 py-3 rounded-pill fw-bold shadow-sm">
                        <i class="fa-solid fa-key me-2"></i> XÁC NHẬN MỞ CA
                    </button>
                </div>
            </div>
        </div>

        <!-- Cột phải: Bàn giao nguyên liệu đầu ca -->
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold text-dark mb-3 border-bottom pb-2 font-serif text-forest">
                        <i class="fa-solid fa-boxes-stacked me-2"></i>2. Bàn giao nguyên liệu đầu ca
                    </h5>
                    <p class="text-muted small mb-3">Kiểm tra số lượng nguyên liệu thực tế tại quầy pha chế.</p>

                    <div class="table-responsive" style="max-height: 480px; overflow-y: auto;">
                        <table class="table align-middle table-hover">
                            <thead class="bg-light text-muted small position-sticky top-0 z-1">
                                <tr>
                                    <th class="py-2 px-3">#</th>
                                    <th class="py-2">Nguyên liệu</th>
                                    <th class="py-2 text-center">ĐVT</th>
                                    <th class="py-2 text-end">Tồn kho nhận</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($ingredients as $idx => $ing): 
                                    $sysQty = (float)$ing['quantity'];
                                ?>
                                <tr>
                                    <td class="px-3 text-muted"><?= $idx + 1 ?></td>
                                    <td><span class="fw-bold text-dark"><?= e($ing['ingredient_name']) ?></span></td>
                                    <td class="text-center"><span class="badge bg-light text-dark border"><?= e($ing['unit']) ?></span></td>
                                    <td class="text-end" style="width: 160px;">
                                        <div class="input-group input-group-sm">
                                            <input type="number" step="0.01" min="0" name="inventory[<?= $ing['id'] ?>]" value="<?= number_format($sysQty, 2, '.', '') ?>" class="form-control form-control-sm text-end font-monospace fw-bold border-secondary border-opacity-25 shadow-none">
                                            <span class="input-group-text small text-muted"><?= e($ing['unit']) ?></span>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
function formatCurrencyInput(input) {
    let value = input.value.replace(/[^0-9]/g, '');
    if (value === '') { input.value = '0'; return; }
    input.value = new Intl.NumberFormat('vi-VN').format(parseInt(value));
}
function setCash(amount) {
    document.getElementById('initialCashInput').value = new Intl.NumberFormat('vi-VN').format(amount);
}
</script>
