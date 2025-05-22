<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pause extends Model
{
    use HasFactory;

    protected $fillable = [
        'trip_id',
        'nombre', 'descripcion', 'motivo',
        'tiempoInicio', 'tiempoFin', 'ubicacion',
    ];

    // Perteneciente a un trip
    public function trip()
    {
        return $this->belongsTo(Trip::class);
    }
}
