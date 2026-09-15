<?php
session_start();

require_once __DIR__ . '/../app/controllers/StoreController.php';

$controller = new StoreController();

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $controller->detail($_GET['id']);
} else {
    $controller->index();
}
