<?php

//Aqui declararemos todas las rutas de la App

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\HotelController;
use App\Http\Controllers\ReservationDetailController;
use App\Http\Controllers\RoomController;
use App\Models\Reservation;
use Illuminate\Support\Facades\Route;

//Ruta para registrarse en la pagina y tambien la de inicio de sesion
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

//Este es un grupo que esta protegido con un middleware para que los usuarios que no tengan
//Un token que es igual a una sesion iniciada no puedan entrar a estas rutas y obtener informacion
Route::middleware('jwt.auth')->group(function () {
    //Para obtener los datos del usuario y cerrar sesion, tambien para actualizar sus datos
    Route::get('datos-usuario', [AuthController::class, 'datos']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::put('user-update', [AuthController::class, 'update']);

    //Administracion de la parte de hoteles
    Route::apiResource('hotels', HotelController::class);

    //Administracion para las habitaciones, las habitaciones dependen de los hoteles
    Route::apiResource('rooms', RoomController::class);
    Route::get('get-room/{id}',[RoomController::class,'getRoom']);
    Route::get('rooms/get-all-rooms/{hotel}',[RoomController::class,'getAllRooms']);


    //Administracion de las reservas
    Route::apiResource('reservations', ReservationDetailController::class);
    //Funcion para buscar por tipo de habitacion pero en las reservas, esto para
    //Saber que habitaciones podemos usar
    Route::post('reservation/find-room',[ReservationDetailController::class,'findRoomInReservations']);

    //Administracion de los huespedes
    Route::apiResource('customercustom', CustomerController::class);

    Route::post('customer/find-by-email',[CustomerController::class,'findByEmail']);
    Route::get('customers/{id}',[CustomerController::class,'getCustomers']);
    Route::get('customer/{id}',[CustomerController::class,'getCustomer']);
    //Route::put('/customer/update/{id}', [CustomerController::class, 'update']);
    Route::post('customers/create',[CustomerController::class, 'create']);
});

Route::get('/test-db', function () {
    return response()->json([
        'users' => \DB::table('users')->count()
    ]);
});
