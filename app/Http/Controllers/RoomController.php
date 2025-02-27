<?php

namespace App\Http\Controllers;

use App\Http\Requests\RoomRequest;
use App\Http\Requests\UpdateRoomRequest;
use App\Models\Hotel;
use App\Models\Room;
use App\Models\User;
use Dotenv\Exception\ValidationException;
use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoomController extends Controller
{

    //Funcion para ver todas las habitaciones disponibles con un paginado
    public function show(Request $request, $hotel_id)
    {
        //SEgundo numero es por si no nos mandan nada en la url
        $registrosPorPagina = $request->query('regirtros', 15);
        //Guarda la ultima habitacion donde nos quedamos para continmuar en la siguiente pagina
        //Con la que sigue de esa, empieza en la cero
        $ultimaPagina = $request->query('utlima', 0);
        //Para saber despues de que registro continua
        $despuesDe = $ultimaPagina * $registrosPorPagina;

        //Mostraremos solo las habitaciones del usuyario que esta logeado
        //Hacemos un paginado
        $rooms = Room::skip($despuesDe)->take($registrosPorPagina)
            ->where(function ($query) use ($hotel_id) {
                // Verifica si el hotel pertenece al usuario actual
                if (Hotel::where('id', $hotel_id)->where('user_id', auth()->id())->exists()) {
                    $query->where('hotel_id', '=', $hotel_id);
                } else {
                    // Si no es del usuario, no debería retornar ninguna habitación
                    $query->whereRaw('1 = 0');  // Esto evita que se muestren resultados
                }
            })
            ->get();
        return response()->json($rooms);
    }

    //Funcion para ingresar habitaciones a la base de datos
    public function store(RoomRequest $request)
    {
        try {
            $data = $request->validated();
            Room::create($data);
            return response()->json(['message' => 'Habitacion creada correctamente', 'room' => $data]);
        } catch (ValidationException $e) {
            return response()->json(['error' => 'Ha ocurrido un error ' . $e], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    //Funcion para modificar alguna habitacion
    public function update(UpdateRoomRequest $request, Room $room)
    {
        //Intentaremos actualizar
        try {
            $data = $request->validated();
            $room->update($data);
            return response()->json(['message' => 'Actualizado correctamente', 'room' => $room], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json(['error' => $e], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
