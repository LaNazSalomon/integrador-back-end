<?php

namespace App\Http\Controllers;

use App\Business\Interfaces\DatesInterface;
use App\Http\Requests\ReservationFindRoomRequest;
use App\Http\Requests\ReservationRequest;
use App\Models\Reservation;
use App\Models\Room;
use Exception;
use Symfony\Component\HttpFoundation\Response;

class ReservationController extends Controller
{
    //Este obj inyectado nos permite acceder a la logica del negocio
    public function __construct(protected DatesInterface $business) {}


    //Traemos todas las reservaciones mientras esten en el hotel indicado
    //Y el hotel indicado pertenezca a el usuario logeado
    public function show($hotelID)
    {
        try {
            $reservas = Reservation::join('hotels', 'reservations.hotel_id', '=', 'hotels.id')
                ->where('hotels.user_id', auth()->id())
                ->where('hotels.id', $hotelID)
                ->select('reservations.check_in','reservations.check_out', 'reservations.status','reservations.people_count')
                ->get();

            return response()->json($reservas, Response::HTTP_OK);
        } catch (Exception) {
            return response()->json(['error' => 'Ocurrio algo inesperado'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    //En esta parte crearesmo una reserva
    //TODO: Agregar soporte para el total (Cantidad a pagar en la reserva)
    public function store(ReservationRequest $request)
    {


        try {
            Reservation::create(

                $request->validated()
            );

            return response()->json(['message' => 'Reservacion exitosa'], Response::HTTP_CREATED);
        } catch (Exception) {
            return response()->json(['error' => 'Aglo no salio bien'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    //TODO: Esto va muy probablemente en Rooms
    //Vamos a buscar el tipo de habitacion solicitado para devolver todas aquellas habitaciones
    //Disponibles en las fechas solicitadas
    public function findRoomInReservations(ReservationFindRoomRequest $request)
    {
        //Vamos a irnos a la logica de negocio
        //Aqui tenemos las fechas que ocuparemos una habitacion
        $dates = $this->business->BusyDays($request->input('check_in'), $request->input('check_out'));
        //Aqui obtendremos el ID de una habitacion desocupada o 'Sin disponibilidad' o 'Sin existencias'"
        $roomID = $this->business->BusyDates($request->input('type'), $dates, $request->input('hotel_id'));

        if (!$roomID || $roomID === 'Sin disponibilidad' || $roomID === 'Sin existencias') {
            return response()->json(['messages' => (string)$roomID], Response::HTTP_NO_CONTENT);
        }

        $room = Room::find($roomID);

        if (!$room) {
            return response()->json(['message' => 'Habitación no encontrada.'], Response::HTTP_NOT_FOUND);
        }

        return response()->json(['room' => $room], Response::HTTP_OK);
    }
}
