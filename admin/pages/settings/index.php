<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <h4 class="font-serif fw-bold text-dark mb-0">Cài đặt hệ thống</h4>
</div>

<?php if ($successMsg = get_flash('success')): ?>
    <div class="alert alert-success alert-dismissible fade show rounded-4 border-0 shadow-sm mb-4">
        <i class="fa-solid fa-circle-check me-2"></i> <?= e($successMsg) ?>
        <button type="button" class="btn-close shadow-none" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<form action="<?= e(admin_url('settings')) ?>" method="POST">
    <div class="row g-4">
        <!-- Thông tin cửa hàng -->
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white border-0 py-3 px-4">
                    <h5 class="mb-0 fw-bold text-dark font-serif text-forest"><i class="fa-solid fa-store me-2"></i> Thông tin cửa hàng</h5>
                </div>
                <div class="card-body px-4 pb-4 pt-0">
                    <div class="mb-3">
                        <label class="form-label fw-medium text-muted small mb-1">Tên cửa hàng</label>
                        <input type="text" name="store_name" class="form-control border-secondary border-opacity-25 shadow-none" value="VAA THÉ">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-medium text-muted small mb-1">Slogan</label>
                        <input type="text" name="slogan" class="form-control border-secondary border-opacity-25 shadow-none" value="GOOD TEA, GOOD MOOD">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-medium text-muted small mb-1">Số điện thoại hotline</label>
                        <input type="text" name="hotline" class="form-control border-secondary border-opacity-25 shadow-none" value="1900 1234">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-medium text-muted small mb-1">Địa chỉ chính</label>
                        <textarea name="address" class="form-control border-secondary border-opacity-25 shadow-none" rows="3">123 Đường VAA, Quận 1, TP. Hồ Chí Minh</textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- Cài đặt bán hàng -->
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white border-0 py-3 px-4">
                    <h5 class="mb-0 fw-bold text-dark font-serif text-forest"><i class="fa-solid fa-cash-register me-2"></i> Cài đặt bán hàng</h5>
                </div>
                <div class="card-body px-4 pb-4 pt-0">
                    <div class="mb-3">
                        <label class="form-label fw-medium text-muted small mb-1">Thuế VAT (%)</label>
                        <input type="number" name="vat" class="form-control border-secondary border-opacity-25 shadow-none" value="8">
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-medium text-muted small mb-1">Phí giao hàng mặc định (VNĐ)</label>
                        <input type="number" name="shipping_fee" class="form-control border-secondary border-opacity-25 shadow-none" value="15000">
                    </div>
                    
                    <hr class="text-muted opacity-25">
                    
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" role="switch" id="autoPrintReceipt" checked>
                        <label class="form-check-label ms-2" for="autoPrintReceipt">Tự động in hóa đơn khi hoàn thành</label>
                    </div>
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" role="switch" id="enableInventoryAlert" checked>
                        <label class="form-check-label ms-2" for="enableInventoryAlert">Cảnh báo nguyên liệu sắp hết</label>
                    </div>
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" role="switch" id="acceptOnlineOrders" checked>
                        <label class="form-check-label ms-2" for="acceptOnlineOrders">Mở nhận đơn hàng Online</label>
                    </div>

                    <div class="mt-4 pt-2">
                        <button type="submit" class="btn btn-forest rounded-pill px-4">
                            <i class="fa-solid fa-floppy-disk me-2"></i> Lưu thay đổi
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
