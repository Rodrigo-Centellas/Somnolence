<?php

// app/Http/Controllers/MapController.php

namespace App\Http\Controllers;

use App\Models\GpsTracker;
use Illuminate\Http\Request;

class MapController extends Controller
{
    public function index()
    {
        return view('map.index');
    }

    public function apiGpsData()
    {
        $data = GpsTracker::with('trip.vehicle')
            ->latest()
            ->take(100)
            ->get()
            ->map(function ($gps) {
                return [
                    'lat' => $gps->latitud,
                    'lng' => $gps->longitud,
                    'vehiculo' => optional($gps->trip->vehicle)->nombre ?? 'N/A',
                    'fecha' => $gps->fecha,
                    'hora' => $gps->hora,
                    'velocidad' => $gps->velocidad,
                ];
            });

        return response()->json($data);
    }
}
