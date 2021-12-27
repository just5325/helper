<?php

require_once './vendor/autoload.php';

use Hcg\HelperArray\HelperArray as HelperArray;

// 将二维数组变成指定key的三维数组
$arr1 = [
    ['id' => 1, 'type' => 1, 'name' => '黄翠刚'],
    ['id' => 2, 'type' => 1, 'name' => '黄翠刚'],
    ['id' => 3, 'type' => 2, 'name' => '黄翠刚'],
    ['id' => 4, 'type' => 2, 'name' => '黄翠刚'],
    ['id' => 5, 'type' => 2, 'name' => '黄翠刚'],
    ['id' => 6, 'type' => 4, 'name' => '黄翠刚'],
];
$arr2 = HelperArray::buildArrByGroupKey($arr1, 'type');
var_dump($arr2);