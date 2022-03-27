<?php

namespace YG\Phalcon\Utils;

class PhoneFormatter
{
    static public function get(?string $data): string
    {
        if ($data == null)
            return '';

        $num = preg_replace('/[^0-9]/', '', $data);
        $len = strlen($num);

        if($len == 7) $num = preg_replace('/([0-9]{2})([0-9]{2})([0-9]{3})/', '$1 $2 $3', $num);
        elseif($len == 8) $num = preg_replace('/([0-9]{3})([0-9]{2})([0-9]{3})/', '$1 - $2 $3', $num);
        elseif($len == 9) $num = preg_replace('/([0-9]{3})([0-9]{2})([0-9]{2})([0-9]{2})/', '$1 - $2 $3 $4', $num);
        elseif($len == 10) $num = preg_replace('/([0-9]{3})([0-9]{3})([0-9]{2})([0-9]{2})/', '0($1)$2 $3 $4', $num);

        return $num;
    }
}