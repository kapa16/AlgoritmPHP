<?php

class Tree
{
    private $stack;
    private $root;
    private $x;
    private $y;
    private $z;
    private $vars = [
        "x",
        "y",
        "z"
    ];

    public function __construct()
    {
        $this->stack = new SplStack();
    }

    public function runCalculation($x = 0, $y = 0, $z = 0)
    {
        //если нам ввели числа, производим рассчёт
        if (is_numeric($x) && is_numeric($y) && is_numeric($x)) {
            $this->x = $x;
            $this->y = $y;
            $this->z = $z;
            //возвращаем расчёт
            return $this->calculate($this->root);
        //иначе говорим, что получили не число
        } else {
            return "not a number";
        }
    }

    public function buildTree($arr)
    {
        //получили массив
        foreach ($arr as $item) {
            //вставляем элементы в массив
            $this->insert($item);
        }
        //
        $this->root = $this->stack->pop();
        return $this->root;
    }
    
    private function insert($item)
    {
        //если вставляемый в массив элемент - число или переменная
        if (preg_match("/[0-9a-zA-Z\.]/", $item)) {
            //тогда пушим его как новую ноду и даём ей имя, равное переданному элементу
            $this->stack->push(new Node($item));
        //а если это операци
        } elseif (preg_match("/[\+\-\*\/\^]/", $item)) {
            //тогда достаём два последних значения
            $leftNode = $this->stack->pop();
            $rightNode = $this->stack->pop();
            //и создаём новую ноду
            //этой ноде мы передаём качестве потомков элементы, которые извлекли
            $this->stack->push(new Node($item, $leftNode, $rightNode));
        }
    }

    //рассчитываем значение выражения
    private function calculate(Node &$node)
    {
        //если у нас число или переменная
        if (preg_match("/[0-9a-zA-Z\.]/", $node->value)) {
            //если это всё таки переменная, а не число
            if (in_array($node->value, $this->vars)) {
                //получаем значение этой перменной
                return $this->assignValue($node->value);
            }
            //а если у нас чило, просто возвращаем его
            return $node->value;
        //иначе, если у нас операция
        } elseif (preg_match("/[\+\-\*\/\^]/", $node->value)) {
            //смотрим какая у нас операция
            //ну и так как при построении дерева, мы записывали для операций
            //в качестве наследников переменные, просто получаем их,
            //и произодим необходимую операцию
            switch ($node->value) {
                case "+": {
                    return $this->calculate($node->right) + $this->calculate($node->left);
                }
                case "-": {
                    return $this->calculate($node->right) - $this->calculate($node->left);
                }
                case "*": {
                    return $this->calculate($node->right) * $this->calculate($node->left);
                }
                case "^": {
                    return pow($this->calculate($node->right), (int)$this->calculate($node->left));
                }
                case "/": {
                    try {
                        return $this->calculate($node->right) / $this->calculate($node->left);
                    //если делим на ноль, возвращаем ошибку
                    } catch (ArithmeticError $e) {
                        exit("division by zero" . $e->getTraceAsString());
                    }
                }
            }
        }
    }

    private function assignValue($value)
    {
        //просто получаем наши переменные, если такие есть
        switch ($value) {
            case "x":
                return $this->x;
            case "y":
                return $this->y;
            case "z":
                return $this->z;
            default:
                exit("not accepted variable");
        }
    }
}