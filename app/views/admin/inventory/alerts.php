<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="font-serif fw-bold text-dark mb-1 text-warning">CẢNH BÁO TỒN KHO</h4>
        <p class="text-muted fst-italic small mb-0">"Danh sách các nguyên liệu cần nhập thêm ngay."</p>
    </div>
</div>

<div class="row g-4">
    <?php if(empty($alerts)): ?>
        <div class="col-12 text-center py-5">
            <div class="text-success mb-3" style="font-size: 3rem;"><i class="fa-solid fa-circle-check"></i></div>
            <h5 class="fw-bold text-dark">Kho hàng ổn định</h5>
            <p class="text-muted">Không có nguyên liệu nào đang trong tình trạng cảnh báo.</p>
        </div>
    <?php else: ?>
        <?php foreach($alerts as $item): 
            $qty = floatval($item['quantity']);
            $min = floatval($item['min_quantity']);
            
            $is_out = ($qty <= 0);
            $cardClass = $is_out ? 'border-danger border-2' : 'border-warning border-2 border-opacity-50';
            $iconClass = $is_out ? 'text-danger fa-circle-xmark' : 'text-warning fa-triangle-exclamation';
            $badgeClass = $is_out ? 'bg-danger text-white' : 'bg-warning text-dark';
            $statusText = $is_out ? 'Hết hàng' : 'Sắp hết';
        ?>
        <div class="col-12 col-md-6 col-lg-4">
            <div class="card shadow-sm rounded-4 <?= $cardClass ?> h-100 position-relative overflow-hidden">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <h5 class="fw-bold text-dark mb-0"><?= htmlspecialchars($item['ingredient_name']) ?></h5>
                        <span class="badge rounded-pill <?= $badgeClass ?> px-3 py-2"><i class="fa-solid <?= $iconClass ?> me-1"></i> <?= $statusText ?></span>
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-end mt-4">
                        <div>
                            <div class="small text-muted mb-1">Tồn kho hiện tại</div>
                            <h3 class="fw-bold mb-0 <?= $is_out ? 'text-danger' : 'text-warning' ?>"><?= $qty ?> <span class="fs-6 text-muted fw-normal"><?= htmlspecialchars($item['unit']) ?></span></h3>
                        </div>
                        <div class="text-end">
                            <div class="small text-muted mb-1">Mức tối thiểu</div>
                            <div class="fw-medium text-dark"><?= $min ?> <?= htmlspecialchars($item['unit']) ?></div>
                        </div>
                    </div>
                    
                    <hr class="my-3 border-secondary border-opacity-10">
                    
                    <a href="admin.php?route=inventory_import" class="btn <?= $is_out ? 'btn-danger' : 'btn-outline-warning text-dark' ?> w-100 fw-medium">
                        <i class="fa-solid fa-arrow-down me-2"></i> Nhập thêm ngay
                    </a>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
