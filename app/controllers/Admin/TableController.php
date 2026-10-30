<?php
require_once __DIR__ . '/../../models/Table.php';

class TableController
{

    public function index()
    {
        $pageTitle = 'Quản lý Bàn';

        $tableModel = new TableModel();
        $tables = $tableModel->getAll();

        ob_start();
        require_once __DIR__ . '/../../views/admin/tables/index.php';
        $content = ob_get_clean();

        require_once __DIR__ . '/../../views/layouts/admin.php';
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_csrf('admin.php?route=tables');
            $tableNumber = filter_input(INPUT_POST, 'table_number', FILTER_DEFAULT);
            $capacity = filter_input(INPUT_POST, 'capacity', FILTER_VALIDATE_INT);

            if ($tableNumber && $capacity) {
                $tableModel = new TableModel();
                if ($tableModel->existsByTableNumber($tableNumber)) {
                    header('Location: admin.php?route=tables&error=duplicate&table=' . urlencode($tableNumber));
                    exit;
                }
                $tableModel->create([
                    'table_number' => $tableNumber,
                    'capacity' => $capacity,
                    'status' => 'available'
                ]);
            }

            header('Location: admin.php?route=tables&msg=created');
            exit;
        }
    }

    public function show()
    {
        $id = $_GET['id'] ?? 0;
        if (!$id) {
            header('Location: admin.php?route=tables');
            exit;
        }

        $pageTitle = 'Chi tiết Bàn';

        $tableModel = new TableModel();
        $table = $tableModel->findById($id);

        if (!$table) {
            header('Location: admin.php?route=tables');
            exit;
        }

        ob_start();
        require_once __DIR__ . '/../../views/admin/tables/show.php';
        $content = ob_get_clean();

        require_once __DIR__ . '/../../views/layouts/admin.php';
    }



    public function update_status()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_csrf('admin.php?route=tables');
            $id = $_POST['id'] ?? 0;
            $status = $_POST['status'] ?? '';

            if ($id && in_array($status, ['available', 'occupied', 'reserved'])) {
                $tableModel = new TableModel();
                $tableModel->updateStatus($id, $status);
            }

            header('Location: admin.php?route=tables&action=show&id=' . $id);
            exit;
        }
    }
}
