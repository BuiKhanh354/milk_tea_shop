<?php
session_start();
require_once __DIR__ . '/../app/config/database.php';


// Lấy route từ URL, mặc định là dashboard
$route = $_GET['route'] ?? 'dashboard';

// Các route KHÔNG cần đăng nhập
$publicRoutes = ['login', 'logout'];

if (!in_array($route, $publicRoutes)) {
    if (!isset($_SESSION['user_id'])) {
        header("Location: index.php?route=login");
        exit;
    }
    
    // Authorization: Nếu là Staff, không cho phép truy cập các trang của Admin
    if ($_SESSION['role'] === 'staff') {
        $staffAllowedRoutes = ['staff_dashboard', 'orders', 'logout', 'tables', 'inventory', 'inventory_import', 'inventory_export', 'profile', 'shifts', 'my-shifts', 'account'];
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

    case 'shifts':
        $dir = ($_SESSION['role'] === 'staff') ? 'Staff' : 'Admin';
        require_once "../app/controllers/$dir/ShiftController.php";
        $controller = new ShiftController();
        if (isset($_GET['action'])) {
            $action = $_GET['action'];
            if (method_exists($controller, $action)) {
                $controller->$action();
                break;
            }
        }
        $controller->index();
        break;

    case 'profile':
        $dir = ($_SESSION['role'] === 'staff') ? 'Staff' : 'Admin';
        require_once "../app/controllers/$dir/ProfileController.php";
        $controller = new ProfileController();
        if (isset($_GET['action'])) {
            $action = $_GET['action'];
            if (method_exists($controller, $action)) {
                $controller->$action();
                break;
            }
        }
        $controller->index();
        break;

    case 'dashboard':
        require_once '../app/controllers/Admin/DashboardController.php';
        $controller = new DashboardController();
        $controller->index();
        break;
        
    case 'orders':
        $dir = ($_SESSION['role'] === 'staff') ? 'Staff' : 'Admin';
        require_once "../app/controllers/$dir/OrderController.php";
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
        $dir = ($_SESSION['role'] === 'staff') ? 'Staff' : 'Admin';
        require_once "../app/controllers/$dir/InventoryController.php";
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
        $dir = ($_SESSION['role'] === 'staff') ? 'Staff' : 'Admin';
        require_once "../app/controllers/$dir/InventoryController.php";
        $controller = new InventoryController();
        $controller->import();
        break;
        
    case 'inventory_export':
        $dir = ($_SESSION['role'] === 'staff') ? 'Staff' : 'Admin';
        require_once "../app/controllers/$dir/InventoryController.php";
        $controller = new InventoryController();
        $controller->export();
        break;

    case 'inventory_history':
        $dir = ($_SESSION['role'] === 'staff') ? 'Staff' : 'Admin';
        require_once "../app/controllers/$dir/InventoryController.php";
        $controller = new InventoryController();
        $controller->history();
        break;

    case 'inventory_report':
        $dir = ($_SESSION['role'] === 'staff') ? 'Staff' : 'Admin';
        require_once "../app/controllers/$dir/InventoryController.php";
        $controller = new InventoryController();
        $controller->report();
        break;

    case 'inventory_alerts':
        $dir = ($_SESSION['role'] === 'staff') ? 'Staff' : 'Admin';
        require_once "../app/controllers/$dir/InventoryController.php";
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
        $dir = ($_SESSION['role'] === 'staff') ? 'Staff' : 'Admin';
        require_once "../app/controllers/$dir/TableController.php";
        $controller = new TableController();
        if (isset($_GET['action'])) {
            $action = $_GET['action'];
            if (method_exists($controller, $action)) {
                $controller->$action();
                break;
            }
        }
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

    case 'reports':
        require_once '../app/controllers/Admin/ReportController.php';
        $controller = new ReportController();
        if (isset($_GET['action']) && method_exists($controller, $_GET['action'])) {
            $action = $_GET['action'];
            $controller->$action();
        } else {
            $controller->index();
        }
        break;

    case 'options':
        require_once '../app/controllers/Admin/OptionController.php';
        $controller = new OptionController();
        if (isset($_GET['action']) && method_exists($controller, $_GET['action'])) {
            $action = $_GET['action'];
            $controller->$action();
        } else {
            $controller->index();
        }
        break;

    case 'toppings':
    case 'sizes':
        require_once '../app/controllers/Admin/ToppingSizeController.php';
        $controller = new ToppingSizeController();
        if (isset($_GET['action']) && method_exists($controller, $_GET['action'])) {
            $action = $_GET['action'];
            $controller->$action();
        } else {
            $controller->index();
        }
        break;

    case 'settings':
        require_once '../app/controllers/Admin/SettingController.php';
        $controller = new SettingController();
        if (isset($_GET['action']) && method_exists($controller, $_GET['action'])) {
            $action = $_GET['action'];
            $controller->$action();
        } else {
            $controller->index();
        }
        break;

    case 'my-shifts':
        require_once '../app/controllers/Staff/MyShiftController.php';
        $controller = new MyShiftController();
        $controller->index();
        break;

    case 'account':
        $dir = ($_SESSION['role'] === 'staff') ? 'Staff' : 'Admin';
        require_once "../app/controllers/$dir/ProfileController.php";
        $controller = new ProfileController();
        if (isset($_GET['action']) && method_exists($controller, $_GET['action'])) {
            $action = $_GET['action'];
            $controller->$action();
        } else {
            $controller->index();
        }
        break;

    default:
        // 404
        http_response_code(404);
        echo "<h1>404 Not Found</h1><p>Route không tồn tại trong Admin Panel.</p>";
        break;
}
