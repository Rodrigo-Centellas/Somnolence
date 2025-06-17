<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Route extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre', 'latitud_origen', 'longitud_origen',
        'latitud_destino', 'longitud_destino'
    ];

    public function stops()
    {
        return $this->hasMany(Stop::class);
    }

    public function trips()
    {
        return $this->hasMany(Trip::class);
    }
}
