<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
        'hotel_id',
        'name',
        'last_name',
        'email',
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function reservation(){
        return $this ->hasMany(Reservation::class);
    }

    public function hotel(){
        return $this -> belongsTo(Hotel::class);
    }
}
