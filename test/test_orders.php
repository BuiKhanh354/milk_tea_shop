<?php
require 'app/config/database.php';
require 'app/models/Order.php';
$model = new Order();
$orders = $model->getCustomerOrders(4);
print_r($orders);
