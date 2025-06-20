<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;

class VehicleApiController extends Controller
{
    public function index()
    {
        return Vehicle::select('id', 'nombre', 'placa', 'velocidad_maxima')->get();
    }
}
