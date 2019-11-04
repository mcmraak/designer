<?php

require_once __DIR__.'/classes/Designer.php';

$designer = new Classes\Designer;
$designer->load();

echo $designer->name .'<br>';
echo $designer->surname .'<br>';
echo $designer->avatar.'<br>';
echo $designer->Calc(1).'<br>';
echo $designer->Calc(2).'<br>';
echo $designer->Calc(3).'<br>';

var_dump($designer->Calc(2));