<?php

class HomeController {
    public function index() {
        // Render home view
        require_once __DIR__ . '/../../views/customer/home/index.php';
    }

    public function about() {
        require_once __DIR__ . '/../../views/customer/about/index.php';
    }

    public function contact() {
        require_once __DIR__ . '/../../views/customer/contact/index.php';
    }

    public function stores() {
        require_once __DIR__ . '/../../models/Store.php';
        $storeModel = new Store();
        $stores = $storeModel->getAll();
        
        require_once __DIR__ . '/../../views/customer/stores/index.php';
    }
}
