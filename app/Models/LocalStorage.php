<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LocalStorage extends Model
{
    use HasFactory;

    protected $fillable = [
        'gps_tracker_id', 'tipo_dato',
        'fecha', 'estado',
    ];

    // Perteneciente a un GPS
    public function gpsTracker()
    {
        return $this->hasMany(GpsTracker::class);
    }
    public function Alert(){
        return $this->hasMany(Alert::class);
    }
}
