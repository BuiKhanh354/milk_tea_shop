<!-- Header & Print Action -->
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
    <div>
        <h4 class="font-serif fw-bold text-dark mb-1">Báo cáo & Thống kê kinh doanh</h4>
        <p class="text-muted small mb-0">
            Kỳ báo cáo hiện tại: <span class="badge bg-forest text-white px-3 py-1 fs-6"><?= e($periodLabel) ?></span>
        </p>
    </div>
    <div class="d-flex gap-2">
        <button class="btn btn-forest rounded-pill px-4" onclick="window.print()">
            <i class="fa-solid fa-print me-2"></i> In / Xuất báo cáo
        </button>
    </div>
</div>

<!-- Bộ lọc theo kỳ (Period Filter Bar) -->
<div class="card border-0 shadow-sm rounded-4 p-3 mb-4 bg-white">
    <form action="<?= e(admin_url('reports')) ?>" method="GET" class="row g-3 align-items-end">
        <input type="hidden" name="page" value="reports">
        
        <div class="col-12">
            <span class="text-muted small fw-bold text-uppercase d-block mb-2">Chọn nhanh kỳ báo cáo:</span>
            <div class="d-flex flex-wrap gap-2">
                <a href="<?= e(admin_url('reports', ['period' => 'today'])) ?>" class="btn btn-sm rounded-pill <?= $period === 'today' ? 'btn-forest fw-bold' : 'btn-outline-secondary' ?>">Hôm nay</a>
                <a href="<?= e(admin_url('reports', ['period' => 'yesterday'])) ?>" class="btn btn-sm rounded-pill <?= $period === 'yesterday' ? 'btn-forest fw-bold' : 'btn-outline-secondary' ?>">Hôm qua</a>
                <a href="<?= e(admin_url('reports', ['period' => 'this_week'])) ?>" class="btn btn-sm rounded-pill <?= $period === 'this_week' ? 'btn-forest fw-bold' : 'btn-outline-secondary' ?>">Tuần này</a>
                <a href="<?= e(admin_url('reports', ['period' => 'this_month'])) ?>" class="btn btn-sm rounded-pill <?= $period === 'this_month' ? 'btn-forest fw-bold' : 'btn-outline-secondary' ?>">Tháng này</a>
                <a href="<?= e(admin_url('reports', ['period' => 'last_month'])) ?>" class="btn btn-sm rounded-pill <?= $period === 'last_month' ? 'btn-forest fw-bold' : 'btn-outline-secondary' ?>">Tháng trước</a>
                <a href="<?= e(admin_url('reports', ['period' => 'this_year'])) ?>" class="btn btn-sm rounded-pill <?= $period === 'this_year' ? 'btn-forest fw-bold' : 'btn-outline-secondary' ?>">Năm nay</a>
                <a href="<?= e(admin_url('reports', ['period' => 'all'])) ?>" class="btn btn-sm rounded-pill <?= $period === 'all' ? 'btn-forest fw-bold' : 'btn-outline-secondary' ?>">Tất cả</a>
            </div>
        </div>

        <input type="hidden" name="period" value="custom">
        <div class="col-12 col-md-4 col-lg-3">
            <label class="form-label small fw-semibold text-muted mb-1">Hoặc từ ngày:</label>
            <input type="date" name="from_date" class="form-control rounded-3" value="<?= e($fromDate) ?>" required>
        </div>
        <div class="col-12 col-md-4 col-lg-3">
            <label class="form-label small fw-semibold text-muted mb-1">Đến ngày:</label>
            <input type="date" name="to_date" class="form-control rounded-3" value="<?= e($toDate) ?>" required>
        </div>
        <div class="col-12 col-md-4 col-lg-3 d-flex gap-2">
            <button type="submit" class="btn btn-primary rounded-3 px-4 w-100">
                <i class="fa-solid fa-filter me-2"></i> Lọc ngày
            </button>
            <a href="<?= e(admin_url('reports', ['period' => 'this_month'])) ?>" class="btn btn-outline-secondary rounded-3" title="Đặt lại">
                <i class="fa-solid fa-rotate-left"></i>
            </a>
        </div>
    </form>
</div>

<!-- Statistics Cards -->
<div class="row g-3 g-xl-4 mb-4">
    <!-- Doanh thu hoàn thành -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm rounded-4 h-100 p-3 bg-white">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="text-muted small fw-semibold text-uppercase">Doanh thu thực nhận</span>
                <div class="bg-success bg-opacity-10 text-success rounded-circle p-2">
                    <i class="fa-solid fa-money-bill-wave"></i>
                </div>
            </div>
            <h4 class="fw-bold mb-1 text-forest font-monospace"><?= money($totalRevenue) ?></h4>
            <small class="text-muted">Đơn hoàn thành trong kỳ</small>
        </div>
    </div>

    <!-- Đơn thành công -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm rounded-4 h-100 p-3 bg-white">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="text-muted small fw-semibold text-uppercase">Đơn thành công</span>
                <div class="bg-primary bg-opacity-10 text-primary rounded-circle p-2">
                    <i class="fa-solid fa-receipt"></i>
                </div>
            </div>
            <h4 class="fw-bold mb-1 text-dark"><?= number_format($totalCompletedOrders) ?> <span class="fs-6 fw-normal text-muted">/ <?= number_format($totalOrdersInPeriod) ?> đơn</span></h4>
            <small class="text-muted">
                <?= $totalOrdersInPeriod > 0 ? round(($totalCompletedOrders / $totalOrdersInPeriod) * 100, 1) : 0 ?>% tỷ lệ giao thành công
            </small>
        </div>
    </div>

    <!-- Giá trị trung bình đơn (AOV) -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm rounded-4 h-100 p-3 bg-white">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="text-muted small fw-semibold text-uppercase">Trung bình / đơn (AOV)</span>
                <div class="bg-warning bg-opacity-15 text-warning rounded-circle p-2">
                    <i class="fa-solid fa-chart-line text-dark"></i>
                </div>
            </div>
            <h4 class="fw-bold mb-1 text-dark font-monospace"><?= money($avgOrderValue) ?></h4>
            <small class="text-muted">Doanh thu bình quân mỗi đơn</small>
        </div>
    </div>

    <!-- Sản phẩm đã bán -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm rounded-4 h-100 p-3 bg-white">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="text-muted small fw-semibold text-uppercase">Sản phẩm bán ra</span>
                <div class="bg-info bg-opacity-10 text-info rounded-circle p-2">
                    <i class="fa-solid fa-mug-hot"></i>
                </div>
            </div>
            <h4 class="fw-bold mb-1 text-dark"><?= number_format($totalSold) ?> <span class="fs-6 fw-normal text-muted">ly</span></h4>
            <small class="text-muted">Khách mới trong kỳ: +<?= $newCustomers ?></small>
        </div>
    </div>
</div>

<!-- Section: Thống kê đơn hàng theo trạng thái -->
<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-header bg-white border-0 py-3 px-4 d-flex justify-content-between align-items-center">
        <h5 class="fw-bold text-dark mb-0 font-serif text-forest">
            <i class="fa-solid fa-chart-pie me-2 text-caramel"></i> Thống kê đơn hàng theo trạng thái
        </h5>
        <span class="badge bg-light text-dark border px-3 py-2 fw-medium">Tổng số: <?= number_format($totalOrdersInPeriod) ?> đơn</span>
    </div>
    <div class="card-body px-4 pt-0">
        <?php if ($totalOrdersInPeriod === 0): ?>
            <p class="text-muted text-center py-4 mb-0">Không có đơn hàng nào trong kỳ đã chọn.</p>
        <?php else: ?>
            <div class="row g-3">
                <?php foreach ($statusMap as $stKey => $stMeta): ?>
                    <?php 
                        $item = $ordersByStatus[$stKey] ?? ['count' => 0, 'amount' => 0];
                        $percent = $totalOrdersInPeriod > 0 ? round(($item['count'] / $totalOrdersInPeriod) * 100, 1) : 0;
                    ?>
                    <div class="col-6 col-md-4 col-xl-2">
                        <div class="p-3 rounded-3 border bg-light bg-opacity-50 text-center h-100 d-flex flex-column justify-content-between">
                            <div>
                                <span class="badge <?= $stMeta['badge'] ?> rounded-pill mb-2 px-3 py-1">
                                    <i class="fa-solid <?= $stMeta['icon'] ?> me-1"></i> <?= $stMeta['label'] ?>
                                </span>
                                <h3 class="fw-bold text-dark mb-0 mt-2"><?= number_format($item['count']) ?></h3>
                                <small class="text-muted"><?= $percent ?>% tổng đơn</small>
                            </div>
                            <div class="mt-2 pt-2 border-top">
                                <small class="fw-bold text-forest d-block font-monospace"><?= money($item['amount']) ?></small>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<div class="row g-4 mb-4">
    <!-- Doanh thu & Đơn hàng theo ngày trong kỳ -->
    <div class="col-12 col-xl-7">
        <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden">
            <div class="card-header bg-white border-0 py-3 px-4 d-flex justify-content-between align-items-center">
                <h5 class="fw-bold text-dark mb-0 font-serif text-forest">
                    <i class="fa-solid fa-calendar-day me-2 text-caramel"></i> Doanh thu & đơn hàng theo ngày
                </h5>
                <span class="small text-muted"><?= count($dailyReports) ?> ngày có phát sinh</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive" style="max-height: 460px;">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light text-muted sticky-top">
                            <tr>
                                <th class="ps-4 py-3 fw-medium border-0">Ngày</th>
                                <th class="py-3 fw-medium border-0 text-center">Tổng đơn</th>
                                <th class="py-3 fw-medium border-0 text-center">Đơn hoàn thành</th>
                                <th class="pe-4 py-3 fw-medium border-0 text-end">Doanh thu thực nhận</th>
                            </tr>
                        </thead>
                        <tbody class="border-top-0">
                            <?php if (empty($dailyReports)): ?>
                                <tr><td colspan="4" class="text-center py-4 text-muted">Không có dữ liệu trong khoảng thời gian này.</td></tr>
                            <?php else: ?>
                                <?php foreach ($dailyReports as $dr): ?>
                                <tr>
                                    <td class="ps-4 fw-semibold text-dark">
                                        <i class="fa-regular fa-calendar me-2 text-muted"></i>
                                        <?= date('d/m/Y', strtotime($dr['report_date'])) ?>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-light text-dark border px-2 py-1"><?= (int)$dr['total_orders'] ?></span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-2 py-1">
                                            <?= (int)$dr['completed_orders'] ?> đơn
                                        </span>
                                    </td>
                                    <td class="pe-4 text-end fw-bold text-forest font-monospace">
                                        <?= money($dr['daily_revenue']) ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Phân tích Hình thức mua & Phương thức thanh toán -->
    <div class="col-12 col-xl-5">
        <div class="card border-0 shadow-sm rounded-4 h-100 p-4">
            <h5 class="fw-bold text-dark mb-3 font-serif text-forest">
                <i class="fa-solid fa-credit-card me-2 text-caramel"></i> Doanh thu theo thanh toán
            </h5>
            <div class="table-responsive mb-4">
                <table class="table table-sm align-middle mb-0">
                    <thead class="text-muted small">
                        <tr>
                            <th>Phương thức</th>
                            <th class="text-center">Số đơn</th>
                            <th class="text-end">Doanh thu</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($paymentReports)): ?>
                            <tr><td colspan="3" class="text-center text-muted py-2">Chưa có giao dịch.</td></tr>
                        <?php else: ?>
                            <?php foreach ($paymentReports as $pr): ?>
                            <tr>
                                <td class="fw-medium text-dark"><?= e($pr['method_name']) ?></td>
                                <td class="text-center"><span class="badge bg-light text-dark border"><?= (int)$pr['order_count'] ?></span></td>
                                <td class="text-end fw-bold text-forest font-monospace"><?= money($pr['total_revenue']) ?></td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <h5 class="fw-bold text-dark mb-3 font-serif text-forest pt-2 border-top">
                <i class="fa-solid fa-store me-2 text-caramel"></i> Theo hình thức phục vụ
            </h5>
            <div class="table-responsive">
                <table class="table table-sm align-middle mb-0">
                    <thead class="text-muted small">
                        <tr>
                            <th>Hình thức</th>
                            <th class="text-center">Số đơn</th>
                            <th class="text-end">Doanh thu</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($typeRows)): ?>
                            <tr><td colspan="3" class="text-center text-muted py-2">Chưa có giao dịch.</td></tr>
                        <?php else: ?>
                            <?php foreach ($typeRows as $tr): ?>
                            <tr>
                                <td class="fw-medium text-dark"><?= e($typeMap[$tr['order_type']] ?? $tr['order_type']) ?></td>
                                <td class="text-center"><span class="badge bg-light text-dark border"><?= (int)$tr['order_count'] ?></span></td>
                                <td class="text-end fw-bold text-forest font-monospace"><?= money($tr['total_revenue']) ?></td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Top Products in Period -->
<div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
    <div class="card-header bg-white border-0 py-3 px-4 d-flex justify-content-between align-items-center">
        <h5 class="fw-bold text-dark mb-0 font-serif text-forest">
            <i class="fa-solid fa-crown me-2 text-warning"></i> Top 10 sản phẩm bán chạy nhất trong kỳ
        </h5>
        <span class="small text-muted"><?= e($periodLabel) ?></span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-muted">
                    <tr>
                        <th class="ps-4 py-3 fw-medium border-0 text-center" style="width: 60px;">#</th>
                        <th class="py-3 fw-medium border-0">Sản phẩm</th>
                        <th class="py-3 fw-medium border-0">Danh mục</th>
                        <th class="py-3 fw-medium border-0 text-center">Số lượng bán</th>
                        <th class="pe-4 py-3 fw-medium border-0 text-end">Tổng doanh số</th>
                    </tr>
                </thead>
                <tbody class="border-top-0">
                    <?php if (empty($topProducts)): ?>
                        <tr><td colspan="5" class="text-center py-4 text-muted">Chưa có dữ liệu sản phẩm trong khoảng thời gian này.</td></tr>
                    <?php else: ?>
                        <?php foreach ($topProducts as $idx => $tp): ?>
                        <tr>
                            <td class="ps-4 text-center">
                                <?php if ($idx === 0): ?>
                                    <span class="badge rounded-circle bg-warning text-dark p-2" title="Hạng 1"><i class="fa-solid fa-trophy"></i></span>
                                <?php elseif ($idx === 1): ?>
                                    <span class="badge rounded-circle bg-secondary text-white p-2" title="Hạng 2">2</span>
                                <?php elseif ($idx === 2): ?>
                                    <span class="badge rounded-circle bg-bronze text-white p-2" style="background:#cd7f32;" title="Hạng 3">3</span>
                                <?php else: ?>
                                    <span class="text-muted fw-bold"><?= $idx + 1 ?></span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="<?= e(product_image($tp['image'])) ?>" alt="<?= e($tp['name']) ?>" class="rounded-3 object-fit-cover me-3 border" width="44" height="44">
                                    <span class="fw-bold text-dark"><?= e($tp['name']) ?></span>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border"><?= e($tp['category_name'] ?? 'Chưa phân loại') ?></span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-1 fs-6">
                                    <?= number_format((int)$tp['sold_count']) ?> ly
                                </span>
                            </td>
                            <td class="pe-4 text-end fw-bold text-forest font-monospace">
                                <?= money($tp['total_sales']) ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
