<?php

namespace App\Business\Services;

use App\Business\Interfaces\DatesInterface;
use App\Models\Reservation;
use App\Models\Room;
use Carbon\CarbonPeriod;

class ReservationsMadeService implements DatesInterface
{

    // Esta función genera todas las fechas ocupadas entre check-in y check-out
    public function BusyDays($check_in, $check_out): array
    {
        $dates = [];
        $period = CarbonPeriod::create($check_in, $check_out);
        foreach ($period as $date) {
            $dates[] = $date->format('Y-m-d');
        }
        return $dates;
    }

    // Busca habitaciones disponibles en un hotel basándose en fechas y tipo de habitación
    public function BusyDates($type, $dates, $hotel_id)
    {
        // Obtener los IDs de las habitaciones del tipo especificado en el hotel
        $rooms = $this->idOfTypes($type, $hotel_id);
        // Si no hay habitaciones de ese tipo, devolvemos "sin existencias"
        if (empty($rooms)) {
            return ['Sin existencias'];
        }

        // Obtener las habitaciones disponibles
        return $this->findIDs($rooms, $dates);
    }

    // Devuelve los IDs de las habitaciones de cierto tipo en un hotel
    private function idOfTypes($type, $idHotel): array
    {
        return Room::where('type', $type)
            ->where('hotel_id', $idHotel)
            ->pluck('id')
            ->toArray();
    }

    // Busca habitaciones disponibles comparando con las fechas ocupadas
    private function findIDs(array $roomIDs, array $requestedDates): array | string
    {
        // Obtener todas las fechas ocupadas en las habitaciones de este tipo
        $occupiedDates = $this->datesOfRooms($roomIDs);

        //Si occupiedDates esta vacio o null eso significa que todas las habitaciones estan disponibles
        if(empty($occupiedDates)){
            return $roomIDs;
        }
        //Sacaremos los valores en caso de haber, todos los IDs de roomIDs que no esten en occupiedDates
        $idsRoomsAviables = array_diff($roomIDs, array_keys($occupiedDates));
        //Si hay habitaciones no reservadas las regresamos automaticamente
        if(!empty($idsRoomsAviables)){
            return $idsRoomsAviables;
        }

        // Filtrar habitaciones que NO tengan intersección de fechas
        foreach ($occupiedDates as $roomID => $dates) {
            //Verificamos que no haya insercciones
            //Inserciiones con las fechas solicitadas y las ocuopadas
            if (empty(array_intersect($dates, $requestedDates))) {
                return $roomID;
            }
        }

        return 'Sin disponibilidad';
    }

    // Devuelve un array con los IDs de habitaciones como llaves y sus fechas ocupadas como valores
    private function datesOfRooms(array $roomIDs): array
    {
        $allDates = [];
        $dates = Reservation::whereIn('room_id', $roomIDs)
            ->select('room_id', 'check_in', 'check_out')
            ->orderBy('room_id')
            ->get();


        foreach ($dates as $reservation) {
            $allDates[$reservation->room_id] = array_merge(
                $allDates[$reservation->room_id] ?? [],
                $this->BusyDays($reservation->check_in, $reservation->check_out)
            );
        }
        return $allDates;
    }
}
