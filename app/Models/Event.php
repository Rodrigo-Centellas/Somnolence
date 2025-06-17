<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'mensaje', 'tipo', 'nivel', 'fecha', 'hora',
        'latitud', 'longitud', 'user_id', 'vehicle_id', 'trip_id'
    ];


    public function trip()
    {
        return $this->belongsTo(Trip::class);
    }
}
