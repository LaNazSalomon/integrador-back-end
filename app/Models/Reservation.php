<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $fillable = [
        'hotel_id',
        'customer_id',
        'room_id',  // Este campo sera un array de habitaciones
        'check_in',
        'check_out',
        'status',
        'payment_method',
        'people_count',
    ];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
