<?php
// Tạm định nghĩa mảng sản phẩm signature (Mock Data)
$signature_drinks = [
    [
        'id' => 1,
        'name' => 'Oolong Sữa Hạnh Nhân',
        'desc' => 'Vị trà Oolong đậm đà quyện cùng sữa hạnh nhân béo ngậy.',
        'price' => '55.000đ',
        'image' => 'https://images.unsplash.com/photo-1576092762791-dd9e2220abd4?auto=format&fit=crop&q=80&w=800'
    ],
    [
        'id' => 2,
        'name' => 'Trà Lài Trái Cây Nhiệt Đới',
        'desc' => 'Thanh mát với trà lài ủ lạnh và trái cây tươi theo mùa.',
        'price' => '60.000đ',
        'image' => 'https://images.unsplash.com/photo-1625937712144-0c6ef5044f51?auto=format&fit=crop&q=80&w=800'
    ],
    [
        'id' => 3,
        'name' => 'Matcha Latte Sữa Đậu Nành',
        'desc' => 'Bột matcha thượng hạng Uji kết hợp sữa đậu nành thanh nhẹ.',
        'price' => '65.000đ',
        'image' => 'https://images.unsplash.com/photo-1515823662972-da6a2e4d3002?auto=format&fit=crop&q=80&w=800'
    ],
    [
        'id' => 4,
        'name' => 'Hồng Trà Kem Phô Mai',
        'desc' => 'Hồng trà cổ điển phủ lớp kem phô mai mặn ngọt đặc trưng.',
        'price' => '50.000đ',
        'image' => 'https://images.unsplash.com/photo-1558160074-4d7d8bdf4256?auto=format&fit=crop&q=80&w=800'
    ]
];
?>

<!-- HERO SECTION -->
<section class="home-hero position-relative d-flex align-items-center">
    <div class="hero-bg position-absolute top-0 start-0 w-100 h-100">
        <img src="https://images.unsplash.com/photo-1563514972413-09559c381c10?auto=format&fit=crop&q=80&w=1920" alt="Tea Background" class="w-100 h-100 object-fit-cover">
        <div class="overlay position-absolute top-0 start-0 w-100 h-100 bg-dark opacity-50"></div>
    </div>
    
    <div class="container position-relative z-1">
        <div class="row">
            <div class="col-lg-8 col-md-10 text-ivory">
                <p class="font-serif fst-italic fs-4 mb-2" data-aos="fade-up">Good Tea, Good Mood</p>
                <h1 class="display-3 font-serif fw-bold mb-4 text-ivory" data-aos="fade-up" data-aos-delay="100">
                    Find Your <br>Tea Moment
                </h1>
                <p class="lead fw-light mb-5 w-75" data-aos="fade-up" data-aos-delay="200">
                    Đánh thức mọi giác quan với những lá trà thượng hạng được tuyển chọn khắt khe, pha chế thủ công bằng cả tâm huyết tại VAA THÉ.
                </p>
                <div class="d-flex gap-3" data-aos="fade-up" data-aos-delay="300">
                    <a href="<?= BASE_URL ?>/products" class="btn btn-sage">EXPLORE MENU</a>
                    <a href="<?= BASE_URL ?>/products" class="btn btn-outline-light rounded-0 px-4 py-2 fw-medium border-2">ORDER NOW</a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- BRAND STORY -->
<section class="py-5 my-5">
    <div class="container">
        <div class="row align-items-center justify-content-between">
            <div class="col-lg-5 mb-5 mb-lg-0">
                <img src="https://images.unsplash.com/photo-1594631252845-29fc4cc8c011?auto=format&fit=crop&q=80&w=800" alt="The Art of Tea" class="img-fluid rounded-1 shadow-sm">
            </div>
            <div class="col-lg-6">
                <span class="text-sage fw-medium tracking-wide small text-uppercase">Our Story</span>
                <h2 class="display-5 font-serif mt-3 mb-4">The Art of Tea</h2>
                <p class="text-muted fs-5 mb-4">
                    Tại VAA THÉ, chúng tôi tin rằng mỗi tách trà không chỉ là một thức uống, mà là một trải nghiệm nghệ thuật. Từ những búp trà non được hái vào sương sớm, qua quá trình sao sấy thủ công, đến cách kết hợp tinh tế cùng các nguyên liệu tự nhiên.
                </p>
                <a href="<?= BASE_URL ?>/about" class="text-forest fw-bold text-decoration-none border-bottom border-forest pb-1 hover-caramel transition-fast">
                    DISCOVER OUR STORY <i class="fa-solid fa-arrow-right ms-2 small"></i>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- SIGNATURE DRINKS -->
<section class="py-5 bg-cream">
    <div class="container py-4">
        <div class="text-center mb-5">
            <h2 class="display-5 font-serif mb-3">Signature Favorites</h2>
            <p class="text-muted w-lg-50 mx-auto">Những tuyệt tác trà sữa được yêu thích nhất, làm nên tên tuổi của VAA THÉ.</p>
        </div>

        <div class="row g-4">
            <?php foreach($signature_drinks as $drink): ?>
                <!-- Product Card Partial (Inline for now, can be extracted later) -->
                <div class="col-lg-3 col-md-6">
                    <div class="card product-card border-0 rounded-0 bg-transparent h-100">
                        <div class="position-relative product-img-wrapper overflow-hidden">
                            <img src="<?= $drink['image'] ?>" class="card-img-top rounded-0 object-fit-cover" height="300" alt="<?= $drink['name'] ?>">
                            <button class="btn btn-light rounded-circle position-absolute top-0 end-0 m-3 p-2 shadow-sm border-0 favorite-btn transition-fast">
                                <i class="fa-regular fa-heart text-forest"></i>
                            </button>
                            <div class="product-action-overlay position-absolute bottom-0 start-0 w-100 p-3 bg-white bg-opacity-75 backdrop-blur transition-fast">
                                <button class="btn btn-forest w-100 btn-sm">ADD TO CART</button>
                            </div>
                        </div>
                        <div class="card-body px-0 pt-3 pb-0 text-center">
                            <h5 class="card-title font-serif fw-bold text-dark fs-5 mb-1"><?= $drink['name'] ?></h5>
                            <p class="card-text text-muted small mb-2 text-truncate px-2"><?= $drink['desc'] ?></p>
                            <p class="card-text fw-semibold text-caramel fs-5"><?= $drink['price'] ?></p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
        <div class="text-center mt-5">
            <a href="<?= BASE_URL ?>/products" class="btn btn-outline-forest">VIEW FULL MENU</a>
        </div>
    </div>
</section>

<!-- TEA EXPERIENCE (STORYTELLING) -->
<section class="py-5 my-5">
    <div class="container text-center mb-5">
        <h2 class="display-5 font-serif mb-3">The Experience</h2>
    </div>
    <div class="container">
        <div class="row g-0 align-items-center mb-5">
            <div class="col-md-6 p-4 p-lg-5 text-end">
                <h3 class="font-serif mb-3 text-forest">1. Tuyển Trà</h3>
                <p class="text-muted">Chỉ những búp trà 1 tôm 2 lá tươi ngon nhất từ các đồn điền cao nguyên mới được chọn lọc để bắt đầu hành trình của mình.</p>
            </div>
            <div class="col-md-6">
                <img src="https://images.unsplash.com/photo-1594315590298-329f49c8dcb9?auto=format&fit=crop&q=80&w=800" class="img-fluid rounded-1" alt="Tea">
            </div>
        </div>
        <div class="row g-0 align-items-center mb-5 flex-md-row-reverse">
            <div class="col-md-6 p-4 p-lg-5 text-start">
                <h3 class="font-serif mb-3 text-forest">2. Phối Trộn</h3>
                <p class="text-muted">Kỹ thuật shake điêu luyện kết hợp với nhiệt độ hoàn hảo giúp đánh thức mọi tầng hương ẩn giấu trong lá trà.</p>
            </div>
            <div class="col-md-6">
                <img src="https://images.unsplash.com/photo-1556679343-c7306c1976bc?auto=format&fit=crop&q=80&w=800" class="img-fluid rounded-1" alt="Shake">
            </div>
        </div>
    </div>
</section>

<!-- PROMOTION BANNER -->
<section class="py-5 bg-forest text-ivory text-center">
    <div class="container py-5">
        <span class="text-sage fw-medium tracking-wide small text-uppercase mb-3 d-block">Special Offer</span>
        <h2 class="display-4 font-serif mb-4 text-cream">Mùa Thu, Mùa Trà Mới</h2>
        <p class="lead fw-light mb-4 w-75 mx-auto text-cream">Tận hưởng ưu đãi 20% cho toàn bộ dòng Trà Sữa Oolong Hạnh Nhân khi đặt hàng qua website từ nay đến cuối tháng.</p>
        <a href="<?= BASE_URL ?>/promotions" class="btn btn-sage">VIEW PROMOTIONS</a>
    </div>
</section>

<!-- CTA -->
<section class="py-5 text-center my-4">
    <div class="container py-5">
        <h2 class="display-5 font-serif mb-4">Your perfect tea is waiting.</h2>
        <a href="<?= BASE_URL ?>/products" class="btn btn-forest px-5 py-3 fs-5">ORDER NOW</a>
    </div>
</section>
