<?php

class CheckoutController {
    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function index() {
        if (empty($_SESSION['cart'])) {
            header('Location: index.php?route=cart');
            exit;
        }

        $cart_items = $_SESSION['cart'];
        $subtotal = 0;
        foreach($cart_items as $item) {
            $subtotal += $item['total_price'] * $item['quantity'];
        }
        $shipping = 15000;
        $total = $subtotal + $shipping;

        $customer = null;
        if (isset($_SESSION['customer_id'])) {
            require_once __DIR__ . '/../../models/Customer.php';
            $customerModel = new Customer();
            $customer = $customerModel->getById($_SESSION['customer_id']);
        }

        require_once __DIR__ . '/../../views/customer/checkout/index.php';
    }

    public function process() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (empty($_SESSION['cart'])) {
                header('Location: index.php?route=cart');
                exit;
            }

            require_once __DIR__ . '/../../models/Customer.php';
            require_once __DIR__ . '/../../models/Order.php';

            $name = $_POST['name'] ?? '';
            $phone = $_POST['phone'] ?? '';
            $address = $_POST['address'] ?? '';
            $note = $_POST['note'] ?? '';
            $paymentMethod = $_POST['payment_method'] ?? 'cash';

            $customerModel = new Customer();
            $customerId = $_SESSION['customer_id'] ?? null;
            if (!$customerId) {
                $customerId = $customerModel->createGuestCustomer($name, $phone, $address);
            }

            $cart_items = $_SESSION['cart'];
            $subtotal = 0;
            foreach($cart_items as $item) {
                $subtotal += $item['total_price'] * $item['quantity'];
            }
            $shipping = 15000;
            $totalAmount = $subtotal + $shipping;

            $orderModel = new Order();
            $orderId = $orderModel->createCustomerOrder($customerId, $totalAmount, $paymentMethod, $cart_items, $note);

            if ($orderId) {
                // Clear cart
                $_SESSION['cart'] = [];
                // Redirect to success page or orders page
                header('Location: index.php?route=checkout&action=success');
                exit;
            } else {
                echo "Có lỗi xảy ra khi tạo đơn hàng. Vui lòng thử lại.";
            }
        }
    }

    public function success() {
        require_once __DIR__ . '/../../views/customer/checkout/success.php';
    }
}
