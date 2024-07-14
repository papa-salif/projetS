<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Events\MessageSent;

class Message extends Model
{
    use HasFactory;

    protected $table = 'messages'; // Assurez-vous que le nom de la table est correct

    protected $connection = 'mysql'; // Si vous utilisez une connexion différente, spécifiez le nom de la connexion
    protected $fillable = ['incident_id', 'user_id', 'message'];

    protected $dispatchesEvents = [
        'created' => MessageSent::class,
    ];
    

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function incident()
    {
        return $this->belongsTo(Incident::class);
    }
}
