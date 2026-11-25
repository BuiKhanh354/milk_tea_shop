<?php
// admin/pages/toppings/action.php — Topping & Size (OOP)
$action = get_param('action', '');
$toppingModel = new Topping();
$sizeModel = new Size();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($action === 'storeTopping') {
        $name = post_param('name');
        $price = (float)post_param('price', 0);
        $status = isset($_POST['status']) ? 1 : 0;
        if (!empty($name)) {
            $toppingModel->store($name, $price, $status);
            set_flash('success', "Đã thêm Topping mới!");
        }
        redirect(admin_url('toppings'));
    }

    if ($action === 'updateTopping') {
        $id = (int)post_param('id');
        $name = post_param('name');
        $price = (float)post_param('price', 0);
        $status = isset($_POST['status']) ? 1 : 0;
        if ($id && !empty($name)) {
            $toppingModel->update($id, $name, $price, $status);
            set_flash('success', "Cập nhật Topping thành công!");
        }
        redirect(admin_url('toppings'));
    }

    if ($action === 'storeSize') {
        $name = post_param('name');
        $extraPrice = (float)post_param('extra_price', 0);
        $status = isset($_POST['status']) ? 1 : 0;
        if (!empty($name)) {
            $sizeModel->store($name, $extraPrice, $status);
            set_flash('success', "Đã thêm Size mới!");
        }
        redirect(admin_url('toppings'));
    }

    if ($action === 'updateSize') {
        $id = (int)post_param('id');
        $name = post_param('name');
        $extraPrice = (float)post_param('extra_price', 0);
        $status = isset($_POST['status']) ? 1 : 0;
        if ($id && !empty($name)) {
            $sizeModel->update($id, $name, $extraPrice, $status);
            set_flash('success', "Cập nhật Size thành công!");
        }
        redirect(admin_url('toppings'));
    }
}

if ($action === 'deleteTopping') {
    $id = (int)get_param('id');
    if ($id) {
        $toppingModel->deleteById($id);
        set_flash('success', "Đã xóa Topping!");
    }
    redirect(admin_url('toppings'));
}

if ($action === 'deleteSize') {
    $id = (int)get_param('id');
    if ($id) {
        $sizeModel->deleteById($id);
        set_flash('success', "Đã xóa Size!");
    }
    redirect(admin_url('toppings'));
}

$toppings = $toppingModel->all('id ASC');
$sizes = $sizeModel->all('extra_price ASC');
$pageTitle = 'Quản lý Topping & Size';
