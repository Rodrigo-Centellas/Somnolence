<?php

namespace App\Http\Controllers;

use App\Models\Route;
use App\Models\Stop;
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
        $allStops = Stop::all();
        return view('rutas.create', compact('allStops'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre'            => ['required','string','max:100'],
            'latitud_origen'    => ['required','numeric'],
            'longitud_origen'   => ['required','numeric'],
            'latitud_destino'   => ['required','numeric'],
            'longitud_destino'  => ['required','numeric'],
        ]);

        $route = Route::create($data);

        // sincronizar paradas con orden
        $syncData = collect($request->input('stops_order', []))
            ->filter(fn($orden) => $orden)
            ->mapWithKeys(fn($orden, $stopId) => [(int)$stopId => ['orden' => (int)$orden]])
            ->toArray();
        $route->stops()->sync($syncData);

        return redirect()
            ->route('rutas.index')
            ->with('success','Ruta creada correctamente.');
    }

    public function edit(Route $ruta)
    {
        $allStops = Stop::all();
        return view('rutas.edit', compact('ruta','allStops'));
    }

    public function update(Request $request, Route $ruta)
    {
        $data = $request->validate([
            'nombre'            => ['required','string','max:100'],
            'latitud_origen'    => ['required','numeric'],
            'longitud_origen'   => ['required','numeric'],
            'latitud_destino'   => ['required','numeric'],
            'longitud_destino'  => ['required','numeric'],
        ]);

        $ruta->update($data);

        // sincronizar paradas con orden
        $syncData = collect($request->input('stops_order', []))
            ->filter(fn($orden) => $orden)
            ->mapWithKeys(fn($orden, $stopId) => [(int)$stopId => ['orden' => (int)$orden]])
            ->toArray();
        $ruta->stops()->sync($syncData);

        return redirect()
            ->route('rutas.index')
            ->with('success','Ruta actualizada correctamente.');
    }

    public function destroy(Route $ruta)
    {
        $ruta->stops()->detach();
        $ruta->delete();

        return redirect()
            ->route('rutas.index')
            ->with('success','Ruta eliminada.');
    }
}
