<?php

namespace App\Http\Controllers\Api;

use App\Events\EventCreated;
use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Container\Attributes\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log as FacadesLog;

use function Illuminate\Log\log;

class EventApiController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'mensaje' => 'required|string',
            'tipo' => 'required|string',
            'nivel' => 'required|string',
            'fecha' => 'required|date',
            'hora' => 'required',
            'latitud' => 'required|numeric',
            'longitud' => 'required|numeric',
            'user_id' => 'required|integer',
            'vehicle_id' => 'nullable|integer',
            'trip_id' => 'nullable|integer',
        ]);

        $existe = Event::where('mensaje', $request->mensaje)
        ->where('fecha', $request->fecha)
        ->where('hora', $request->hora)
        ->where('user_id', $request->user_id)
        ->where('latitud', $request->latitud)
        ->where('longitud', $request->longitud)
        ->exists();

        if ($existe) {
    FacadesLog::info("🟡 Evento duplicado detectado, se descarta: {$request->mensaje} {$request->hora}");
    return response()->json([
        'success' => true
    ], 200);
}



        $evento = Event::create($validated);
FacadesLog::info("🔔 [EventApiController] a punto de broadcast EventCreated ID={$evento->id}");
broadcast(new EventCreated($evento))->toOthers();
FacadesLog::info('🔔 [EventApiController] broadcast disparado');


        return response()->json([
            'success' => true,
            'evento_id' => $evento->id,
        ], 201);
    }
}
