<?php
// admin/pages/product_ingredients/action.php — Quản lý công thức sản phẩm
$action = get_param('action', '');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'store') {
    $productId = (int)post_param('product_id');
    $recipesInput = $_POST['recipes'] ?? [];

    if ($productId) {
        db()->execute("DELETE FROM product_ingredients WHERE product_id = ?", [$productId]);
        foreach ($recipesInput as $sizeId => $data) {
            $sid = (int)$sizeId;
            $ingIds = $data['ingredient_id'] ?? [];
            $qtys = $data['quantity'] ?? [];
            for ($i = 0; $i < count($ingIds); $i++) {
                $invId = (int)($ingIds[$i] ?? 0);
                $qty = (float)($qtys[$i] ?? 0);
                if ($invId > 0 && $qty > 0) {
                    db()->execute("INSERT INTO product_ingredients (product_id, size_id, inventory_id, quantity) VALUES (?, ?, ?, ?)", 
                                  [$productId, $sid, $invId, $qty]);
                }
            }
        }
        set_flash('success', "Cập nhật định lượng công thức thành công!");
    }
    redirect(admin_url('product_ingredients'));
}

if ($action === 'edit') {
    $id = (int)get_param('id');
    $product = db()->fetchOne("SELECT p.*, c.name as category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id WHERE p.id = ?", [$id]);
    if (!$product) {
        redirect(admin_url('product_ingredients'));
    }
    $sizes = db()->fetchAll("SELECT * FROM sizes WHERE status = 1 ORDER BY extra_price ASC");
    $all_ingredients = db()->fetchAll("SELECT * FROM inventory ORDER BY ingredient_name ASC");

    // Lấy công thức hiện có gom theo size_id
    $recipesRaw = db()->fetchAll("SELECT pi.*, i.ingredient_name, i.unit FROM product_ingredients pi JOIN inventory i ON pi.inventory_id = i.id WHERE pi.product_id = ? ORDER BY i.ingredient_name ASC", [$id]);
    $groupedRecipes = [];
    foreach ($recipesRaw as $r) {
        $sid = (int)$r['size_id'];
        $groupedRecipes[$sid][] = $r;
    }
    $pageTitle = 'Cấu hình công thức - ' . $product['name'];
} else {
    $productsRaw = db()->fetchAll("SELECT p.*, c.name as category_name, 
        (SELECT COUNT(DISTINCT inventory_id) FROM product_ingredients WHERE product_id = p.id) as ingredients_count 
        FROM products p 
        LEFT JOIN categories c ON p.category_id = c.id 
        ORDER BY p.id ASC");
    $pageTitle = 'Quản lý Công thức Sản phẩm';
}

