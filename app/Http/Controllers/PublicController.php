<?php

namespace App\Http\Controllers;

use App\Models\Rifa;
use App\Models\Reserva;
use App\Models\Boleto;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Numero; 

class PublicController extends Controller
{
 public function showRifa($id)
{
    $rifa = Rifa::findOrFail($id);

    // boletos paginados
    $boletos = Boleto::where('rifa_id', $id)
                     ->orderBy('numero')
                     ->paginate(10000); // ajusta si quieres menos por página

    // lista completa de números disponibles para la máquina de la suerte
    $disponibles = Boleto::where('rifa_id', $id)
                         ->where('vendido', 0)
                         ->where('disponible', 1)
                         ->pluck('numero');

    return view('public.rifa', compact('rifa', 'boletos', 'disponibles'));
}


public function reservar(Request $request)
{
    $request->validate([
        'rifa_id' => 'required|exists:rifas,id',
        'boletos' => 'required|array|min:1',
        'nombre' => 'required|string|max:255',
        'apellido' => 'required|string|max:255',
        'telefono' => 'required|string|max:20',
        'estado' => 'required|string|max:255',
    ]);

    $boletosReservados = [];
    foreach ($request->boletos as $numero) {
        $boleto = Boleto::where('numero', $numero)
                        ->where('rifa_id', $request->rifa_id)
                        ->where('disponible', 1)
                        ->where('vendido', 0)
                        ->first();

        if (!$boleto) {
            return response()->json([
                'success' => false,
                'message' => "El boleto $numero ya no está disponible para esta rifa."
            ], 400);
        }

        Reserva::create([
            'boleto_id' => $boleto->id,
            'nombre' => $request->nombre,
            'apellido' => $request->apellido,
            'telefono' => $request->telefono,
            'estado' => $request->estado,
            'expira_en' => now()->addHours(24),
        ]);

        $boleto->disponible = 0;
        $boleto->save();

        $boletosReservados[] = $numero;
    }

    // 📌 Elegir número aleatorio de la tabla `numeros`
    $numeroWhatsApp = Numero::inRandomOrder()->first()->numero;

    return response()->json([
        'success' => true,
        'message' => 'Boletos reservados con éxito.',
        'boletos' => $boletosReservados,
        'numero' => $numeroWhatsApp, // 👈 lo mandamos al frontend
    ]);
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
