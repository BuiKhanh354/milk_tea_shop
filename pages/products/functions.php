<?php
// pages/products/functions.php — Các hàm truy vấn sản phẩm thuần chuẩn minishop

function products_get_categories()
{
    $sql = "SELECT * FROM categories ORDER BY id ASC";
    return db()->fetchAll($sql);
}

function products_get_paginated($page = 1, $limit = 8, $filters = array())
{
    $offset = ($page - 1) * $limit;
    $where = array("p.status = 1");
    $params = array();

    if (!empty($filters['category_id'])) {
        $where[] = "p.category_id = ?";
        $params[] = (int)$filters['category_id'];
    }

    if (!empty($filters['search'])) {
        $where[] = "p.name LIKE ?";
        $params[] = '%' . $filters['search'] . '%';
    }

    $whereClause = implode(" AND ", $where);

    // Tính tổng số lượng
    $countSql = "SELECT COUNT(*) as total FROM products p WHERE $whereClause";
    $countRow = db()->fetchOne($countSql, $params);
    $total = (int)($countRow['total'] ?? 0);

    // Lấy danh sách sản phẩm
    $sql = "SELECT p.*, c.name as category_name 
            FROM products p 
            LEFT JOIN categories c ON p.category_id = c.id 
            WHERE $whereClause 
            ORDER BY p.id DESC 
            LIMIT ? OFFSET ?";
    
    $paramsWithLimit = array_merge($params, array((int)$limit, (int)$offset));
    $products = db()->fetchAll($sql, $paramsWithLimit);

    return array(
        'data'        => $products,
        'total'       => $total,
        'page'        => $page,
        'limit'       => $limit,
        'total_pages' => $total > 0 ? (int)ceil($total / $limit) : 1
    );
}

function product_get_by_id($id, $includeHidden = false)
{
    $statusClause = ($includeHidden || isset($_SESSION['user_id'])) ? "" : "AND p.status = 1";
    $sql = "SELECT p.*, c.name as category_name 
            FROM products p 
            LEFT JOIN categories c ON p.category_id = c.id 
            WHERE p.id = ? $statusClause";
    return db()->fetchOne($sql, array((int)$id));
}

function product_get_toppings($productId = null)
{
    if ($productId !== null) {
        $sql = "SELECT t.* FROM toppings t
                JOIN product_toppings pt ON t.id = pt.topping_id
                WHERE pt.product_id = ? AND (t.status = 'available' OR t.status = 1)
                ORDER BY t.price ASC";
        return db()->fetchAll($sql, array((int)$productId));
    }
    
    $sql = "SELECT * FROM toppings WHERE status = 'available' OR status = 1 ORDER BY price ASC";
    return db()->fetchAll($sql);
}

function product_get_sizes($includeHidden = false)
{
    $statusCondition = $includeHidden ? "" : "WHERE status = 1";
    $sql = "SELECT id, name, extra_price, extra_price as price_modifier, status FROM sizes $statusCondition ORDER BY extra_price ASC";
    return db()->fetchAll($sql);
}

function product_get_sugar_options()
{
    $sql = "SELECT id, name, value, is_default FROM item_options WHERE type = 'sugar' AND (status = 1 OR status IS NULL) ORDER BY value ASC";
    return db()->fetchAll($sql);
}

// Giữ lại alias cho tương thích
function product_get_sugar_levels()
{
    return product_get_sugar_options();
}

function product_get_ice_options()
{
    $sql = "SELECT id, name, value, is_default FROM item_options WHERE type = 'ice' AND (status = 1 OR status IS NULL) ORDER BY value ASC";
    return db()->fetchAll($sql);
}

// Giữ lại alias cho tương thích
function product_get_ice_levels()
{
    return product_get_ice_options();
}

function products_get_related($categoryId, $excludeId, $limit = 4)
{
    $sql = "SELECT * FROM products 
            WHERE category_id = ? AND id != ? AND status = 1 
            ORDER BY id DESC LIMIT ?";
    return db()->fetchAll($sql, array((int)$categoryId, (int)$excludeId, (int)$limit));
}

function product_get_ingredient_names($productId)
{
    $sql = "SELECT DISTINCT i.ingredient_name 
            FROM product_ingredients pi
            JOIN inventory i ON pi.inventory_id = i.id
            WHERE pi.product_id = ?
            ORDER BY i.ingredient_name ASC";
    $rows = db()->fetchAll($sql, array((int)$productId));
    return !empty($rows) ? array_column($rows, 'ingredient_name') : array();
}

function product_get_reviews($productId)
{
    $sql = "SELECT r.rating, r.comment, DATE_FORMAT(r.created_at, '%d/%m/%Y') as date, c.full_name as name 
            FROM reviews r 
            JOIN customers c ON r.customer_id = c.id 
            WHERE r.product_id = ? AND (r.status = 1 OR r.status IS NULL) 
            ORDER BY r.created_at DESC";
    return db()->fetchAll($sql, array((int)$productId));
}

function product_is_favorited($customerId, $productId)
{
    if (!$customerId) return false;
    $row = db()->fetchOne("SELECT id FROM favorites WHERE customer_id = ? AND product_id = ?", array((int)$customerId, (int)$productId));
    return !empty($row);
}

function product_calculate_stock($productId, $sizeId = null)
{
    $productId = (int)$productId;

    // 1. Lấy toàn bộ tồn kho nguyên liệu hiện tại
    $invRows = db()->fetchAll("SELECT id, ingredient_name, quantity, unit FROM inventory");
    $inventoryMap = array();
    foreach ($invRows as $row) {
        $inventoryMap[(int)$row['id']] = array(
            'name'  => $row['ingredient_name'],
            'stock' => (float)$row['quantity'],
            'unit'  => $row['unit']
        );
    }

    // 2. Lấy danh sách sizes
    $sizes = db()->fetchAll("SELECT id, name FROM sizes WHERE status = 1 ORDER BY id ASC");

    // 3. Lấy công thức của sản phẩm
    $sql = "SELECT pi.*, i.ingredient_name, i.unit, s.name as size_name 
            FROM product_ingredients pi
            JOIN inventory i ON pi.inventory_id = i.id
            LEFT JOIN sizes s ON pi.size_id = s.id
            WHERE pi.product_id = ?
            ORDER BY pi.size_id ASC, i.ingredient_name ASC";
    $allRecipes = db()->fetchAll($sql, array($productId));

    if (empty($allRecipes)) {
        return array(
            'has_recipe'         => false,
            'available_quantity' => null,
            'bottleneck'         => null,
            'by_size'            => array()
        );
    }

    $recipeBySize = array();
    foreach ($allRecipes as $item) {
        $sid = (int)$item['size_id'];
        $recipeBySize[$sid][] = $item;
    }

    $bySize = array();
    $overallMin = null;
    $overallBottleneck = null;

    foreach ($sizes as $s) {
        $sId = (int)$s['id'];
        $recipe = isset($recipeBySize[$sId]) ? $recipeBySize[$sId] : (isset($recipeBySize[0]) ? $recipeBySize[0] : array());

        if (empty($recipe)) {
            $bySize[$sId] = array(
                'size_id'            => $sId,
                'size_name'          => $s['name'],
                'available_quantity' => 0,
                'bottleneck'         => 'Chưa có công thức cho size này',
                'ingredients'        => array()
            );
            continue;
        }

        $sizeMin = PHP_INT_MAX;
        $sizeBottleneck = null;
        $ingDetails = array();

        foreach ($recipe as $ing) {
            $invId = (int)$ing['inventory_id'];
            $reqQty = (float)$ing['quantity'];
            $invItem = isset($inventoryMap[$invId]) ? $inventoryMap[$invId] : null;
            $currentStock = $invItem ? $invItem['stock'] : 0.0;
            $ingName = $invItem ? $invItem['name'] : (isset($ing['ingredient_name']) ? $ing['ingredient_name'] : '');

            if ($reqQty > 0) {
                $canMake = (int)floor($currentStock / $reqQty);
                if ($canMake < 0) $canMake = 0;
            } else {
                $canMake = PHP_INT_MAX;
            }

            $ingDetails[] = array(
                'name'     => $ingName,
                'required' => $reqQty,
                'stock'    => $currentStock,
                'can_make' => $canMake
            );

            if ($canMake < $sizeMin) {
                $sizeMin = $canMake;
                $sizeBottleneck = $ingName;
            }
        }

        if ($sizeMin === PHP_INT_MAX) {
            $sizeMin = 0;
        }

        $bySize[$sId] = array(
            'size_id'            => $sId,
            'size_name'          => $s['name'],
            'available_quantity' => $sizeMin,
            'bottleneck'         => $sizeBottleneck,
            'ingredients'        => $ingDetails
        );

        if ($overallMin === null || $sizeMin < $overallMin) {
            $overallMin = $sizeMin;
            $overallBottleneck = $sizeBottleneck;
        }
    }

    $selectedAvailable = null;
    $selectedBottleneck = null;
    if ($sizeId !== null && isset($bySize[$sizeId])) {
        $selectedAvailable = $bySize[$sizeId]['available_quantity'];
        $selectedBottleneck = $bySize[$sizeId]['bottleneck'];
    } else {
        $firstSize = reset($bySize);
        $selectedAvailable = $firstSize ? $firstSize['available_quantity'] : ($overallMin ?? 0);
        $selectedBottleneck = $firstSize ? $firstSize['bottleneck'] : $overallBottleneck;
    }

    return array(
        'has_recipe'         => true,
        'available_quantity' => $selectedAvailable,
        'bottleneck'         => $selectedBottleneck,
        'by_size'            => $bySize
    );
}
