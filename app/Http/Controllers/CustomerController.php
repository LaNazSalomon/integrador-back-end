<?php

namespace App\Http\Controllers;

use App\Http\Requests\CustomerRequest;
use App\Http\Requests\CustomerUpdateRequest;
use App\Http\Requests\FindCustomerByIDRequest;
use App\Models\Customer;
use Exception;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class CustomerController extends Controller
{


    //Muestra todos los huedespdes del hotel
    public function getCustomers($hotel_id)
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
    public function create(CustomerRequest $request)
    {
        //Intenrartemos crear a un cliente
        try {
            $customer = Customer::create($request->validated());
            return response()->json(['customer' => $customer], Response::HTTP_CREATED);
        } catch (ValidationException $e) {
            return response()->json(['error' => $e], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    //Muestra un solo huesped
    public function getCustomer($id)
    {
        $customer = Customer::where('id', $id)
            ->get();
        return response()->json($customer);
    }


    //Actualiza
    public function update(CustomerUpdateRequest $request, Customer $customercustom)
    {
        try {
            $data = $request->validated();
            $customercustom->update($data);
            return response()->json(['response' => 'El huésped se actualizó correctamente.'], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json(['error' => 'No se pudo actualizar' . $e], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    //Buscaremos un usuario por el correo electronico
    public function findByEmail(FindCustomerByIDRequest $request)
    {
        try {
            $customer = Customer::where('hotel_id', $request->input('hotel'))
                ->where('email', $request->input('email'))
                ->get();

            return response()->json($customer, Response::HTTP_ACCEPTED);
        } catch (Exception $e) {
            return response()->json(['error' => 'Ha ocurrido algo inesperado'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
