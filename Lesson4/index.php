<?php

require './Parser.php';
require './Node.php';
require './Tree.php';

$parser = new Parser();
$tree = new Tree();

$str = '(x + 2) ^ 2 + 7 * y - z';
$arr = $parser->run($str);
$tree->buildTree($arr);
//echo '<pre>', print_r($tree), '</pre>';

echo $str;
$variables = ['x' => 2, 'y' => 1, 'z' => 10];
var_dump($variables);
echo '<br>результат = ' . $tree->runCalculation($variables);
$variables = ['x' => 3, 'y' => 5, 'z' => 20];
var_dump($variables);
echo '<br>результат = ' . $tree->runCalculation($variables);

echo '<hr>';
$parser2 = new Parser();
$tree2 = new Tree();

$str2 = 'x * 2 + 4 / (4 - 2) ^ 2 + 3';
$arr2 = $parser2->run($str2);

$tree2->buildTree($arr2);
//echo '<pre>', print_r($tree2), '</pre>';

echo $str2;
$variables = ['x' => 2];
var_dump($variables);
echo '<br>результат = ' . $tree2->runCalculation($variables);
$variables = ['x' => 3];
var_dump($variables);
echo '<br>результат = ' . $tree2->runCalculation($variables);


