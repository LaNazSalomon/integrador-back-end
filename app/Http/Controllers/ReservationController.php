<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Illuminate\Http\Request;

class ReservationController extends Controller
{

    public function store(Request $request)
    {
        Reservation::create(
            [
                'cliente_id' => $request->input('cliente_id'),
                'json_id' => $request->input('json_id'),
            ]
        );
    }
}
