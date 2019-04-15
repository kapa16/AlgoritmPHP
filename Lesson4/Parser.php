<?php

class Parser
{
    private $config = [
        "-" => ["priority" => "2", "association" => "left"],
        "+" => ["priority" => "2", "association" => "left"],
        "*" => ["priority" => "3", "association" => "left"],
        "/" => ["priority" => "3", "association" => "left"],
        "^" => ["priority" => "4", "association" => "right"]
    ];
    private $stack;
    private $buffer;

    public function __construct()
    {
        $this->stack = new SplStack();
        $this->buffer = [];
    }

    //запустили парсинг
    public function run($str)
    {
        $arr = $this->prepareString($str);
        return $this->parse($arr);
    }

    // подготовили строку
    private function prepareString($str)
    {
        $str = preg_replace("/\s/", "", $str);
        $str = str_replace(",", ".", $str);
        $str = str_split($str);
        return $str;
    }

    //пушим элемент, в соответствии с некоторыми условиями
    private function pushOperation($value)
    {
        while (true) {
            if ($this->stack->isEmpty()) {
                //если массив пуст, пушим первое значение и выходим из цикла
                $this->stack->push($value);
                break;
            } else {
                //смотрим какая была последняя операция, извлекаем её
                $lastOperation = $this->stack->pop();
                //берём ёё приоритет
                $prevPriority = $config[$lastOperation]["priority"];
                //берём нынешнюю операцию её приоритет
                $currentPriority = $config[$value]["priority"];
                //соответственно узнаём куда будем добавлять
                $currentAssociation = $config[$value]["association"];
                //если налево(все операции кроме степени)
                if ($currentAssociation === "left") {
                    //если приоритет текущего действия выше предыдущего
                    if ($currentPriority > $prevPriority) {
                        //добавляем в стэк операцию, которую извлекли
                        $this->stack->push($lastOperation);
                        //и после неё добавляем нынешнюю и выходим из цикла
                        $this->stack->push($value);
                        break;
                    //если у нас была степень до этого, а сейчас умножение или деление, или сложение, или вычитание, или снова степень
                    //или если у нас было умножение, а теперь, снова умножение или деление или вычитание или сложение
                    } else {
                        //иначе откладываем эту операцию во временное хранилище
                        //и при новом проходе берём предшевствовавший ему элемент
                        //сравниваем с нынешним и если его приоритет снова выше
                        //либо равен текущему, снова бросаем в буфер
                        $this->buffer[] = $lastOperation;
                    }
                //если пушим направо(степень)
                } elseif ($currentAssociation === "right") {
                    //если приоритет текущего действия выше предыдущего либо такой же
                    if ($currentPriority >= $prevPriority) {
                        //пушим назад предыдущий элемент
                        $this->stack->push($lastOperation);
                        //за ним пушим текущий элемент и выходим из цикла
                        $this->stack->push($value);
                        break;
                    } else {
                        //иначе откладываем эту операцию во временное хранилище
                        //и при новом проходе берём предшевствовавший ему элемент
                        //сравниваем с нынешним и если его приоритет снова выше
                        //снова бросаем в буфер
                        $this->buffer[] = $lastOperation;
                    }
                }
            }
        }
    }

    private function parse($arr)
    {
        //начинаем перебор массива
        //изначально ожидаем получить число
        $lastSymbolIsNumber = true;
        foreach ($arr as $key => $value) {
            //сверяем с паттерном "/[\+\-\*\/\^]/", и если подходит
            //а это значит, что у нас не число
            if (preg_match("/[\+\-\*\/\^]/", $value)) {
                //пушим текущую операцию
                $this->pushOperation($value);
                //и делаем себе пометку, что у нас была операция
                $lastSymbolIsNumber = false;
            //проверяем на число или букву(то есть переменнную)
            } elseif (preg_match("/[0-9a-zA-Z\.]/", $value)) {
                //смотрим, а до этого было число или нет и если число
                if ($lastSymbolIsNumber) {
                    //тогда нынешнее число добавляем в буфер(не в стэк) склеивая с предыдущим
                    //смысл в том, что когда мы разбивали наше выражение, числа, которые там могли быть
                    //тоже разбились, например 234 превратилось в 3 элемента массива: 2, 3, 4
                    //и здесь мы просто снова собираем это число
                    $this->buffer[] = array_pop($this->buffer) . $value;
                //если предыдущее было не число
                } else {
                    //нынешнее добавляем в буфер
                    $this->buffer[] = $value;
                    //и запоминаем, что мы добавили именно число
                    $lastSymbolIsNumber = true;
                }
            //возможно у нас не число не оператор, а левая скобка
            } elseif ($value == "(") {
                //тогда пушим скобку
                $this->stack->push($value);
                //и запоминаем, что это не скобка
                $lastSymbolIsNumber = false;
            //если другая скобка, то всё
            } elseif ($value == ")") {
                while (true) {
                    //достаём последний символ в стопке
                    $symbol = $this->stack->pop();
                    //и если он не открывающая скобка, кладём в буфер
                    if ($symbol != "(") {
                        $this->buffer[] = $symbol;
                    //а если открывающая скобка, то просто закончили добавлять всё в буфер
                    //таким образом в буфер попало только выражение из скобок без самих скобок
                    } else {
                        break;
                    }
                }
                //и так как закончили мы за открывающей скобке,
                //то говорим себе что оно было не числом
                $lastSymbolIsNumber = false;
            }
        }

        //после того как мы прошлись всем символам
        //узнаём какой длины оказался стэк
        //вне стэка(в буфере) у нас всё что было в скобках
        //а так же всё что было с меньшим приоритетом 
        $length = $this->stack->count();
        for ($i = 0; $i < $length; $i++) {
            //и теперь сам стэк добавляем в буфер
            $this->buffer[] = $this->stack->pop();
        }
        //получаем наш отсортированный массив
        return $this->buffer;
    }
}
//добавление в буфер дало нам то, что в самом начале
//теперь раполагаются самые высокоприоритетные операции