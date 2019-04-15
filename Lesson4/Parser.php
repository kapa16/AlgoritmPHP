<?php

class Parser
{
    private $config = [
        '-' => ['priority' => '2', 'association' => 'left'],
        '+' => ['priority' => '2', 'association' => 'left'],
        '*' => ['priority' => '3', 'association' => 'left'],
        '/' => ['priority' => '3', 'association' => 'left'],
        '^' => ['priority' => '4', 'association' => 'right'],
    ];
    private $stack;
    private $buffer = [];

    public function __construct()
    {
        $this->stack = new SplStack();
    }

    public function run($str): array
    {
        $arr = $this->prepareString($str);
        return $this->parse($arr);
    }

    private function prepareString($str)
    {
        $str = preg_replace('/\s/', '', $str);
        $str = str_replace(',', '.', $str);
        $str = str_split($str);
        return $str;
    }

    private function pushOperation($value): void
    {
        while (true) {
            if ($this->stack->isEmpty()) {
                $this->stack->push($value);
                break;
            }

            $lastOperation = $this->stack->pop();
            $prevPriority = $this->config[$lastOperation]['priority'];
            $currentPriority = $this->config[$value]['priority'];
            $currentAssociation = $this->config[$value]['association'];

            if (($currentAssociation === 'left') && $currentPriority > $prevPriority) {
                $this->stack->push($lastOperation);
                $this->stack->push($value);
                break;
            }
            if (($currentAssociation === 'right') && $currentPriority >= $prevPriority) {
                $this->stack->push($lastOperation);
                $this->stack->push($value);
                break;
            }
            $this->buffer[] = $lastOperation;
        }
    }

    private function parse($arr): array
    {
        $lastSymbolIsNumber = true;
        foreach ($arr as $key => $value) {
            if (preg_match('/[\+\-\*\/\^]/', $value)) {
                $this->pushOperation($value);
                $lastSymbolIsNumber = false;
            }
            if (preg_match('/[0-9a-zA-Z\.]/', $value)) {
                if ($lastSymbolIsNumber) {
                    $this->buffer[] = array_pop($this->buffer) . $value;
                } else {
                    $this->buffer[] = $value;
                    $lastSymbolIsNumber = true;
                }
            }
            if ($value === '(') {
                $this->stack->push($value);
                $lastSymbolIsNumber = false;
            }
            if ($value === ')') {
                while (true) {
                    $symbol = $this->stack->pop();
                    if ($symbol === '(') {
                        break;
                    }
                    $this->buffer[] = $symbol;
                }
                $lastSymbolIsNumber = false;
            }
        }

        $length = $this->stack->count();
        for ($i = 0; $i < $length; $i++) {
            $this->buffer[] = $this->stack->pop();
        }
        return $this->buffer;
    }
}