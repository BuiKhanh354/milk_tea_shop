<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="font-serif fw-bold text-dark mb-1">LỊCH SỬ KHO</h4>
        <p class="text-muted fst-italic small mb-0">"Theo dõi mọi biến động của kho hàng."</p>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 py-3 fw-medium border-0 rounded-top-left">Thời gian</th>
                        <th class="py-3 fw-medium border-0">Nguyên liệu</th>
                        <th class="py-3 fw-medium border-0">Loại giao dịch</th>
                        <th class="py-3 fw-medium border-0 text-end">Số lượng</th>
                        <th class="py-3 fw-medium border-0">Người thực hiện</th>
                        <th class="pe-4 py-3 fw-medium border-0 rounded-top-right">Ghi chú</th>
                    </tr>
                </thead>
                <tbody class="border-top-0 bg-white">
                    <?php if(empty($transactions)): ?>
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">Chưa có giao dịch nào trong lịch sử.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach($transactions as $trans): 
                            $typeLabel = '';
                            $typeClass = '';
                            $qtyPrefix = '';
                            $qtyClass = '';
                            
                            switch($trans['type']) {
                                case 'IMPORT':
                                    $typeLabel = 'Nhập kho';
                                    $typeClass = 'bg-success bg-opacity-10 text-success';
                                    $qtyPrefix = '+';
                                    $qtyClass = 'text-success';
                                    break;
                                case 'EXPORT':
                                    $typeLabel = 'Xuất kho';
                                    $typeClass = 'bg-danger bg-opacity-10 text-danger';
                                    $qtyPrefix = '-';
                                    $qtyClass = 'text-danger';
                                    break;
                                case 'USAGE':
                                    $typeLabel = 'Tiêu hao';
                                    $typeClass = 'bg-warning bg-opacity-10 text-warning';
                                    $qtyPrefix = '-';
                                    $qtyClass = 'text-warning';
                                    break;
                                default:
                                    $typeLabel = 'Điều chỉnh';
                                    $typeClass = 'bg-secondary bg-opacity-10 text-secondary';
                                    $qtyPrefix = '';
                                    $qtyClass = 'text-muted';
                            }
                        ?>
                        <tr>
                            <td class="ps-4 text-muted small">
                                <?= date('d/m/Y H:i', strtotime($trans['created_at'])) ?>
                            </td>
                            <td>
                                <div class="fw-bold text-dark"><?= htmlspecialchars($trans['ingredient_name']) ?></div>
                            </td>
                            <td>
                                <span class="badge rounded-pill px-3 py-2 <?= $typeClass ?>"><?= $typeLabel ?></span>
                            </td>
                            <td class="text-end fw-bold <?= $qtyClass ?>">
                                <?= $qtyPrefix ?><?= floatval($trans['quantity']) ?> <?= htmlspecialchars($trans['unit']) ?>
                            </td>
                            <td class="text-muted small">
                                <?= htmlspecialchars($trans['user_name'] ?? 'Hệ thống') ?>
                            </td>
                            <td class="pe-4 text-muted small" style="max-width: 250px;">
                                <?= htmlspecialchars($trans['note']) ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
