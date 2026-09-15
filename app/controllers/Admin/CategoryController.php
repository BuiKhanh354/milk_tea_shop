<?php
require_once __DIR__ . '/../../models/Category.php';

class CategoryController {
    public function index() {
        $categoryModel = new Category();
        $categories = $categoryModel->getAll();

        $pageTitle = 'Quản lý Danh mục';
        
        ob_start();
        require_once __DIR__ . '/../../views/admin/categories/index.php';
        $content = ob_get_clean();

        require_once __DIR__ . '/../../views/layouts/admin.php';
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name'] ?? '');
            $description = trim($_POST['description'] ?? '');

            if (!empty($name)) {
                $categoryModel = new Category();
                $categoryModel->create($name, $description);
                $_SESSION['flash_success'] = "Đã thêm danh mục mới thành công!";
            }
        }
        header('Location: admin.php?route=categories');
        exit;
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? null;
            $name = trim($_POST['name'] ?? '');
            $description = trim($_POST['description'] ?? '');

            if ($id && !empty($name)) {
                $categoryModel = new Category();
                $categoryModel->update($id, $name, $description);
                $_SESSION['flash_success'] = "Cập nhật danh mục thành công!";
            }
        }
        header('Location: admin.php?route=categories');
        exit;
    }

    public function delete() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? null;
            if ($id) {
                $categoryModel = new Category();
                if ($categoryModel->delete($id)) {
                    $_SESSION['flash_success'] = "Đã xóa danh mục!";
                } else {
                    $_SESSION['flash_error'] = "Không thể xóa danh mục vì vẫn còn sản phẩm thuộc danh mục này.";
                }
            }
        }
        header('Location: admin.php?route=categories');
        exit;
    }
}
