<?php

namespace App\Events;

use App\Models\Gpslocation;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

class GpsUpdated implements ShouldBroadcast
{
    use SerializesModels;

    /**
     * El nuevo registro de GPS.
     *
     * @var \App\Models\Gpslocation
     */
    public function __construct(public Gpslocation $gps)
    {
        //
    }

    /**
     * El canal público donde se emitirá la actualización de posición.
     *
     * @return \Illuminate\Broadcasting\Channel
     */
    public function broadcastOn(): Channel
    {
        return new Channel('gpslocations');
    }

    /**
     * Los datos que se enviarán al frontend.
     *
     * @return array<string, mixed>
     */
    public function broadcastWith(): array
    {
        return [
            'trip_id' => $this->gps->trip_id,
            'lat'     => $this->gps->latitud,
            'lng'     => $this->gps->longitud,
            'velocidad' => $this->gps->velocidad,
        ];
    }
}
