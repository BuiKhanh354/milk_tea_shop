<?php

class FavoriteController {
    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function index() {
        if (!isset($_SESSION['customer_id'])) {
            header("Location: index.php?route=login");
            exit;
        }

        require_once __DIR__ . '/../../models/Favorite.php';
        $favoriteModel = new Favorite();
        $favorites = $favoriteModel->getCustomerFavorites($_SESSION['customer_id']);

        require_once __DIR__ . '/../../views/customer/favorites/index.php';
    }

    public function toggle() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid request']);
            return;
        }

        header('Content-Type: application/json');

        if (!isset($_SESSION['customer_id'])) {
            echo json_encode(['success' => false, 'message' => 'Vui lòng đăng nhập để thêm vào yêu thích.', 'require_login' => true]);
            return;
        }

        $productId = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
        
        if ($productId <= 0) {
            echo json_encode(['success' => false, 'message' => 'Sản phẩm không hợp lệ']);
            return;
        }

        require_once __DIR__ . '/../../models/Favorite.php';
        $favoriteModel = new Favorite();
        
        $isFavorited = $favoriteModel->toggleFavorite($_SESSION['customer_id'], $productId);
        
        echo json_encode([
            'success' => true,
            'is_favorited' => $isFavorited,
            'message' => $isFavorited ? 'Đã thêm vào yêu thích' : 'Đã bỏ yêu thích'
        ]);
    }
}
