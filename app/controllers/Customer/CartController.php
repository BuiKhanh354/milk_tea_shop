<?php

class CartController {
    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
    }

    public function index() {
        $cart_items = $_SESSION['cart'];
        $subtotal = 0;
        foreach($cart_items as $item) {
            $subtotal += $item['total_price'] * $item['quantity'];
        }
        $shipping = 15000;
        $total = $subtotal > 0 ? $subtotal + $shipping : 0;

        require_once __DIR__ . '/../../views/customer/cart/index.php';
    }

    public function add() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            header('Content-Type: application/json');
            
            $product_id = $_POST['product_id'] ?? null;
            $product_name = $_POST['product_name'] ?? '';
            $product_image = $_POST['product_image'] ?? '';
            $base_price = (float)($_POST['base_price'] ?? 0);
            
            $size = $_POST['size'] ?? '';
            $size_price = (float)($_POST['size_price'] ?? 0);
            
            $sugar = $_POST['sugar'] ?? '';
            $ice = $_POST['ice'] ?? '';
            
            $quantity = (int)($_POST['quantity'] ?? 1);
            if ($quantity < 1) $quantity = 1;

            // Toppings: array of JSON strings or arrays
            $toppings = [];
            $toppings_price = 0;
            if (isset($_POST['toppings']) && is_array($_POST['toppings'])) {
                foreach ($_POST['toppings'] as $t) {
                    $toppings[] = $t;
                    $toppings_price += (float)$t['price'];
                }
            }

            $total_price = $base_price + $size_price + $toppings_price;

            // Generate a unique cart item ID based on options
            $topping_ids = array_map(function($t) { return $t['id']; }, $toppings);
            sort($topping_ids);
            $cart_id = md5($product_id . $size . $sugar . $ice . implode(',', $topping_ids));

            if (isset($_SESSION['cart'][$cart_id])) {
                $_SESSION['cart'][$cart_id]['quantity'] += $quantity;
            } else {
                $_SESSION['cart'][$cart_id] = [
                    'cart_id' => $cart_id,
                    'product_id' => $product_id,
                    'name' => $product_name,
                    'image' => $product_image,
                    'size' => $size,
                    'sugar' => $sugar,
                    'ice' => $ice,
                    'toppings' => $toppings,
                    'total_price' => $total_price,
                    'quantity' => $quantity
                ];
            }

            echo json_encode(['success' => true, 'message' => 'Đã thêm vào giỏ hàng', 'cart_count' => $this->getCartCount()]);
            exit;
        }
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            header('Content-Type: application/json');
            $cart_id = $_POST['cart_id'] ?? '';
            $quantity = (int)($_POST['quantity'] ?? 1);

            if ($quantity < 1) $quantity = 1;

            if (isset($_SESSION['cart'][$cart_id])) {
                $_SESSION['cart'][$cart_id]['quantity'] = $quantity;
                echo json_encode(['success' => true]);
                exit;
            }
            echo json_encode(['success' => false, 'message' => 'Sản phẩm không tồn tại trong giỏ hàng']);
            exit;
        }
    }

    public function remove() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            header('Content-Type: application/json');
            $cart_id = $_POST['cart_id'] ?? '';

            if (isset($_SESSION['cart'][$cart_id])) {
                unset($_SESSION['cart'][$cart_id]);
                echo json_encode(['success' => true, 'cart_count' => $this->getCartCount()]);
                exit;
            }
            echo json_encode(['success' => false, 'message' => 'Sản phẩm không tồn tại trong giỏ hàng']);
            exit;
        }
    }

    private function getCartCount() {
        $count = 0;
        foreach ($_SESSION['cart'] as $item) {
            $count += $item['quantity'];
        }
        return $count;
    }
}
