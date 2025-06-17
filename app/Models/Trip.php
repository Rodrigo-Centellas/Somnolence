<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Trip extends Model
{
    use HasFactory;

    protected $fillable = ['estado', 'fecha_inicio', 'fecha_fin', 'vehicle_id', 'route_id'];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function gpslocations()
    {
        return $this->hasMany(Gpslocation::class);
    }

    public function events()
    {
        return $this->hasMany(Event::class);
    }

    public function route()
    {
        return $this->belongsTo(Route::class);
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }
}
