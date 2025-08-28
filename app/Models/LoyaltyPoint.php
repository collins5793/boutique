<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoyaltyPoint extends Model
{
    protected $fillable = [
        'user_id',
        'points',
        'reason',
    ];

    // Relation avec l'utilisateur (si tu as un modèle User)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
