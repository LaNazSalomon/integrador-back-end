<?php

namespace App\Business\Services;

use App\Business\Interfaces\DatesInterface;
use App\Models\ReservationDetail;
use App\Models\Room;
use Carbon\CarbonPeriod;

class ReservationsMadeService implements DatesInterface
{
    public function BusyDays($check_in, $check_out): array
    {
        //Declararemos una variable donde almacenar fechas
        //sera un arreglo
        $dates = [];

        $period = CarbonPeriod::create($check_in, $check_out);
        foreach ($period as $date) {
            $dates[] = $date->format('Y-m-d');
        }

        return $dates;
    }

    //En dates recibimos las fechas de entrada y salida
    //En type recibimos el tipo de habitacion
    //En hotel_id recibimos el ID del hotel donde pensamos hacer la reservacion
    public function BusyDates($type, $dates, $hotel_id)
    {
        //Crearemos una funcion que recibira el tipo de habitacion que es esta buscando
        //Obtendremos sus IDs
        $rooms = $this->idOfTypes($type, $hotel_id);

        //Calculamos las fechas en las que estaremos hospedados
        $this->BusyDays($dates[0], $dates[1]);


        //Si hay una habitacion de este tipo disponible pues hacemos la reserva ahi
        $idsHotelsAvailable = $this->findIDs($rooms, $hotel_id, $dates);
        //Devolvemos un arreglo con todas los IDs de las habitaciones disponibles
        return $idsHotelsAvailable;
    }


    //Vamos a devolver todas las habitaciones de el tipo que esta buscando el usuaro dentro
    //de el hotel en el que esta
    private function idOfTypes($type, $idHotel)
    {
        $rooms = Room::where('type', $type)
            ->where('hotel_id', $idHotel)
            ->pluck('id')
            ->flatten()
            ->toArray();


        return !empty($rooms) ? $rooms : null;
    }

    //Buscaremos en la DB NoSQL todos los IDs de las habitaciones que estamos buscando
    //Si alguna no esta, reservaremos esa
    //y si esta reservada pero los dias no coinciden con los que necesitamos tambien
    private function findIDs($ids, $idHotel, $dates): array
    {
        //Si IDs es null no vale la pena seguir buscando porque es obvio que no tenemos de esas habitaciones
        if(empty($ids)){
            return [0 => 'Sin existencias'];
        }
        //Obtenemos todas las habitaciones del mismo tipo que estan ocupadas
        $OccupiedRooms = ReservationDetail::where('hotel_id', $idHotel)
            ->whereIn('rooms', $ids)
            ->where('busy_days', 'elemMatch', ['$in' => $dates])
            ->pluck('rooms')
            ->flatten() //Esto unificara mas arreglos para no hacer subArregkis [[1,2],[3,2]] =>[1,2,3,2]
            ->unique()
            ->toArray();

        //Habitacviones deispobles
        $availableRooms = array_diff($ids, $OccupiedRooms);

        //Solo regresamos la primera
        return !empty($availableRooms) ? $availableRooms : null;
    }
}
