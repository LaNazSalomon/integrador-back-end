<?php

//Aqui declararemos todas las rutas de la App

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HotelController;
use App\Http\Controllers\RoomController;
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
});
