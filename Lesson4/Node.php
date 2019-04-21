<?php

class Node
{
    public $value;
    public $left;
    public $right;

    public function __construct($value = null, Node $left = null, Node $right = null)
    {
        $this->value = $value;
        $this->right = $right;
        $this->left = $left;
    }
}