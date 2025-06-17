<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Gpslocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'latitud', 'longitud', 'velocidad', 'fecha', 'hora',
        'user_id', 'vehicle_id', 'trip_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function trip()
    {
        return $this->belongsTo(Trip::class);
    }
}
