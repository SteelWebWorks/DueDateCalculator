<?php

require_once 'src/bootstrap.php';
use Calculator\Calculator;

$dueTime = new Calculator();
echo $dueTime->CalculateDueTime('2023-09-18 12:12', '13');
