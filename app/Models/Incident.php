<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;



class Incident extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'type', 'description', 'localisation', 'latitude', 'longitude','numero', 'ville', 'secteur', 'preuves', 'agent_id', 'status'
    ];

    protected $casts = [
        'preuves' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'agent_id');
    }

    // public function agent()
    // {
    //     return $this->belongsTo(User::class, 'agent_id');
    // }


    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function reportedBy()
{
    return $this->belongsTo(User::class, 'user_id');
}
public function ratings()
{
    return $this->hasMany(Rating::class);
}



}

