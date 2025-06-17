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
        $trips = Trip::with(['users','vehicle','route'])->get();
        return view('Trip.index', compact('trips'));
    }

    public function create()
    {
        $users = User::where('estado', '!=', 'ocupado')->get();
        $vehicles = Vehicle::all();
        $routes = Route::all();
        return view('Trip.create', compact('users','vehicles','routes'));
    }

    public function store(Request $request)
    {
        Log::info('[TripController@store] Request recibido', $request->all());

        $data = $request->validate([
            'route_id'     => ['required','exists:routes,id'],
            'vehicle_id'   => ['required','exists:vehicles,id'],
            'estado'       => ['required','string'],
            'fecha_inicio' => ['required','date'],
            'fecha_fin'    => ['nullable','date','after_or_equal:fecha_inicio'],
            'users'        => ['required','array','min:1'],
            'users.*'      => ['exists:users,id'],
        ]);

        try {
            $trip = Trip::create($data);
            $trip->users()->sync($data['users']);

            // Cambiar estado a "ocupado"
            User::whereIn('id', $data['users'])->update(['estado' => 'ocupado']);

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
        $selected = $trip->users->pluck('id')->toArray();

        return view('Trip.edit', compact('trip','users','vehicles','routes','selected'));
    }

    public function update(Request $request, Trip $trip)
    {
        $data = $request->validate([
            'vehicle_id'   => ['required','exists:vehicles,id'],
            'route_id'     => ['required','exists:routes,id'],
            'estado'       => ['required','string'],
            'fecha_inicio' => ['required','date'],
            'fecha_fin'    => ['nullable','date','after_or_equal:fecha_inicio'],
            'users'        => ['required','array','min:1'],
            'users.*'      => ['exists:users,id'],
        ]);

        $trip->update($data);
        $trip->users()->sync($data['users']);

        return redirect()
            ->route('trips.index')
            ->with('success','Viaje actualizado correctamente.');
    }

    public function destroy(Trip $trip)
    {
        $trip->users()->detach();
        $trip->delete();

        return redirect()->route('trips.index')->with('success','Viaje eliminado.');
    }
}
