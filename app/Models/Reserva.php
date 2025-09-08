<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Reserva extends Model
{
    use HasFactory;

    protected $fillable = [
        'boleto_id',
        'nombre',
        'apellido',
        'telefono',
        'estado', // <-- agregado
        'expira_en',
    ];

    protected $dates = ['expira_en'];

    public function boleto()
    {
        return $this->belongsTo(Boleto::class);
    }

    // Saber si ya expiró
    public function isExpired()
    {
        return $this->expira_en->isPast();
    }
}
