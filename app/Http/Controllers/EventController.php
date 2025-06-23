<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $query = Event::with(['trip.users', 'trip.vehicle', 'trip.route']);

        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }

        if ($request->filled('nivel')) {
            $query->where('nivel', $request->nivel);
        }

        if ($request->filled('fecha')) {
            $query->whereDate('fecha', $request->fecha);
        }

        if ($request->filled('usuario')) {
            $query->whereHas('trip.users', function ($q) use ($request) {
                $q->where('nombre', 'like', "%{$request->usuario}%")
                  ->orWhere('apellido', 'like', "%{$request->usuario}%");
            });
        }

        $eventos = $query->orderByDesc('fecha')->orderByDesc('hora')->get();

        return view('eventos.index', [
            'eventos' => $eventos,
            'tipos' => ['Velocidad', 'Ruta', 'Seguridad', 'Emergencia'],
            'niveles' => ['Informacion', 'Advertencia', 'Critico'],
            'usuarioFiltro' => $request->usuario
        ]);
    }
}
