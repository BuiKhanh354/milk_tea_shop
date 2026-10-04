<?php
require_once __DIR__ . '/../config/database.php';

class Product {
    private $conn;

    public function __construct() {
        global $conn;
        $this->conn = $conn;
    }

    private function formatImageUrl($image) {
        if (empty($image)) {
            return 'https://images.unsplash.com/photo-1558160074-4d7d8bdf4256?auto=format&fit=crop&q=80&w=150';
        }
        if (strpos($image, 'http') === 0) {
            return $image;
        }
        return 'assets/images/products/' . $image;
    }

    public function findById($id) {
        // Fetch product and category
        $sql = "SELECT p.*, c.name as category_name 
                FROM products p 
                LEFT JOIN categories c ON p.category_id = c.id 
                WHERE p.id = ? AND p.status = 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $product = $result->fetch_assoc();
        
        if (!$product) return null;

        // Fetch rating and review count
        $ratingSql = "SELECT AVG(rating) as rating, COUNT(*) as review_count FROM reviews WHERE product_id = ? AND status = 1";
        $rStmt = $this->conn->prepare($ratingSql);
        $rStmt->bind_param("i", $id);
        $rStmt->execute();
        $rRes = $rStmt->get_result()->fetch_assoc();
        
        $product['rating'] = $rRes['rating'] ? (float)$rRes['rating'] : 5.0; // Default to 5.0 if no reviews
        $product['review_count'] = $rRes['review_count'] ? (int)$rRes['review_count'] : 0;

        // Generic mock data for fields that don't exist in the current database schema
        if (empty($product['story'])) {
            $product['story'] = 'Mỗi thức uống là một câu chuyện được VAA THÉ ấp ủ, tuyển chọn từ những nguyên liệu tươi ngon nhất để mang đến trải nghiệm tuyệt vời.';
        }
        if (empty($product['ingredients'])) {
            $product['ingredients'] = 'Nguyên liệu tự nhiên, an toàn cho sức khỏe.';
        }

        $product['image'] = $this->formatImageUrl($product['image'] ?? '');
        return $product;
    }

            public function getPaginated($page = 1, $limit = 8, $includeHidden = false, $filters = []) {
        $offset = ($page - 1) * $limit;
        
        $conditions = [];
        $params = [];
        $types = "";
        
        if (!$includeHidden) {
            $conditions[] = "p.status = 1";
        } else if (isset($filters['status']) && $filters['status'] !== '') {
            $conditions[] = "p.status = ?";
            $params[] = (int)$filters['status'];
            $types .= "i";
        }
        
        if (!empty($filters['search'])) {
            $conditions[] = "(p.name LIKE ? OR p.description LIKE ?)";
            $searchTerm = "%" . $filters['search'] . "%";
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $types .= "ss";
        }
        
        if (!empty($filters['category_id']) && $filters['category_id'] !== 'all') {
            $conditions[] = "p.category_id = ?";
            $params[] = (int)$filters['category_id'];
            $types .= "i";
        }
        
        $whereClause = !empty($conditions) ? "WHERE " . implode(" AND ", $conditions) : "";
        
        $orderClause = "ORDER BY p.id DESC";
        if (!empty($filters['sort'])) {
            switch ($filters['sort']) {
                case 'price_asc':
                    $orderClause = "ORDER BY p.price ASC";
                    break;
                case 'price_desc':
                    $orderClause = "ORDER BY p.price DESC";
                    break;
                case 'name_asc':
                    $orderClause = "ORDER BY p.name ASC";
                    break;
            }
        }
        
        // Get total count
        $countSql = "SELECT COUNT(*) as total FROM products p $whereClause";
        if (!empty($params)) {
            $cStmt = $this->conn->prepare($countSql);
            $cStmt->bind_param($types, ...$params);
            $cStmt->execute();
            $total = $cStmt->get_result()->fetch_assoc()['total'];
        } else {
            $total = $this->conn->query($countSql)->fetch_assoc()['total'];
        }
        
        // Get paginated data
        $sql = "SELECT p.*, c.name as category_name 
                FROM products p 
                LEFT JOIN categories c ON p.category_id = c.id 
                $whereClause
                $orderClause
                LIMIT ? OFFSET ?";
                
        $stmt = $this->conn->prepare($sql);
        $allParams = array_merge($params, [$limit, $offset]);
        $allTypes = $types . "ii";
        $stmt->bind_param($allTypes, ...$allParams);
        $stmt->execute();
        $products = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        
        foreach ($products as &$p) {
            $p['image'] = $this->formatImageUrl($p['image']);
        }
        
        return [
            'data' => $products,
            'total' => $total,
            'total_pages' => ceil($total / $limit),
            'current_page' => $page
        ];
    }

    public function getAll($includeHidden = false) {
        $statusCondition = $includeHidden ? "" : "WHERE p.status = 1";
        $sql = "SELECT p.*, c.name as category_name 
                FROM products p 
                LEFT JOIN categories c ON p.category_id = c.id 
                $statusCondition
                ORDER BY p.id DESC";
        $result = $this->conn->query($sql);
        $products = $result->fetch_all(MYSQLI_ASSOC);
        foreach ($products as &$p) {
            $p['image'] = $this->formatImageUrl($p['image']);
        }
        return $products;
    }

    public function getSizes($includeHidden = false) {
        $statusCondition = $includeHidden ? "" : "WHERE status = 1";
        $sql = "SELECT * FROM sizes $statusCondition ORDER BY extra_price ASC";
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function createSize($data) {
        $sql = "INSERT INTO sizes (name, extra_price, status) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sii", $data['name'], $data['extra_price'], $data['status']);
        return $stmt->execute();
    }

    public function updateSize($id, $data) {
        $sql = "UPDATE sizes SET name=?, extra_price=?, status=? WHERE id=?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("siii", $data['name'], $data['extra_price'], $data['status'], $id);
        return $stmt->execute();
    }

    public function deleteSize($id) {
        $sql = "DELETE FROM sizes WHERE id=?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    public function getToppings($includeHidden = false) {
        $statusCondition = $includeHidden ? "" : "WHERE status = 1";
        $sql = "SELECT * FROM toppings $statusCondition";
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function createTopping($data) {
        $sql = "INSERT INTO toppings (name, price, status) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sii", $data['name'], $data['price'], $data['status']);
        return $stmt->execute();
    }

    public function updateTopping($id, $data) {
        $sql = "UPDATE toppings SET name=?, price=?, status=? WHERE id=?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("siii", $data['name'], $data['price'], $data['status'], $id);
        return $stmt->execute();
    }

    public function deleteTopping($id) {
        $sql = "DELETE FROM toppings WHERE id=?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    public function getRelatedProducts($categoryId, $excludeId) {
        $sql = "SELECT * FROM products WHERE category_id = ? AND id != ? AND status = 1 LIMIT 4";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $categoryId, $excludeId);
        $stmt->execute();
        $result = $stmt->get_result();
        $products = $result->fetch_all(MYSQLI_ASSOC);
        foreach ($products as &$p) {
            $p['image'] = $this->formatImageUrl($p['image']);
        }
        return $products;
    }

    public function getReviews($id) {
        $sql = "SELECT r.rating, r.comment, DATE_FORMAT(r.created_at, '%d/%m/%Y') as date, c.full_name as name 
                FROM reviews r 
                JOIN customers c ON r.customer_id = c.id 
                WHERE r.product_id = ? AND r.status = 1 
                ORDER BY r.created_at DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $products = $result->fetch_all(MYSQLI_ASSOC);
        foreach ($products as &$p) {
            $p['image'] = $this->formatImageUrl($p['image']);
        }
        return $products;
    }

    public function create($data) {
        $sql = "INSERT INTO products (name, category_id, description, price, image, status) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sisdsi", $data['name'], $data['category_id'], $data['description'], $data['price'], $data['image'], $data['status']);
        return $stmt->execute();
    }

    public function update($id, $data) {
        if (!empty($data['image'])) {
            $sql = "UPDATE products SET name=?, category_id=?, description=?, price=?, image=?, status=? WHERE id=?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("sisdsii", $data['name'], $data['category_id'], $data['description'], $data['price'], $data['image'], $data['status'], $id);
        } else {
            $sql = "UPDATE products SET name=?, category_id=?, description=?, price=?, status=? WHERE id=?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("sisdii", $data['name'], $data['category_id'], $data['description'], $data['price'], $data['status'], $id);
        }
        return $stmt->execute();
    }

    public function delete($id) {
        $sql = "DELETE FROM products WHERE id=?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    public function toggleStatus($id) {
        $sql = "UPDATE products SET status = IF(status=1, 0, 1) WHERE id=?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
