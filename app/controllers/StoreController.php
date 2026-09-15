<?php
require_once __DIR__ . '/../models/Store.php';

class StoreController {
    
    public function index() {
        $storeModel = new Store();
        $stores = $storeModel->getAll();

        require_once __DIR__ . '/../views/customer/stores/index.php';
    }

    public function detail($id) {
        $storeModel = new Store();
        $store = $storeModel->findById($id);

        if (!$store) {
            header('Location: stores.php');
            exit;
        }

        require_once __DIR__ . '/../views/customer/stores/detail.php';
    }
}
