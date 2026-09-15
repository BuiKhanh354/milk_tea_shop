<?php
require_once __DIR__ . '/../app/controllers/ProductController.php';

$controller = new ProductController();
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$controller->detail($id);
