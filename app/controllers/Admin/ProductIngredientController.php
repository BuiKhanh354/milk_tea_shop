<?php
require_once __DIR__ . '/../../models/Product.php';
require_once __DIR__ . '/../../models/Inventory.php';
require_once __DIR__ . '/../../models/ProductIngredient.php';

require_once __DIR__ . '/../../models/ItemOption.php';

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

        $pageTitle = 'Cấu hình Công thức theo Size';
        $productModel = new Product();
        $inventoryModel = new Inventory();
        $piModel = new ProductIngredient();
        $optionModel = new ItemOption();

        $product = $productModel->findById($id, true);
        if (!$product) {
            header('Location: admin.php?route=product_ingredients');
            exit;
        }

        $sizes = $productModel->getSizes(true);
        $all_ingredients = $inventoryModel->getAll();
        $groupedRecipes = $piModel->getRecipeGroupedBySizes($id);
        $sugarOptions = $optionModel->getSugarOptions();
        $groupedSugarLevels = $piModel->getAllSugarLevelsGrouped($id);

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
            require_csrf('admin.php?route=product_ingredients');
            $product_id = $_POST['product_id'] ?? null;
            $recipesInput = $_POST['recipes'] ?? [];
            $sugarInput = $_POST['sugar_levels'] ?? [];
            
            if ($product_id) {
                $recipesBySize = [];
                foreach ($recipesInput as $size_id => $data) {
                    $sid = (int)$size_id;
                    $ingIds = $data['ingredient_id'] ?? [];
                    $qtys = $data['quantity'] ?? [];
                    $recipesBySize[$sid] = [];
                    for ($i = 0; $i < count($ingIds); $i++) {
                        $inv_id = (int)($ingIds[$i] ?? 0);
                        $qty = (float)($qtys[$i] ?? 0);
                        if ($inv_id > 0 && $qty > 0) {
                            $recipesBySize[$sid][] = [
                                'inventory_id' => $inv_id,
                                'quantity' => $qty
                            ];
                        }
                    }
                }
                
                $piModel = new ProductIngredient();
                $piModel->setIngredientsBySizes($product_id, $recipesBySize);
                $piModel->setSugarLevelsBySizes($product_id, $sugarInput);
                
                $_SESSION['flash_success'] = "Cập nhật công thức và quy chuẩn định lượng đường thành công!";
            }
        }
        
        header('Location: admin.php?route=product_ingredients');
        exit;
    }
}
