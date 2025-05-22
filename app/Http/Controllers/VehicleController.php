<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    public function index()
    {
        $vehicles = Vehicle::all();
        return view('Vehicle.index', compact('vehicles'));
    }

    public function create()
    {
        return view('Vehicle.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'modelo'          => ['required','string','max:255'],
            'nombre'          => ['required','string','max:255'],
            'placa'           => ['required','string','max:100','unique:vehicles'],
            'capacidad'       => ['required','numeric'],
            'velocidad_maxima'=> ['required','numeric'],
        ]);

        Vehicle::create($data);

        return redirect()
            ->route('vehicle_index')
            ->with('success', "Vehículo “{$data['nombre']} ({$data['placa']})” creado correctamente.");
    }

    public function edit(Vehicle $vehicle)
    {
        return view('Vehicle.edit', compact('vehicle'));
    }

    public function update(Request $request, Vehicle $vehicle)
    {
        $data = $request->validate([
            'modelo'          => ['required','string','max:255'],
            'nombre'          => ['required','string','max:255'],
            'placa'           => ['required','string','max:100',"unique:vehicles,placa,{$vehicle->id}"],
            'capacidad'       => ['required','numeric'],
            'velocidad_maxima'=> ['required','numeric'],
        ]);

        $vehicle->update($data);

        return redirect()
            ->route('vehicle_index')
            ->with('success', "Vehículo “{$vehicle->nombre} ({$vehicle->placa})” actualizado correctamente.");
    }

    public function destroy(Vehicle $vehicle)
    {
        $vehicle->delete();

        return redirect()
            ->route('vehicle_index')
            ->with('success', "Vehículo “{$vehicle->nombre} ({$vehicle->placa})” eliminado.");
    }
}
