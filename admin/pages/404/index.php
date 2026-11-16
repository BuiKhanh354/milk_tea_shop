<!-- admin/pages/404/index.php — Trang 404 cho phân hệ Admin -->
<div class="card border-0 shadow-sm rounded-4 p-5 text-center my-4 bg-white">
    <div class="py-4">
        <i class="fa-solid fa-triangle-exclamation fa-4x text-warning mb-3"></i>
        <h3 class="font-serif fw-bold text-forest mb-2">Chức năng đang được cập nhật</h3>
        <p class="text-muted mb-4">Trang bạn vừa truy cập chưa được tạo hoặc đang trong quá trình nâng cấp.</p>
        <a href="<?= e(admin_url('dashboard')) ?>" class="btn btn-forest rounded-pill px-4 py-2 fw-bold">
            <i class="fa-solid fa-arrow-left me-2"></i> Quay về Dashboard
        </a>
    </div>
</div>

