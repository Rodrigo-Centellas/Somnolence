<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GpsTracker extends Model
{
    use HasFactory;

    protected $fillable = [
        'trip_id', 'fecha', 'hora',
        'latitud', 'longitud', 'velocidad',
    ];

    // Perteneciente a un trip
    public function trip()
    {
        return $this->belongsTo(Trip::class);
    }

    // Relación many-to-many con Alert (tabla pivot alert_gps)
    public function alerts()
    {
        return $this->belongsToMany(Alert::class, 'alert_gps')
                    ->withPivot(['fecha','hora'])
                    ->withTimestamps();
    }

    // Un GPS puede cargar muchos registros en LocalStorage
    public function localStorage()
    {
        return $this->hasMany(LocalStorage::class);
    }
}
