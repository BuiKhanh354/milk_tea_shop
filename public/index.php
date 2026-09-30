<?php
session_start();
require_once __DIR__ . '/../app/config/database.php';


$route = $_GET['route'] ?? 'home';

switch($route) {
    case 'home':
        require_once '../app/controllers/Customer/HomeController.php';
        (new HomeController())->index();
        break;
    
    case 'about':
        require_once '../app/controllers/Customer/HomeController.php';
        (new HomeController())->about();
        break;
        
    case 'contact':
        require_once '../app/controllers/Customer/HomeController.php';
        (new HomeController())->contact();
        break;
        
    case 'stores':
        require_once '../app/controllers/Customer/HomeController.php';
        (new HomeController())->stores();
        break;

    case 'login':
        require_once '../app/controllers/Customer/AuthController.php';
        (new AuthController())->login();
        break;

    case 'register':
        require_once '../app/controllers/Customer/AuthController.php';
        (new AuthController())->register();
        break;

    case 'logout':
        require_once '../app/controllers/Customer/AuthController.php';
        (new AuthController())->logout();
        break;

    case 'products':
        require_once '../app/controllers/Customer/ProductController.php';
        (new ProductController())->index();
        break;

    case 'product_detail':
        require_once '../app/controllers/Customer/ProductController.php';
        (new ProductController())->detail();
        break;

    case 'cart':
        require_once '../app/controllers/Customer/CartController.php';
        $controller = new CartController();
        if (isset($_GET['action']) && method_exists($controller, $_GET['action'])) {
            $action = $_GET['action'];
            $controller->$action();
        } else {
            $controller->index();
        }
        break;

    case 'orders':
        require_once '../app/controllers/Customer/OrderController.php';
        (new OrderController())->index();
        break;

    case 'profile':
        require_once '../app/controllers/Customer/ProfileController.php';
        (new ProfileController())->index();
        break;

    case 'password':
        require_once '../app/controllers/Customer/ProfileController.php';
        (new ProfileController())->password();
        break;

    case 'favorites':
        require_once '../app/controllers/Customer/ProfileController.php';
        (new ProfileController())->favorites();
        break;

    default:
        // Handle 404
        header("HTTP/1.0 404 Not Found");
        require_once '../app/views/customer/not_found.php';
        break;
}
