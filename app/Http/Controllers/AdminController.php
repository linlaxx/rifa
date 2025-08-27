<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rifa;

class AdminController extends Controller
{
    // Vista del formulario
    public function crear()
    {
        return view('admin.crearSorteo');
    }

    // Guardar la rifa y los boletos
   public function store(Request $request)
{
    // Validación básica
    $request->validate([
        'nombre' => 'required|string|max:255',
        'descripcion' => 'nullable|string',
        'fotos' => 'nullable|string', // si quieres subir imagen luego lo cambiamos
        'precio_boleto' => 'required|numeric|min:1',
        'total_boletos' => 'required|integer|min:1',
    ]);

    // Crear rifa
    $rifa = Rifa::create([
        'nombre' => $request->nombre,
        'descripcion' => $request->descripcion,
        'fotos' => $request->fotos,
        'precio_boleto' => $request->precio_boleto,
        'total_boletos' => $request->total_boletos,
        'estado' => 'activa'
    ]);

    // Preparar boletos para insert masivo
    $boletos = [];
    $ahora = now();

    for ($i = 1; $i <= $rifa->total_boletos; $i++) {
        $boletos[] = [
            'rifa_id' => $rifa->id,
            'numero' => $i,
            'disponible' => true,
            'created_at' => $ahora,
            'updated_at' => $ahora
        ];

        // Insertar en bloques de 5000 para no sobrecargar memoria
        if (count($boletos) >= 5000) {
            \DB::table('boletos')->insert($boletos);
            $boletos = []; // limpiar array
        }
    }

    // Insertar cualquier boleto restante
    if (count($boletos) > 0) {
        \DB::table('boletos')->insert($boletos);
    }

    return redirect()->route('admin.listado')->with('success', 'Rifa creada correctamente.');
}


    // Listado de rifas
    public function listado()
    {
        $rifas = Rifa::all();
        return view('admin.listado', compact('rifas'));
    }

    // Vista principal del admin
    public function index()
    {
        return view('admin.dashboard');
    }
}
