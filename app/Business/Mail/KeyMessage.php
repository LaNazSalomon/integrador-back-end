<?php

namespace App\Business\Mail;

class KeyMessage
{


    public static function KEY()
    {
        $longitud = 10;
        $iniSubstaccion = 0;
        return substr(bin2hex(random_bytes($longitud)), $iniSubstaccion, $longitud);
    }
}
