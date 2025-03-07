<?php

namespace App\Http\Controllers;

use App\Http\Requests\CustomerRequest;
use App\Http\Requests\CustomerUpdateRequest;
use App\Models\Customer;
use Exception;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class CustomerController extends Controller
{


    public function show($hotel_id)
    {
        //Devolvemos todos los huespedes del hotel y verificamos que el hotel sea del usuario
        $customers = Customer::join('hotels', 'customers.hotel_id', 'hotels.id')
            ->select('customers.*')
            ->where('hotels.user_id', auth()->id())
            ->where('hotels.id', $hotel_id)
            ->get();


        return response()->json($customers);
    }

    //Funcion para crear clientes
    public function store(CustomerRequest $request)
    {
        //Intenrartemos crear a un cliente
        try {
            $customer = Customer::create($request->validated());
            return response()->json(['customer' => $customer], Response::HTTP_CREATED);
        } catch (ValidationException $e) {
            return response()->json(['error' => $e], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function edit($id)
    {
        $customer = Customer::where('id', $id)
            ->get();
        return response()->json($customer);
    }

    public function update(CustomerUpdateRequest $request, Customer $customer)
    {
        try {

            $data = $request->only('name', 'last_name', 'email');
            $customer -> update($data);

            return response()->json(['response' => 'Actualizado correctamente'], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json(['error' => 'No se pudo actualizar'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
