<?php
class Controller {
    // Gọi model
    public function model($model) {
        if(file_exists(__DIR__ . '/../models/' . $model . '.php')){
            require_once __DIR__ . '/../models/' . $model . '.php';
            return new $model();
        }
        return false;
    }

    // Gọi view với layout
    public function view($view, $data = [], $layout = 'main') {
        // Biến $data có thể được truy xuất trong view
        extract($data);

        // Đường dẫn tới view cụ thể
        $viewPath = __DIR__ . '/../views/' . $view . '.php';

        // Gọi layout, trong layout sẽ nhúng $viewPath
        if ($layout !== false && file_exists(__DIR__ . '/../views/layouts/' . $layout . '.php')) {
            require_once __DIR__ . '/../views/layouts/' . $layout . '.php';
        } else {
            // Render trực tiếp view nếu không có layout
            if (file_exists($viewPath)) {
                require_once $viewPath;
            }
        }
    }
}
