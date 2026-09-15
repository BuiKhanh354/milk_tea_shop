<?php
require 'app/models/User.php';
$userModel = new User();
$adminUser = $userModel->authenticate('admin', '123456');
var_dump($adminUser);

$staffUser = $userModel->authenticate('staff01', '123456');
var_dump($staffUser);
