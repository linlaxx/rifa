<?php

namespace App\Http\Controllers;

use App\Models\Rifa;
use Illuminate\Http\Request;

class PublicController extends Controller
{
 // Página principal con carrusel
public function index()
{
    $rifas = Rifa::where('estado', 'activa')->get();
    return view('public.index', compact('rifas'));
}


    // Vista individual de la rifa
    public function showRifa($id)
    {
        $rifa = Rifa::with('boletos')->findOrFail($id);
        return view('public.rifa', compact('rifa'));
    }

    // Vista de métodos de pago
    public function metodosPago()
    {
        return view('public.metodosPago');
    }
}
