<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class PasswordResetToken extends Model
{
    protected $table = 'password_reset_tokens';
    public $timestamps = false;
    protected $primaryKey = 'email';
    public $incrementing = false;

    protected $fillable = [
        'email',
        'token',
        'expires_at'
    ];

    protected $casts = [
        'expires_at' => 'datetime'
    ];

    /**
     * Verifica si el token ha expirado
     */
    public function isExpired(): bool
    {
        return Carbon::now()->gt($this->expires_at);
    }

    /**
     * Relación con el usuario
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'email', 'email');
    }
}
