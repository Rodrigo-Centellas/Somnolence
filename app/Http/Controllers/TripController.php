<?php

namespace App\Http\Controllers;

use App\Models\Route;
use App\Models\Trip;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class TripController extends Controller
{
    public function index()
    {
        $trips = Trip::with(['users','vehicle'])->get();
        return view('Trip.index', compact('trips'));
    }

    public function create()
    {
        $users = User::all();
        $vehicles = Vehicle::all();
        $routes = Route::all();
        return view('Trip.create', compact('users','vehicles','routes'));
    }


public function store(Request $request)
{
    Log::info('[TripController@store] Request recibido', $request->all());

    $data = $request->validate([
        'route_id'           => ['required','exists:routes,id'], // aquí
        'vehicle_id'         => ['required','exists:vehicles,id'],
        'distancia_recorrida'=> ['required','numeric','min:0'],
        'estado'             => ['required','string'],
        'fecha_inicio'       => ['required','date'],
        'fecha_fin'          => ['date','nullable'],
        'users'              => ['required','array','min:1'],
        'users.*'            => ['exists:users,id'],
    ]);

    Log::info('[TripController@store] Datos validados', $data);

    try {
        $trip = Trip::create($data);
        Log::info("[TripController@store] Trip creado con ID {$trip->id}");
        $trip->users()->sync($data['users']);
        foreach($trip->users as $user)
            {
                $user->estado = 'ocupado';
                $user->save();
            }


        Log::info("[TripController@store] Usuarios asociados", ['users' => $data['users']]);
        return redirect()->route('trips.index')->with('success','Viaje creado correctamente.');
    } catch (\Exception $e) {
        Log::error('[TripController@store] Error al guardar Trip', [
            'message' => $e->getMessage(),
            'trace'   => $e->getTraceAsString(),
        ]);
        return back()->withInput()->withErrors(['error'=>'No se pudo guardar el viaje.']);
    }
}



    public function edit(Trip $trip)
    {
        $users = User::all();
        $vehicles = Vehicle::all();
        $routes = Route::all();
        // IDs de usuarios ya asignados
        $selected = $trip->users->pluck('id')->toArray();

        return view('Trip.edit', compact('trip','users','vehicles','routes','selected'));
    }

    public function update(Request $request, Trip $trip)
    {
        $data = $request->validate([
            'vehicle_id'          => ['required','exists:vehicles,id'],
            'route_id'             => ['required','exists:routes,id'],
            'distancia_recorrida' => ['required','numeric','min:0'],
            'estado'              => ['required','boolean'],
            'fecha_inicio'        => ['required','date'],
            'fecha_fin'           => ['nullable','date','after_or_equal:fecha_inicio'],
            'users'               => ['required','array','min:1'],
            'users.*'             => ['exists:users,id'],
        ]);

        $trip->update($data);

        // Sincronizar pivot
        $trip->users()->sync($data['users']);

        return redirect()
            ->route('trips.index')
            ->with('success','Viaje actualizado correctamente.');
    }

}
