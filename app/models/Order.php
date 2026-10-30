<?php
require_once __DIR__ . '/../config/database.php';

class Order {
    private $conn;

    public function __construct() {
        global $conn;
        $this->conn = $conn;
    }

            public function getPaginated($page = 1, $limit = 10, $filters = []) {
        $offset = ($page - 1) * $limit;
        
        $conditions = [];
        $params = [];
        $types = "";
        
        if (!empty($filters['search'])) {
            $conditions[] = "(o.id LIKE ? OR c.full_name LIKE ? OR u.full_name LIKE ?)";
            $searchTerm = "%" . ltrim($filters['search'], '#ORD0') . "%"; 
            $params[] = $searchTerm;
            $params[] = "%" . $filters['search'] . "%";
            $params[] = "%" . $filters['search'] . "%";
            $types .= "sss";
        }
        
        if (!empty($filters['status'])) {
            $conditions[] = "o.status = ?";
            $params[] = $filters['status'];
            $types .= "s";
        }
        
        $whereClause = !empty($conditions) ? "WHERE " . implode(" AND ", $conditions) : "";
        
        $countSql = "SELECT COUNT(*) as total FROM orders o 
                     LEFT JOIN customers c ON o.customer_id = c.id
                     LEFT JOIN users u ON o.user_id = u.id 
                     $whereClause";
                     
        if (!empty($params)) {
            $cStmt = $this->conn->prepare($countSql);
            $cStmt->bind_param($types, ...$params);
            $cStmt->execute();
            $total = $cStmt->get_result()->fetch_assoc()['total'] ?? 0;
        } else {
            $total = $this->conn->query($countSql)->fetch_assoc()['total'] ?? 0;
        }
        
        $sql = "SELECT o.*, 
                       COALESCE(c.full_name, u.full_name, 'Khách vãng lai') as customer_name,
                       COALESCE(p.method, 'Tiền mặt') as payment_method
                FROM orders o 
                LEFT JOIN customers c ON o.customer_id = c.id
                LEFT JOIN users u ON o.user_id = u.id
                LEFT JOIN payments p ON o.id = p.order_id
                $whereClause
                ORDER BY o.created_at DESC
                LIMIT ? OFFSET ?";
                
        $stmt = $this->conn->prepare($sql);
        $allParams = array_merge($params, [$limit, $offset]);
        $allTypes = $types . "ii";
        $stmt->bind_param($allTypes, ...$allParams);
        $stmt->execute();
        $orders = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        
        return [
            'data' => $orders,
            'total' => $total,
            'total_pages' => ceil($total / $limit),
            'current_page' => $page
        ];
    }

    public function getAll() {
        $sql = "SELECT o.*, 
                       COALESCE(c.full_name, u.full_name, 'Khách vãng lai') as customer_name,
                       COALESCE(p.method, 'Tiền mặt') as payment_method
                FROM orders o 
                LEFT JOIN customers c ON o.customer_id = c.id
                LEFT JOIN users u ON o.user_id = u.id
                LEFT JOIN payments p ON o.id = p.order_id
                ORDER BY o.created_at DESC";
        $result = $this->conn->query($sql);
        if (!$result) return [];
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getRecent($limit = 5) {
        $sql = "SELECT o.*, 
                       COALESCE(c.full_name, u.full_name, 'Khách vãng lai') as customer_name,
                       COALESCE(p.method, 'Tiền mặt') as payment_method
                FROM orders o 
                LEFT JOIN customers c ON o.customer_id = c.id
                LEFT JOIN users u ON o.user_id = u.id
                LEFT JOIN payments p ON o.id = p.order_id
                ORDER BY o.created_at DESC LIMIT ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $limit);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getStats() {
        $stats = [
            'total_revenue' => 0,
            'today_orders' => 0,
            'revenue_growth' => '+0%',
            'order_growth' => '+0%'
        ];

        // Lấy doanh thu ngày hôm nay (chỉ tính đơn hoàn thành)
        $sqlRev = "SELECT SUM(final_amount) as total FROM orders WHERE status = 'completed' AND DATE(created_at) = CURDATE()";
        $resRev = $this->conn->query($sqlRev);
        if ($resRev) {
            $row = $resRev->fetch_assoc();
            $stats['total_revenue'] = $row['total'] ? (float)$row['total'] : 0;
        }

        // Đếm đơn hôm nay (tất cả trạng thái)
        $sqlOrd = "SELECT COUNT(id) as total FROM orders WHERE DATE(created_at) = CURDATE()";
        $resOrd = $this->conn->query($sqlOrd);
        if ($resOrd) {
            $row = $resOrd->fetch_assoc();
            $stats['today_orders'] = $row['total'] ? (int)$row['total'] : 0;
        }

        return $stats;
    }

    public function getCustomerOrders($customerId) {
        $sql = "SELECT o.id, o.created_at as date, o.final_amount as total, o.status 
                FROM orders o 
                WHERE o.customer_id = ? 
                ORDER BY o.created_at DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $customerId);
        $stmt->execute();
        $orders = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

        // Fetch items for each order to build the summary string
        $sqlDetails = "SELECT od.order_id, p.name, od.quantity 
                       FROM order_details od
                       JOIN products p ON od.product_id = p.id
                       WHERE od.order_id = ?";
        $stmtDetails = $this->conn->prepare($sqlDetails);
        
        // bind_param requires variable by reference
        $currentOrderId = 0;
        $stmtDetails->bind_param("i", $currentOrderId);

        foreach ($orders as &$order) {
            $currentOrderId = $order['id'];
            $stmtDetails->execute();
            $items = $stmtDetails->get_result()->fetch_all(MYSQLI_ASSOC);
            
            $itemStrings = [];
            foreach ($items as $item) {
                $itemStrings[] = $item['name'] . ' (x' . $item['quantity'] . ')';
            }
            $order['items'] = implode(', ', $itemStrings);
            // Format ID
            $order['raw_id'] = $order['id'];
            $order['id'] = 'VT' . str_pad($order['id'], 3, '0', STR_PAD_LEFT);
            // Format Date
            $order['date'] = date('d/m/Y', strtotime($order['date']));
            // Format Total
            $order['total'] = number_format($order['total'], 0, ',', '.') . 'đ';
            
            // Map statuses if necessary
            $statusMap = [
                'pending' => 'Chờ xác nhận',
                'Pending' => 'Chờ xác nhận',
                'processing' => 'Đang chuẩn bị',
                'Processing' => 'Đang chuẩn bị',
                'completed' => 'Hoàn thành',
                'Completed' => 'Hoàn thành',
                'cancelled' => 'Đã huỷ',
                'Cancelled' => 'Đã huỷ'
            ];
            $order['status'] = $statusMap[$order['status']] ?? $order['status'];
        }

        return $orders;
    }

    public function findByIdWithDetails($id) {
        $sql = "SELECT o.*, 
                       c.full_name as customer_name, c.phone as customer_phone, c.address as customer_address,
                       u.full_name as user_name, u.phone as user_phone,
                       p.method as payment_method
                FROM orders o 
                LEFT JOIN customers c ON o.customer_id = c.id
                LEFT JOIN users u ON o.user_id = u.id
                LEFT JOIN payments p ON o.id = p.order_id
                WHERE o.id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $order = $stmt->get_result()->fetch_assoc();

        if (!$order) return null;

        // Fetch details
        $sqlDetails = "SELECT od.*, p.name as product_name, p.image as product_image, s.name as size_name
                       FROM order_details od
                       JOIN products p ON od.product_id = p.id
                       LEFT JOIN sizes s ON od.size_id = s.id
                       WHERE od.order_id = ?";
        $stmtD = $this->conn->prepare($sqlDetails);
        $stmtD->bind_param("i", $id);
        $stmtD->execute();
        $items = $stmtD->get_result()->fetch_all(MYSQLI_ASSOC);

        // Fetch toppings for each item
        $sqlToppings = "SELECT t.name 
                        FROM order_detail_toppings odt 
                        JOIN toppings t ON odt.topping_id = t.id 
                        WHERE odt.order_detail_id = ?";
        $stmtT = $this->conn->prepare($sqlToppings);
        
        foreach ($items as &$item) {
            $stmtT->bind_param("i", $item['id']);
            $stmtT->execute();
            $toppingRows = $stmtT->get_result()->fetch_all(MYSQLI_ASSOC);
            // the previous code in show.php expects 'toppings' as string if it was using string. Let's check show.php.
            // Oh, previously 'toppings' was a comma separated string.
            $item['toppings'] = implode(', ', array_column($toppingRows, 'name'));
        }
        
        $order['items'] = $items;

        return $order;
    }

    public function updateStatus($id, $status) {
        $validStatuses = ['pending', 'confirmed', 'preparing', 'ready', 'completed', 'cancelled'];
        if (!in_array($status, $validStatuses)) return false;

        $sql = "UPDATE orders SET status = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("si", $status, $id);
        return $stmt->execute();
    }

    public function assignShiftSession($id, $shiftSessionId) {
        $sql = "UPDATE orders SET shift_session_id = ? WHERE id = ? AND shift_session_id IS NULL";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $shiftSessionId, $id);
        return $stmt->execute();
    }

    public function markPaymentPaid($orderId) {
        $sql = "UPDATE payments SET status = 'paid', paid_at = NOW() WHERE order_id = ? AND status = 'pending'";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $orderId);
        return $stmt->execute();
    }

    public function createOrder($userId, $customerName, $totalAmount, $orderType, $tableId, $paymentMethod, $items, $shiftSessionId = null, $status = 'Pending') {
        $this->conn->begin_transaction();
        try {
            // Insert order
            $sql = "INSERT INTO orders (user_id, status, total_amount, final_amount, order_type, table_id, shift_session_id) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->conn->prepare($sql);
            $tId = ($tableId > 0) ? (int)$tableId : null;
            $sId = ($shiftSessionId > 0) ? (int)$shiftSessionId : null;
            $stmt->bind_param("isddsii", $userId, $status, $totalAmount, $totalAmount, $orderType, $tId, $sId);
            $stmt->execute();
            $orderId = $stmt->insert_id;

            // Payment status
            $paymentStatus = (strtolower($status) === 'completed') ? 'paid' : 'pending';
            $paidAt = (strtolower($status) === 'completed') ? date('Y-m-d H:i:s') : null;
            $sqlPay = "INSERT INTO payments (order_id, method, amount, status, paid_at) VALUES (?, ?, ?, ?, ?)";
            $stmtPay = $this->conn->prepare($sqlPay);
            $stmtPay->bind_param("isdss", $orderId, $paymentMethod, $totalAmount, $paymentStatus, $paidAt);
            $stmtPay->execute();

            // Insert order details
            $sqlDetail = "INSERT INTO order_details (order_id, product_id, size_id, quantity, unit_price, subtotal, sugar_level, ice_level, note) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmtDetail = $this->conn->prepare($sqlDetail);
            
            $sqlTopping = "INSERT INTO order_detail_toppings (order_detail_id, topping_id, quantity, price) VALUES (?, ?, 1, ?)";
            $stmtTopping = $this->conn->prepare($sqlTopping);

            foreach ($items as $item) {
                $sizeId = !empty($item['size_id']) ? (int)$item['size_id'] : null;
                $quantity = (int)$item['quantity'];
                $price = (float)$item['price'];
                $subtotal = $price * $quantity;
                
                // Parse sugar & ice
                $sugarLevel = 100;
                if (isset($item['sugar_level'])) {
                    $sugarLevel = (int)$item['sugar_level'];
                } elseif (isset($item['sugar'])) {
                    $cleanSugar = preg_replace('/[^0-9]/', '', (string)$item['sugar']);
                    if ($cleanSugar !== '') $sugarLevel = (int)$cleanSugar;
                }
                
                $temp = $item['temperature'] ?? 'cold';
                $iceLevel = ($temp === 'hot') ? 0 : 100;
                if ($temp !== 'hot') {
                    if (isset($item['ice_level'])) {
                        $iceLevel = (int)$item['ice_level'];
                    } elseif (isset($item['ice'])) {
                        $cleanIce = preg_replace('/[^0-9]/', '', (string)$item['ice']);
                        if ($cleanIce !== '') $iceLevel = (int)$cleanIce;
                    }
                }
                
                $itemNote = ($temp === 'hot') ? 'Dùng nóng' : 'Dùng lạnh';
                if (!empty($item['note'])) {
                    $itemNote .= ' - ' . $item['note'];
                }
                
                $stmtDetail->bind_param("iiiiddiis", $orderId, $item['product_id'], $sizeId, $quantity, $price, $subtotal, $sugarLevel, $iceLevel, $itemNote);
                $stmtDetail->execute();
                
                $orderDetailId = $stmtDetail->insert_id;
                
                if (!empty($item['toppings']) && is_array($item['toppings'])) {
                    foreach ($item['toppings'] as $topping) {
                        $tId = (int)$topping['id'];
                        $tPrice = (float)$topping['price'];
                        $stmtTopping->bind_param("iid", $orderDetailId, $tId, $tPrice);
                        $stmtTopping->execute();
                    }
                }
            }

            $this->conn->commit();
            return $orderId;
        } catch (Exception $e) {
            $this->conn->rollback();
            return false;
        }
    }

    public function createCustomerOrder($customerId, $totalAmount, $paymentMethod, $items, $note = '') {
        $this->conn->begin_transaction();
        try {
            // Tự động tìm ca làm việc đang mở tại quầy nếu có
            require_once __DIR__ . '/ShiftSession.php';
            $shiftSessionModel = new ShiftSession();
            $activeSession = $shiftSessionModel->getCurrentOpenSession();
            $shiftSessionId = $activeSession ? (int)$activeSession['id'] : null;

            $orderType = 'delivery'; // Customer orders from web are usually delivery
            $sql = "INSERT INTO orders (customer_id, status, total_amount, final_amount, order_type, shift_session_id, note) VALUES (?, 'Pending', ?, ?, ?, ?, ?)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("iddsis", $customerId, $totalAmount, $totalAmount, $orderType, $shiftSessionId, $note);
            $stmt->execute();
            $orderId = $stmt->insert_id;

            $sqlPay = "INSERT INTO payments (order_id, method, amount, status) VALUES (?, ?, ?, 'pending')";
            $stmtPay = $this->conn->prepare($sqlPay);
            $stmtPay->bind_param("isd", $orderId, $paymentMethod, $totalAmount);
            $stmtPay->execute();

            $sqlDetail = "INSERT INTO order_details (order_id, product_id, size_id, quantity, unit_price, subtotal, sugar_level, ice_level, note) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmtDetail = $this->conn->prepare($sqlDetail);
            
            $sqlTopping = "INSERT INTO order_detail_toppings (order_detail_id, topping_id, quantity, price) VALUES (?, ?, 1, ?)";
            $stmtTopping = $this->conn->prepare($sqlTopping);

            // Pre-fetch size mappings by name for fast lookup
            $sizeMap = [];
            $sizeRes = $this->conn->query("SELECT id, name FROM sizes");
            if ($sizeRes) {
                while ($sRow = $sizeRes->fetch_assoc()) {
                    $sizeMap[strtoupper(trim($sRow['name']))] = (int)$sRow['id'];
                }
            }

            foreach ($items as $item) {
                $sizeId = null;
                if (!empty($item['size_id']) && is_numeric($item['size_id'])) {
                    $sizeId = (int)$item['size_id'];
                } elseif (!empty($item['size'])) {
                    if (is_numeric($item['size'])) {
                        $sizeId = (int)$item['size'];
                    } else {
                        $sizeKey = strtoupper(trim($item['size']));
                        $sizeId = $sizeMap[$sizeKey] ?? null;
                    }
                }
                
                $quantity = (int)$item['quantity'];
                $price = (float)($item['total_price'] ?? ($item['price'] ?? 0)); 
                $subtotal = $price * $quantity;
                
                // Parse sugar & ice
                $sugarLevel = 100;
                if (isset($item['sugar_level'])) {
                    $sugarLevel = (int)$item['sugar_level'];
                } elseif (isset($item['sugar'])) {
                    $cleanSugar = preg_replace('/[^0-9]/', '', (string)$item['sugar']);
                    if ($cleanSugar !== '') $sugarLevel = (int)$cleanSugar;
                }
                
                $temp = $item['temperature'] ?? 'cold';
                $iceLevel = ($temp === 'hot') ? 0 : 100;
                if ($temp !== 'hot') {
                    if (isset($item['ice_level'])) {
                        $iceLevel = (int)$item['ice_level'];
                    } elseif (isset($item['ice'])) {
                        $cleanIce = preg_replace('/[^0-9]/', '', (string)$item['ice']);
                        if ($cleanIce !== '') $iceLevel = (int)$cleanIce;
                    }
                }
                
                $itemNote = ($temp === 'hot') ? 'Dùng nóng' : 'Dùng lạnh';
                if (!empty($item['note'])) {
                    $itemNote .= ' - ' . $item['note'];
                }
                
                $stmtDetail->bind_param("iiiiddiis", $orderId, $item['product_id'], $sizeId, $quantity, $price, $subtotal, $sugarLevel, $iceLevel, $itemNote);
                $stmtDetail->execute();
                
                $orderDetailId = $stmtDetail->insert_id;
                
                if (!empty($item['toppings']) && is_array($item['toppings'])) {
                    foreach ($item['toppings'] as $topping) {
                        $tId = (int)$topping['id'];
                        $tPrice = (float)$topping['price'];
                        $stmtTopping->bind_param("iid", $orderDetailId, $tId, $tPrice);
                        $stmtTopping->execute();
                    }
                }
            }

            $this->conn->commit();
            return $orderId;
        } catch (Exception $e) {
            $this->conn->rollback();
            return false;
        }
    }
}
