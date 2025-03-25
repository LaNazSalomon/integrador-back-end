<?php

namespace App\Business\Mail;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
use App\Business\Mail\KeyMessage;
use App\Business\Mail\Messages;
use Illuminate\Support\Env;
use Symfony\Component\HttpFoundation\Response;

class StructureMessage
{
    public static function MESSAGE($userEmail, $altBody, $HTMLBody, $subject)
    {
        //Create an instance; passing `true` enables exceptions
        $mail = new PHPMailer(true);

        try {
            //Server settings
            $mail->SMTPDebug = 0;                      //Enable verbose debug output Es para ver toda la informacion -> SMTP::DEBUG_SERVER
            $mail->isSMTP();                                            //Send using SMTP
            $mail->Host       = env('MAIL_HOST');                     //Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
            $mail->Username   = env('MAIL_FROM_ADDRESS');                     //SMTP username
            $mail->Password   = env('MAIL_PASSWORD');                               //SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
            $mail->Port       = env('MAIL_PORT');                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

            //Recipients
            $mail->setFrom(env('MAIL_FROM_ADDRESS'), 'ADMINISTRACION-DEPARTAMENTO TI');
            $mail->addAddress($userEmail);     //Add a recipient


            //Content
            $mail->isHTML(true);                                  //Set email format to HTML
            $mail->Subject = ($subject);
            $mail->Body    = ($HTMLBody);
            $mail->AltBody = ($altBody);

            $mail->send();

            return response() -> json(['message' => 'Se envió la clave de recuperación a tu correo.'], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json(['error' => 'No se pudo enviar el correo'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
