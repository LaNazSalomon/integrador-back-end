<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $fillable = [
        'cliente_id',
        'room_id',
        'check_in',
        'check_out'
    ];

    public function room(){
        return $this->belongsTo(Room::class);
    }

    public function customer(){
        return $this -> belongsTo(Customer::class);
    }

}
