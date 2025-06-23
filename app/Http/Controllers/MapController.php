<?php

// app/Http/Controllers/MapController.php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\GpsTracker;
use App\Models\Trip;
use Illuminate\Http\Request;

class MapController extends Controller
{// MapController.php
public function index()
{
    // Últimos 20 eventos
    $eventos = Event::orderBy('created_at', 'desc')
                     ->limit(20)
                     ->get();

    // Todos los trips con relación a vehículo, ruta y usuarios
    $trips = Trip::with(['vehicle', 'route', 'users', 'gpslocations'])->get();

    // Info de conductor/placa/ruta
    $tripsInfo = $trips->mapWithKeys(function (Trip $trip) {
        $user = $trip->users->first();
        return [
            $trip->id => [
                'placa'     => $trip->vehicle->placa,
                'ruta'      => $trip->route->nombre,
                'conductor' => $user ? "{$user->nombre} {$user->apellido}" : 'Desconocido',
            ]
        ];
    });

    // Última ubicación de cada trip
    $lastLocations = [];
    foreach ($trips as $trip) {
        $last = $trip->gpslocations->sortByDesc('created_at')->first();
        if ($last) {
            $lastLocations[$trip->id] = [
                'lat' => $last->latitud,
                'lng' => $last->longitud,
                'velocidad' => $last->velocidad,
            ];
        }
    }

    return view('map.index', compact('eventos', 'tripsInfo', 'lastLocations'));
}


    // public function apiGpsData()
    // {
    //     $data = GpsTracker::with('trip.vehicle')
    //         ->latest()
    //         ->take(100)
    //         ->get()
    //         ->map(function ($gps) {
    //             return [
    //                 'lat' => $gps->latitud,
    //                 'lng' => $gps->longitud,
    //                 'vehiculo' => optional($gps->trip->vehicle)->nombre ?? 'N/A',
    //                 'fecha' => $gps->fecha,
    //                 'hora' => $gps->hora,
    //                 'velocidad' => $gps->velocidad,
    //             ];
    //         });

    //     return response()->json($data);
    // }
}
