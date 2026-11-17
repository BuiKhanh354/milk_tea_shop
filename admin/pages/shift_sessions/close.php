<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="font-serif fw-bold text-dark mb-1">
            <a href="<?= e(admin_url('shift_sessions')) ?>" class="text-muted me-2"><i class="fa-solid fa-arrow-left"></i></a>
            Kết ca & Bàn giao (#<?= e($currentOpen['session_code']) ?>)
        </h4>
        <p class="text-muted small mb-0">
            Mở lúc: <strong><?= date('d/m/Y H:i', strtotime($currentOpen['opened_at'])) ?></strong> 
            | Thu ngân: <strong><?= e($currentOpen['user_name']) ?></strong>
            | Tiền két ban đầu: <strong class="text-forest"><?= money($summary['initial_cash']) ?></strong>
        </p>
    </div>
</div>

<form action="<?= e(admin_url('shift_sessions', ['action' => 'storeClose'])) ?>" method="POST" id="closeShiftForm">
    <input type="hidden" name="session_id" value="<?= (int)$currentOpen['id'] ?>">

    <div class="row g-4 mb-4">
        <!-- Doanh thu ca -->
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-2">
                        <h5 class="fw-bold text-dark mb-0 font-serif text-forest">
                            <i class="fa-solid fa-chart-pie me-2"></i>1. Doanh thu bán được trong ca
                        </h5>
                        <span class="badge bg-light text-muted border px-3 py-2">
                            <?= (int)$summary['total_orders'] ?> đơn hàng (<?= (int)$summary['completed_orders'] ?> hoàn thành)
                        </span>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-sm-4">
                            <div class="p-3 bg-light rounded-3 border">
                                <span class="text-muted small d-block mb-1"><i class="fa-solid fa-money-bill text-success me-1"></i>Tiền mặt (POS)</span>
                                <h5 class="fw-bold text-dark mb-0 font-monospace"><?= money($summary['cash_sales']) ?></h5>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="p-3 bg-light rounded-3 border">
                                <span class="text-muted small d-block mb-1"><i class="fa-solid fa-building-columns text-primary me-1"></i>Chuyển khoản</span>
                                <h5 class="fw-bold text-dark mb-0 font-monospace"><?= money($summary['bank_sales']) ?></h5>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="p-3 bg-light rounded-3 border">
                                <span class="text-muted small d-block mb-1"><i class="fa-solid fa-wallet text-danger me-1"></i>Ví MoMo</span>
                                <h5 class="fw-bold text-dark mb-0 font-monospace"><?= money($summary['momo_sales']) ?></h5>
                            </div>
                        </div>
                    </div>

                    <div class="p-3 bg-success bg-opacity-10 rounded-3 border border-success d-flex justify-content-between align-items-center">
                        <div>
                            <span class="fw-bold text-white fs-6">TỔNG DOANH THU CA:</span>
                            <small class="d-block text-muted">Toàn bộ tiền mặt, chuyển khoản và ví điện tử</small>
                        </div>
                        <h3 class="fw-bold text-forest mb-0 font-monospace"><?= money($summary['total_sales']) ?></h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kiểm tiền mặt trong két -->
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">
                    <h5 class="fw-bold text-dark mb-3 border-bottom pb-2 font-serif text-forest">
                        <i class="fa-solid fa-cash-register me-2"></i>2. Kiểm đếm tiền mặt trong két
                    </h5>

                    <div class="mb-3">
                        <div class="d-flex justify-content-between text-muted small mb-1">
                            <span>Tiền mặt ban đầu:</span>
                            <span class="font-monospace fw-bold"><?= money($summary['initial_cash']) ?></span>
                        </div>
                        <div class="d-flex justify-content-between text-muted small mb-1">
                            <span>+ Doanh thu tiền mặt:</span>
                            <span class="font-monospace fw-bold text-success">+<?= money($summary['cash_sales']) ?></span>
                        </div>
                        <hr class="my-2">
                        <div class="d-flex justify-content-between text-dark fw-bold mb-2">
                            <span>TIỀN KÉT LÝ THUYẾT:</span>
                            <span class="font-monospace text-forest fs-5" id="expectedCashText" data-val="<?= (float)$summary['expected_cash'] ?>"><?= money($summary['expected_cash']) ?></span>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-dark mb-1">Tiền mặt thực tế đếm được <span class="text-danger">*</span></label>
                        <input type="text" name="actual_cash" id="actualCashInput" class="form-control form-control-lg border-secondary border-opacity-25 font-monospace fw-bold shadow-none" value="<?= number_format($summary['expected_cash'], 0, ',', '.') ?>" onkeyup="calcCashDiff(this)" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted mb-1">Lý do chênh lệch (nếu có)</label>
                        <input type="text" name="cash_difference_reason" id="cashReasonInput" class="form-control border-secondary border-opacity-25 shadow-none" placeholder="Nhập lý do nếu thừa/thiếu tiền két...">
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted mb-1">Ghi chú bàn giao</label>
                        <textarea name="note" class="form-control border-secondary border-opacity-25 shadow-none" rows="2" placeholder="Ghi chú thêm..."></textarea>
                    </div>

                    <button type="submit" class="btn btn-forest w-100 py-3 rounded-pill fw-bold shadow-sm" onclick="return confirm('Xác nhận kết thúc ca làm việc và chốt toàn bộ số liệu?')">
                        <i class="fa-solid fa-check-double me-2"></i> XÁC NHẬN KẾT CA & BÀN GIAO
                    </button>
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
}
</script>

