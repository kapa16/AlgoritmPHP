<?php
$connect = mysqli_connect('localhost', 'root', '', 'alg');

$result = mysqli_query($connect, <<<SQL
SELECT id, `title`, lft, rgt FROM tree ORDER BY lft
SQL
);

$categories = [];
if (mysqli_num_rows($result) > 0) {
    while ($element = mysqli_fetch_assoc($result)) {
        $categories[$element['lft']] = $element;
    }
}

function buildTree(array $categories, $left = 1): string
{
    $tree = '<ul>';

    $tree .= $categories[$left];

    $tree .='</ul>';
    return $tree;
}

echo buildTree($categories);