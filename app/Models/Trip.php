<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trip extends Model
{
    use HasFactory;

    protected $fillable = [
        'route_id',
        'vehicle_id',
        'distancia_recorrida',
        'estado',
        'fecha_inicio',
        'fecha_fin',
    ];

    // Trip pertenece a una ruta
    public function route()
    {
        return $this->belongsTo(Route::class);
    }

    // Trip pertenece a un vehículo
    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    // Muchos usuarios pueden asignarse a un trip (tabla pivote user_trip)
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_trip')
                    ->withTimestamps();
    }

    // Un trip puede generar muchos GpsTracker
    public function gpsTrackers()
    {
        return $this->hasMany(GpsTracker::class);
    }

    // Un trip puede sugerir muchas pausas
    public function pauses()
    {
        return $this->hasMany(Pause::class);
    }

    // Un trip puede tener muchas detecciones de fatiga
    public function fatigueDetections()
    {
        return $this->hasMany(FatigueDetection::class);
    }
}
