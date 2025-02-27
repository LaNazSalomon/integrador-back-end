<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $fillable = [
        'hotel_id',
        'number',
        'type',
        'price',
        'description',
        'status',

    ];

    public function hotel(){
        return $this->belongsTo(Hotel::class);
    }

    public function reservation(){
        return $this -> hasMany(Reservation::class);
    }
}
