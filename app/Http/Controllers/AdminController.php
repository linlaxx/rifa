<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rifa;
use App\Models\Boleto;


class AdminController extends Controller
{

       // Listado de rifas
 public function listado()
{
    $rifas = Rifa::withCount('boletos')->paginate(10); // solo una línea
    return view('admin.listado', compact('rifas'));
}



    // Vista principal del admin
    public function index()
    {
        return view('admin.dashboard');
    }


    // Vista del formulario
    public function vista()
    {
        return view('admin.crearSorteo');
    }

    // Guardar la rifa y los boletos
   public function guardar(Request $request)
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

        // Actualizar total_boletos en la tabla rifas
$rifa->total_boletos = $rifa->boletos()->count(); 
$rifa->save();


    return redirect()->route('admin.listado')->with('success', 'Rifa creada correctamente.');
}
// Mostrar formulario de editar
public function VistaEditar($id)
{
    $rifa = Rifa::findOrFail($id);
    return view('admin.editar', compact('rifa'));
}

//Editar rifa 
public function editar(Request $request, $id)
    {
        $rifa = Rifa::findOrFail($id);

        // Validar inputs
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'fotos' => 'nullable|image|mimes:jpg,png|max:2048',
            'precio_boleto' => 'required|numeric|min:0',
            'total_boletos' => 'required|integer|min:1',
            'estado' => 'required|in:activa,finalizada',
        ]);

        // Actualizar datos básicos de la rifa
        $rifa->nombre = $request->nombre;
        $rifa->descripcion = $request->descripcion;
        $rifa->precio_boleto = $request->precio_boleto;
        $rifa->estado = $request->estado;

        // Si se sube nueva foto, reemplazar
        if ($request->hasFile('fotos')) {
            if ($rifa->fotos) {
                Storage::delete($rifa->fotos); // borrar foto anterior si existe
            }
            $rifa->fotos = $request->file('fotos')->store('rifas', 'public');
        }

        $rifa->save();

        // Ajustar boletos según el nuevo total
        $totalActual = $rifa->boletos()->count();
        $nuevoTotal = $request->total_boletos;

        if ($nuevoTotal < $totalActual) {
            // Reducir boletos: eliminar los que no están vendidos
            $boletosAEliminar = $rifa->boletos()
                ->where('vendido', false)
                ->orderByDesc('id')
                ->take($totalActual - $nuevoTotal)
                ->get();

            foreach ($boletosAEliminar as $boleto) {
                $boleto->delete();
            }
        } elseif ($nuevoTotal > $totalActual) {
            // Aumentar boletos: crear nuevos con números consecutivos
            $faltan = $nuevoTotal - $totalActual;
            for ($i = 1; $i <= $faltan; $i++) {
                Boleto::create([
                    'rifa_id' => $rifa->id,
                    'numero' => $totalActual + $i,
                    'disponible' => true,
                    'vendido' => false,
                ]);
            }
        }

        return redirect()->route('admin.listado')->with('success', 'Rifa actualizada correctamente');
    }


//Eliminar rifa y sus boletos
public function destroy($id)
    {
        $rifa = Rifa::findOrFail($id);
        $rifa->delete();

        return redirect()->route('admin.listado')->with('success', 'Rifa eliminada correctamente.');
    }

    // Vista de boletos
    public function vistaBoletos()
    {
        return view('admin.boletos');
    }

 
}
