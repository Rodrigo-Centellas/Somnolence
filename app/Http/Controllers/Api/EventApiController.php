<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;

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
            'vehicle_id' => 'required|integer',
            'trip_id' => 'required|integer',
        ]);

        $evento = Event::create($validated);

        return response()->json([
            'success' => true,
            'evento_id' => $evento->id,
        ], 201);
    }
}
