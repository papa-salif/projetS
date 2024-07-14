<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ration extends Model
{
    use HasFactory;

    protected $table = 'rations';

    protected $fillable = ['user_id', 'rating'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($ration) {
            $previousRation = self::where('user_id', $ration->user_id)->first();

            if ($previousRation) {
                return false; // Annuler la création de la nouvelle entrée
            }
        });
    }
}

