<?php
require_once __DIR__ . '/../models/Product.php';

class ProductController {
    public function detail($id) {
        $productModel = new Product();
        $product = $productModel->findById($id);

        if (!$product) {
            // Product not found
            require_once __DIR__ . '/../views/customer/products/not_found.php';
            return;
        }

        $sizes = $productModel->getSizes();
        $toppings = $productModel->getToppings();
        $relatedProducts = $productModel->getRelatedProducts($product['category_id'] ?? 1, $id);
        $reviews = $productModel->getReviews($id);

        // Render view
        require_once __DIR__ . '/../views/customer/products/detail.php';
    }
}
