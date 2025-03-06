<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class ReservationDetail extends Model
{
    protected $connection = 'integrador';

     // Definir los campos que serán accesibles
     protected $fillable = [
        'customer_id',
        'rooms',  // Este campo sera un array de habitaciones
        'reservation_date',
        'check_in',
        'check_out',
        'status',
        'payment_method',
        'people_count',
        'busy_days',
    ];

    protected $casts = [
        'rooms' => 'array',
        'fecha_reserva' => 'datetime',
    ];

}
