<?php

namespace App\Helpers;

class HelperClass
{
    public static function dateConvert($dateTime = null)
    {
        if (isset($dateTime)) {
            return date('d-m-Y', strtotime($dateTime));
        } else {
            return $dateTime;
        }
    }
}
