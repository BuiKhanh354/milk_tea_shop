<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="font-serif fw-bold text-dark mb-1">
            <i class="fa-solid fa-clipboard-check text-forest me-2"></i>Kiểm kê Kho & Điều chỉnh Tồn kho
        </h4>
        <p class="text-muted small mb-0">Đối chiếu số lượng thực tế sau khi cân/đếm với tồn kho trên hệ thống để phát hiện thừa thiếu</p>
    </div>
    <div class="d-flex gap-2">
        <a href="admin.php?route=inventory_consumption" class="btn btn-outline-forest rounded-pill px-3">
            <i class="fa-solid fa-chart-pie me-1"></i> Báo cáo Tiêu hao & Hao hụt
        </a>
        <a href="admin.php?route=inventory" class="btn btn-light border rounded-pill px-3">
            <i class="fa-solid fa-boxes-stacked me-1"></i> Danh sách Nguyên liệu
        </a>
    </div>
</div>

<?php if (isset($_SESSION['flash_success'])): ?>
    <div class="alert alert-success alert-dismissible fade show rounded-3 mb-4" role="alert">
        <i class="fa-solid fa-circle-check me-2"></i><?= $_SESSION['flash_success']; unset($_SESSION['flash_success']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if (isset($_SESSION['flash_error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show rounded-3 mb-4" role="alert">
        <i class="fa-solid fa-triangle-exclamation me-2"></i><?= $_SESSION['flash_error']; unset($_SESSION['flash_error']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<!-- Tabs navigation -->
<ul class="nav nav-pills mb-4 gap-2" id="auditTabs" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active rounded-pill px-4 fw-medium" id="new-audit-tab" data-bs-toggle="pill" data-bs-target="#new-audit" type="button" role="tab">
            <i class="fa-solid fa-plus-circle me-1"></i> Phiếu kiểm kê mới
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link rounded-pill px-4 fw-medium" id="history-audit-tab" data-bs-toggle="pill" data-bs-target="#history-audit" type="button" role="tab">
            <i class="fa-solid fa-clock-rotate-left me-1"></i> Lịch sử kiểm kê (<?= count($audits) ?>)
        </button>
    </li>
</ul>

<div class="tab-content" id="auditTabContent">
    <!-- TAB 1: PHIẾU KIỂM KÊ MỚI -->
    <div class="tab-pane fade show active" id="new-audit" role="tabpanel">
        <form action="admin.php?route=inventory_audit&action=storeAudit" method="POST" id="auditForm">
    <?= csrf_field() ?>
            <!-- Card Summary / Instructions -->
            <div class="row g-3 mb-4">
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100">
                        <span class="text-muted small fw-medium">Tổng nguyên liệu kho</span>
                        <h4 class="fw-bold text-dark mt-1 mb-0"><?= count($ingredients) ?></h4>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100">
                        <span class="text-muted small fw-medium">Đã nhập số lượng đếm</span>
                        <h4 class="fw-bold text-primary mt-1 mb-0" id="countedCount">0</h4>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100">
                        <span class="text-muted small fw-medium">Nguyên liệu thiếu hụt (-)</span>
                        <h4 class="fw-bold text-danger mt-1 mb-0" id="lossCount">0</h4>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100">
                        <span class="text-muted small fw-medium">Nguyên liệu dư thừa (+)</span>
                        <h4 class="fw-bold text-success mt-1 mb-0" id="surplusCount">0</h4>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="col-md-4">
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                                <input type="text" id="auditSearchInput" class="form-control bg-light border-0" placeholder="Lọc nhanh nguyên liệu..." onkeyup="filterAuditTable()">
                            </div>
                        </div>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3" onclick="fillSystemQtyAll()">
                                <i class="fa-solid fa-copy me-1"></i> Điền tồn hệ thống cho tất cả
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-danger rounded-pill px-3" onclick="clearAllInputs()">
                                <i class="fa-solid fa-rotate-left me-1"></i> Xóa hết đã nhập
                            </button>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table align-middle table-hover" id="auditTable">
                            <thead class="bg-light text-muted small">
                                <tr>
                                    <th class="py-3 px-3" style="width: 5%;">#</th>
                                    <th class="py-3" style="width: 25%;">Tên nguyên liệu</th>
                                    <th class="py-3" style="width: 10%;">Đơn vị</th>
                                    <th class="py-3 text-end" style="width: 13%;">Tồn hệ thống</th>
                                    <th class="py-3" style="width: 17%;">Tồn thực tế (cân/đếm)</th>
                                    <th class="py-3 text-center" style="width: 12%;">Chênh lệch</th>
                                    <th class="py-3" style="width: 18%;">Lý do chênh lệch</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($ingredients as $idx => $ing): 
                                    $sysQty = (float)$ing['quantity'];
                                ?>
                                <tr class="audit-row" data-name="<?= strtolower($ing['ingredient_name']) ?>" data-id="<?= $ing['id'] ?>">
                                    <td class="px-3 text-muted"><?= $idx + 1 ?></td>
                                    <td>
                                        <span class="fw-bold text-dark d-block"><?= htmlspecialchars($ing['ingredient_name']) ?></span>
                                        <small class="text-muted">Đơn giá: <?= number_format($ing['price'], 0, ',', '.') ?>đ/<?= htmlspecialchars($ing['unit']) ?></small>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border"><?= htmlspecialchars($ing['unit']) ?></span>
                                    </td>
                                    <td class="text-end">
                                        <span class="fw-bold font-monospace fs-6" id="sys_<?= $ing['id'] ?>"><?= number_format($sysQty, 2, '.', '') ?></span>
                                    </td>
                                    <td>
                                        <div class="input-group input-group-sm">
                                            <input type="number" 
                                                   step="0.01" 
                                                   min="0" 
                                                   name="items[<?= $ing['id'] ?>][actual_qty]" 
                                                   id="actual_<?= $ing['id'] ?>" 
                                                   class="form-control form-control-sm text-end font-monospace fw-bold actual-input" 
                                                   placeholder="Nhập..." 
                                                   oninput="calcDiff(<?= $ing['id'] ?>, <?= $sysQty ?>)">
                                            <span class="input-group-text small text-muted"><?= htmlspecialchars($ing['unit']) ?></span>
                                        </div>
                                    </td>
                                    <td class="text-center" id="diff_col_<?= $ing['id'] ?>">
                                        <span class="text-muted small fst-italic">Chưa nhập</span>
                                    </td>
                                    <td>
                                        <input type="text" 
                                               name="items[<?= $ing['id'] ?>][reason]" 
                                               id="reason_<?= $ing['id'] ?>" 
                                               class="form-control form-control-sm reason-input" 
                                               placeholder="Lý do chênh lệch (nếu có)...">
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Ghi chú chung & Submit -->
                    <div class="border-top pt-4 mt-3">
                        <div class="row align-items-center">
                            <div class="col-md-7 mb-3 mb-md-0">
                                <label class="form-label small fw-bold text-muted mb-1">Ghi chú chung cho đợt kiểm kê này</label>
                                <textarea name="note" class="form-control bg-light border-0" rows="2" placeholder="Ví dụ: Kiểm kê định kỳ cuối tuần, bàn giao ca, hoặc kiểm kê nguyên liệu pha chế..."></textarea>
                            </div>
                            <div class="col-md-5 text-md-end">
                                <div class="text-muted small mb-2">
                                    <i class="fa-solid fa-triangle-exclamation text-warning me-1"></i> 
                                    Sau khi xác nhận, tồn kho sẽ tự động được điều chỉnh theo số lượng thực tế.
                                </div>
                                <button type="button" class="btn btn-forest px-4 py-2 fw-medium rounded-pill shadow-sm" onclick="confirmSubmitAudit()">
                                    <i class="fa-solid fa-check-double me-1"></i> Cân bằng kho & Lưu phiếu
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- TAB 2: LỊCH SỬ CÁC ĐỢT KIỂM KÊ -->
    <div class="tab-pane fade" id="history-audit" role="tabpanel">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4">
                <?php if (empty($audits)): ?>
                    <div class="text-center text-muted py-5">
                        <i class="fa-solid fa-clipboard-list fs-1 mb-3 text-secondary opacity-50"></i>
                        <p class="mb-0">Chưa có phiếu kiểm kê nào được tạo trong hệ thống.</p>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="bg-light text-muted small">
                                <tr>
                                    <th class="py-3 px-3">Mã phiếu</th>
                                    <th class="py-3">Thời gian</th>
                                    <th class="py-3">Người kiểm kê</th>
                                    <th class="py-3 text-center">Tổng món kiểm</th>
                                    <th class="py-3 text-center">Món có chênh lệch</th>
                                    <th class="py-3">Ghi chú</th>
                                    <th class="py-3 text-center">Trạng thái</th>
                                    <th class="py-3 text-end px-3">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($audits as $a): ?>
                                <tr>
                                    <td class="px-3 fw-bold text-forest">
                                        <a href="admin.php?route=inventory_audit&action=showAudit&id=<?= $a['id'] ?>" class="text-forest text-decoration-none">
                                            <?= htmlspecialchars($a['audit_code']) ?>
                                        </a>
                                    </td>
                                    <td class="text-muted small">
                                        <i class="fa-regular fa-clock me-1"></i><?= date('d/m/Y H:i', strtotime($a['created_at'])) ?>
                                    </td>
                                    <td>
                                        <span class="fw-medium text-dark"><?= htmlspecialchars($a['user_name']) ?></span>
                                    </td>
                                    <td class="text-center font-monospace fw-bold">
                                        <?= $a['total_items'] ?>
                                    </td>
                                    <td class="text-center">
                                        <?php if ($a['total_difference_items'] > 0): ?>
                                            <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-3">
                                                <?= $a['total_difference_items'] ?> món chênh lệch
                                            </span>
                                        <?php else: ?>
                                            <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3">
                                                Khớp 100%
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="small text-muted text-truncate" style="max-width: 200px;">
                                        <?= htmlspecialchars($a['note'] ?? 'Không có') ?>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-success rounded-pill px-3">Đã cân bằng kho</span>
                                    </td>
                                    <td class="text-end px-3">
                                        <a href="admin.php?route=inventory_audit&action=showAudit&id=<?= $a['id'] ?>" class="btn btn-sm btn-outline-forest rounded-pill px-3">
                                            Xem chi tiết
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
    function filterAuditTable() {
        const query = document.getElementById('auditSearchInput').value.toLowerCase();
        document.querySelectorAll('.audit-row').forEach(row => {
            const name = row.getAttribute('data-name');
            row.style.display = name.includes(query) ? '' : 'none';
        });
    }

    function calcDiff(invId, sysQty) {
        const inputEl = document.getElementById('actual_' + invId);
        const diffCol = document.getElementById('diff_col_' + invId);
        const reasonInput = document.getElementById('reason_' + invId);

        if (inputEl.value.trim() === '') {
            diffCol.innerHTML = '<span class="text-muted small fst-italic">Chưa nhập</span>';
            reasonInput.classList.remove('border-danger', 'border-warning');
            updateCounters();
            return;
        }

        const actualQty = parseFloat(inputEl.value);
        if (isNaN(actualQty)) return;

        const diff = actualQty - sysQty;
        const diffFormatted = Math.abs(diff).toFixed(2);

        if (Math.abs(diff) < 0.001) {
            diffCol.innerHTML = '<span class="badge bg-success bg-opacity-15 text-light rounded-pill px-3 fw-bold">Khớp (0)</span>';
            reasonInput.classList.remove('border-danger', 'border-warning');
        } else if (diff < 0) {
            diffCol.innerHTML = `<span class="badge bg-danger rounded-pill px-3 fw-bold">Thiếu -${diffFormatted}</span>`;
            reasonInput.classList.add('border-danger');
            if (reasonInput.value.trim() === '') reasonInput.placeholder = 'Bắt buộc nhập lý do thiếu hụt...';
        } else {
            diffCol.innerHTML = `<span class="badge bg-warning text-dark rounded-pill px-3 fw-bold">Thừa +${diffFormatted}</span>`;
            reasonInput.classList.add('border-warning');
            if (reasonInput.value.trim() === '') reasonInput.placeholder = 'Ghi chú lý do dôi dư...';
        }

        updateCounters();
    }

    function updateCounters() {
        let counted = 0;
        let loss = 0;
        let surplus = 0;

        document.querySelectorAll('.actual-input').forEach(input => {
            if (input.value.trim() !== '') {
                counted++;
                const invId = input.id.replace('actual_', '');
                const sysQty = parseFloat(document.getElementById('sys_' + invId).innerText);
                const actualQty = parseFloat(input.value);
                const diff = actualQty - sysQty;

                if (diff < -0.001) loss++;
                else if (diff > 0.001) surplus++;
            }
        });

        document.getElementById('countedCount').innerText = counted;
        document.getElementById('lossCount').innerText = loss;
        document.getElementById('surplusCount').innerText = surplus;
    }

    function fillSystemQtyAll() {
        if (!confirm('Bạn có muốn tự động điền số lượng tồn hệ thống vào các ô tồn thực tế để kiểm tra nhanh?')) return;
        document.querySelectorAll('.actual-input').forEach(input => {
            const invId = input.id.replace('actual_', '');
            const sysQty = parseFloat(document.getElementById('sys_' + invId).innerText);
            input.value = sysQty;
            calcDiff(invId, sysQty);
        });
    }

    function clearAllInputs() {
        if (!confirm('Xóa toàn bộ các số lượng thực tế đã nhập?')) return;
        document.querySelectorAll('.actual-input').forEach(input => {
            input.value = '';
            const invId = input.id.replace('actual_', '');
            const sysQty = parseFloat(document.getElementById('sys_' + invId).innerText);
            calcDiff(invId, sysQty);
        });
    }

    function confirmSubmitAudit() {
        let counted = 0;
        let diffCount = 0;
        document.querySelectorAll('.actual-input').forEach(input => {
            if (input.value.trim() !== '') {
                counted++;
                const invId = input.id.replace('actual_', '');
                const sysQty = parseFloat(document.getElementById('sys_' + invId).innerText);
                const diff = parseFloat(input.value) - sysQty;
                if (Math.abs(diff) > 0.001) diffCount++;
            }
        });

        if (counted === 0) {
            alert('Vui lòng nhập số lượng kiểm kê thực tế cho ít nhất 1 nguyên liệu trước khi lưu!');
            return;
        }

        let msg = `Bạn đã kiểm kê ${counted} nguyên liệu (trong đó có ${diffCount} nguyên liệu có chênh lệch).\n\nHệ thống sẽ tự động cập nhật số lượng tồn kho mới và ghi nhận lịch sử điều chỉnh (ADJUSTMENT).\n\nBạn có chắc chắn muốn hoàn tất?`;
        if (confirm(msg)) {
            document.getElementById('auditForm').submit();
        }
    }
</script>
