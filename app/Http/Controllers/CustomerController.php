<?php

namespace App\Http\Controllers;

use App\Http\Requests\CustomerRequest;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class CustomerController extends Controller
{
    public function getCustomers(){
        //Devolvemos directamente todos los usuarios que peretenezcan a el usuario
        $customers = Customer::where("user_id", "=", auth() ->id())
        ->get();

        return response() -> json($customers);
    }

    //Funcion para crear clientes
    public function create(CustomerRequest $request){
        //Intenrartemos crear a un cliente
        try{
            $customer = Customer::create($request -> validated());
            return response() -> json(['customer' => $customer],Response::HTTP_CREATED);
        }catch(ValidationException $e){
            return response() -> json(['error' => $e], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
