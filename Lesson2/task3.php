<?php

$arr = [];
for ($i = 0; $i < 30; $i++) {
    $arr[] = mt_rand(0, 1000);
}

function merge($leftArr, $rightArr)
{
    $totalCount = count($leftArr) + count($rightArr);

    $left = 0;
    $right = 0;
    $leftArr[] = INF;
    $rightArr[] = INF;

    $arr = [];
    for ($i = 0; $i < $totalCount; $i++) {
        if ($leftArr[$left] <= $rightArr[$right]) {
            $arr[] = $leftArr[$left++];
        } else {
            $arr[] = $rightArr[$right++];
        }
    }

    return $arr;
}

function mergeSort($arr)
{
    if (count($arr) <= 1) {
        return $arr;
    }

    $middle = (int)(count($arr) / 2);
    return merge(mergeSort(array_slice($arr, 0, $middle)), mergeSort(array_slice($arr, $middle)));
}

var_dump(mergeSort($arr));