<?php

namespace App\Events;

use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

class EventCreated implements ShouldBroadcast
{
    use SerializesModels;

    /**
     * El modelo de evento recién creado.
     *
     * @var \App\Models\Event
     */
    public function __construct(public Event $event)
    {
        //
    }

    /**
     * El canal público donde se emitirá este evento.
     *
     * @return \Illuminate\Broadcasting\Channel
     */
    public function broadcastOn(): Channel
    {
        return new Channel('events');
    }

    /**
     * Los datos que se enviarán al frontend.
     *
     * @return array<string, mixed>
     */
    public function broadcastWith(): array
    {
        return [
            'id'       => $this->event->id,
            'tipo'     => $this->event->tipo,
            'nivel'    => $this->event->nivel,
            'mensaje'  => $this->event->mensaje,
            'hora' =>   Carbon::parse($this->event->hora)->format('H:i'),
            'lat'      => $this->event->latitud,
            'lng'      => $this->event->longitud,
            'vehiculo' => $this->event->trip->vehicle->placa,
            'ruta'     => $this->event->trip->route->nombre,
        ];
    }
}
