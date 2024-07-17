<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Incident;


class IncidentCompleted
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */

     public $incident;
     public $oldStatus;
     public $newStatus;
 
     public function __construct($incident, $oldStatus, $newStatus)
     {
         $this->incident = $incident;
         $this->oldStatus = $oldStatus;
         $this->newStatus = $newStatus;
     }

    // public function __construct(Incident $incident)
    // {
    //     $this->incident = $incident;

    // }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('channel-name'),
        ];
    }


    // app/Events/IncidentCompleted.php


}
