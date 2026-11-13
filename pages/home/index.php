<!-- HERO SECTION -->
<section class="hero-section home-hero bg-cream fade-up">
    <div class="container px-4 px-lg-5">
        <div class="row align-items-center hero-row">
            <div class="col-lg-5 text-center text-lg-start z-1">
                <span class="small-label mb-3 d-inline-block">Premium Milk Tea</span>
                <h1 class="mb-4">Hương vị tinh tế<br>từ những lá trà<br>thượng hạng</h1>
                <p class="mb-5 text-muted hero-desc">
                    Mỗi ly trà sữa là sự kết hợp hoàn hảo giữa hương vị truyền thống và sự sáng tạo hiện đại, mang đến trải nghiệm tinh tế cho mọi giác quan.
                </p>
                <a href="<?= e(url('products')) ?>" class="btn-vaa btn-caramel">Khám phá menu <i class="fa-solid fa-arrow-right ms-2"></i></a>
            </div>
            
            <div class="col-lg-7 position-relative mt-5 mt-lg-0">
                <div class="hero-image-wrapper shadow-lg">
                    <img src="https://scontent.fsgn5-10.fna.fbcdn.net/v/t39.99422-6/719161390_1559162298984184_1470607750049226317_n.png?stp=dst-jpg_tt6&cstp=mx1152x1440&ctp=s1152x1440&_nc_cat=111&_nc_map=urlgen_bucketless&ccb=1-7&_nc_sid=833d8c&_nc_ohc=axGAVhiL7u8Q7kNvwEpiBmE&_nc_oc=Ado4PsedEJ2_hHrj4Vsw0zR9bNjAOFI_n67rsxKgCqzobCNhc8AH941UllMLn-r1EXU-9oW-ik4cAvVuhGO2LqCK&_nc_zt=14&_nc_ht=scontent.fsgn5-10.fna&_nc_gid=NfHVWTR9aJKzhhf3s9I1wg&_nc_ss=7b2a8&oh=00_AQOg0_oK5evh24ZKtOFdDi9-f6bWE86erhC3IlNUxfAPHw&oe=6AD7C82C" alt="Premium Milk Tea" class="img-fluid">
                </div>
                <div class="position-absolute d-none d-lg-block hero-floating-text">
                    <span class="font-serif fs-3 text-caramel fw-bold fst-italic">Good Tea<br>Good Mood</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- BRAND STORY -->
<section class="section-padding bg-ivory fade-up">
    <div class="container px-4 px-lg-5">
        <div class="row align-items-center mb-5">
            <div class="col-lg-5 mb-4 mb-lg-0">
                <span class="small-label mb-2 d-inline-block">Our Story</span>
                <h2 class="mb-4">Tinh hoa trà<br>trong từng khoảnh khắc</h2>
                <p class="text-muted">Chúng tôi tin rằng, một ly trà ngon không chỉ đến từ nguyên liệu, mà còn từ sự tận tâm và tỉ mỉ trong từng công đoạn pha chế. VAA THÉ ra đời với sứ mệnh mang đến những trải nghiệm thưởng trà thanh tao và khác biệt.</p>
            </div>
            <div class="col-lg-6 offset-lg-1">
                <div class="rounded-4 overflow-hidden shadow-sm" style="height: 350px;">
                    <img src="https://taiwan-scene.com/app/uploads/2019/07/yilan-taiwanese-tea-tour-tea-1.jpg" alt="Tea Leaves" class="w-100 h-100 object-fit-cover">
                </div>
            </div>
        </div>
        
        <div class="row g-4 mt-4">
            <div class="col-md-3 col-6 text-center text-md-start">
                <h5 class="font-serif text-caramel fs-2 mb-2">01</h5>
                <h6 class="fw-bold mb-2">Tuyển chọn nguyên liệu</h6>
                <p class="text-muted small">Lá trà non được thu hái thủ công từ những đồi trà sương mù.</p>
            </div>
            <div class="col-md-3 col-6 text-center text-md-start">
                <h5 class="font-serif text-caramel fs-2 mb-2">02</h5>
                <h6 class="fw-bold mb-2">Công thức độc quyền</h6>
                <p class="text-muted small">Sự pha trộn tinh tế tạo nên hương vị đặc trưng không thể nhầm lẫn.</p>
            </div>
            <div class="col-md-3 col-6 text-center text-md-start">
                <h5 class="font-serif text-caramel fs-2 mb-2">03</h5>
                <h6 class="fw-bold mb-2">The Shaking Art</h6>
                <p class="text-muted small">Kỹ thuật pha chế đánh thức trọn vẹn hương thơm của trà.</p>
            </div>
            <div class="col-md-3 col-6 text-center text-md-start">
                <h5 class="font-serif text-caramel fs-2 mb-2">04</h5>
                <h6 class="fw-bold mb-2">Hạt trân châu thượng hạng</h6>
                <p class="text-muted small">Độ dẻo dai hoàn hảo, quyện cùng vị ngọt thanh tao.</p>
            </div>
        </div>
    </div>
</section>

<!-- BEST SELLERS -->
<section class="section-padding bg-ivory fade-up" id="products">
    <div class="container px-4 px-lg-5">
        <div class="d-flex justify-content-between align-items-end mb-5">
            <div>
                <span class="small-label mb-2 d-inline-block">Best Sellers</span>
                <h2>Sản phẩm<br>được yêu thích</h2>
            </div>
            <div class="d-none d-md-block">
                <a href="<?= e(url('products')) ?>" class="link-caramel">Xem tất cả <i class="fa-solid fa-arrow-right ms-1"></i></a>
            </div>
        </div>
        
        <div class="row g-4">
            <?php foreach ($bestSellers as $prod): ?>
            <div class="col-md-6 col-lg-3">
                <div class="product-card h-100 d-flex flex-column border-0 shadow-sm">
                    <div class="product-image-box position-relative">
                        <a href="<?= e(url('product-detail', ['id' => $prod['id']])) ?>">
                            <img src="<?= e(product_image($prod['image'])) ?>" alt="<?= e($prod['name']) ?>" class="img-fluid drop-shadow">
                        </a>
                    </div>
                    <div class="p-4 bg-white d-flex flex-column flex-grow-1 text-center">
                        <h6 class="font-serif fw-bold fs-5 mb-1"><?= e($prod['name']) ?></h6>
                        <p class="text-caramel fw-bold mb-3 mt-auto fs-5"><?= money($prod['price']) ?></p>
                        <a href="<?= e(url('product-detail', ['id' => $prod['id']])) ?>" class="btn btn-outline-forest w-100 rounded-pill py-2 fw-semibold">
                            <i class="fa-solid fa-arrow-right me-2"></i> Chi tiết
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

