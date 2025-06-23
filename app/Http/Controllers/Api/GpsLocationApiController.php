<?php

namespace App\Http\Controllers\Api;

use App\Events\GpsUpdated;
use App\Http\Controllers\Controller;
use App\Models\Gpslocation;
use Illuminate\Http\Request;

class GpsLocationApiController extends Controller
{
    public function store(Request $request)
    {
        // Validar la solicitud (asegúrate de que los datos estén presentes)
        $validated = $request->validate([
            'latitud' => 'required|numeric',
            'longitud' => 'required|numeric',
            'velocidad' => 'required|numeric',
            'user_id' => 'required|integer',
            'vehicle_id' => 'required|integer',
            'trip_id' => 'required|integer',
            'fecha' => 'required|date',
            'hora' => 'required|string',
        ]);

        // Crear el registro de ubicación
        $gpsLocation = new Gpslocation();
        $gpsLocation->latitud = $validated['latitud'];
        $gpsLocation->longitud = $validated['longitud'];
        $gpsLocation->velocidad = $validated['velocidad'];
        $gpsLocation->user_id = $validated['user_id'];
        $gpsLocation->vehicle_id = $validated['vehicle_id'];
        $gpsLocation->trip_id = $validated['trip_id'];
        $gpsLocation->fecha = $validated['fecha'];
        $gpsLocation->hora = $validated['hora'];
        $gpsLocation->save();
        broadcast(new GpsUpdated($gpsLocation))->toOthers();
        return response()->json(['message' => 'Ubicación registrada correctamente.'], 200);
    }
}
