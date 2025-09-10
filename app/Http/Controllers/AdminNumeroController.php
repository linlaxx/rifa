<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Numero;

class AdminNumeroController extends Controller
{
    // 📌 Listar números
    public function index()
    {
        $numeros = Numero::all();
        return view('admin.numeros.index', compact('numeros'));
    }

    // 📌 Agregar número
    public function store(Request $request)
    {
        $request->validate([
            'numero' => 'required|string|unique:numeros,numero',
            'nombre' => 'nullable|string|max:255',
        ]);

        Numero::create($request->only('nombre', 'numero'));

        return redirect()->route('admin.numeros.index')->with('success', 'Número agregado correctamente.');
    }

    // 📌 Eliminar número
    public function destroy($id)
    {
        $numero = Numero::findOrFail($id);
        $numero->delete();

        return redirect()->route('admin.numeros.index')->with('success', 'Número eliminado correctamente.');
    }
}
