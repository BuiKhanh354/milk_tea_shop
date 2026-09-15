<?php
$favorites = [
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
    ]
];
?>

<div class="account-page bg-cream py-5" style="min-height: calc(100vh - 300px); padding-top: 120px !important;">
    <div class="container">
        <div class="row gy-4">
            <!-- Sidebar -->
            <div class="col-lg-3">
                <?php require_once __DIR__ . '/../../partials/account-sidebar.php'; ?>
            </div>

            <!-- Main Content -->
            <div class="col-lg-9">
                <div class="card border-0 rounded-0 shadow-sm h-100">
                    <div class="card-header bg-white border-bottom border-sage border-opacity-25 p-4">
                        <h4 class="font-serif fw-bold text-forest mb-0">MY FAVORITES</h4>
                    </div>
                    <div class="card-body p-4">
                        
                        <?php if(empty($favorites)): ?>
                            <?php 
                                $empty_icon = 'fa-regular fa-heart';
                                $empty_title = 'NO FAVORITES YET';
                                $empty_message = 'You haven\'t saved any favorites yet.';
                                $empty_btn_text = 'EXPLORE MENU';
                                $empty_btn_link = '/products';
                                require __DIR__ . '/../../partials/empty-state.php'; 
                            ?>
                        <?php else: ?>
                            <div class="row g-4">
                                <?php foreach($favorites as $drink): ?>
                                    <div class="col-md-4 col-sm-6">
                                        <div class="card product-card border-0 rounded-0 bg-transparent h-100">
                                            <div class="position-relative product-img-wrapper overflow-hidden">
                                                <img src="<?= $drink['image'] ?>" class="card-img-top rounded-0 object-fit-cover" height="250" alt="<?= $drink['name'] ?>">
                                                <button class="btn btn-light rounded-circle position-absolute top-0 end-0 m-2 p-2 shadow-sm border-0 favorite-btn transition-fast">
                                                    <i class="fa-solid fa-heart text-danger"></i>
                                                </button>
                                                <div class="product-action-overlay position-absolute bottom-0 start-0 w-100 p-3 bg-white bg-opacity-75 backdrop-blur transition-fast">
                                                    <button class="btn btn-forest w-100 btn-sm">ADD TO CART</button>
                                                </div>
                                            </div>
                                            <div class="card-body px-0 pt-3 pb-0 text-center">
                                                <h6 class="card-title font-serif fw-bold text-dark mb-1"><?= $drink['name'] ?></h6>
                                                <p class="card-text fw-semibold text-caramel"><?= $drink['price'] ?></p>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
