<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmailRequest;
use App\Business\Mail\StructureMessage;
use App\Business\Mail\KeyMessage;
use App\Business\Mail\Messages;
use App\Http\Requests\KeyPasswordRequest;
use App\Http\Requests\PasswordRequest;
use App\Models\PasswordResetToken;
use App\Models\User;
use Exception;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Psy\Command\WhereamiCommand;
use Symfony\Component\HttpFoundation\Response;

class EmailController extends Controller
{
    public function sendEmail(EmailRequest $request)
    {
        $data = $request->validated();
        $userEmail = $data['email'];

        // Verificar si ya existe un token reciente (15 minutos)
        $recentToken = PasswordResetToken::where('email', $userEmail)
            ->where('expires_at', '>', Carbon::now())
            ->first();

        if ($recentToken) {
            return response()->json([
                'message' => 'Ya se ha enviado un código recientemente. Por favor revisa tu correo.'
            ], Response::HTTP_TOO_MANY_REQUESTS);
        }

        // Generar nuevo token
        $rawToken = KeyMessage::KEY();
        $hashedToken = $rawToken;

        // Crear o actualizar registro en la base de datos
        PasswordResetToken::updateOrCreate(
            ['email' => $userEmail],
            [
                'token' => $hashedToken,
                'expires_at' => Carbon::now()->addMinutes(15)
            ]
        );

        //contenido del email
        $subject = "Codigo de recuperacion - Simulador de hospedaje";
        $body = Messages::RESETPASSWORD($rawToken);
        $altBody = 'Tu código de recuperación es: ' . $rawToken;

        // Enviar email usando tu StructureMessage
        try
        {
            StructureMessage::MESSAGE($userEmail, $altBody, $body, $subject);

        return response()->json([
            'message' => 'Se ha enviado el código de recuperación a tu correo electrónico',
            'status' => 'success'
        ]);
        }catch(Exception)
        {
            return response() -> json(['error' => 'No fue posible enviar el correo']);
        }
    }

    public function resetPassword(KeyPasswordRequest $request)
    {
        $data = $request->validated();
        $userToken = $data['token'];
        $userEmail = $data['email'];

        // Buscar token en la base de datos
        $token = PasswordResetToken::where('token', '=', $userToken)
            ->where('expires_at', '>', Carbon::now())
            ->where('email', '=', $userEmail)
            ->first();


        if (!$token) {
            return response()->json([
                'message' => 'El código de recuperación es inválido o ha expirado.'
            ], Response::HTTP_BAD_REQUEST);
        }

        return response()->json([
            'message' => 'Código de recuperación válido.'
        ]);
    }

    //Funcion para actualizar la contrasena
    public function updatePassword(PasswordRequest $request)
    {
        //Datos validados del request
        $data = $request->validated();

        //Verificaremos nuevamente que se nos envie el token correcto
        $token = PasswordResetToken::where('token', '=', $data['token'])
            ->where('email', '=', $data['email'])
            ->first();

        if (!$token) {
            return response()->json([
                'message' => 'El código de recuperación es inválido o ha expirado.'
            ], Response::HTTP_BAD_REQUEST);
        }

        //Obtenemos al usuario que coincide con el correo para poder actualizar la contrasena
        $user = User::where('email', $data['email'])
            ->first();

        //Si el usuario manda la contraseña que se encripte con la funcion que ya trae laravel
        $data['password'] = Hash::make($request['password']);

        try {

            //Hacemos la actualizacion
            $user->update($data);

            return response()->json(['message' => 'Contraseña actualizada correctamente', 'user' => $user], Response::HTTP_OK);
        } catch (Exception) {
            return response()->json(['error' => 'No se pudo actualizar la contraseña'], Response::HTTP_BAD_REQUEST);
        }
    }
}
