<?php
$prices = [
    [
        'price' => 21999,
        'shop_name' => 'Shop 1',
        'shop_link' => 'http://'
    ],
    [
        'price' => 21550,
        'shop_name' => 'Shop 2',
        'shop_link' => 'http://'
    ],
    [
        'price' => 21950,
        'shop_name' => 'Shop 2',
        'shop_link' => 'http://'
    ],
    [
        'price' => 21350,
        'shop_name' => 'Shop 2',
        'shop_link' => 'http://'
    ],
    [
        'price' => 21050,
        'shop_name' => 'Shop 2',
        'shop_link' => 'http://'
    ]
];

//Оценка алгоритма
//Присваивание не учитываем, делается мгновенно
function ShellSort($elements) {
    $k=0;                                           //1 шаг
    $length = count($elements);                     //1 шаг
    $gap[0] = (int) ($length / 2);                  //1 шаг

    while($gap[$k] > 1) {                           //количество проходов цикла - log(n)-1
        $k++;                                       //на кождом проходе выполняется 3 операции: сравнение, инкремент, деление
        $gap[$k]= (int)($gap[$k-1] / 2);            //итого на цикл 3f(n) шагов
    }                                               //Для заданных данных 3 шага

    for($i = 0; $i <= $k; $i++){                    //количество проходов log(n) - для заданных данных 2 прохода, на цикл 4 шага
        $step = $gap[$i];

        for($j = $step; $j < $length; $j++) {       //от n/2 до n-1 шагов (сравнение и инкремент - 2 шага) - для заданных данных 3 и 4 прохода
            $temp = $elements[$j];
            $p = $j - $step;                        //1 шаг вычитание

            while($p >= 0 && $temp['price'] < $elements[$p]['price']) {     //Худший случай n-2  - для заданных данных от 0 до 2 проходов всего 5
                $elements[$p + $step] = $elements[$p];
                $p = $p - $step;                    //1 шаг
            }

            $elements[$p + $step] = $temp;
        }
    }

    return $elements;
}

ShellSort($prices);
//Задание 1
//Итого шагов: 3 + 3f(n) + log(n) + (n-1) + 1 + (n-2) + 1
//По заданным данным - 3 + 3 + 4 + 7*3 + 5*2 = 41 шаг

//Задание 2
//Сложность алгоритма O(3) + O(3(log(n)-1)) + O(log(n)) * O(n-1) * O(n-2)
//Константы опускаем
//O(log(n) + O(n^2*log(n))
//Итого худший случай
//O(n^2*log(n))
