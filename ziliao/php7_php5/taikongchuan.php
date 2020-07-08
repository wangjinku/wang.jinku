<?php
$x = 10;
$y = 100;
$z = $x <=> $y;
var_dump($z);

$z = $y <=> $x;
var_dump($z);

$v = 10;
$z = $x <=>$v;
var_dump($z);
