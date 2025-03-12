<?php

return [
    'paths' => ['api/*'], // Se aplica a las rutas que comiencen con "api/"
    'allowed_methods' => ['*'], // Permite todos los métodos HTTP
    'allowed_origins' => ['https://simulador-utvm.vercel.app'], // Cambia este valor al dominio de tu frontend
    'allowed_origins_patterns' => [],
    'allowed_headers' => ['*'], // Permite todos los headers
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => false, // Ponlo en true si necesitas enviar cookies
];
