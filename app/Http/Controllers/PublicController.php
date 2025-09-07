<?php

namespace App\Http\Controllers;

use App\Models\Rifa;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    public function showRifa($id)
{
    $rifa = Rifa::with('boletos')->findOrFail($id);
    return view('public.rifa', compact('rifa'));
}
public function metodosPago()
{
    return view('public.metodosPago');
}
 // Página principal con carrusel
public function index()
{
    $rifas = Rifa::withCount([
        'boletos as vendidos' => function ($q) {
            $q->where('vendido', 1);
        },
        'boletos as disponibles' => function ($q) {
            $q->where('vendido', 0);
        }
    ])
    ->where('estado', 'activa')
    ->get();

    return view('public.index', compact('rifas'));
}
}
