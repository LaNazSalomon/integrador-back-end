<?php
namespace App\Business\Services;

use App\Business\Interfaces\DatesInterface;
use Carbon\CarbonPeriod;

class ReservationsMadeService implements DatesInterface
{
    public function BusyDays($check_in, $check_out): Array{
        //DEclararemos una variable donde almacenar fechas
        //sera un arreglo
        $dates = [];


        $period = CarbonPeriod::create($check_in, $check_out);
        foreach($period as $date){
            $dates[] = $date -> format('Y-m-d');
        }

        return $dates;
    }

    public function BusyDates($type, $dates){

    }
}
