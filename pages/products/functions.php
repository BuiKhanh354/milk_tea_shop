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

function product_get_by_id($id)
{
    $sql = "SELECT p.*, c.name as category_name 
            FROM products p 
            LEFT JOIN categories c ON p.category_id = c.id 
            WHERE p.id = ? AND p.status = 1";
    return db()->fetchOne($sql, array((int)$id));
}

function product_get_toppings()
{
    $sql = "SELECT * FROM toppings WHERE status = 'available' OR status = 1 ORDER BY price ASC";
    return db()->fetchAll($sql);
}

function product_get_sizes()
{
    $sql = "SELECT id, name, extra_price as price_modifier FROM sizes WHERE status = 1 ORDER BY extra_price ASC";
    return db()->fetchAll($sql);
}

function product_get_sugar_levels()
{
    $sql = "SELECT id, name, value, is_default FROM item_options WHERE type = 'sugar' AND (status = 1 OR status IS NULL) ORDER BY value ASC";
    return db()->fetchAll($sql);
}

function product_get_ice_levels()
{
    $sql = "SELECT id, name, value, is_default FROM item_options WHERE type = 'ice' AND (status = 1 OR status IS NULL) ORDER BY value ASC";
    return db()->fetchAll($sql);
}

function products_get_related($categoryId, $excludeId, $limit = 4)
{
    $sql = "SELECT * FROM products 
            WHERE category_id = ? AND id != ? AND status = 1 
            ORDER BY id DESC LIMIT ?";
    return db()->fetchAll($sql, array((int)$categoryId, (int)$excludeId, (int)$limit));
}
