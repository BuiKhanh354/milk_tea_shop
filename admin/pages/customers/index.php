<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="font-serif fw-bold text-dark mb-0">Danh sách Khách hàng</h4>
</div>

<?php if ($successMsg = get_flash('success')): ?>
    <div class="alert alert-success alert-dismissible fade show rounded-4 border-0 shadow-sm" role="alert">
        <i class="fa-solid fa-check-circle me-2"></i> <?= e($successMsg) ?>
        <button type="button" class="btn-close shadow-none" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="card border-0 shadow-sm mb-4 rounded-4">
    <div class="card-body p-3">
        <form action="<?= e(admin_url('customers')) ?>" method="GET">
            <input type="hidden" name="page" value="customers">
            <div class="row g-3">
                <div class="col-md-6">
                    <input type="text" name="search" class="form-control bg-light border-0 shadow-none" placeholder="Tìm tên, email, số điện thoại..." value="<?= e(get_param('search')) ?>">
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-select bg-light border-0 shadow-none">
                        <option value="">Tất cả trạng thái</option>
                        <option value="1" <?= get_param('status') === '1' ? 'selected' : '' ?>>Hoạt động</option>
                        <option value="0" <?= get_param('status') === '0' ? 'selected' : '' ?>>Đã khóa</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-outline-forest w-100"><i class="fa-solid fa-filter me-2"></i> Lọc</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="bg-light text-muted">
                    <tr>
                        <th class="ps-4 py-3 fw-medium border-0">Khách hàng</th>
                        <th class="py-3 fw-medium border-0">Liên hệ</th>
                        <th class="py-3 fw-medium border-0">Tổng đơn</th>
                        <th class="py-3 fw-medium border-0">Tổng chi tiêu</th>
                        <th class="py-3 fw-medium border-0">Ngày đăng ký</th>
                        <th class="pe-4 py-3 fw-medium border-0 text-end">Trạng thái</th>
                    </tr>
                </thead>
                <tbody class="border-top-0">
                    <?php if(empty($customers)): ?>
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">Chưa có khách hàng nào.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach($customers as $customer): ?>
                        <tr>
                            <td class="ps-4">
                                <div class="fw-bold text-dark"><?= e($customer['full_name']) ?></div>
                            </td>
                            <td>
                                <div class="small text-muted mb-1"><i class="fa-solid fa-phone me-1"></i> <?= e($customer['phone'] ?? 'Chưa có SĐT') ?></div>
                                <div class="small text-muted"><i class="fa-solid fa-envelope me-1"></i> <?= e($customer['email'] ?? 'Chưa có Email') ?></div>
                            </td>
                            <td>
                                <span class="badge bg-secondary bg-opacity-10 text-dark rounded-pill px-3 py-2"><?= (int)$customer['total_orders'] ?> đơn</span>
                            </td>
                            <td class="fw-bold text-dark"><?= money($customer['total_spent']) ?></td>
                            <td class="text-muted small"><?= date('d/m/Y', strtotime($customer['created_at'])) ?></td>
                            <td class="text-end pe-4">
                                <div class="d-flex justify-content-end align-items-center gap-2">
                                    <?php if($customer['status'] == 1): ?>
                                        <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill mb-0 me-2"><i class="fa-solid fa-check me-1"></i> Hoạt động</span>
                                        <form action="<?= e(admin_url('customers', ['action' => 'toggle_status'])) ?>" method="POST" class="m-0" onsubmit="return confirm('Bạn muốn khóa tài khoản này?');">
                                            <input type="hidden" name="id" value="<?= (int)$customer['id'] ?>">
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Khóa tài khoản"><i class="fa-solid fa-lock"></i></button>
                                        </form>
                                    <?php else: ?>
                                        <span class="badge bg-danger bg-opacity-10 text-danger px-3 py-2 rounded-pill mb-0 me-2"><i class="fa-solid fa-lock me-1"></i> Đã khóa</span>
                                        <form action="<?= e(admin_url('customers', ['action' => 'toggle_status'])) ?>" method="POST" class="m-0" onsubmit="return confirm('Bạn muốn mở khóa tài khoản này?');">
                                            <input type="hidden" name="id" value="<?= (int)$customer['id'] ?>">
                                            <button type="submit" class="btn btn-sm btn-outline-success" title="Mở khóa tài khoản"><i class="fa-solid fa-unlock"></i></button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <?php if ($totalPages > 1): ?>
        <?php 
            $queryString = '';
            if (get_param('search') !== '') $queryString .= '&search=' . urlencode(get_param('search'));
            if (get_param('status') !== '') $queryString .= '&status=' . urlencode(get_param('status'));
        ?>
        <div class="card-footer bg-white border-top py-3 px-4 d-flex align-items-center justify-content-between">
            <span class="text-muted small">Hiển thị trang <?= $currentPage ?> của <?= $totalPages ?></span>
            <nav aria-label="Page navigation">
                <ul class="pagination pagination-sm mb-0">
                    <li class="page-item <?= $currentPage <= 1 ? 'disabled' : '' ?>">
                        <a class="page-link <?= $currentPage <= 1 ? '' : 'text-forest' ?>" href="<?= $currentPage > 1 ? admin_url('customers') . $queryString . '&p=' . ($currentPage - 1) : '#' ?>">Trước</a>
                    </li>
                    
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <li class="page-item <?= $i === $currentPage ? 'active' : '' ?>">
                            <a class="page-link <?= $i === $currentPage ? 'bg-forest border-forest text-white' : 'text-forest' ?>" href="<?= admin_url('customers') . $queryString . '&p=' . $i ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>
                    
                    <li class="page-item <?= $currentPage >= $totalPages ? 'disabled' : '' ?>">
                        <a class="page-link <?= $currentPage >= $totalPages ? '' : 'text-forest' ?>" href="<?= $currentPage < $totalPages ? admin_url('customers') . $queryString . '&p=' . ($currentPage + 1) : '#' ?>">Sau</a>
                    </li>
                </ul>
            </nav>
        </div>
        <?php endif; ?>
    </div>
</div>
