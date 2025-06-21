<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Trip;
use Illuminate\Http\Request;

class TripApiController extends Controller
{
    public function getTripActivoPorUsuario($userId)
    {
        $trip = Trip::with(['route.stops', 'users'])
            ->where('estado', 'activo')
            ->whereHas('users', function ($query) use ($userId) {
                $query->where('users.id', $userId);
            })
            ->first();

        if (!$trip) {
            return response()->json(['success' => false, 'message' => 'No hay viaje activo'], 404);
        }

        return response()->json([
            'success' => true,
            'trip' => [
                'id' => $trip->id,
                'fecha_inicio' => $trip->fecha_inicio,
                'hora_inicio' => $trip->hora_inicio,
                'vehicle_id' => $trip->vehicle_id,
                'route' => [
                    'id' => $trip->route->id,
                    'nombre' => $trip->route->nombre,
                    'origen_lat' => $trip->route->latitud_origen,
                    'origen_lng' => $trip->route->longitud_origen,
                    'destino_lat' => $trip->route->latitud_destino,
                    'destino_lng' => $trip->route->longitud_destino,
                    'stops' => $trip->route->stops->map(function ($stop) {
                        return [
                            'nombre' => $stop->nombre,
                            'latitud' => $stop->latitud,
                            'longitud' => $stop->longitud,
                            'posicion' => $stop->pivot->orden,
                        ];
                    })->values()
                ],
                'conductores' => $trip->users->map(function ($user) {
                    return [
                        'id' => $user->id,
                        'ci' => $user->ci,
                        'nombre' => $user->nombre,
                        'apellido' => $user->apellido,
                        'rol' => $user->role_id,
                        'foto_url' => $user->profile_photo_path
                    ];
                })->values()
            ]
        ]);
    }
}
