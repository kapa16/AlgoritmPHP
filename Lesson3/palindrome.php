<?php

function testPalindrome(array $arr = []): string
{
    if (count($arr) <= 1) {
        return 'Полиндром';
    }
    if (array_shift($arr) === array_pop($arr)) {
        return testPalindrome($arr);
    }
    return 'Не полиндром';
}

function palindrome(string $str = ''): string
{
    $str = mb_strtolower($str);
    $strArr = preg_split('//', $str, -1, PREG_SPLIT_NO_EMPTY);
    return $str . ' - ' . testPalindrome($strArr) . '<br>';
}

echo palindrome('palindrome');
echo palindrome('Racecar');
echo palindrome('Rotator');
echo palindrome('Redder');