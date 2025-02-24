<?php

namespace App\Http\Controllers;

use App\Http\Requests\HotelRequest;
use App\Models\Hotel;
use Illuminate\Support\Facades\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class HotelController extends Controller
{

    //Devuelve solo los hoteles que son del usuario actual
    public function index(){
        $user_id = auth() -> id();
        $hotel = Hotel::where('user_id', $user_id)
        -> get();

        return response() -> json([$hotel]);
    }


    //De acurdo a la documentacion de laravel cuando se usa apiResource ya tiene metodos asignados para
    //cada uno de los verbos https://laravel.com/docs/11.x/controllers

    //Este es el metodo para agregar un nuevo hotel
    public function store(HotelRequest $request){
        //Intentarmos crear un hotel
        try{
            $hotel = Hotel::create($request -> validated());
            return response() -> json($hotel);
        }catch(ValidationException $e){
            return response() -> json(['error' => $e -> errors()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

    }
}
