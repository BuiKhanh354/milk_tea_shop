<?php
require_once __DIR__ . '/../config/database.php';

class Cart {
    private $conn;

    public function __construct() {
        global $conn;
        $this->conn = $conn;
    }

    /**
     * Lấy cart_token từ Cookie, nếu chưa có thì tạo mới và set cookie 30 ngày
     */
    public static function getOrCreateCartToken() {
        if (!empty($_COOKIE['cart_token'])) {
            return $_COOKIE['cart_token'];
        }
        $token = bin2hex(random_bytes(16));
        setcookie('cart_token', $token, time() + 30 * 24 * 3600, '/');
        $_COOKIE['cart_token'] = $token;
        return $token;
    }

    /**
     * Nạp giỏ hàng từ Database vào Session (gọi khi vào trang hoặc sau khi login)
     */
    public function loadCartToSession($customerId = null, $cartToken = null) {
        if (!$cartToken) {
            $cartToken = $_COOKIE['cart_token'] ?? null;
        }

        $items = [];
        if ($customerId) {
            $sql = "SELECT * FROM cart_items WHERE customer_id = ? ORDER BY id ASC";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("i", $customerId);
            $stmt->execute();
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                $items[$row['cart_key']] = $this->formatCartRow($row);
            }
        } elseif ($cartToken) {
            $sql = "SELECT * FROM cart_items WHERE cart_token = ? AND (customer_id IS NULL OR customer_id = 0) ORDER BY id ASC";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("s", $cartToken);
            $stmt->execute();
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                $items[$row['cart_key']] = $this->formatCartRow($row);
            }
        }

        $_SESSION['cart'] = $items;
        return $items;
    }

    private function formatCartRow($row) {
        return [
            'cart_id' => $row['cart_key'],
            'product_id' => $row['product_id'],
            'name' => $row['name'],
            'image' => $row['image'],
            'size' => $row['size'],
            'sugar' => $row['sugar'],
            'ice' => $row['ice'],
            'temperature' => $row['temperature'] ?? 'cold',
            'toppings' => !empty($row['toppings']) ? json_decode($row['toppings'], true) : [],
            'total_price' => (float)$row['total_price'],
            'quantity' => (int)$row['quantity']
        ];
    }

    /**
     * Thêm hoặc cộng dồn sản phẩm vào CSDL
     */
    public function addItem($item, $customerId = null, $cartToken = null) {
        if (!$cartToken) {
            $cartToken = self::getOrCreateCartToken();
        }

        $cartKey = $item['cart_id'];
        $toppingsJson = json_encode($item['toppings'] ?? []);
        $temp = $item['temperature'] ?? 'cold';

        // Kiểm tra xem món này đã có trong CSDL chưa
        if ($customerId) {
            $sql = "SELECT id, quantity FROM cart_items WHERE customer_id = ? AND cart_key = ? LIMIT 1";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("is", $customerId, $cartKey);
        } else {
            $sql = "SELECT id, quantity FROM cart_items WHERE cart_token = ? AND (customer_id IS NULL OR customer_id = 0) AND cart_key = ? LIMIT 1";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("ss", $cartToken, $cartKey);
        }
        $stmt->execute();
        $res = $stmt->get_result()->fetch_assoc();

        if ($res) {
            // Đã có -> Cập nhật số lượng
            $newQty = $res['quantity'] + $item['quantity'];
            $updateSql = "UPDATE cart_items SET quantity = ?, total_price = ?, toppings = ?, updated_at = NOW() WHERE id = ?";
            $uStmt = $this->conn->prepare($updateSql);
            $uStmt->bind_param("idsi", $newQty, $item['total_price'], $toppingsJson, $res['id']);
            $uStmt->execute();
        } else {
            // Chưa có -> Thêm mới
            $insertSql = "INSERT INTO cart_items (customer_id, cart_token, cart_key, product_id, name, image, size, sugar, ice, temperature, toppings, total_price, quantity) 
                          VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $iStmt = $this->conn->prepare($insertSql);
            $cid = $customerId ? $customerId : null;
            $iStmt->bind_param("ississsssssdi", $cid, $cartToken, $cartKey, $item['product_id'], $item['name'], $item['image'], $item['size'], $item['sugar'], $item['ice'], $temp, $toppingsJson, $item['total_price'], $item['quantity']);
            $iStmt->execute();
        }
    }

    /**
     * Cập nhật số lượng
     */
    public function updateQuantity($cartKey, $quantity, $customerId = null, $cartToken = null) {
        if (!$cartToken) {
            $cartToken = $_COOKIE['cart_token'] ?? '';
        }

        if ($customerId) {
            $sql = "UPDATE cart_items SET quantity = ?, updated_at = NOW() WHERE customer_id = ? AND cart_key = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("iis", $quantity, $customerId, $cartKey);
        } else {
            $sql = "UPDATE cart_items SET quantity = ?, updated_at = NOW() WHERE cart_token = ? AND (customer_id IS NULL OR customer_id = 0) AND cart_key = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("iss", $quantity, $cartToken, $cartKey);
        }
        return $stmt->execute();
    }

    /**
     * Xóa 1 món khỏi giỏ hàng
     */
    public function removeItem($cartKey, $customerId = null, $cartToken = null) {
        if (!$cartToken) {
            $cartToken = $_COOKIE['cart_token'] ?? '';
        }

        if ($customerId) {
            $sql = "DELETE FROM cart_items WHERE customer_id = ? AND cart_key = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("is", $customerId, $cartKey);
        } else {
            $sql = "DELETE FROM cart_items WHERE cart_token = ? AND (customer_id IS NULL OR customer_id = 0) AND cart_key = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("ss", $cartToken, $cartKey);
        }
        return $stmt->execute();
    }

    /**
     * Xóa toàn bộ giỏ hàng (sau khi đặt hàng thành công)
     */
    public function clearCart($customerId = null, $cartToken = null) {
        if (!$cartToken) {
            $cartToken = $_COOKIE['cart_token'] ?? '';
        }

        if ($customerId) {
            $sql = "DELETE FROM cart_items WHERE customer_id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("i", $customerId);
            $stmt->execute();
        }
        if ($cartToken) {
            $sql = "DELETE FROM cart_items WHERE cart_token = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("s", $cartToken);
            $stmt->execute();
        }
        $_SESSION['cart'] = [];
    }

    /**
     * Gộp giỏ hàng khách vãng lai (cart_token) vào tài khoản khách hàng khi đăng nhập
     */
    public function mergeGuestCartToCustomer($customerId, $cartToken) {
        if (empty($cartToken)) {
            $this->loadCartToSession($customerId, null);
            return;
        }

        // Lấy danh sách món trong giỏ hàng vãng lai
        $sql = "SELECT * FROM cart_items WHERE cart_token = ? AND (customer_id IS NULL OR customer_id = 0)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $cartToken);
        $stmt->execute();
        $guestItems = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

        foreach ($guestItems as $gItem) {
            // Kiểm tra tài khoản này đã có món này trong giỏ chưa
            $checkSql = "SELECT id, quantity FROM cart_items WHERE customer_id = ? AND cart_key = ? LIMIT 1";
            $cStmt = $this->conn->prepare($checkSql);
            $cStmt->bind_param("is", $customerId, $gItem['cart_key']);
            $cStmt->execute();
            $existing = $cStmt->get_result()->fetch_assoc();

            if ($existing) {
                // Đã có -> cộng dồn số lượng và xóa món guest
                $newQty = $existing['quantity'] + $gItem['quantity'];
                $uSql = "UPDATE cart_items SET quantity = ?, updated_at = NOW() WHERE id = ?";
                $uStmt = $this->conn->prepare($uSql);
                $uStmt->bind_param("ii", $newQty, $existing['id']);
                $uStmt->execute();

                // Xóa món guest
                $dSql = "DELETE FROM cart_items WHERE id = ?";
                $dStmt = $this->conn->prepare($dSql);
                $dStmt->bind_param("i", $gItem['id']);
                $dStmt->execute();
            } else {
                // Chưa có -> gán customer_id cho món guest
                $uSql = "UPDATE cart_items SET customer_id = ?, updated_at = NOW() WHERE id = ?";
                $uStmt = $this->conn->prepare($uSql);
                $uStmt->bind_param("ii", $customerId, $gItem['id']);
                $uStmt->execute();
            }
        }

        // Tải lại toàn bộ giỏ hàng của customer vào session
        $this->loadCartToSession($customerId, $cartToken);
    }
}
