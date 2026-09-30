<?php
class OptionController {
    public function index() {
        $pageTitle = 'Tùy chọn (Sugar & Ice)';
        
        require_once __DIR__ . '/../../models/ItemOption.php';
        $optionModel = new ItemOption();
        $sugarOptions = $optionModel->getSugarOptions(true);
        $iceOptions = $optionModel->getIceOptions(true);
        
        ob_start();
        require_once __DIR__ . '/../../views/admin/options/index.php';
        $content = ob_get_clean();

        require_once __DIR__ . '/../../views/layouts/admin.php';
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once __DIR__ . '/../../models/ItemOption.php';
            $optionModel = new ItemOption();
            
            $data = [
                'type' => $_POST['type'] ?? 'sugar',
                'name' => $_POST['name'] ?? '',
                'value' => (int)($_POST['value'] ?? 0),
                'is_default' => isset($_POST['is_default']) ? 1 : 0,
                'status' => isset($_POST['status']) ? 1 : 0
            ];
            
            $optionModel->create($data);
            header('Location: admin.php?route=options&msg=created');
            exit;
        }
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once __DIR__ . '/../../models/ItemOption.php';
            $optionModel = new ItemOption();
            
            $id = (int)$_POST['id'];
            $data = [
                'type' => $_POST['type'] ?? 'sugar',
                'name' => $_POST['name'] ?? '',
                'value' => (int)($_POST['value'] ?? 0),
                'is_default' => isset($_POST['is_default']) ? 1 : 0,
                'status' => isset($_POST['status']) ? 1 : 0
            ];
            
            $optionModel->update($id, $data);
            header('Location: admin.php?route=options&msg=updated');
            exit;
        }
    }

    public function delete() {
        if (isset($_GET['id'])) {
            require_once __DIR__ . '/../../models/ItemOption.php';
            $optionModel = new ItemOption();
            
            $id = (int)$_GET['id'];
            $optionModel->delete($id);
            
            header('Location: admin.php?route=options&msg=deleted');
            exit;
        }
    }
}
