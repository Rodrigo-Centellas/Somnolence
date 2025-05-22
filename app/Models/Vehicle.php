<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = ['modelo', 'nombre', 'placa', 'capacidad', 'velocidad_maxima'];

    public function trips()
    {
        return $this->hasMany(Trip::class);
    }
}
