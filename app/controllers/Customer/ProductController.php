<?php

class ProductController {
    
    public function index() {
        require_once __DIR__ . '/../../models/Product.php';
        $productModel = new Product();
        $productsList = $productModel->getAll();
        
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

        require_once __DIR__ . '/../../views/customer/products/detail.php';
    }
}
