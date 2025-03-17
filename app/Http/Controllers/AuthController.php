<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\RegisterUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

//Este controlador es el encargado de getionar los registros e inicios de sesion
//implementando jwt
class AuthController extends Controller
{
    public function register(RegisterUserRequest $request)
    {
        $datosValidados = $request->validated();

        //Merge de datos, los del modelo toman los del areglo de datos ya validados
        User::create([
            'name' => $datosValidados['name'],
            'email' => $datosValidados['email'],
            'password' => Hash::make($datosValidados['password'])
        ]);

        return response()->json(['message' => 'usuario creado exitosamente'], Response::HTTP_CREATED);
    }

    public function login(LoginUserRequest $request)
    {
        $datosValidados = $request->validated();

        $credenciales = [
            'email' => $datosValidados['email'],
            'password' => $datosValidados['password']
        ];

        //Intentando hacer la autentificacion con JWT
        try {

            //Intentando que se cree, esto devuievle un bool si se pudo o no
            //El metodo attempt es de jwt
            if (!$token = JWTAuth::attempt($credenciales)) {
                return response()->json(['error' => 'El correo o la contraseña son invalidos'], Response::HTTP_UNAUTHORIZED);
            }
        } catch (JWTException $e) {
            return response()->json(['message' => 'No se pudo validar el token: '.$e], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        //Si se logro crear el token es que no quedo dentro del if ni genero algun erro para ser
        //cachado, entonces el paso siguiente es devolverlo
        return response()->json(['token' => $token, 'usuario' => auth()->user()]);
    }

    //Funcion para retornar los datos que tiene el token
    //user por alguna extraña razon el editor la marca como que no existe pero ignramos eso
    public function datos()
    {
        return response()->json(['data' => auth()->user()]);
    }

    public function update(UpdateUserRequest $request)
    {
        //Obtenemos el usuario autenticado
        $user = auth()->user();

        //Solo actualizar estos datos, la conreaseña es aparte, esto para encriptarla
        $data = $request->only(['name', 'email']);

        //Si el usuario manda la contraseña que se encripte con la funcion que ya trae laravel
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request['password']);
        }

        //Hacemos la actualizacion
        $user->update($data);

        return response()->json(['message' => 'Datos actualizados correctamente', 'user' => $user], Response::HTTP_OK);
    }

    //Funcion para cerrar sesion
    public function logout()
    {
        try {
            //Cerrado de sesion
            //1.- Obtenemos token
            $token = JWTAuth::getToken();
            //2.- Invalidar token
            JWTAuth::invalidate($token);
            return response()->json(['message' => 'Sesion cerrada correctamente']);
        } catch (JWTException $e) {
            return response()->json(['error' => 'Token invalido '], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        //Nos falta la parte de refescar token, esto lo haremos mas adelante, si nos da
        //timempo XD, lo estamos haciendo el 23/02/2025

    }
}
