<?php
require_once __DIR__ . '/../config/database.php';

class ProductIngredient {
    private $conn;

    public function __construct() {
        global $conn;
        $this->conn = $conn;
    }

    public function getByProductId($product_id, $size_id = null) {
        if ($size_id !== null) {
            $sql = "SELECT pi.*, i.ingredient_name, i.unit, s.name as size_name 
                    FROM product_ingredients pi
                    JOIN inventory i ON pi.inventory_id = i.id
                    LEFT JOIN sizes s ON pi.size_id = s.id
                    WHERE pi.product_id = ? AND pi.size_id = ?
                    ORDER BY i.ingredient_name ASC";
            $stmt = $this->conn->prepare($sql);
            if ($stmt) {
                $stmt->bind_param("ii", $product_id, $size_id);
                $stmt->execute();
                return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
            }
        } else {
            $sql = "SELECT pi.*, i.ingredient_name, i.unit, s.name as size_name 
                    FROM product_ingredients pi
                    JOIN inventory i ON pi.inventory_id = i.id
                    LEFT JOIN sizes s ON pi.size_id = s.id
                    WHERE pi.product_id = ?
                    ORDER BY pi.size_id ASC, i.ingredient_name ASC";
            $stmt = $this->conn->prepare($sql);
            if ($stmt) {
                $stmt->bind_param("i", $product_id);
                $stmt->execute();
                return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
            }
        }
        return [];
    }

    public function getByProductIdAndSize($product_id, $size_id = 0) {
        $recipe = $this->getByProductId($product_id, $size_id);
        if (empty($recipe) && $size_id != 0) {
            // Fallback sang công thức chung (size_id = 0) nếu size cụ thể chưa cấu hình
            $recipe = $this->getByProductId($product_id, 0);
        }
        return $recipe;
    }

    public function getRecipeGroupedBySizes($product_id) {
        $rows = $this->getByProductId($product_id);
        $grouped = [];
        foreach ($rows as $r) {
            $sid = (int)$r['size_id'];
            if (!isset($grouped[$sid])) {
                $grouped[$sid] = [];
            }
            $grouped[$sid][] = $r;
        }
        return $grouped;
    }

    public function setIngredientsBySizes($product_id, $recipesBySize) {
        // $recipesBySize format: [ size_id => [ ['inventory_id' => ..., 'quantity' => ...], ... ] ]
        $this->conn->begin_transaction();
        try {
            // Xóa toàn bộ công thức cũ của sản phẩm này
            $sql_del = "DELETE FROM product_ingredients WHERE product_id = ?";
            $stmt_del = $this->conn->prepare($sql_del);
            $stmt_del->bind_param("i", $product_id);
            $stmt_del->execute();

            // Thêm công thức theo từng size
            $sql_in = "INSERT INTO product_ingredients (product_id, size_id, inventory_id, quantity) VALUES (?, ?, ?, ?)";
            $stmt_in = $this->conn->prepare($sql_in);

            foreach ($recipesBySize as $size_id => $items) {
                $sid = (int)$size_id;
                if (!empty($items)) {
                    foreach ($items as $ing) {
                        $inv_id = (int)($ing['inventory_id'] ?? 0);
                        $qty = (float)($ing['quantity'] ?? 0);
                        if ($inv_id > 0 && $qty > 0) {
                            $stmt_in->bind_param("iiid", $product_id, $sid, $inv_id, $qty);
                            $stmt_in->execute();
                        }
                    }
                }
            }

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollback();
            return false;
        }
    }

    /**
     * Lấy danh sách quy chuẩn mức đường theo sản phẩm và size
     */
    public function getSugarLevels($productId, $sizeId = 0) {
        $sql = "SELECT psl.*, i.ingredient_name, i.unit 
                FROM product_sugar_levels psl
                LEFT JOIN inventory i ON psl.inventory_id = i.id
                WHERE psl.product_id = ? AND psl.size_id = ?
                ORDER BY psl.sugar_percent ASC";
        $stmt = $this->conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("ii", $productId, $sizeId);
            $stmt->execute();
            $res = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
            $levels = [];
            foreach ($res as $row) {
                $levels[$row['sugar_percent']] = $row;
            }
            return $levels;
        }
        return [];
    }

    /**
     * Lấy toàn bộ quy chuẩn mức đường của 1 sản phẩm gom nhóm theo Size
     */
    public function getAllSugarLevelsGrouped($productId) {
        $sql = "SELECT psl.*, i.ingredient_name, i.unit 
                FROM product_sugar_levels psl
                LEFT JOIN inventory i ON psl.inventory_id = i.id
                WHERE psl.product_id = ?
                ORDER BY psl.size_id ASC, psl.sugar_percent ASC";
        $stmt = $this->conn->prepare($sql);
        $grouped = [];
        if ($stmt) {
            $stmt->bind_param("i", $productId);
            $stmt->execute();
            $res = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
            foreach ($res as $row) {
                $sid = (int)$row['size_id'];
                $percent = (int)$row['sugar_percent'];
                if (!isset($grouped[$sid])) {
                    $grouped[$sid] = [
                        'inventory_id' => $row['inventory_id'],
                        'levels' => []
                    ];
                }
                $grouped[$sid]['levels'][$percent] = (float)$row['quantity'];
                if (!empty($row['inventory_id'])) {
                    $grouped[$sid]['inventory_id'] = $row['inventory_id'];
                }
            }
        }
        return $grouped;
    }

    /**
     * Lưu bảng quy chuẩn mức đường cho sản phẩm theo từng Size
     */
    public function setSugarLevelsBySizes($productId, $sugarData) {
        if (empty($sugarData)) return true;

        $sqlDel = "DELETE FROM product_sugar_levels WHERE product_id = ?";
        $stmtDel = $this->conn->prepare($sqlDel);
        $stmtDel->bind_param("i", $productId);
        $stmtDel->execute();

        $sqlIns = "INSERT INTO product_sugar_levels (product_id, size_id, sugar_percent, quantity, inventory_id) 
                   VALUES (?, ?, ?, ?, ?)";
        $stmtIns = $this->conn->prepare($sqlIns);

        foreach ($sugarData as $sizeId => $data) {
            $sid = (int)$sizeId;
            $invId = !empty($data['inventory_id']) ? (int)$data['inventory_id'] : null;
            $levels = $data['levels'] ?? [];

            foreach ($levels as $percent => $qty) {
                $pct = (int)$percent;
                $quantity = (float)$qty;
                $stmtIns->bind_param("iiidi", $productId, $sid, $pct, $quantity, $invId);
                $stmtIns->execute();
            }
        }
        return true;
    }

    /**
     * Lấy định lượng đường tiêu hao khi khách đặt món (dùng trong InventoryService)
     */
    public function getSugarDeduction($productId, $sizeId, $sugarPercent) {
        $sugarPercent = (int)$sugarPercent;
        $sizeId = (int)$sizeId;

        // Ưu tiên theo size cụ thể
        $sql = "SELECT quantity, inventory_id FROM product_sugar_levels WHERE product_id = ? AND size_id = ? AND sugar_percent = ? LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("iii", $productId, $sizeId, $sugarPercent);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();

        // Nếu không có, thử tìm ở size_id = 0 (mặc định)
        if (!$row && $sizeId !== 0) {
            $zero = 0;
            $stmt->bind_param("iii", $productId, $zero, $sugarPercent);
            $stmt->execute();
            $row = $stmt->get_result()->fetch_assoc();
        }

        return $row;
    }

    /**
     * Tính số lượng ly tối đa có thể pha chế dựa vào công thức và tồn kho nguyên liệu hiện tại
     *
     * @param int $productId ID sản phẩm
     * @param int|null $sizeId ID kích cỡ cụ thể (hoặc null để tính cho tất cả kích cỡ)
     * @return array
     */
    public function calculateAvailableStock($productId, $sizeId = null) {
        $productId = (int)$productId;

        // 1. Lấy toàn bộ tồn kho nguyên liệu hiện tại
        $invRes = $this->conn->query("SELECT id, ingredient_name, quantity, unit FROM inventory");
        $inventoryMap = [];
        if ($invRes) {
            while ($row = $invRes->fetch_assoc()) {
                $inventoryMap[(int)$row['id']] = [
                    'name' => $row['ingredient_name'],
                    'stock' => (float)$row['quantity'],
                    'unit' => $row['unit']
                ];
            }
        }

        // 2. Lấy danh sách sizes
        $sizeRes = $this->conn->query("SELECT id, name FROM sizes WHERE status = 1 ORDER BY id ASC");
        $sizes = [];
        if ($sizeRes) {
            while ($sRow = $sizeRes->fetch_assoc()) {
                $sizes[] = $sRow;
            }
        }

        // 3. Lấy công thức của sản phẩm
        $allRecipes = $this->getByProductId($productId);
        if (empty($allRecipes)) {
            return [
                'has_recipe' => false,
                'available_quantity' => null, // Chưa cấu hình công thức
                'bottleneck' => null,
                'by_size' => []
            ];
        }

        // Gom công thức theo size_id (size_id = 0 là công thức chung)
        $recipeBySize = [];
        foreach ($allRecipes as $item) {
            $sid = (int)$item['size_id'];
            $recipeBySize[$sid][] = $item;
        }

        $bySize = [];
        $overallMin = null;
        $overallBottleneck = null;

        // Tính cho từng size
        foreach ($sizes as $s) {
            $sId = (int)$s['id'];

            // Lấy công thức của size này, nếu không có thì fallback sang size_id = 0
            $recipe = $recipeBySize[$sId] ?? ($recipeBySize[0] ?? []);

            if (empty($recipe)) {
                $bySize[$sId] = [
                    'size_id' => $sId,
                    'size_name' => $s['name'],
                    'available_quantity' => 0,
                    'bottleneck' => 'Chưa có công thức cho size này',
                    'ingredients' => []
                ];
                continue;
            }

            $sizeMin = PHP_INT_MAX;
            $sizeBottleneck = null;
            $ingDetails = [];

            foreach ($recipe as $ing) {
                $invId = (int)$ing['inventory_id'];
                $reqQty = (float)$ing['quantity'];
                $invItem = $inventoryMap[$invId] ?? null;
                $currentStock = $invItem ? $invItem['stock'] : 0.0;
                $ingName = $invItem ? $invItem['name'] : $ing['ingredient_name'];

                if ($reqQty > 0) {
                    $canMake = (int)floor($currentStock / $reqQty);
                    if ($canMake < 0) $canMake = 0;
                } else {
                    $canMake = PHP_INT_MAX;
                }

                $ingDetails[] = [
                    'name' => $ingName,
                    'required' => $reqQty,
                    'stock' => $currentStock,
                    'can_make' => $canMake
                ];

                if ($canMake < $sizeMin) {
                    $sizeMin = $canMake;
                    $sizeBottleneck = $ingName;
                }
            }

            if ($sizeMin === PHP_INT_MAX) {
                $sizeMin = 0;
            }

            $bySize[$sId] = [
                'size_id' => $sId,
                'size_name' => $s['name'],
                'available_quantity' => $sizeMin,
                'bottleneck' => $sizeBottleneck,
                'ingredients' => $ingDetails
            ];

            if ($overallMin === null || $sizeMin < $overallMin) {
                $overallMin = $sizeMin;
                $overallBottleneck = $sizeBottleneck;
            }
        }

        // Nếu người dùng truyền $sizeId cụ thể
        $selectedAvailable = null;
        $selectedBottleneck = null;
        if ($sizeId !== null && isset($bySize[$sizeId])) {
            $selectedAvailable = $bySize[$sizeId]['available_quantity'];
            $selectedBottleneck = $bySize[$sizeId]['bottleneck'];
        } else {
            // Mặc định lấy size đầu tiên
            $firstSize = reset($bySize);
            $selectedAvailable = $firstSize ? $firstSize['available_quantity'] : ($overallMin ?? 0);
            $selectedBottleneck = $firstSize ? $firstSize['bottleneck'] : $overallBottleneck;
        }

        return [
            'has_recipe' => true,
            'available_quantity' => $selectedAvailable,
            'bottleneck' => $selectedBottleneck,
            'by_size' => $bySize
        ];
    }
}
