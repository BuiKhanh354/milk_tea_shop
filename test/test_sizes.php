<?php require 'app/config/database.php'; require 'app/models/Product.php'; $m = new Product(); print_r($m->getSizes());
