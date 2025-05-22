<?php

namespace App\Http\Controllers;

use App\Models\Route;
use App\Models\Ruta;
use Illuminate\Http\Request;

class RutaController extends Controller
{
    public function index()
    {
        $rutas = Route::all();
        return view('rutas.index', compact('rutas'));
    }

    public function create()
    {
        return view('rutas.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => ['required','string','max:100'],
            'origen'    => ['required','string','max:255'],
            'destino'   => ['required','string','max:255'],
            'distancia' => ['required','numeric'],
            'estado'    => ['required','boolean'],
        ]);

        Route::create($data);

        return redirect()
            ->route('rutas.index')
            ->with('success', 'Ruta creada correctamente.');
    }

    public function edit(Route $ruta)
    {
        return view('rutas.edit', compact('ruta'));
    }

    public function update(Request $request, Route $ruta)
    {
        $data = $request->validate([
            'origen'    => ['required','string','max:255'],
            'destino'   => ['required','string','max:255'],
            'distancia' => ['required','numeric'],
            'estado'    => ['required','boolean'],
        ]);

        $ruta->update($data);

        return redirect()
            ->route('rutas.index')
            ->with('success', 'Ruta actualizada correctamente.');
    }

    public function destroy(Route $ruta)
    {
        $ruta->delete();

        return redirect()
            ->route('rutas.index')
            ->with('success', 'Ruta eliminada correctamente.');
    }
}
