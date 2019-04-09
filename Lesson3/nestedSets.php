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

function buildTree(array $categories, $key = 1, $left = 1): string
{
    $tree = '<ul>';
var_dump($tree);
    if (count($categories) < $key) {
        $tree .='</ul>';
        return $tree;
    }
    if (!empty($categories[$key])) {
        buildTree($categories, $key + 1, $left -1);
    }
    $tree .= '<li>' . $categories[$key]['title'] . '</li>';


}

echo buildTree($categories);