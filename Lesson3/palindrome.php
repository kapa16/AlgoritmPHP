<?php

function testPalindrome(array $arr = []): bool
{
    if (count($arr) <= 1) {
        return true;
    }
    if (array_shift($arr) === array_pop($arr)) {
        testPalindrome($arr);
    } else {
        return false;
    }
}

function palindrome(string $str = ''): bool
{
    $str = mb_strtolower($str);
    $strArr = explode('', $str);
    return testPalindrome($strArr);
}

echo palindrome('тест');
echo palindrome('nhyyhn');