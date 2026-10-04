<?php
require 'app/config/database.php';
require 'app/models/Shift.php';
$m = new Shift();
print_r($m->getEmployeeShifts('2026-09-23', '2026-10-07'));
