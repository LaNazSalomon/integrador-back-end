<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
        'user_id',
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
}
