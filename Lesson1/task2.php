<?php

//------------------Итератор

$arr = array_fill(0, 1000000, 123);
$iterator= new ArrayIterator($arr);

$start = microtime(true);

while ($iterator->valid()) {
    $some = $iterator->current();
    $iterator->next();
}

$stop = microtime(true);

echo 'iterator: ' . ($stop - $start) . PHP_EOL;

//------------------массив

$arr = array_fill(0, 1000000, 123);


$start = microtime(true);

foreach ($arr as $key => $value) {
    $some = $key . $value;
}

$stop = microtime(true);

echo 'foreach ($arr as $key => $value): ' . ($stop - $start) . PHP_EOL;


//------------------массив значение

$arr = array_fill(0, 1000000, 123);


$start = microtime(true);

foreach ($arr as $value) {
    $some = $value;
}

$stop = microtime(true);

echo 'foreach ($arr as $value): ' . ($stop - $start) . PHP_EOL;

