<?php

//Aqui declararemos todas las rutas de la App

use App\Http\Requests\AuthController;
use Illuminate\Support\Facades\Route;

//Ruta para registrarse en la pagina y tambien la de inicio de sesion
Route::post('register',[AuthController::class, 'register']);

Route::middleware('jwt.auth') -> group(function()
{

});
