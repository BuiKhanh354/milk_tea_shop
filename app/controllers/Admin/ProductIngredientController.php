<?php
require_once __DIR__ . '/../../models/Product.php';
require_once __DIR__ . '/../../models/Inventory.php';
require_once __DIR__ . '/../../models/ProductIngredient.php';

class ProductIngredientController {
    
    public function index() {
        $pageTitle = 'Quản lý Công thức';
        
        $productModel = new Product();
        $piModel = new ProductIngredient();
        
        $productsRaw = $productModel->getAll();
        $products = [];
        
        foreach ($productsRaw as $p) {
            $recipe = $piModel->getByProductId($p['id']);
            $products[] = [
                'id' => $p['id'],
                'name' => $p['name'],
                'image' => $p['image'],
                'category' => $p['category_name'],
                'ingredients_count' => count($recipe)
            ];
        }
        
        ob_start();
        require_once __DIR__ . '/../../views/admin/product_ingredients/index.php';
        $content = ob_get_clean();

        require_once __DIR__ . '/../../views/layouts/admin.php';
    }

    public function edit() {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header('Location: admin.php?route=product_ingredients');
            exit;
        }

        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: admin.php?route=product_ingredients');
            exit;
        }

        $pageTitle = 'Sửa Công thức SP';
        $productModel = new Product();
        $inventoryModel = new Inventory();
        $piModel = new ProductIngredient();

        $product = $productModel->findById($id);
        if (!$product) {
            header('Location: admin.php?route=product_ingredients');
            exit;
        }

        $all_ingredients = $inventoryModel->getAll();
        $recipe = $piModel->getByProductId($id);
        
        // Map recipe by inventory_id for easy view
        $current_recipe = [];
        foreach ($recipe as $r) {
            $current_recipe[$r['inventory_id']] = floatval($r['quantity']);
        }

        ob_start();
        require_once __DIR__ . '/../../views/admin/product_ingredients/edit.php';
        $content = ob_get_clean();

        require_once __DIR__ . '/../../views/layouts/admin.php';
    }

    public function store() {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header('Location: admin.php?route=product_ingredients');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $product_id = $_POST['product_id'] ?? null;
            $ingredient_ids = $_POST['ingredient_id'] ?? [];
            $quantities = $_POST['quantity'] ?? [];
            
            if ($product_id) {
                $ingredients = [];
                for ($i = 0; $i < count($ingredient_ids); $i++) {
                    $inv_id = (int)$ingredient_ids[$i];
                    $qty = (float)$quantities[$i];
                    if ($inv_id > 0 && $qty > 0) {
                        $ingredients[] = [
                            'inventory_id' => $inv_id,
                            'quantity' => $qty
                        ];
                    }
                }
                
                $piModel = new ProductIngredient();
                if ($piModel->setIngredients($product_id, $ingredients)) {
                    $_SESSION['flash_success'] = "Cập nhật công thức thành công!";
                } else {
                    $_SESSION['flash_error'] = "Có lỗi xảy ra khi lưu công thức.";
                }
            }
        }
        
        header('Location: admin.php?route=product_ingredients');
        exit;
    }
}
