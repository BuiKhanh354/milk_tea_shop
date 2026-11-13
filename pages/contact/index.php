<?php
// pages/contact/index.php
$title = 'Liên hệ';
?>
<section class="py-5">
    <div class="container py-4">
        <div class="row g-5">
            <div class="col-lg-5">
                <span class="small-label mb-2 d-inline-block text-caramel">Hỗ Trợ Khách Hàng</span>
                <h1 class="font-serif display-5 fw-bold text-forest mb-4">Liên Hệ Với VAA THÉ</h1>
                <p class="text-muted mb-4">Chúng tôi luôn sẵn sàng lắng nghe mọi góp ý và phản hồi từ bạn để hoàn thiện chất lượng dịch vụ từng ngày.</p>
                
                <div class="mb-3 d-flex align-items-center gap-3">
                    <div class="bg-sage text-forest rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                        <i class="fa-solid fa-location-dot"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-0 text-forest">Trụ sở chính</h6>
                        <small class="text-muted">123 Nguyễn Văn Cừ, Quận 5, TP.HCM</small>
                    </div>
                </div>

                <div class="mb-3 d-flex align-items-center gap-3">
                    <div class="bg-sage text-forest rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                        <i class="fa-solid fa-phone"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-0 text-forest">Điện thoại</h6>
                        <small class="text-muted">090 123 4567</small>
                    </div>
                </div>

                <div class="mb-3 d-flex align-items-center gap-3">
                    <div class="bg-sage text-forest rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                        <i class="fa-solid fa-envelope"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-0 text-forest">Email</h6>
                        <small class="text-muted">contact@vaathe.vn</small>
                    </div>
                </div>
            </div>

            <div class="col-lg-7">
                <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5">
                    <h4 class="font-serif fw-bold text-forest mb-4">Gửi Lời Nhắn Cho Chúng Tôi</h4>
                    <form onsubmit="alert('Cảm ơn bạn đã gửi lời nhắn! Chúng tôi sẽ phản hồi sớm nhất.'); return false;">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Họ và tên *</label>
                                <input type="text" class="form-control" required placeholder="Nguyễn Văn A">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Email *</label>
                                <input type="email" class="form-control" required placeholder="email@example.com">
                            </div>
                            <div class="col-12">
                                <label class="form-label small fw-bold">Tiêu đề</label>
                                <input type="text" class="form-control" placeholder="Chủ đề góp ý...">
                            </div>
                            <div class="col-12">
                                <label class="form-label small fw-bold">Nội dung tin nhắn *</label>
                                <textarea class="form-control" rows="4" required placeholder="Nhập nội dung bạn muốn chia sẻ..."></textarea>
                            </div>
                            <div class="col-12 mt-4">
                                <button type="submit" class="btn btn-forest px-4 py-2 rounded-pill fw-bold">Gửi Tin Nhắn</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

