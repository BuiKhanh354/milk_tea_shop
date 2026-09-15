<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="font-serif fw-bold text-dark mb-0">Sơ đồ Bàn</h4>
    <div class="d-flex gap-3 text-muted small">
        <div><span class="d-inline-block rounded-circle bg-white border border-secondary me-1" style="width:12px;height:12px;"></span> Bàn trống</div>
        <div><span class="d-inline-block rounded-circle bg-primary bg-opacity-25 border border-primary me-1" style="width:12px;height:12px;"></span> Đang có khách</div>
        <div><span class="d-inline-block rounded-circle bg-warning bg-opacity-25 border border-warning me-1" style="width:12px;height:12px;"></span> Đã đặt trước</div>
    </div>
</div>

<div class="row g-4">
    <?php if(empty($tables)): ?>
        <div class="col-12 text-center text-muted py-5">
            Chưa có dữ liệu bàn.
        </div>
    <?php else: ?>
        <?php foreach($tables as $table): ?>
            <?php 
                $bgClass = 'bg-white';
                $borderClass = 'border-secondary border-opacity-25';
                $textClass = 'text-dark';
                $icon = 'fa-chair';
                
                if($table['status'] == 'occupied') {
                    $bgClass = 'bg-primary bg-opacity-10';
                    $borderClass = 'border-primary';
                    $textClass = 'text-primary';
                    $icon = 'fa-users';
                } else if($table['status'] == 'reserved') {
                    $bgClass = 'bg-warning bg-opacity-10';
                    $borderClass = 'border-warning';
                    $textClass = 'text-warning';
                    $icon = 'fa-calendar-check';
                }
            ?>
            <div class="col-6 col-md-3 col-xl-2">
                <div class="card <?= $bgClass ?> <?= $borderClass ?> shadow-sm rounded-4 text-center h-100" style="cursor: pointer; transition: all 0.3s;" onmouseover="this.classList.add('shadow')" onmouseout="this.classList.remove('shadow')">
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
