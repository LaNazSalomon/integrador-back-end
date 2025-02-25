<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\ReservationDetail;
use Illuminate\Http\Request;

class ReservationDetailController extends Controller
{
    public function index()
    {
        $reservaciones = ReservationDetail::all();

        return response()->json($reservaciones);
    }

    public function store(Request $request)
    {
        // Validar los datos recibidos
        $validated = $request->validate([
            'rooms' => 'required|array',
            'fecha_reserva' => 'required|date',
            'status' => 'required|string',
            'payment_method' => 'required|string',
            'people_count' => 'required|integer',
        ]);

        // Crear un nuevo detalle de reserva con los datos validados
        $reservationDetail = ReservationDetail::create($validated);
        $id = $reservationDetail->_id;
        $this->saveReservation($id, $request->input('cliente_id'), $request->input('check_in'), $request->input('check_out'));

        return response()->json($reservationDetail->_id, 201);
    }

    private function saveReservation($id, $client_id, $check_in, $check_out)
    {
        Reservation::create([
            'cliente_id' => $client_id,
            'json_id' => $id,
            'check_in' => $check_in,
            'check_out' => $check_out
        ]);
    }
}
