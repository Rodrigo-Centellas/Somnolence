<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FatigueDetection extends Model
{
    use HasFactory;

    protected $fillable = [
        'trip_id',
        'contador_bostezo',
        'nivel_alerta',
        'tiempo_sin_movimiento',
        'valor_inclinacion',
        'valor_parpadeo'
    ];

    public function Trip(){
        return $this->belongsTo(Trip::class);
    }

     public function alerts()
    {
        return $this->belongsToMany(Alert::class, 'fatigue_alert')
                    ->withPivot(['fecha','hora'])
                    ->withTimestamps();
    }
}
