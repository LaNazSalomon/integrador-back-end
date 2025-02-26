<?php
 namespace App\Business\Interfaces;

 interface DatesInterface{
    //Dias ocupados pero en ingles, mas mamador (Ando aprendiendo XD)
    public function BusyDays($check_in, $check_out): Array;
 }
