<?php

namespace App\Http\Controllers;

use App\Models\Stop;
use Illuminate\Http\Request;

class StopController extends Controller
{
    /**
     * Mostrar listado de paradas.
     */
    public function index()
    {
        $stops = Stop::all();
        return view('stops.index', compact('stops'));
    }

    /**
     * Mostrar formulario de creación.
     */
    public function create()
    {
        return view('stops.create');
    }

    /**
     * Almacenar nueva parada.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre'    => ['required', 'string', 'max:255'],
            'latitud'   => ['required', 'numeric'],
            'longitud'  => ['required', 'numeric'],
        ]);

        Stop::create($data);

        return redirect()
            ->route('stops.index')
            ->with('success', 'Parada creada correctamente.');
    }

    /**
     * Mostrar formulario de edición.
     */
    public function edit(Stop $stop)
    {
        return view('stops.edit', compact('stop'));
    }

    /**
     * Actualizar parada existente.
     */
    public function update(Request $request, Stop $stop)
    {
        $data = $request->validate([
            'nombre'    => ['required', 'string', 'max:255'],
            'latitud'   => ['required', 'numeric'],
            'longitud'  => ['required', 'numeric'],
        ]);

        $stop->update($data);

        return redirect()
            ->route('stops.index')
            ->with('success', 'Parada actualizada correctamente.');
    }

    /**
     * Eliminar parada.
     */
    public function destroy(Stop $stop)
    {
        $stop->delete();

        return redirect()
            ->route('stops.index')
            ->with('success', 'Parada eliminada correctamente.');
    }
}
