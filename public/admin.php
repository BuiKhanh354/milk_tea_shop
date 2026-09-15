<?php
session_start();

// Lấy route từ URL, mặc định là dashboard
$route = $_GET['route'] ?? 'dashboard';

// Các route KHÔNG cần đăng nhập
$publicRoutes = ['login', 'logout'];

if (!in_array($route, $publicRoutes)) {
    if (!isset($_SESSION['user_id'])) {
        header('Location: login.php');
        exit;
    }
    
    // Authorization: Nếu là Staff, không cho phép truy cập các trang của Admin
    if ($_SESSION['role'] === 'staff') {
        $staffAllowedRoutes = ['staff_dashboard', 'orders', 'logout'];
        if (!in_array($route, $staffAllowedRoutes)) {
            header('Location: admin.php?route=staff_dashboard');
            exit;
        }
    }
}

// Simple Router
switch ($route) {
    case 'login':
    case 'logout':
        require_once '../app/controllers/Admin/AuthController.php';
        $controller = new AuthController();
        if ($route === 'login') {
            $controller->login();
        } else {
            $controller->logout();
        }
        break;

    case 'dashboard':
        require_once '../app/controllers/Admin/DashboardController.php';
        $controller = new DashboardController();
        $controller->index();
        break;
        
    case 'orders':
        require_once '../app/controllers/Admin/OrderController.php';
        $controller = new OrderController();
        if (isset($_GET['action'])) {
            $action = $_GET['action'];
            if (method_exists($controller, $action)) {
                $controller->$action();
                break;
            }
        }
        $controller->index();
        break;
        
    case 'inventory':
        require_once '../app/controllers/Admin/InventoryController.php';
        $controller = new InventoryController();
        if (isset($_GET['action'])) {
            $action = $_GET['action'];
            if (method_exists($controller, $action)) {
                $controller->$action();
                break;
            }
        }
        $controller->index();
        break;

    case 'inventory_import':
        require_once '../app/controllers/Admin/InventoryController.php';
        $controller = new InventoryController();
        $controller->import();
        break;
        
    case 'inventory_export':
        require_once '../app/controllers/Admin/InventoryController.php';
        $controller = new InventoryController();
        $controller->export();
        break;

    case 'inventory_history':
        require_once '../app/controllers/Admin/InventoryController.php';
        $controller = new InventoryController();
        $controller->history();
        break;

    case 'inventory_alerts':
        require_once '../app/controllers/Admin/InventoryController.php';
        $controller = new InventoryController();
        $controller->alerts();
        break;

    case 'product_ingredients':
        require_once '../app/controllers/Admin/ProductIngredientController.php';
        $controller = new ProductIngredientController();
        if (isset($_GET['action'])) {
            $action = $_GET['action'];
            if (method_exists($controller, $action)) {
                $controller->$action();
                break;
            }
        }
        $controller->index();
        break;

    case 'categories':
        require_once '../app/controllers/Admin/CategoryController.php';
        $controller = new CategoryController();
        if (isset($_GET['action'])) {
            $action = $_GET['action'];
            if (method_exists($controller, $action)) {
                $controller->$action();
                break;
            }
        }
        $controller->index();
        break;

    case 'customers':
        require_once '../app/controllers/Admin/CustomerController.php';
        $controller = new CustomerController();
        if (isset($_GET['action'])) {
            $action = $_GET['action'];
            if (method_exists($controller, $action)) {
                $controller->$action();
                break;
            }
        }
        $controller->index();
        break;
        
    case 'staff':
        require_once '../app/controllers/Admin/StaffController.php';
        $controller = new StaffController();
        if (isset($_GET['action'])) {
            $action = $_GET['action'];
            if (method_exists($controller, $action)) {
                $controller->$action();
                break;
            }
        }
        $controller->index();
        break;
        
    case 'tables':
        require_once '../app/controllers/Admin/TableController.php';
        $controller = new TableController();
        $controller->index();
        break;

    case 'products':
        require_once '../app/controllers/Admin/ProductController.php';
        $controller = new ProductController();
        if (isset($_GET['action'])) {
            $action = $_GET['action'];
            if (method_exists($controller, $action)) {
                $controller->$action();
                break;
            }
        }
        $controller->index();
        break;
        
    case 'staff_dashboard':
        require_once '../app/controllers/Staff/DashboardController.php';
        $controller = new DashboardController();
        $controller->index();
        break;

    default:
        // 404
        http_response_code(404);
        echo "<h1>404 Not Found</h1><p>Route không tồn tại trong Admin Panel.</p>";
        break;
}
