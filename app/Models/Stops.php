<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Stop extends Model
{
    use HasFactory;

    protected $fillable = ['nombre', 'posicion', 'latitud', 'longitud', 'route_id'];

    public function route()
    {
        return $this->belongsTo(Route::class);
    }
}
