<?php
// admin/pages/product_ingredients/action.php — Quản lý công thức sản phẩm (OOP)
$action = get_param('action', '');
$recipeModel = new ProductIngredient();
$productModel = new Product();
$sizeModel = new Size();
$ingredientModel = new Ingredient();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'store') {
    $productId = (int)post_param('product_id');
    $recipesInput = $_POST['recipes'] ?? [];

    if ($productId) {
        $recipeModel->saveRecipes($productId, $recipesInput);
        set_flash('success', "Cập nhật định lượng công thức thành công!");
    }
    redirect(admin_url('product_ingredients'));
}

if ($action === 'edit') {
    $id = (int)get_param('id');
    $product = $productModel->findWithCategory($id, true);
    if (!$product) {
        redirect(admin_url('product_ingredients'));
    }
    $sizes = $sizeModel->getActive(false);
    $all_ingredients = $ingredientModel->all('ingredient_name ASC');
    $groupedRecipes = $recipeModel->getRecipesByProductGroupedBySize($id);
    $pageTitle = 'Cấu hình công thức - ' . $product['name'];
} else {
    $productsRaw = $recipeModel->getProductsWithIngredientCount();
    $pageTitle = 'Quản lý Công thức Sản phẩm';
}
