<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Http\Controllers\Auth\LoginController;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'agent_type',
    ];

    public function isAgent()
    {
        return $this->role === 'agent';
    }

    public function getAgentType()
    {
        return $this->isAgent() ? $this->agent_type : null;
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
    public function incidents()
    {
        return $this->hasMany(Incident::class, 'agent_id');
    }

    public function incident()
    {
        return $this->hasMany(Incident::class, 'user_id');
    }
    

    public function isAdmin()
{
    return $this->role === 'admin';
}

public function messages()
{
    return $this->hasMany(Message::class);
}

public function ration()
    {
        return $this->belongsTo(Ration::class);
    }

    public function hasRole($role)
{
    return $this->role === $role;
}


}
