<?php

class Tree
{
    private $stack;
    private $root;
    private $x;
    private $y;
    private $z;
    private $vars = [
        'x',
        'y',
        'z'
    ];

    public function __construct()
    {
        $this->stack = new SplStack();
    }

    public function runCalculation($x = 0, $y = 0, $z = 0)
    {
        if (is_numeric($x) && is_numeric($y) && is_numeric($z)) {
            $this->x = $x;
            $this->y = $y;
            $this->z = $z;
            return $this->calculate(clone $this->root);
        }
        return 'not a number';
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
        if (preg_match('/[0-9a-zA-Z\.]/', $node->value)) {
            if (in_array($node->value, $this->vars, true)) {
                return $this->assignValue($node->value);
            }
            return $node->value;
        }

        if (preg_match('/[\+\-\*\/\^]/', $node->value)) {
            switch ($node->value) {
                case '+': {
                    return $this->calculate($node->right) + $this->calculate($node->left);
                }
                case '-': {
                    return $this->calculate($node->right) - $this->calculate($node->left);
                }
                case '*': {
                    return $this->calculate($node->right) * $this->calculate($node->left);
                }
                case '^': {
                    return $this->calculate($node->right) ** (int)$this->calculate($node->left);
                }
                case '/': {
                    try {
                        return $this->calculate($node->right) / $this->calculate($node->left);
                    } catch (ArithmeticError $e) {
                        exit('division by zero' . $e->getTraceAsString());
                    }
                }
            }
        }
    }

    private function assignValue($value)
    {
        switch ($value) {
            case 'x':
                return $this->x;
            case 'y':
                return $this->y;
            case 'z':
                return $this->z;
            default:
                exit('not accepted variable');
        }
    }
}