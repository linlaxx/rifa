<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rifa;
use App\Models\Boleto;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    // 📌 Listado de rifas
    public function listado()
    {
        $rifas = Rifa::withCount('boletos')->paginate(10);
        return view('admin.listado', compact('rifas'));
    }

    // 📌 Dashboard principal del admin
    public function index()
    {
        $rifasActivas = Rifa::where('estado', 'activa')->count();
        $rifasFinalizadas = Rifa::where('estado', 'finalizada')->count();
        $boletosVendidos = \DB::table('boletos')->where('vendido', true)->count();

        $rifas = Rifa::withCount([
            'boletos as vendidos' => function($q) {
                $q->where('vendido', 1);
            },
            'boletos as disponibles' => function($q) {
                $q->where('vendido', 0);
            }
        ])->get();

        return view('admin.dashboard', compact(
            'rifasActivas', 
            'rifasFinalizadas', 
            'boletosVendidos',
            'rifas'
        ));
    }

    // 📌 Formulario de creación
    public function vista()
    {
        return view('admin.crearSorteo');
    }

    // 📌 Guardar rifa
    public function guardar(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'fotos' => 'required|array|min:1|max:3',
            'fotos.*' => 'image|mimes:jpg,jpeg,png|max:2048',
            'precio_boleto' => 'required|numeric|min:1',
            'total_boletos' => 'required|integer|min:1',
        ]);

        $paths = [];
        if ($request->hasFile('fotos')) {
            foreach ($request->file('fotos') as $foto) {
                $paths[] = $foto->store('rifas', 'public');
            }
        }

        $rifa = Rifa::create([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'fotos' => json_encode($paths), // 👈 Siempre guardamos JSON
            'precio_boleto' => $request->precio_boleto,
            'total_boletos' => $request->total_boletos,
            'estado' => 'activa'
        ]);

        // Generar boletos
        $boletos = [];
        $ahora = now();
        // 👇 Calculamos el largo máximo según el total
$longitud = strlen((string)$rifa->total_boletos);

for ($i = 1; $i <= $rifa->total_boletos; $i++) {
    $boletos[] = [
        'rifa_id' => $rifa->id,
        'numero' => str_pad($i, $longitud, '0', STR_PAD_LEFT), // 👈 Aquí formateamos
        'disponible' => true,
        'vendido' => false,
        'created_at' => $ahora,
        'updated_at' => $ahora
    ];

            if (count($boletos) >= 5000) {
                \DB::table('boletos')->insert($boletos);
                $boletos = [];
            }
        }
        if (count($boletos) > 0) {
            \DB::table('boletos')->insert($boletos);
        }

        return redirect()->route('admin.listado')->with('success', 'Rifa creada correctamente.');
    }

    // 📌 Vista de edición
    public function VistaEditar($id)
    {
        $rifa = Rifa::findOrFail($id);
        return view('admin.editar', compact('rifa'));
    }

    // 📌 Editar rifa
    public function editar(Request $request, $id)
    {
        $rifa = Rifa::findOrFail($id);

        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'fotos' => 'nullable|array|max:3',
            'fotos.*' => 'image|mimes:jpg,jpeg,png|max:2048',
            'precio_boleto' => 'required|numeric|min:0',
            'total_boletos' => 'required|integer|min:1',
            'estado' => 'required|in:activa,finalizada',
        ]);

        $rifa->nombre = $request->nombre;
        $rifa->descripcion = $request->descripcion;
        $rifa->precio_boleto = $request->precio_boleto;
        $rifa->estado = $request->estado;

        // 📌 Manejo de fotos (siempre JSON → array)
        $fotosActuales = is_array($rifa->fotos) ? $rifa->fotos : json_decode($rifa->fotos, true) ?? [];

        // Eliminar fotos seleccionadas
        if ($request->filled('remove_fotos')) {
            $aEliminar = explode(',', $request->remove_fotos);
            foreach ($aEliminar as $foto) {
                if (in_array($foto, $fotosActuales)) {
                    Storage::disk('public')->delete($foto);
                    $fotosActuales = array_diff($fotosActuales, [$foto]);
                }
            }
        }

        // Subir nuevas fotos
        if ($request->hasFile('fotos')) {
            foreach ($request->file('fotos') as $foto) {
                $fotosActuales[] = $foto->store('rifas', 'public');
            }
        }

        // Máximo 3 y guardamos como JSON
        $rifa->fotos = json_encode(array_slice($fotosActuales, 0, 3));
        $rifa->save();

        // 📌 Ajustar boletos
        $totalActual = $rifa->boletos()->count();
        $nuevoTotal = $request->total_boletos;

        if ($nuevoTotal < $totalActual) {
            $rifa->boletos()
                ->where('vendido', false)
                ->orderByDesc('id')
                ->take($totalActual - $nuevoTotal)
                ->delete();
        } elseif ($nuevoTotal > $totalActual) {
            $faltan = $nuevoTotal - $totalActual;
           $longitud = strlen((string)$nuevoTotal);

for ($i = 1; $i <= $faltan; $i++) {
    Boleto::create([
        'rifa_id' => $rifa->id,
        'numero' => str_pad($totalActual + $i, $longitud, '0', STR_PAD_LEFT),
        'disponible' => true,
        'vendido' => false,
    ]);
}

        }

        return redirect()->route('admin.listado')->with('success', 'Rifa actualizada correctamente');
    }

    // 📌 Eliminar rifa
    public function destroy($id)
    {
        $rifa = Rifa::findOrFail($id);

        if ($rifa->fotos) {
            $fotos = is_array($rifa->fotos) ? $rifa->fotos : json_decode($rifa->fotos, true);
            foreach ($fotos as $foto) {
                Storage::disk('public')->delete($foto);
            }
        }

        $rifa->delete();
        return redirect()->route('admin.listado')->with('success', 'Rifa eliminada correctamente.');
    }

    // 📌 Mostrar boletos
    public function boletos($rifaId)
    {
        $rifa = Rifa::findOrFail($rifaId);

        $boletos = $rifa->boletos()
            ->orderBy('numero')
            ->paginate(500);

        $total = $rifa->boletos()->count();
        $vendidos = $rifa->boletos()->where('vendido', true)->count();
        $disponibles = $total - $vendidos;

        return view('admin.boletos', compact('rifa', 'boletos', 'total', 'vendidos', 'disponibles'));
    }

    // 📌 Buscar boletos
    public function buscarBoletos(Request $request, $rifaId)
    {
        $rifa = Rifa::findOrFail($rifaId);

        $busqueda = $request->query('q');

        $boletos = $rifa->boletos()
            ->when($busqueda, function($query) use ($busqueda) {
                $query->where('numero', 'like', $busqueda.'%');
            })
            ->orderBy('numero')
            ->paginate(500);

        return view('admin.partials.boletos-list', compact('boletos'))->render();
    }

    // 📌 Cambiar estado de un boleto
    public function toggleBoleto(Request $request, $boletoId)
    {
        $boleto = Boleto::findOrFail($boletoId);
        $boleto->vendido = !$boleto->vendido;
        $boleto->save();

        return response()->json([
            'success' => true,
            'vendido' => $boleto->vendido
        ]);
    }
}
