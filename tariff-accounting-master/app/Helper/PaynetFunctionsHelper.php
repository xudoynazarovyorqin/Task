<?php


namespace App\Helper;


class PaynetFunctionsHelper
{
    // keyni qaytaradi
    public static function searchParamKeyForIdInMultidimensional($id = null, $array = []) {
        foreach ($array as $key => $val) {
            if ($val['paramKey'] == $id) {
                return $key;
            }
        }
        return null;
    }
}
