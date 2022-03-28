<?php

namespace YG\Phalcon\Utils;

class DateFormatter
{
    static public function dateFormat(?string $date): string
    {
        if ($date == '')
            return '';

        return date('d.m.Y', strtotime($date));
    }

    static public function datetimeFormat(?string $date): string
    {
        if ($date == '')
            return '';

        return date('d.m.Y H:i', strtotime($date));
    }
}