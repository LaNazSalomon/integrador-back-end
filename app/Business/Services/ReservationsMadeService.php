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

<<<<<<< HEAD
    public function BusyDates($type, $dates, $hotel_id){
        //Primero vamos a la DB relacional por todas las habitaciones que esten en nuestro
        //Hotel y sean del tipo que quiere reservar el usuario
        $rooms = Room::where('hotel_id','=',$hotel_id)
        ->where('type','=',$type)
        ->pluck('id');

        return $rooms;
=======
    public function BusyDates($type, $dates){
        //Crearemos una funcion que recibira el tipo de habitacion que es esta buscando
        //Un arreglo con todas las fechas de reserva de ese tipo de habitacion en ese hotel
        //Para saber si esta disponible o no alguna habitacion
>>>>>>> 8e836c90f260ebb43d65e0b434f461f7567e49c3
    }
}
