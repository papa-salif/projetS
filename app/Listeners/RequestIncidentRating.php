<?php

namespace App\Listeners;

use App\Events\IncidentCompleted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use app\Models\Rating;

class RequestIncidentRating
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(IncidentCompleted $event): void
    {
        
        $incident = $event->incident;
        $user = $incident->reportedBy;

        $hasRated = Rating::where('incident_id', $incident->id)
                          ->where('user_id', $user->id)
                          ->exists();

        if (!$hasRated) {
            // Logique pour notifier l'utilisateur ou lui montrer un formulaire
            // Par exemple, envoyer un email ou une notification en temps réel
        }
    
    }
}
