<?php
// Mật khẩu logic UI
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
                        <h4 class="font-serif fw-bold text-forest mb-0">CHANGE PASSWORD</h4>
                    </div>
                    <div class="card-body p-4 p-lg-5">
                        
                        <form class="account-form" style="max-width: 600px;">
                            <div class="row g-4">
                                <div class="col-12">
                                    <label class="form-label">Current Password</label>
                                    <div class="input-group">
                                        <input type="password" class="form-control border-end-0" placeholder="Enter current password">
                                        <button class="btn border border-start-0 bg-white shadow-none text-muted" type="button">
                                            <i class="fa-regular fa-eye"></i>
                                        </button>
                                    </div>
                                </div>
                                
                                <div class="col-12">
                                    <label class="form-label">New Password</label>
                                    <div class="input-group">
                                        <input type="password" class="form-control border-end-0" placeholder="Enter new password">
                                        <button class="btn border border-start-0 bg-white shadow-none text-muted" type="button">
                                            <i class="fa-regular fa-eye"></i>
                                        </button>
                                    </div>
                                    <div class="progress mt-2" style="height: 4px;">
                                        <div class="progress-bar bg-sage" role="progressbar" style="width: 25%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <div class="form-text small mt-1">Password must be at least 8 characters long.</div>
                                </div>

                                <div class="col-12">
                                    <label class="form-label">Confirm New Password</label>
                                    <div class="input-group">
                                        <input type="password" class="form-control border-end-0" placeholder="Confirm new password">
                                        <button class="btn border border-start-0 bg-white shadow-none text-muted" type="button">
                                            <i class="fa-regular fa-eye"></i>
                                        </button>
                                    </div>
                                </div>
                                
                                <div class="col-12 mt-5">
                                    <button type="button" class="btn btn-forest px-5">CHANGE PASSWORD</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
