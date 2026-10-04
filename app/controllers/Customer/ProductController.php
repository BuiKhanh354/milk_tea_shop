<?php

class ProductController {
    
    public function index() {
        require_once __DIR__ . '/../../models/Product.php';
        $productModel = new Product();
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 12;
        
        $filters = [
            'search' => $_GET['search'] ?? '',
            'category_id' => $_GET['category_id'] ?? 'all',
            'sort' => $_GET['sort'] ?? 'default'
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
        $id = $_GET['id'] ?? null;
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
        $toppings = $productModel->getToppings();
        
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

        require_once __DIR__ . '/../../views/customer/products/detail.php';
    }
}
