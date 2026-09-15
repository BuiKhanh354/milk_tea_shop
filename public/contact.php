<?php
session_start();
$is_logged_in = isset($_SESSION['customer_id']);
$customer_name = $is_logged_in ? $_SESSION['customer_name'] : '';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liên hệ - VAA THÉ</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Playfair+Display:ital,wght@0,600;0,700;1,600&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .contact-hero {
            background-color: #EFE8DA;
            padding: 80px 0 60px;
            text-align: center;
        }
        .contact-info-card {
            background-color: #fff;
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(24, 35, 29, 0.05);
            height: 100%;
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
        }
        .contact-form {
            background-color: #fff;
            padding: 50px;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(24, 35, 29, 0.05);
        }
        .form-control {
            background-color: #F7F4ED;
            border: none;
            padding: 12px 20px;
            border-radius: 8px;
        }
        .form-control:focus {
            background-color: #fff;
            border: 1px solid #AAB79F;
            box-shadow: 0 0 0 0.25rem rgba(170, 183, 159, 0.25);
        }
    </style>
</head>
<body style="background-color: #F7F4ED; color: #18231D; font-family: 'Inter', sans-serif;">

    <!-- Navbar -->
    <?php include __DIR__ . '/../app/views/partials/navbar_old.php'; ?>

    <!-- Hero Section -->
    <section class="contact-hero mt-5">
        <div class="container">
            <p class="text-uppercase fw-bold mb-2" style="color: #9A7654; letter-spacing: 3px; font-size: 0.85rem;">Get in touch</p>
            <h1 class="fw-bold mb-3" style="font-family: 'Playfair Display', serif; color: #263A30; font-size: 3.5rem;">Liên hệ với chúng tôi</h1>
            <p style="color: #555; max-width: 600px; margin: 0 auto;">VAA THÉ luôn trân trọng mọi ý kiến đóng góp từ bạn. Hãy để lại tin nhắn, chúng tôi sẽ phản hồi trong thời gian sớm nhất.</p>
        </div>
    </section>

    <!-- Contact Content -->
    <section class="py-5">
        <div class="container py-4">
            <div class="row g-5">
                
                <!-- Contact Info -->
                <div class="col-12 col-lg-4">
                    <div class="row g-4">
                        <div class="col-12 col-md-6 col-lg-12">
                            <div class="contact-info-card">
                                <div class="contact-icon"><i class="fa-solid fa-location-dot"></i></div>
                                <h5 class="fw-bold" style="color: #263A30; font-family: 'Playfair Display', serif;">Trụ sở chính</h5>
                                <p class="text-muted mb-0">123 Đường Sách, Phường Bến Nghé, Quận 1, TP. Hồ Chí Minh</p>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-12">
                            <div class="contact-info-card">
                                <div class="contact-icon"><i class="fa-solid fa-phone"></i></div>
                                <h5 class="fw-bold" style="color: #263A30; font-family: 'Playfair Display', serif;">Hotline Hỗ trợ</h5>
                                <p class="text-muted mb-1">CSKH: 1900 1234</p>
                                <p class="text-muted mb-0">Hợp tác: (028) 38 123 456</p>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-12">
                            <div class="contact-info-card">
                                <div class="contact-icon"><i class="fa-solid fa-envelope"></i></div>
                                <h5 class="fw-bold" style="color: #263A30; font-family: 'Playfair Display', serif;">Email</h5>
                                <p class="text-muted mb-1">Hỗ trợ: cskh@vaathe.vn</p>
                                <p class="text-muted mb-0">Công việc: work@vaathe.vn</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact Form -->
                <div class="col-12 col-lg-8">
                    <div class="contact-form">
                        <h4 class="fw-bold mb-4" style="color: #263A30; font-family: 'Playfair Display', serif;">Gửi tin nhắn cho VAA THÉ</h4>
                        
                        <?php if($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
                            <div class="alert alert-success rounded-3 mb-4">
                                <i class="fa-solid fa-check-circle me-2"></i> Cảm ơn bạn! Tin nhắn đã được gửi đi thành công.
                            </div>
                        <?php endif; ?>

                        <form action="contact.php" method="POST">
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <label class="form-label fw-medium">Họ và tên <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="name" required placeholder="Nhập họ tên của bạn">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-medium">Số điện thoại <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="phone" required placeholder="Nhập số điện thoại">
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-medium">Email liên hệ <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control" name="email" required placeholder="example@email.com">
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-medium">Chủ đề cần hỗ trợ</label>
                                    <select class="form-select form-control" name="subject">
                                        <option value="Đóng góp ý kiến">Đóng góp ý kiến dịch vụ</option>
                                        <option value="Hỗ trợ đơn hàng">Hỗ trợ đơn hàng</option>
                                        <option value="Hợp tác kinh doanh">Hợp tác kinh doanh</option>
                                        <option value="Tuyển dụng">Tuyển dụng</option>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-medium">Nội dung chi tiết <span class="text-danger">*</span></label>
                                    <textarea class="form-control" rows="5" name="message" required placeholder="Viết nội dung tin nhắn của bạn ở đây..."></textarea>
                                </div>
                                <div class="col-12 mt-4 text-end">
                                    <button type="submit" class="btn text-white px-5 py-2 fw-medium rounded-pill" style="background-color: #263A30;">Gửi tin nhắn</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Footer -->
    <?php include __DIR__ . '/../app/views/layouts/footer.html'; ?>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.nav-link').forEach(link => {
                if(link.textContent.trim() === 'Liên hệ') {
                    link.classList.add('active');
                }
            });
        });
    </script>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
