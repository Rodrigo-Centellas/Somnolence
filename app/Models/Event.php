<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Event extends Model
{
    use HasFactory;

    protected $fillable = ['descripcion', 'tipo', 'trip_id'];

    public function trip()
    {
        return $this->belongsTo(Trip::class);
    }

    public function alerts()
    {
        return $this->belongsToMany(Alert::class, 'alert_event');
    }
}
