<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Alert extends Model
{
    use HasFactory;

    protected $fillable = ['fecha', 'nombre', 'tipo'];
    public function local_storage(){
        
        return $this->belongsTo(LocalStorage::class);
    }

        // Relación many-to-many con GpsTracker
    public function gpsTrackers()
    {
        return $this->belongsToMany(GpsTracker::class, 'alert_gps')
                    ->withPivot(['fecha','hora'])
                    ->withTimestamps();
    }

    // Relación many-to-many con FatigueDetection
    public function fatigueDetections()
    {
        return $this->belongsToMany(FatigueDetection::class, 'fatigue_alert')
                    ->withPivot(['fecha','hora'])
                    ->withTimestamps();
    }
}

