<?php

class ProductController {
    
    public function index() {
        require_once __DIR__ . '/../../models/Product.php';
        $productModel = new Product();
        $page = max(1, (int)($_GET['page'] ?? 1));
        $limit = 12;

        $categoryId = trim((string)($_GET['category_id'] ?? 'all'));
        if ($categoryId !== 'all' && (!ctype_digit($categoryId) || (int)$categoryId < 1)) {
            $categoryId = 'all';
        }
        $sort = (string)($_GET['sort'] ?? 'default');
        $allowedSorts = ['default', 'price_asc', 'price_desc', 'name_asc'];
        if (!in_array($sort, $allowedSorts, true)) {
            $sort = 'default';
        }

        $filters = [
            'search' => trim((string)($_GET['search'] ?? '')),
            'category_id' => $categoryId,
            'sort' => $sort
        ];
        
        $paginatedData = $productModel->getPaginated($page, $limit, false, $filters);
        $productsList = $paginatedData['data'];
        $totalPages = $paginatedData['total_pages'];
        $currentPage = $paginatedData['current_page'];
        $currentSearch = $filters['search'];
        $currentCategory = $filters['category_id'];
        $currentSort = $filters['sort'];
        
        $favorites = [];
        if (isset($_SESSION['customer_id'])) {
            require_once __DIR__ . '/../../models/Favorite.php';
            $favoriteModel = new Favorite();
            $favList = $favoriteModel->getCustomerFavorites($_SESSION['customer_id']);
            $favorites = array_column($favList, 'id');
        }
        
        require_once __DIR__ . '/../../models/Category.php';
        $categoryModel = new Category();
        $categoriesList = $categoryModel->getAll();
        
        require_once __DIR__ . '/../../views/customer/products/layout.php';
    }

    public function detail() {
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
        if (!$id) {
            header('Location: index.php?route=products');
            exit;
        }

        require_once __DIR__ . '/../../models/Product.php';
        $productModel = new Product();
        $product = $productModel->findById($id);

        if (!$product) {
            require_once __DIR__ . '/../../views/customer/not_found.php';
            exit;
        }

        $sizes = $productModel->getSizes();
        $toppings = $productModel->getToppingsForProduct($id);
        
        require_once __DIR__ . '/../../models/ItemOption.php';
        $optionModel = new ItemOption();
        $sugarOptions = $optionModel->getSugarOptions();
        $iceOptions = $optionModel->getIceOptions();

        $relatedProducts = $productModel->getRelatedProducts($product['category_id'] ?? 1, $id);
        $reviews = $productModel->getReviews($id);

        $isFavorited = false;
        if (isset($_SESSION['customer_id'])) {
            require_once __DIR__ . '/../../models/Favorite.php';
            $favoriteModel = new Favorite();
            $isFavorited = $favoriteModel->isFavorite($_SESSION['customer_id'], $id);
        }

        // Tính toán tồn kho sản phẩm dựa vào công thức định lượng nguyên liệu
        require_once __DIR__ . '/../../models/ProductIngredient.php';
        $piModel = new ProductIngredient();
        $stockInfo = $piModel->calculateAvailableStock($id);
        $ingredientRows = $piModel->getByProductId($id);
        $ingredientNames = array_values(array_unique(array_filter(array_column($ingredientRows, 'ingredient_name'))));

        require_once __DIR__ . '/../../views/customer/products/detail.php';
    }
}
