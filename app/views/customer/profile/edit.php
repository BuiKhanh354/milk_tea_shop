<?php
$customer_name = $_SESSION['customer_name'] ?? 'Khách hàng';
$customer_email = $_SESSION['customer_email'] ?? '';
$customer = [
    'name' => $customer_name,
    'email' => $customer_email,
    'phone' => '',
    'address' => '',
    'avatar' => strtoupper(substr($customer_name, 0, 1))
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
                <div class="card border-0 rounded-0 shadow-sm">
                    <div class="card-header bg-white border-bottom border-sage border-opacity-25 p-4">
                        <h4 class="font-serif fw-bold text-forest mb-0">EDIT PROFILE</h4>
                    </div>
                    <div class="card-body p-4 p-lg-5">
                        
                        <div class="d-flex align-items-center mb-5 pb-4 border-bottom border-sage border-opacity-25">
                            <div class="avatar-wrapper me-4">
                                <?= $customer['avatar'] ?>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-2">Profile Photo</h6>
                                <div class="d-flex gap-2">
                                    <button class="btn btn-sm btn-outline-forest">CHANGE</button>
                                    <button class="btn btn-sm text-danger hover-sage transition-fast">REMOVE</button>
                                </div>
                            </div>
                        </div>

                        <form class="account-form">
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <label class="form-label">Full Name</label>
                                    <input type="text" class="form-control" value="<?= $customer['name'] ?>">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Phone Number</label>
                                    <input type="tel" class="form-control" value="<?= $customer['phone'] ?>">
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label">Email Address</label>
                                    <input type="email" class="form-control bg-light" value="<?= $customer['email'] ?>" readonly>
                                    <div class="form-text small mt-1"><i class="fa-solid fa-circle-info me-1 text-sage"></i> Email cannot be changed.</div>
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label">Delivery Address</label>
                                    <input type="text" class="form-control" value="<?= $customer['address'] ?>">
                                </div>
                                
                                <div class="col-12 mt-5 d-flex gap-3">
                                    <button type="button" class="btn btn-forest px-5">SAVE CHANGES</button>
                                    <a href="<?= BASE_URL ?>/profile" class="btn btn-outline-forest px-4">CANCEL</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
