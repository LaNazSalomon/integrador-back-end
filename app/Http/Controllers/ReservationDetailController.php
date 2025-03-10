<?php

namespace App\Http\Controllers;

use App\Business\Interfaces\DatesInterface;
use App\Http\Requests\ReservationFindRoomRequest;
use App\Http\Requests\ReservationRequest;
use App\Models\Reservation;
use App\Models\ReservationDetail;
use App\Models\Room;
use Exception;
use Symfony\Component\HttpFoundation\Response;

class ReservationDetailController extends Controller
{

    //Usaremos el ocntructor para inyeccion de depenencias y no tener que declarar las clases en
    //cada funcion que usemos
    public function __construct(protected DatesInterface $dates){}

    public function show($id)
    {
        $reservaciones = ReservationDetail::all()
        ->
        where("hotel_id", "=", $id);

        return response()->json($reservaciones);
    }

    public function store(ReservationRequest $request)
    {
       // Crear un nuevo detalle de reserva con los datos validados
       $data = $request ->validated();


       //En esta parte hacemos un arreglo con las fechas de entrada y salida
       //gracias al objeto inyectado
       $data['busy_days']= $this->dates->BusyDays($request->input('check_in'),$request->input('check_out'));
       //Iremos a nuestro metodo para saber si las habitaciones que queremos estaran disponibles




        $reservationDetail = ReservationDetail::create($data);
        $id = $reservationDetail->_id;
        $this->saveReservation($request->input('customer_id'), $id);
        //dd($data);

        return response()->json($reservationDetail->_id, 201);
    }

    private function saveReservation($customer_id, $id)
    {
        Reservation::create([
            'cliente_id' => $customer_id,
            'json_id' => $id,
        ]);
    }


    //TODO: Esto va muy probablemente en Rooms
    //Vamos a buscar el tipo de habitacion solicitado para devolver todas aquellas habitaciones
    //Disponibles en las fechas solicitadas
    //TODO: HacerRequest personalizado para esta parte
    public function findRoomInReservations(ReservationFindRoomRequest $request){
       try{

        $dates = $this->dates->BusyDays($request->input('check_in'),$request->input('check_out'));
        $ids = $this->dates->BusyDates($request->input('type'),$dates,$request->input('hotel_id'));

        $numbersOfRoms = Room::whereIn('id', $ids)
        ->pluck('number')
        ->toArray();

        return response() -> json(['numbers' => $numbersOfRoms],Response::HTTP_OK);
       }catch(Exception $e){
        return response() ->json(['error' => 'Algo no salio bien '.$e],Response::HTTP_BAD_REQUEST);
       }
    }
}
