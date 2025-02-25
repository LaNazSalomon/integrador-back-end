<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class ReservationDetail extends Model
{
    protected $connection = 'integrador';

     // Definir los campos que serán accesibles
     protected $fillable = [
        'cliente_id',
        'rooms',  // Este campo sera un array de habitaciones
        'fecha_reserva',
        'status',
        'payment_method',
        'people_count',
    ];

    protected $casts = [
        'rooms' => 'array',
        'fecha_reserva' => 'datetime',
    ];

}
