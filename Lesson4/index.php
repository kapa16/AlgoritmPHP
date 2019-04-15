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
echo '<br>для x = 2, y = 1, z = 10 равно = ' . $tree->runCalculation(2, 1, 10);
echo '<br>для x = 3, y = 5, z = 20 равно = ' . $tree->runCalculation(3, 5, 20);

echo '<hr>';
$parser2 = new Parser();
$tree2 = new Tree();

$str2 = 'x * 2 + 4 / (4 - 2) ^ 2 + 3';
$arr2 = $parser2->run($str2);

$tree2->buildTree($arr2);
//echo '<pre>', print_r($tree2), '</pre>';

echo $str2;
echo '<br>для x = 2 равно = ' . $tree2->runCalculation(2);
echo '<br>для x = 3 равно = ' . $tree2->runCalculation(3);