<?php

namespace App\Http\Controllers;

use App\Http\Requests\RoomRequest;
use App\Http\Requests\UpdateRoomRequest;
use App\Models\Room;
use Dotenv\Exception\ValidationException;
use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoomController extends Controller
{

    //Funcion para ver todas las habitaciones disponibles con un paginado
    public function show(Request $request, $hotel_id)
    {
        $registrosPorPagina = $request->query('registros', 3);
        $pagina = $request->query('pagina', 0);

        $offset = $pagina * $registrosPorPagina;

        $query = Room::where('hotel_id', $hotel_id)
                   ->whereHas('hotel', function($q) {
                       $q->where('user_id', auth()->id());
                   });

        $total = $query->count();

        $rooms = $query->skip($offset)
                     ->take($registrosPorPagina)
                     ->get();

        return response()->json([
            'rooms' => $rooms,
            'total' => $total
        ]);
    }

    //Funcion para ingresar habitaciones a la base de datos
    public function store(RoomRequest $request)
    {
        try {
            $data = $request->validated();
            Room::create($data);
            return response()->json(['message' => 'Habitacion creada correctamente', 'room' => $data], Response::HTTP_OK);
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

    //Funcion para obtener una sola habitacion
    //Minetras este en nuestro hotel
    public function getRoom($id){
        $room = Room::join('hotels','rooms.hotel_id', '=', 'hotels.id')
        ->where('hotels.user_id', auth() -> id())
        ->where('rooms.id', $id)
        ->get(['rooms.*']);


        return response() -> json($room);
    }

    //Para obtener todas las habitaciones, bueno solo obtendermos su tipo
    //TODO: Buscar una mejor implementacion
    public function getAllRooms($hotel){
        $types = Room::where('hotel_id', $hotel)
        ->pluck('type')
        ->unique();

        return response() -> json(['types' => $types]);
    }

    public function destroy($id){
        $reservation = Room::find($id);

        if(!$reservation){
            return response() -> json(['error' => 'Habitacion no encontrada'], Response::HTTP_NOT_FOUND);
        }

        $reservation -> delete();

        return response() -> json(['message' => 'Habitacion eliminada correctamente'],
        Response::HTTP_OK);
    }
}
