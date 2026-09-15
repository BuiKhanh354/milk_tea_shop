<?php
require_once __DIR__ . '/../../models/Product.php';

class ProductController {
    
    public function index() {
        $pageTitle = 'Quản lý sản phẩm';
        
        $productModel = new Product();
        $productsRaw = $productModel->getAll();
        
        $products = [];
        foreach($productsRaw as $p) {
            $products[] = [
                'id' => $p['id'],
                'name' => $p['name'],
                'category' => $p['category_name'] ?? 'Chưa phân loại',
                'price' => number_format($p['price'], 0, ',', '.') . ' ₫',
                'status' => $p['status'],
                'date' => date('d/m/Y', strtotime($p['created_at'])),
                'image' => $p['image'] ? 'assets/images/products/' . $p['image'] : 'https://images.unsplash.com/photo-1558160074-4d7d8bdf4256?auto=format&fit=crop&q=80&w=150'
            ];
        }

        ob_start();
        require_once __DIR__ . '/../../views/admin/products/index.php';
        $content = ob_get_clean();

        require_once __DIR__ . '/../../views/layouts/admin.php';
    }

    public function create() {
        $pageTitle = 'Thêm sản phẩm mới';
        
        require_once __DIR__ . '/../../models/Category.php';
        $categoryModel = new Category();
        $categories = $categoryModel->getAll();

        ob_start();
        require_once __DIR__ . '/../../views/admin/products/create.php';
        $content = ob_get_clean();

        require_once __DIR__ . '/../../views/layouts/admin.php';
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'name' => $_POST['name'] ?? '',
                'category_id' => $_POST['category_id'] ?? 0,
                'price' => str_replace(['.', ','], '', $_POST['price'] ?? 0),
                'status' => $_POST['status'] ?? 1,
                'description' => $_POST['description'] ?? '',
                'image' => ''
            ];

            // Handle image upload
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = __DIR__ . '/../../../public/assets/images/products/';
                if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
                
                $fileName = time() . '_' . basename($_FILES['image']['name']);
                if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $fileName)) {
                    $data['image'] = $fileName;
                }
            }

            $productModel = new Product();
            $productModel->create($data);
            
            header('Location: admin.php?route=products');
            exit;
        }
    }

    public function edit() {
        $id = $_GET['id'] ?? 0;
        if (!$id) {
            header('Location: admin.php?route=products');
            exit;
        }

        $pageTitle = 'Chỉnh sửa sản phẩm';
        
        require_once __DIR__ . '/../../models/Category.php';
        $categoryModel = new Category();
        $categories = $categoryModel->getAll();

        $productModel = new Product();
        $product = $productModel->findById($id);

        ob_start();
        require_once __DIR__ . '/../../views/admin/products/edit.php';
        $content = ob_get_clean();

        require_once __DIR__ . '/../../views/layouts/admin.php';
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? 0;
            $data = [
                'name' => $_POST['name'] ?? '',
                'category_id' => $_POST['category_id'] ?? 0,
                'price' => str_replace(['.', ','], '', $_POST['price'] ?? 0),
                'status' => isset($_POST['status']) ? 1 : 0,
                'description' => $_POST['description'] ?? '',
                'image' => ''
            ];

            // Handle image upload
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = __DIR__ . '/../../../public/assets/images/products/';
                if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
                
                $fileName = time() . '_' . basename($_FILES['image']['name']);
                if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $fileName)) {
                    $data['image'] = $fileName;
                }
            }

            $productModel = new Product();
            $productModel->update($id, $data);
            
            header('Location: admin.php?route=products');
            exit;
        }
    }

    public function delete() {
        $id = $_GET['id'] ?? 0;
        if ($id) {
            $productModel = new Product();
            $productModel->delete($id);
        }
        header('Location: admin.php?route=products');
        exit;
    }

    public function toggle() {
        $id = $_GET['id'] ?? 0;
        if ($id) {
            $productModel = new Product();
            $productModel->toggleStatus($id);
        }
        header('Location: admin.php?route=products');
        exit;
    }
}
