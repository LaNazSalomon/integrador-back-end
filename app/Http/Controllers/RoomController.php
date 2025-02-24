<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;

class RoomController extends Controller
{

    //Funcion para ver todas las habitaciones disponibles con un paginado
    public function index(Request $request){
        //SEgundo numero es por si no nos mandan nada en la url
        $registrosPorPagina = $request->query('regirtros', 15);
        //Guarda la ultima habitacion donde nos quedamos para continmuar en la siguiente pagina
        //Con la que sigue de esa, empieza en la cero
        $ultimaPagina = $request -> query('utlima', 0);
        //Para saber despues de que registro continua
        $despuesDe = $ultimaPagina * $registrosPorPagina;

        $rooms = Room::skip($despuesDe) -> take($registrosPorPagina) -> get();
        return response() -> json($rooms);
    }
}
