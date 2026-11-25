<?php
// pages/products/functions.php — Các hàm truy vấn sản phẩm thuần chuẩn minishop

function products_get_categories()
{
    return (new Category())->all('id ASC');
}

function products_get_paginated($page = 1, $limit = 8, $filters = array())
{
    return (new Product())->getPaginated((int)$page, (int)$limit, $filters, true);
}

function product_get_by_id($id, $includeHidden = false)
{
    return (new Product())->findWithCategory((int)$id, $includeHidden || isset($_SESSION['user_id']));
}

function product_get_toppings($productId = null)
{
    return (new Topping())->getAvailable($productId !== null ? (int)$productId : null);
}

function product_get_sizes($includeHidden = false)
{
    return (new Size())->getActive((bool)$includeHidden);
}

function product_get_sugar_options()
{
    return (new ProductOption())->getByType('sugar', true);
}

// Giữ lại alias cho tương thích
function product_get_sugar_levels()
{
    return product_get_sugar_options();
}

function product_get_ice_options()
{
    return (new ProductOption())->getByType('ice', true);
}

// Giữ lại alias cho tương thích
function product_get_ice_levels()
{
    return product_get_ice_options();
}

function products_get_related($categoryId, $excludeId, $limit = 4)
{
    return (new Product())->getRelated((int)$categoryId, (int)$excludeId, (int)$limit);
}

function product_get_ingredient_names($productId)
{
    return (new ProductIngredient())->getIngredientNames((int)$productId);
}

function product_get_reviews($productId)
{
    return (new Review())->getVisibleByProduct((int)$productId);
}

function product_is_favorited($customerId, $productId)
{
    return (new Favorite())->isFavorited($customerId ? (int)$customerId : null, (int)$productId);
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
