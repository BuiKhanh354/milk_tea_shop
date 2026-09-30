<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="font-serif fw-bold text-forest mb-0">Cài đặt hệ thống</h3>
        <button class="btn btn-caramel rounded-pill px-4"><i class="fa-solid fa-save me-2"></i> Lưu thay đổi</button>
    </div>

    <div class="row g-4">
        <!-- General Settings -->
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0 fw-bold text-dark"><i class="fa-solid fa-store text-caramel me-2"></i> Thông tin cửa hàng</h5>
                </div>
                <div class="card-body">
                    <form>
                        <div class="mb-3">
                            <label class="form-label fw-medium text-muted small">Tên cửa hàng</label>
                            <input type="text" class="form-control border-light shadow-none bg-light" value="VAA THÉ">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-medium text-muted small">Slogan</label>
                            <input type="text" class="form-control border-light shadow-none bg-light" value="GOOD TEA, GOOD MOOD">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-medium text-muted small">Số điện thoại liên hệ</label>
                            <input type="text" class="form-control border-light shadow-none bg-light" value="1900 1234">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-medium text-muted small">Địa chỉ chính</label>
                            <textarea class="form-control border-light shadow-none bg-light" rows="3">123 Đường VAA, Quận 1, TP.HCM</textarea>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- POS Settings -->
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0 fw-bold text-dark"><i class="fa-solid fa-cash-register text-caramel me-2"></i> Cài đặt bán hàng</h5>
                </div>
                <div class="card-body">
                    <form>
                        <div class="mb-4">
                            <label class="form-label fw-medium text-muted small">Thuế VAT (%)</label>
                            <input type="number" class="form-control border-light shadow-none bg-light" value="8">
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-medium text-muted small">Phí giao hàng mặc định (VNĐ)</label>
                            <input type="number" class="form-control border-light shadow-none bg-light" value="15000">
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
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
