<?php

namespace App\Http\Controllers;

use App\Business\Interfaces\DatesInterface;
use App\Http\Requests\ReservationRequest;
use App\Models\Reservation;
use App\Models\ReservationDetail;

class ReservationDetailController extends Controller
{

    //Usaremos el ocntructor para inyeccion de depenencias y no tener que declarar las clases en
    //cada funcion que usemos
    public function __construct(protected DatesInterface $dates){}

    public function index()
    {
        $reservaciones = ReservationDetail::all();

        return response()->json($reservaciones);
    }

    public function store(ReservationRequest $request)
    {
       // Crear un nuevo detalle de reserva con los datos validados
       $data = $request ->validated();

       //En esta parte hacemos un arreglo con las fechas de entrada y salida
       //gracias al objeto inyectado
       $data['busy_days']= $this->dates->BusyDays($request->input('check_in'),$request->input('check_out'));




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
}
