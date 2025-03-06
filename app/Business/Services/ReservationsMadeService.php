<?php
namespace App\Business\Services;

use App\Business\Interfaces\DatesInterface;
use App\Models\ReservationDetail;
use App\Models\Room;
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

    public function BusyDates($type, $dates, $hotel_id){
        //Primero vamos a la DB relacional por todas las habitaciones que esten en nuestro
        //Hotel y sean del tipo que quiere reservar el usuario
        $rooms = Room::where('hotel_id','=',$hotel_id)
        ->where('type','=',$type)
        ->pluck('id');

        return $rooms;
    }
}
