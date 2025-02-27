<?php
 namespace App\Business\Interfaces;

 interface DatesInterface{
    //Dias ocupados pero en ingles, mas mamador (Ando aprendiendo XD)
    public function BusyDays($check_in, $check_out): Array;

    //Esta funcion permitira comparar fechas para saber si las habitaciones estan ocupadas
    //o no
    public function BusyDates($type, $dates);
 }
