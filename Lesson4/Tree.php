<?php

class Tree
{
    private $stack;
    private $root;
    private $variables;

    public function __construct()
    {
        $this->stack = new SplStack();
    }

    public function runCalculation(array $variables = [])
    {
        foreach ($variables as $value) {
            if (!is_numeric($value)) {
                return 'Variable not a number';
            }
        }
        $this->variables = $variables;
        return $this->calculate(clone $this->root);

    }

    public function buildTree($arr)
    {
        foreach ($arr as $item) {
            $this->insert($item);
        }
        $this->root = $this->stack->pop();
        return $this->root;
    }

    private function insert($item): void
    {
        if (preg_match('/[0-9a-zA-Z\.]/', $item)) {
            $this->stack->push(new Node($item));
        } elseif (preg_match('/[\+\-\*\/\^]/', $item)) {
            $leftNode = $this->stack->pop();
            $rightNode = $this->stack->pop();
            $this->stack->push(new Node($item, $leftNode, $rightNode));
        }
    }

    private function calculate(Node $node)
    {
        if (preg_match('/[0-9\.]/', $node->value)) {
            return $node->value;
        }

        if (preg_match('/[a-zA-Z]/', $node->value)) {
            return $this->variables[$node->value] ?? 0;
        }

        if (preg_match('/[\+\-\*\/\^]/', $node->value)) {
            $left = $this->calculate($node->left);
            $right = $this->calculate($node->right);

            switch ($node->value) {
                case '+':
                    return $right + $left;
                case '-':
                    return $right - $left;
                case '*':
                    return $right * $left;
                case '^':
                    return $right ** (int)$left;
                case '/':
                    try {
                        return $right / $left;
                    } catch (ArithmeticError $e) {
                        exit('division by zero' . $e->getTraceAsString());
                    }
            }
        }
    }

}