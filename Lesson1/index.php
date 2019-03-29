<?php

$path = $_POST['path'] ?: __DIR__;

$iterator = new DirectoryIterator($path);

while ($iterator->valid()) {
    var_dump($iterator);
    if ($iterator->isDir()) {
        echo "<a href='task1.php?path={$iterator->getPathname()}'>{$iterator->getFilename()}</a><br>";
    } else {
        echo "<p>{$iterator->getFilename()}</p><br>";
    }
    $iterator->next();
}