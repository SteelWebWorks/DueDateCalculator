<?php

require_once __DIR__ . '/src/bootstrap.php';
use Calculator\Calculator;

$dueTime = new Calculator();

echo $dueTime->CalculateDueTime('2023-09-18 12:12', '13') . "\n";
echo $dueTime->CalculateDueTime('2023-09-14 12:12', 168);
