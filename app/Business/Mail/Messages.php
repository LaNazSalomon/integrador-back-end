<?php

namespace App\Business\Mail;

use App\Business\Mail\KeyMessage;

class Messages
{
    public static function RESETPASSWORD($rawToken)
    {
        return '
            <!DOCTYPE html>
            <html lang="es">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Recuperación de Contraseña</title>
                <style>
                    body {
                        font-family: Arial, sans-serif;
                        background-color: #f4f4f4;
                        margin: 0;
                        padding: 0;
                        display: flex;
                        justify-content: center;
                        align-items: center;
                        min-height: 100vh;
                    }
                    .email-container {
                        max-width: 600px;
                        width: 100%;
                        background-color: #ffffff;
                        border-radius: 12px;
                        overflow: hidden;
                        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
                        text-align: center;
                    }
                    .email-header {
                        background-color: #007bff;
                        color: #ffffff;
                        padding: 30px 20px;
                    }
                    .email-header h1 {
                        margin: 0;
                        font-size: 28px;
                        font-weight: bold;
                    }
                    .email-body {
                        padding: 30px 20px;
                        color: #333333;
                    }
                    .email-body h2 {
                        font-size: 24px;
                        margin-bottom: 20px;
                        color: #007bff;
                    }
                    .email-body p {
                        font-size: 16px;
                        line-height: 1.6;
                        margin-bottom: 20px;
                    }
                    .token {
                        display: inline-block;
                        background-color: #007bff;
                        color: #ffffff;
                        padding: 15px 30px;
                        border-radius: 8px;
                        font-size: 20px;
                        font-weight: bold;
                        margin: 20px 0;
                        text-decoration: none;
                        transition: background-color 0.3s ease;
                    }
                    .token:hover {
                        background-color: #0056b3;
                    }
                    .email-footer {
                        background-color: #f1f1f1;
                        padding: 20px;
                        font-size: 14px;
                        color: #666666;
                    }
                    .email-footer a {
                        color: #007bff;
                        text-decoration: none;
                        font-weight: bold;
                    }
                    .email-footer a:hover {
                        text-decoration: underline;
                    }
                </style>
            </head>
            <body>
                <div class="email-container">
                    <!-- Encabezado del correo -->
                    <div class="email-header">
                        <h1>Recuperación de Contraseña</h1>
                    </div>

                    <!-- Cuerpo del correo -->
                    <div class="email-body">
                        <h2>Hola,</h2>
                        <p>
                            Hemos recibido una solicitud para restablecer tu contraseña. Utiliza el siguiente código
                            para completar el proceso:
                        </p>
                        <div class="token">' . $rawToken . '</div>
                        <p>
                            Si no solicitaste este cambio, puedes ignorar este mensaje. Tu contraseña permanecerá segura.
                        </p>
                        <p>
                            Gracias,<br>
                            El equipo de soporte.
                        </p>
                    </div>

                    <!-- Pie de página del correo -->
                    <div class="email-footer">
                        <p>
                            Si tienes alguna pregunta, no dudes en contactarnos en
                            <a href="mailto:' . env('MAIL_FROM_ADDRESS') . '">' . env('MAIL_FROM_ADDRESS') . '</a>.
                        </p>
                    </div>
                </div>
            </body>
            </html>
        ';
    }
}
