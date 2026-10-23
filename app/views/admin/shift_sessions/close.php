<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="font-serif fw-bold text-dark mb-1">
            <a href="admin.php?route=shift_sessions" class="text-muted me-2"><i class="fa-solid fa-arrow-left"></i></a>
            Kết ca & Bàn giao (#<?= htmlspecialchars($currentOpen['session_code']) ?>)
        </h4>
        <p class="text-muted small mb-0">
            Mở lúc: <strong><?= date('d/m/Y H:i', strtotime($currentOpen['opened_at'])) ?></strong> 
            | Thu ngân: <strong><?= htmlspecialchars($currentOpen['user_name']) ?></strong>
            | Tiền két ban đầu: <strong class="text-forest"><?= number_format($summary['initial_cash'], 0, ',', '.') ?>đ</strong>
        </p>
    </div>
</div>

<form action="admin.php?route=shift_sessions&action=storeClose" method="POST" id="closeShiftForm">
    <input type="hidden" name="session_id" value="<?= $currentOpen['id'] ?>">

    <!-- PHẦN 1: TỔNG KẾT DOANH THU & KIỂM ĐẾM TIỀN MẶT -->
    <div class="row g-4 mb-4">
        <!-- Bảng phân loại doanh thu ca -->
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-2">
                        <h5 class="fw-bold text-dark mb-0">
                            <i class="fa-solid fa-chart-pie text-forest me-2"></i>1. Doanh thu bán được trong ca
                        </h5>
                        <span class="badge bg-light text-muted border px-3 py-2">
                            <?= $summary['total_orders'] ?> đơn hàng (<?= $summary['completed_orders'] ?> hoàn thành)
                        </span>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-sm-4">
                            <div class="p-3 bg-light rounded-3 border">
                                <span class="text-muted small d-block mb-1"><i class="fa-solid fa-money-bill text-success me-1"></i>Tiền mặt (POS)</span>
                                <h5 class="fw-bold text-dark mb-0 font-monospace"><?= number_format($summary['cash_sales'], 0, ',', '.') ?>đ</h5>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="p-3 bg-light rounded-3 border">
                                <span class="text-muted small d-block mb-1"><i class="fa-solid fa-building-columns text-primary me-1"></i>Chuyển khoản</span>
                                <h5 class="fw-bold text-dark mb-0 font-monospace"><?= number_format($summary['bank_sales'], 0, ',', '.') ?>đ</h5>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="p-3 bg-light rounded-3 border">
                                <span class="text-muted small d-block mb-1"><i class="fa-solid fa-wallet text-danger me-1"></i>Ví MoMo</span>
                                <h5 class="fw-bold text-dark mb-0 font-monospace"><?= number_format($summary['momo_sales'], 0, ',', '.') ?>đ</h5>
                            </div>
                        </div>
                    </div>

                    <!-- Tổng doanh thu ca -->
                    <div class="p-3 bg-forest bg-opacity-10 rounded-3 border border-forest d-flex justify-content-between align-items-center">
                        <div>
                            <span class="fw-bold text-light fs-6">TỔNG DOANH THU CA NÀY BÁN ĐƯỢC:</span>
                            <small class="d-block text-light">Toàn bộ tiền mặt, chuyển khoản và ví điện tử</small>
                        </div>
                        <h3 class="fw-bold text-light mb-0 font-monospace"><?= number_format($summary['total_sales'], 0, ',', '.') ?>đ</h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kiểm tiền mặt trong két -->
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">
                    <h5 class="fw-bold text-dark mb-3 border-bottom pb-2">
                        <i class="fa-solid fa-cash-register text-forest me-2"></i>2. Kiểm đếm tiền mặt trong két
                    </h5>

                    <div class="mb-3">
                        <div class="d-flex justify-content-between text-muted small mb-1">
                            <span>Tiền mặt ban đầu:</span>
                            <span class="font-monospace fw-bold"><?= number_format($summary['initial_cash'], 0, ',', '.') ?>đ</span>
                        </div>
                        <div class="d-flex justify-content-between text-muted small mb-2">
                            <span>+ Tiền mặt bán được:</span>
                            <span class="font-monospace fw-bold text-success">+<?= number_format($summary['cash_sales'], 0, ',', '.') ?>đ</span>
                        </div>
                        <div class="d-flex justify-content-between p-2 bg-light rounded border fw-bold">
                            <span>= Tiền mặt lý thuyết trong két:</span>
                            <span class="font-monospace text-primary fs-6" id="expectedCashText" data-val="<?= $summary['expected_cash'] ?>">
                                <?= number_format($summary['expected_cash'], 0, ',', '.') ?>đ
                            </span>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-dark mb-1">
                            Tiền mặt thực tế đếm được <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <input type="text" 
                                   name="actual_cash" 
                                   id="actualCashInput" 
                                   class="form-control form-control-lg bg-light border-0 fw-bold fs-4 font-monospace" 
                                   placeholder="0" 
                                   value="<?= number_format($summary['expected_cash'], 0, ',', '.') ?>" 
                                   required 
                                   onkeyup="calcCashDiff(this)">
                            <span class="input-group-text bg-light border-0 fw-bold text-muted">VNĐ</span>
                        </div>
                    </div>

                    <!-- Khối hiển thị chênh lệch tiền -->
                    <div class="p-3 rounded-3 mb-3 text-center border" id="cashDiffAlert">
                        <span class="small text-muted d-block mb-1">Chênh lệch tiền mặt két:</span>
                        <h4 class="fw-bold font-monospace mb-0" id="cashDiffValue">0đ</h4>
                    </div>

                    <div class="mb-2">
                        <label class="form-label small fw-bold text-muted mb-1">Lý do chênh lệch (nếu thừa / thiếu tiền)</label>
                        <input type="text" name="cash_difference_reason" id="cashReasonInput" class="form-control bg-light border-0" placeholder="Ví dụ: Thối nhầm 5.000đ cho khách, tiền rách...">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- PHẦN 2: XÁC NHẬN NGUYÊN LIỆU CUỐI CA & TIÊU HAO -->
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-2 flex-wrap gap-2">
                <div>
                    <h5 class="fw-bold text-dark mb-1">
                        <i class="fa-solid fa-boxes-stacked text-forest me-2"></i>3. Xác nhận số dư nguyên liệu còn lại cuối ca
                    </h5>
                    <p class="text-muted small mb-0">Hệ thống tự động tính tồn lý thuyết dựa trên công thức của các đơn đã bán trong ca. Hãy nhập số cân/đếm thực tế còn lại.</p>
                </div>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3" onclick="fillAllEndingTheory()">
                        <i class="fa-solid fa-copy me-1"></i> Điền tồn lý thuyết cho tất cả
                    </button>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table align-middle table-hover">
                    <thead class="bg-light text-muted small">
                        <tr>
                            <th class="py-3 px-3">#</th>
                            <th class="py-3">Tên nguyên liệu</th>
                            <th class="py-3 text-center">ĐVT</th>
                            <th class="py-3 text-end">Tồn đầu ca</th>
                            <th class="py-3 text-end">Tiêu hao theo đơn (Dự kiến)</th>
                            <th class="py-3 text-end">Tồn lý thuyết cuối ca</th>
                            <th class="py-3" style="width: 200px;">Thực tế còn lại (Cân/đếm)</th>
                            <th class="py-3 text-center">Chênh lệch ca</th>
                            <th class="py-3" style="width: 200px;">Lý do chênh lệch</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($endingInventory as $idx => $row): 
                            $invId = (int)$row['inventory_id'];
                            $startQty = (float)$row['start_quantity'];
                            $expUsage = (float)$row['calculated_expected_usage'];
                            $expEnd = (float)$row['calculated_ending_qty'];
                        ?>
                        <tr class="inv-end-row">
                            <td class="px-3 text-muted"><?= $idx + 1 ?></td>
                            <td>
                                <span class="fw-bold text-dark"><?= htmlspecialchars($row['ingredient_name']) ?></span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-light text-dark border"><?= htmlspecialchars($row['unit']) ?></span>
                            </td>
                            <td class="text-end font-monospace">
                                <?= number_format($startQty, 2, '.', '') ?>
                            </td>
                            <td class="text-end font-monospace text-forest fw-medium">
                                <?= number_format($expUsage, 2, '.', '') ?>
                            </td>
                            <td class="text-end font-monospace fw-bold text-dark" id="theory_<?= $invId ?>" data-val="<?= $expEnd ?>">
                                <?= number_format($expEnd, 2, '.', '') ?>
                            </td>
                            <td>
                                <div class="input-group input-group-sm">
                                    <input type="number" 
                                           step="0.01" 
                                           min="0" 
                                           name="inventory_endings[<?= $invId ?>][actual_ending_qty]" 
                                           id="act_end_<?= $invId ?>" 
                                           value="<?= number_format($expEnd, 2, '.', '') ?>" 
                                           class="form-control form-control-sm text-end font-monospace fw-bold act-end-input" 
                                           oninput="calcInvEndDiff(<?= $invId ?>)">
                                    <span class="input-group-text small text-muted"><?= htmlspecialchars($row['unit']) ?></span>
                                </div>
                            </td>
                            <td class="text-center font-monospace" id="diff_cell_<?= $invId ?>">
                                <span class="badge bg-success bg-opacity-15 text-light rounded-pill px-3">Khớp (0)</span>
                            </td>
                            <td>
                                <input type="text" 
                                       name="inventory_endings[<?= $invId ?>][reason]" 
                                       id="reason_inv_<?= $invId ?>" 
                                       class="form-control form-control-sm" 
                                       placeholder="Ghi chú nếu hao hụt/thừa...">
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Ghi chú chung & Hoàn tất -->
            <div class="border-top pt-4 mt-3">
                <div class="row align-items-center">
                    <div class="col-md-7 mb-3 mb-md-0">
                        <label class="form-label small fw-bold text-muted mb-1">Ghi chú bàn giao ca chung</label>
                        <textarea name="note" class="form-control bg-light border-0" rows="2" placeholder="Ghi chú dặn dò cho ca tiếp theo hoặc gửi quản lý..."></textarea>
                    </div>
                    <div class="col-md-5 text-md-end">
                        <div class="text-muted small mb-2">
                            <i class="fa-solid fa-lock text-warning me-1"></i> Sau khi kết ca, phiên làm việc sẽ được đóng và tạo biên bản bàn giao.
                        </div>
                        <button type="button" class="btn btn-warning py-3 px-5 fw-bold rounded-pill shadow-sm fs-6 text-dark" onclick="confirmCloseShift()">
                            <i class="fa-solid fa-check-double me-2"></i> XÁC NHẬN KẾT CA & BÀN GIAO
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
    const expectedCash = parseFloat(document.getElementById('expectedCashText').dataset.val) || 0;

    function calcCashDiff(input) {
        let rawVal = input.value.replace(/[^0-9]/g, '');
        if (rawVal === '') rawVal = '0';
        const numVal = parseInt(rawVal);
        input.value = new Intl.NumberFormat('vi-VN').format(numVal);

        const diff = numVal - expectedCash;
        const diffEl = document.getElementById('cashDiffValue');
        const alertEl = document.getElementById('cashDiffAlert');
        const reasonInput = document.getElementById('cashReasonInput');

        if (Math.abs(diff) < 1) {
            diffEl.innerText = '0đ (Khớp hoàn toàn)';
            diffEl.className = 'fw-bold font-monospace mb-0 text-success';
            alertEl.className = 'p-3 rounded-3 mb-3 text-center border bg-success bg-opacity-10 border-success';
            reasonInput.placeholder = 'Lý do chênh lệch (nếu có)...';
            reasonInput.classList.remove('border-danger', 'border-warning');
        } else if (diff < 0) {
            diffEl.innerText = `Thiếu ${new Intl.NumberFormat('vi-VN').format(diff)}đ`;
            diffEl.className = 'fw-bold font-monospace mb-0 text-danger';
            alertEl.className = 'p-3 rounded-3 mb-3 text-center border bg-danger bg-opacity-10 border-danger';
            reasonInput.placeholder = 'Bắt buộc giải trình lý do thiếu tiền...';
            reasonInput.classList.add('border-danger');
        } else {
            diffEl.innerText = `Thừa +${new Intl.NumberFormat('vi-VN').format(diff)}đ`;
            diffEl.className = 'fw-bold font-monospace mb-0 text-warning text-dark';
            alertEl.className = 'p-3 rounded-3 mb-3 text-center border bg-warning bg-opacity-10 border-warning';
            reasonInput.placeholder = 'Ghi chú lý do thừa tiền trong két...';
            reasonInput.classList.add('border-warning');
        }
    }

    function calcInvEndDiff(invId) {
        const theoryQty = parseFloat(document.getElementById('theory_' + invId).dataset.val) || 0;
        const inputEl = document.getElementById('act_end_' + invId);
        const cellEl = document.getElementById('diff_cell_' + invId);
        const reasonEl = document.getElementById('reason_inv_' + invId);

        if (inputEl.value.trim() === '') {
            cellEl.innerHTML = '<span class="text-muted small fst-italic">Chưa nhập</span>';
            return;
        }

        const actualQty = parseFloat(inputEl.value) || 0;
        const diff = actualQty - theoryQty;
        const diffFormatted = Math.abs(diff).toFixed(2);

        if (Math.abs(diff) < 0.001) {
            cellEl.innerHTML = '<span class="badge bg-success bg-opacity-15 text-light rounded-pill px-3">Khớp (0)</span>';
            reasonEl.classList.remove('border-danger');
        } else if (diff < 0) {
            cellEl.innerHTML = `<span class="badge bg-danger rounded-pill px-3">Hụt -${diffFormatted}</span>`;
            reasonEl.classList.add('border-danger');
        } else {
            cellEl.innerHTML = `<span class="badge bg-warning text-dark rounded-pill px-3">Dôi +${diffFormatted}</span>`;
            reasonEl.classList.add('border-warning');
        }
    }

    function fillAllEndingTheory() {
        document.querySelectorAll('.act-end-input').forEach(input => {
            const invId = input.id.replace('act_end_', '');
            const theory = parseFloat(document.getElementById('theory_' + invId).dataset.val) || 0;
            input.value = theory.toFixed(2);
            calcInvEndDiff(invId);
        });
    }

    function confirmCloseShift() {
        const rawCash = document.getElementById('actualCashInput').value.replace(/[^0-9]/g, '');
        const actualCash = parseInt(rawCash) || 0;
        const diffCash = actualCash - expectedCash;
        const reason = document.getElementById('cashReasonInput').value.trim();

        if (Math.abs(diffCash) > 0 && reason === '') {
            if (!confirm(`Tiền mặt trong két đang bị chênh lệch (${new Intl.NumberFormat('vi-VN').format(diffCash)}đ) so với lý thuyết và chưa có lý do giải trình. Bạn có chắc chắn muốn tiếp tục đóng ca?`)) {
                document.getElementById('cashReasonInput').focus();
                return;
            }
        }

        if (confirm('Xác nhận kết thúc ca làm việc và chốt toàn bộ số liệu doanh thu / nguyên liệu?')) {
            document.getElementById('closeShiftForm').submit();
        }
    }

    // Trigger initial calculation
    calcCashDiff(document.getElementById('actualCashInput'));
</script>
