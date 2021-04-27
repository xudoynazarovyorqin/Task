<?php


namespace App\Helper;


class NumberHelper
{
    public static function round_up($value, $precision = 2) {
        $pow = pow ( 10, $precision );
        return ( ceil ( $pow * $value ) + ceil ( $pow * $value - ceil ( $pow * $value ) ) ) / $pow;
    }
}
