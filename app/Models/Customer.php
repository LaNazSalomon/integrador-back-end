<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
        'name',
        'last_name',
        'email',
        'password',
    ];

    public function reservation(){
        return $this ->hasMany(Reservation::class);
    }
}
