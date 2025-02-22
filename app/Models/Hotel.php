<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Hotel extends Model
{
    use HasFactory, Notifiable;

    //Todos los campos que permitimos sean rellenables de forma masiva
    protected $fillable = [
        'user_id',
        'name',
    ];


    //Estamos indicando a quien prestences, en este caoso un hotel pertenece a un uaurio
    public function user(){
        return $this -> belongsTo(User::class);
    }

    public function rooms(){
        return $this-> hasMany(Room::class);
    }
}
