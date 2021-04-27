<?php

namespace App\Helper;

class MonthNameInRussian
{

    public static function monthNameToRussian($month)
    {
        $months = array(
            'Jan' => 'январь', 'Feb' => 'февраль', 'Mar' => 'март', 'Apr' => 'апрель',
            'May' => 'май', 'Jun' => 'июнь', 'Jul' => 'июль', 'Aug' => 'август',
            'Sep' => 'сентябрь', 'Oct' => 'октябрь', 'Nov' => 'ноябрь', 'Dec' => 'декабрь',
        );

        return $months[$month];
    }

    public static function monthNameToRussianWithCapitalLetter($month)
    {
        $months = array(
            'Jan' => 'Январь', 'Feb' => 'Февраль', 'Mar' => 'Март', 'Apr' => 'Апрель',
            'May' => 'Май', 'Jun' => 'Июнь', 'Jul' => 'Июль', 'Aug' => 'Август',
            'Sep' => 'Сентябрь', 'Oct' => 'Октябрь', 'Nov' => 'Ноябрь', 'Dec' => 'Декабрь',
        );

        return $months[$month];
    }

}
