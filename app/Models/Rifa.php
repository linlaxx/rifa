<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rifa extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre', 
        'descripcion', 
        'fotos', 
        'precio_boleto', 
        'total_boletos', 
        'estado'
    ];

    // 👇 Aquí agregamos el cast para fotos
    protected $casts = [
        'fotos' => 'array',
    ];

    public function boletos()
    {
        return $this->hasMany(Boleto::class);
    }
}
