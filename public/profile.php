<?php
session_start();

if (!isset($_SESSION['customer_id'])) {
    header("Location: login.php");
    exit;
}

require_once __DIR__ . '/../app/config/database.php';

$customer_id = $_SESSION['customer_id'];
$success_msg = "";
$error_msg = "";

// Xử lý cập nhật thông tin
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $address = trim($_POST['address'] ?? '');

    if (empty($name)) {
        $error_msg = "Vui lòng nhập họ và tên.";
    } else {
        $sql = "UPDATE customers SET full_name = ?, phone = ?, address = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("sssi", $name, $phone, $address, $customer_id);
            if ($stmt->execute()) {
                $success_msg = "Cập nhật thông tin thành công!";
                // Cập nhật lại session name
                $_SESSION['customer_name'] = $name;
            } else {
                $error_msg = "Lỗi khi cập nhật: " . $conn->error;
            }
            $stmt->close();
        } else {
        }
    }
}

// Lấy thông tin hiện tại
$customer = [];
$sql = "SELECT full_name, email, phone, address, created_at FROM customers WHERE id = ?";
$stmt = $conn->prepare($sql);
if ($stmt) {
    $stmt->bind_param("i", $customer_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $customer = $row;
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông tin cá nhân - VAA THÉ</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .form-control {
            border: none;
            border-bottom: 1px solid var(--vaa-sage);
            border-radius: 0;
            padding: 0.75rem 0;
            font-family: var(--font-sans);
            color: var(--vaa-forest);
            background-color: transparent;
        }

        .form-control:focus {
            box-shadow: none;
            border-color: var(--vaa-caramel);
            background-color: transparent;
        }

        .form-label {
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: var(--vaa-caramel);
            font-weight: 600;
        }

        .avatar-circle {
            width: 80px;
            height: 80px;
            background-color: var(--vaa-cream);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: var(--font-serif);
            font-size: 2.5rem;
            color: var(--vaa-forest);
        }
    </style>
</head>

<body class="bg-ivory">

    <!-- Header tối giản cho vùng Account -->
    <?php include '../app/views/partials/account-header.php'; ?>

    <main class="container py-5" style="min-height: calc(100vh - 70px);">
        <div class="row g-4">
            <!-- Sidebar (Cột Trái) -->
            <div class="col-lg-3">
                <?php include '../app/views/partials/account-sidebar.php'; ?>
            </div>

            <!-- Content (Cột Phải) -->
            <div class="col-lg-9">
                <div class="bg-white p-4 p-md-5 border border-sage border-opacity-25 shadow-sm h-100 fade-up visible">

                    <div class="d-flex align-items-center gap-4 mb-5 pb-4 border-bottom border-light">
                        <div class="avatar-circle">
                            <?= mb_strtoupper(mb_substr($customer['full_name'] ?? 'K', 0, 1)) ?>
                        </div>
                        <div>
                            <span class="small-label d-block mb-1">THÔNG TIN CÁ NHÂN</span>
                            <h3 class="font-serif fw-bold text-forest mb-1">Xin chào, <?= htmlspecialchars($customer['full_name'] ?? '') ?></h3>
                            <p class="text-muted mb-0">Quản lý thông tin tài khoản của bạn</p>
                        </div>
                    </div>

                    <?php if (!empty($success_msg)): ?>
                        <div class="alert alert-success border-0 rounded-0 bg-sage bg-opacity-25 text-forest">
                            <i class="fa-solid fa-check-circle me-2"></i> <?= htmlspecialchars($success_msg) ?>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($error_msg)): ?>
                        <div class="alert alert-danger border-0 rounded-0">
                            <i class="fa-solid fa-triangle-exclamation me-2"></i> <?= htmlspecialchars($error_msg) ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="profile.php">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="form-label">Họ và Tên</label>
                                <input type="text" class="form-control" name="name" value="<?= htmlspecialchars($customer['full_name'] ?? '') ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Số điện thoại</label>
                                <input type="tel" class="form-control" name="phone" placeholder="Chưa cập nhật" value="<?= htmlspecialchars($customer['phone'] ?? '') ?>">
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control text-muted" value="<?= htmlspecialchars($customer['email'] ?? '') ?>" readonly disabled>
                                <small class="text-muted d-block mt-1">Email không thể thay đổi.</small>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">Địa chỉ giao hàng mặc định</label>
                                <input type="text" class="form-control" name="address" placeholder="Ví dụ: 123 Đường ABC, Quận 1, TP.HCM" value="<?= htmlspecialchars($customer['address'] ?? '') ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Ngày tham gia</label>
                                <?php
                                $join_date = !empty($customer['created_at']) ? date('d/m/Y', strtotime($customer['created_at'])) : 'Không xác định';
                                ?>
                                <input type="text" class="form-control text-muted" value="<?= $join_date ?>" readonly disabled>
                            </div>

                            <div class="col-12 mt-5">
                                <button type="submit" class="btn btn-caramel px-5 py-2 fw-bold tracking-wide">LƯU THAY ĐỔI</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/main.js"></script>
</body>

</html>