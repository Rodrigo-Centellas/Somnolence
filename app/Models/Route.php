<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Route extends Model
{
    use HasFactory;

    protected $fillable = ['nombre', 'origen', 'destino', 'distancia'];

    public function vehicles()
    {
        return $this->belongsToMany(Vehicle::class, 'vehicle_route');
    }
}
