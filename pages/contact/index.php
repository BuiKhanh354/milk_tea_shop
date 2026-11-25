<?php
// pages/contact/index.php — Khôi phục giao diện Liên hệ chuẩn gốc VAA THÉ
$title = 'Liên hệ';
?>
<style>
    .contact-hero {
        background-color: #EFE8DA;
        padding: 70px 0 50px;
        text-align: center;
    }
    .contact-info-card {
        background-color: #fff;
        padding: 30px;
        border-radius: 16px;
        box-shadow: 0 10px 30px rgba(24, 35, 29, 0.05);
        height: 100%;
        border: solid 1px;
        transition: transform 0.3s ease;
    }
    .contact-info-card:hover {
        transform: translateY(-5px);
    }
    .contact-icon {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background-color: #F7F4ED;
        color: #9A7654;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        margin-bottom: 20px;
        border: solid 2px;
    }
    .contact-form {
        background-color: #fff;
        padding: 34px;
        border-radius: 16px;
        box-shadow: 0 10px 30px rgba(24, 35, 29, 0.05);
        border: solid 1px;
    }
    .contact-form .form-control, .contact-form .form-select {
        background-color: #F7F4ED;
        border: 1px solid #E4DAC5;
        padding: 12px 18px;
        border-radius: 10px;
    }
    .contact-form .form-control:focus, .contact-form .form-select:focus {
        background-color: #fff;
        border-color: #AAB79F;
        box-shadow: 0 0 0 0.25rem rgba(170, 183, 159, 0.25);
    }
</style>

<!-- Hero Section -->
<section class="contact-hero fade-up">
    <div class="container">
        <p class="text-uppercase fw-bold mb-2 text-caramel tracking-wider small">Get in touch</p>
        <h1 class="font-serif fw-bold mb-3 text-forest display-5">Liên hệ với chúng tôi</h1>
        <p class="text-muted mx-auto" style="max-width: 650px;">VAA THÉ luôn trân trọng mọi ý kiến đóng góp từ bạn. Hãy để lại tin nhắn, chúng tôi sẽ phản hồi trong thời gian sớm nhất.</p>
    </div>
</section>

<!-- Contact Content -->
<section class="py-5 fade-up">
    <div class="container py-4">
        <div class="row g-5">
            
            <!-- Contact Info -->
            <div class="col-12 col-lg-4">
                <div class="row g-4">
                    <div class="col-12">
                        <div class="contact-info-card">
                            <div class="contact-icon"><i class="fa-solid fa-location-dot"></i></div>
                            <h5 class="fw-bold text-forest mb-2">Trụ sở chính</h5>
                            <p class="text-muted mb-0">18/1A Cộng Hoà, Phường Tân Sân Nhất, Thành phố Hồ Chí Minh</p>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="contact-info-card">
                            <div class="contact-icon"><i class="fa-solid fa-phone"></i></div>
                            <h5 class="fw-bold text-forest mb-2">Hotline Hỗ trợ</h5>
                            <p class="text-muted mb-1">CSKH: 1900 633 988</p>
                            <p class="text-muted mb-0">Hợp tác: (028) 38 123 456</p>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="contact-info-card">
                            <div class="contact-icon"><i class="fa-solid fa-envelope"></i></div>
                            <h5 class="fw-bold text-forest mb-2">Email</h5>
                            <p class="text-muted mb-1">Hỗ trợ: cskh@vaathe.vn</p>
                            <p class="text-muted mb-0">Công việc: work@vaathe.vn</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact Form -->
            <div class="col-12 col-lg-8">
                <div class="contact-form">
                    <h4 class="font-serif fw-bold mb-4 text-forest">Gửi tin nhắn cho VAA THÉ</h4>
                    
                    <?php if(isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST'): ?>
                        <div class="alert alert-success rounded-3 mb-4">
                            <i class="fa-solid fa-check-circle me-2"></i> Cảm ơn bạn! Tin nhắn đã được gửi đi thành công. Chúng tôi sẽ liên hệ lại với bạn sớm nhất.
                        </div>
                    <?php endif; ?>

                    <form action="<?= e(url('contact')) ?>" method="POST">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="form-label fw-medium text-forest small">Họ và tên <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="name" required placeholder="Nhập họ tên của bạn">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-medium text-forest small">Số điện thoại <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="phone" required placeholder="Nhập số điện thoại">
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-medium text-forest small">Email liên hệ <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" name="email" required placeholder="example@email.com">
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-medium text-forest small">Chủ đề cần hỗ trợ</label>
                                <select class="form-select form-control" name="subject">
                                    <option value="Đóng góp ý kiến">Đóng góp ý kiến dịch vụ</option>
                                    <option value="Hỗ trợ đơn hàng">Hỗ trợ đơn hàng</option>
                                    <option value="Hợp tác kinh doanh">Hợp tác kinh doanh</option>
                                    <option value="Tuyển dụng">Tuyển dụng</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-medium text-forest small">Nội dung chi tiết <span class="text-danger">*</span></label>
                                <textarea class="form-control" rows="5" name="message" required placeholder="Viết nội dung tin nhắn của bạn ở đây..."></textarea>
                            </div>
                            <div class="col-12 mt-4 text-end">
                                <button type="submit" class="btn btn-forest text-white px-5 py-3 fw-bold rounded-pill">
                                    Gửi tin nhắn <i class="fa-solid fa-paper-plane ms-2"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</section>
