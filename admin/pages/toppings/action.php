<?php
// admin/pages/toppings/action.php — Topping & Size
$action = get_param('action', '');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($action === 'storeTopping') {
        $name = post_param('name');
        $price = (float)post_param('price', 0);
        $status = isset($_POST['status']) ? 1 : 0;
        if (!empty($name)) {
            db()->execute("INSERT INTO toppings (name, price, status) VALUES (?, ?, ?)", [$name, $price, $status]);
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
            db()->execute("UPDATE toppings SET name = ?, price = ?, status = ? WHERE id = ?", [$name, $price, $status, $id]);
            set_flash('success', "Cập nhật Topping thành công!");
        }
        redirect(admin_url('toppings'));
    }

    if ($action === 'storeSize') {
        $name = post_param('name');
        $extraPrice = (float)post_param('extra_price', 0);
        $status = isset($_POST['status']) ? 1 : 0;
        if (!empty($name)) {
            db()->execute("INSERT INTO sizes (name, extra_price, status) VALUES (?, ?, ?)", [$name, $extraPrice, $status]);
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
            db()->execute("UPDATE sizes SET name = ?, extra_price = ?, status = ? WHERE id = ?", [$name, $extraPrice, $status, $id]);
            set_flash('success', "Cập nhật Size thành công!");
        }
        redirect(admin_url('toppings'));
    }
}

if ($action === 'deleteTopping') {
    $id = (int)get_param('id');
    if ($id) {
        db()->execute("DELETE FROM toppings WHERE id = ?", [$id]);
        set_flash('success', "Đã xóa Topping!");
    }
    redirect(admin_url('toppings'));
}

if ($action === 'deleteSize') {
    $id = (int)get_param('id');
    if ($id) {
        db()->execute("DELETE FROM sizes WHERE id = ?", [$id]);
        set_flash('success', "Đã xóa Size!");
    }
    redirect(admin_url('toppings'));
}

$toppings = db()->fetchAll("SELECT * FROM toppings ORDER BY id ASC");
$sizes = db()->fetchAll("SELECT * FROM sizes ORDER BY extra_price ASC");
$pageTitle = 'Quản lý Topping & Size';

