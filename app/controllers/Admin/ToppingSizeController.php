<?php

// Need a Size model, let's assume we use Product model or create Size model
// Wait, Product model has getSizes() and getToppings()

class ToppingSizeController {
    public function index() {
        $pageTitle = 'Quản lý Topping & Size';
        
        // Fetch toppings and sizes
        require_once __DIR__ . '/../../models/Product.php';
        $productModel = new Product();
        $toppings = $productModel->getToppings(true);
        $sizes = $productModel->getSizes(true);
        
        ob_start();
        require_once __DIR__ . '/../../views/admin/toppings/index.php';
        $content = ob_get_clean();

        require_once __DIR__ . '/../../views/layouts/admin.php';
    }

    public function storeTopping() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once __DIR__ . '/../../models/Product.php';
            $productModel = new Product();
            $data = [
                'name' => $_POST['name'] ?? '',
                'price' => (int)($_POST['price'] ?? 0),
                'status' => isset($_POST['status']) ? 1 : 0
            ];
            $productModel->createTopping($data);
            header('Location: admin.php?route=toppings&msg=created');
            exit;
        }
    }

    public function updateTopping() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once __DIR__ . '/../../models/Product.php';
            $productModel = new Product();
            $id = (int)$_POST['id'];
            $data = [
                'name' => $_POST['name'] ?? '',
                'price' => (int)($_POST['price'] ?? 0),
                'status' => isset($_POST['status']) ? 1 : 0
            ];
            $productModel->updateTopping($id, $data);
            header('Location: admin.php?route=toppings&msg=updated');
            exit;
        }
    }

    public function deleteTopping() {
        if (isset($_GET['id'])) {
            require_once __DIR__ . '/../../models/Product.php';
            $productModel = new Product();
            $productModel->deleteTopping((int)$_GET['id']);
            header('Location: admin.php?route=toppings&msg=deleted');
            exit;
        }
    }

    public function storeSize() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once __DIR__ . '/../../models/Product.php';
            $productModel = new Product();
            $data = [
                'name' => $_POST['name'] ?? '',
                'extra_price' => (int)($_POST['extra_price'] ?? 0),
                'status' => isset($_POST['status']) ? 1 : 0
            ];
            $productModel->createSize($data);
            header('Location: admin.php?route=toppings&msg=created');
            exit;
        }
    }

    public function updateSize() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once __DIR__ . '/../../models/Product.php';
            $productModel = new Product();
            $id = (int)$_POST['id'];
            $data = [
                'name' => $_POST['name'] ?? '',
                'extra_price' => (int)($_POST['extra_price'] ?? 0),
                'status' => isset($_POST['status']) ? 1 : 0
            ];
            $productModel->updateSize($id, $data);
            header('Location: admin.php?route=toppings&msg=updated');
            exit;
        }
    }

    public function deleteSize() {
        if (isset($_GET['id'])) {
            require_once __DIR__ . '/../../models/Product.php';
            $productModel = new Product();
            $productModel->deleteSize((int)$_GET['id']);
            header('Location: admin.php?route=toppings&msg=deleted');
            exit;
        }
    }
}
