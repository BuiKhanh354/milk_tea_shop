<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h4 class="font-serif fw-bold text-dark mb-1">
            <a href="admin.php?route=shift_sessions" class="text-muted me-2"><i class="fa-solid fa-arrow-left"></i></a>
            Mở Ca làm việc mới
        </h4>
        <p class="text-muted small mb-0">
            Hôm nay: <strong class="text-dark"><?= date('d/m/Y') ?></strong> | Giờ hiện tại: <strong class="text-forest"><?= date('H:i') ?></strong>
        </p>
    </div>
    <div>
        <a href="admin.php?route=shifts" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
            <i class="fa-solid fa-calendar-days me-1"></i> Lịch làm việc của tôi
        </a>
    </div>
</div>

<?php if (!$isAdmin && empty($assignedShifts)): ?>
    <!-- CẢNH BÁO: Nhân viên KHÔNG có ca làm việc nào được phân công hôm nay -->
    <div class="card border-0 shadow-sm rounded-4 mb-4 border-start border-warning border-4">
        <div class="card-body p-4 text-center py-5">
            <div class="mb-3">
                <span class="badge bg-warning bg-opacity-15 text-warning p-3 rounded-circle fs-2">
                    <i class="fa-solid fa-calendar-xmark"></i>
                </span>
            </div>
            <h5 class="fw-bold text-dark mb-2">Hôm nay bạn không có ca làm việc nào được phân công!</h5>
            <p class="text-muted mx-auto mb-4" style="max-width: 600px;">
                Hệ thống chỉ cho phép nhân viên mở ca và bán hàng đúng ca được phân công trong ngày hôm nay (<strong class="text-dark"><?= date('d/m/Y') ?></strong>). 
                Không được mở ca đã qua ngày hoặc các ca chưa được xếp lịch.
            </p>
            <div class="d-flex justify-content-center gap-3">
                <a href="admin.php?route=shifts" class="btn btn-forest rounded-pill px-4 py-2">
                    <i class="fa-solid fa-calendar-check me-2"></i>Xem lịch làm việc cá nhân
                </a>
                <a href="admin.php?route=dashboard" class="btn btn-outline-secondary rounded-pill px-4 py-2">
                    <i class="fa-solid fa-house me-2"></i>Về trang chủ
                </a>
            </div>
        </div>
    </div>
<?php else: ?>

<form action="admin.php?route=shift_sessions&action=storeOpen" method="POST" id="openShiftForm">
    <div class="row g-4">
        <!-- Cột trái: Thông tin ca & Tiền mặt ban đầu -->
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold text-dark mb-3 border-bottom pb-2">
                        <i class="fa-solid fa-business-time text-forest me-2"></i>1. Ca làm việc & Tiền két
                    </h5>
                    
                    <!-- Phần hiển thị ca làm việc -->
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted mb-1">Ca làm việc được phân công</label>
                        
                        <?php if (!$isAdmin): ?>
                            <?php if (count($assignedShifts) === 1 && $activeAssignedShift): ?>
                                <!-- Có đúng 1 ca hôm nay: Khóa cứng và hiển thị ca đó luôn -->
                                <input type="hidden" name="shift_id" value="<?= (int)$activeAssignedShift['shift_id'] ?>">
                                <div class="p-3 bg-forest bg-opacity-10 border border-forest rounded-3">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <span class="badge bg-forest text-light rounded-pill px-2 py-1">
                                            <i class="fa-solid fa-check-circle me-1"></i> Ca đúng phân công
                                        </span>
                                        <small class="text-light fw-bold"><?= date('d/m/Y', strtotime($activeAssignedShift['work_date'])) ?></small>
                                    </div>
                                    <h5 class="fw-bold text-light mb-1 mt-1"><?= htmlspecialchars($activeAssignedShift['shift_name']) ?></h5>
                                    <div class="text-light small">
                                        <i class="fa-regular fa-clock me-1 text-light"></i> 
                                        Thời gian: <strong><?= substr($activeAssignedShift['start_time'], 0, 5) ?> - <?= substr($activeAssignedShift['end_time'], 0, 5) ?></strong>
                                    </div>
                                </div>
                                <small class="text-success d-block mt-1">
                                    <i class="fa-solid fa-circle-check me-1"></i> Hệ thống đã tự động gán vào đúng ca được giao hôm nay của bạn.
                                </small>
                            <?php else: ?>
                                <!-- Có nhiều ca trong ngày hôm nay: Chỉ cho phép chọn trong các ca hôm nay -->
                                <select name="shift_id" class="form-select bg-light border-0 py-2 fw-bold" required>
                                    <option value="">-- Chọn ca được giao hôm nay --</option>
                                    <?php foreach ($assignedShifts as $ash): 
                                        $selected = ($activeAssignedShift && $activeAssignedShift['shift_id'] == $ash['shift_id']) ? 'selected' : '';
                                    ?>
                                        <option value="<?= $ash['shift_id'] ?>" <?= $selected ?>>
                                            <?= htmlspecialchars($ash['shift_name']) ?> (<?= substr($ash['start_time'], 0, 5) ?> - <?= substr($ash['end_time'], 0, 5) ?>)
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <small class="text-muted d-block mt-1">
                                    <i class="fa-solid fa-info-circle me-1"></i> Bạn có nhiều ca trong ngày hôm nay. Hệ thống đã ưu tiên chọn ca gần nhất.
                                </small>
                            <?php endif; ?>

                        <?php else: ?>
                            <!-- Dành cho Admin: Được chọn linh hoạt -->
                            <select name="shift_id" class="form-select bg-light border-0 py-2">
                                <option value="0">-- Ca thông thường / Không cố định --</option>
                                <?php foreach ($shifts as $sh): 
                                    $selected = ($activeAssignedShift && $activeAssignedShift['shift_id'] == $sh['id']) ? 'selected' : '';
                                ?>
                                    <option value="<?= $sh['id'] ?>" <?= $selected ?>>
                                        <?= htmlspecialchars($sh['name']) ?> (<?= substr($sh['start_time'], 0, 5) ?> - <?= substr($sh['end_time'], 0, 5) ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <small class="text-muted d-block mt-1">
                                <i class="fa-solid fa-user-shield me-1"></i> Quản trị viên: Có thể chọn ca linh hoạt để mở ca thu ngân.
                            </small>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-dark mb-1">
                            Tiền mặt ban đầu trong két (Tiền thối lại) <span class="text-danger">*</span>
                        </label>
                        <div class="input-group input-group-lg">
                            <input type="text" 
                                   name="initial_cash" 
                                   id="initialCashInput" 
                                   class="form-control bg-light border-0 fw-bold text-forest fs-4 font-monospace" 
                                   placeholder="0" 
                                   value="1.000.000" 
                                   required 
                                   onkeyup="formatCurrencyInput(this)">
                            <span class="input-group-text bg-light border-0 fw-bold text-muted">VNĐ</span>
                        </div>
                        <div class="d-flex gap-2 mt-2 flex-wrap">
                            <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-2" onclick="setCash(500000)">500.000đ</button>
                            <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-2" onclick="setCash(1000000)">1.000.000đ</button>
                            <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-2" onclick="setCash(1500000)">1.500.000đ</button>
                            <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-2" onclick="setCash(2000000)">2.000.000đ</button>
                        </div>
                        <small class="text-muted d-block mt-2">
                            <i class="fa-solid fa-circle-info me-1"></i> Tiền két này sẽ được dùng để đối chiếu khi kết thúc ca làm việc.
                        </small>
                    </div>

                    <div class="mb-4">
                        <label class="form-label small fw-bold text-muted mb-1">Ghi chú đầu ca</label>
                        <textarea name="note" class="form-control bg-light border-0" rows="2" placeholder="Ví dụ: Nhận két từ quản lý, kiểm tra đủ tiền lẻ..."></textarea>
                    </div>

                    <button type="submit" class="btn btn-forest w-100 py-3 fw-bold rounded-pill shadow-sm fs-6">
                        <i class="fa-solid fa-key me-2"></i> XÁC NHẬN MỞ CA & BÁN HÀNG
                    </button>
                </div>
            </div>
        </div>

        <!-- Cột phải: Bàn giao nguyên liệu đầu ca -->
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-2 flex-wrap gap-2">
                        <h5 class="fw-bold text-dark mb-0">
                            <i class="fa-solid fa-boxes-stacked text-forest me-2"></i>2. Bàn giao nguyên liệu đầu ca
                        </h5>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3" onclick="resetAllInventory()">
                                <i class="fa-solid fa-rotate-left me-1"></i> Điền lại tồn chuẩn
                            </button>
                        </div>
                    </div>

                    <p class="text-muted small mb-3">
                        Kiểm tra số lượng nguyên liệu thực tế tại quầy pha chế. Mặc định hệ thống đã điền sẵn số tồn kho hiện tại. Bạn có thể điều chỉnh nếu phát hiện sai lệch trước khi mở ca.
                    </p>

                    <div class="table-responsive" style="max-height: 480px; overflow-y: auto;">
                        <table class="table align-middle table-hover">
                            <thead class="bg-light text-muted small position-sticky top-0 z-1">
                                <tr>
                                    <th class="py-2 px-3">#</th>
                                    <th class="py-2">Nguyên liệu</th>
                                    <th class="py-2 text-center">ĐVT</th>
                                    <th class="py-2 text-end">Tồn kho nhận bàn giao</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($ingredients as $idx => $ing): 
                                    $sysQty = (float)$ing['quantity'];
                                ?>
                                <tr>
                                    <td class="px-3 text-muted"><?= $idx + 1 ?></td>
                                    <td>
                                        <span class="fw-bold text-dark"><?= htmlspecialchars($ing['ingredient_name']) ?></span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-light text-dark border"><?= htmlspecialchars($ing['unit']) ?></span>
                                    </td>
                                    <td class="text-end" style="width: 180px;">
                                        <div class="input-group input-group-sm">
                                            <input type="number" 
                                                   step="0.01" 
                                                   min="0" 
                                                   name="inventory[<?= $ing['id'] ?>]" 
                                                   id="inv_input_<?= $ing['id'] ?>" 
                                                   data-default="<?= $sysQty ?>" 
                                                   value="<?= number_format($sysQty, 2, '.', '') ?>" 
                                                   class="form-control form-control-sm text-end font-monospace fw-bold inv-start-input">
                                            <span class="input-group-text small text-muted"><?= htmlspecialchars($ing['unit']) ?></span>
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

<?php endif; ?>

<script>
    function formatCurrencyInput(input) {
        let value = input.value.replace(/[^0-9]/g, '');
        if (value === '') {
            input.value = '0';
            return;
        }
        input.value = new Intl.NumberFormat('vi-VN').format(parseInt(value));
    }

    function setCash(amount) {
        document.getElementById('initialCashInput').value = new Intl.NumberFormat('vi-VN').format(amount);
    }

    function resetAllInventory() {
        if (!confirm('Đặt lại toàn bộ số lượng nguyên liệu về tồn kho hệ thống mặc định?')) return;
        document.querySelectorAll('.inv-start-input').forEach(input => {
            input.value = parseFloat(input.dataset.default).toFixed(2);
        });
    }
</script>
