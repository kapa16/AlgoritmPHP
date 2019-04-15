<?php

class Parser
{
    private $operationPriority = [
        '-' => 2,
        '+' => 2,
        '*' => 3,
        '/' => 3,
        '^' => 4,
    ];
    private $stack;
    private $buffer = [];

    public function __construct()
    {
        $this->stack = new SplStack();
    }

    public function run($str): array
    {
        $arr = $this->prepareStringToArray($str);
        return $this->parse($arr);
    }

    private function prepareStringToArray($str)
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
            $prevPriority = $this->operationPriority[$lastOperation];
            $currentPriority = $this->operationPriority[$value];

            if ($currentPriority >= $prevPriority) {
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
            if (preg_match('/[0-9a-zA-Z\.]/', $value)) {
                if ($lastSymbolIsNumber) {
                    $this->buffer[] = array_pop($this->buffer) . $value;
                } else {
                    $this->buffer[] = $value;
                }
                $lastSymbolIsNumber = true;
                continue;
            }
            $lastSymbolIsNumber = false;
            if (preg_match('/[\+\-\*\/\^]/', $value)) {
                $this->pushOperation($value);
            }
            if ($value === '(') {
                $this->stack->push($value);
            }
            if ($value === ')') {
                do {
                    $symbol = $this->stack->pop();
                    $this->buffer[] = $symbol;
                } while ($symbol !== '(');
            }
        }

        $length = $this->stack->count();
        for ($i = 0; $i < $length; $i++) {
            $this->buffer[] = $this->stack->pop();
        }
        return $this->buffer;
    }
}