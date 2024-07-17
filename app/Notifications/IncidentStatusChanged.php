<?php

namespace App\Notifications;

use App\Events\IncidentCompleted;
use App\Models\Incident;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;


class IncidentStatusChanged extends Notification
{
    use Queueable;

    protected $incident;
    protected $oldStatus;
    protected $newStatus;


    public function __construct(Incident $incident)
    {
        $this->incident = $incident;
    //     $this->oldStatus = $oldStatus;
    //     $this->newStatus = $newStatus;
     }

    public function via($notifiable)
    {
        return ['broadcast', 'database'];
    }

    public function toArray($notifiable)
    {
        return [
            'incident_id' => $this->incident->id,
            'old_status' => $this->oldStatus,
            'new_status' => $this->newStatus,
        ];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'incident_id' => $this->incident->id,
            'status' => $this->incident->status,
        ]);
    }
    
    public function handle(IncidentCompleted $event)
    {
        $user = $event->incident->user;
        $user->notify(new IncidentCompleted($event->incident, $event->oldStatus, $event->newStatus));
    }

    // public function via($notifiable)
    // {
    //     return ['database'];
    // }

    // public function toArray($notifiable)
    // {
    //     return [
    //         'message' => "Le statut de l'incident #{$this->incident->id} a changé en {$this->incident->status}.",
    //         'incident_id' => $this->incident->id,
    //     ];
    // }
}
