<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Delivery extends Model
{
    use HasFactory;

    protected $table = 'deliveries';

    // Les champs qu'on peut remplir en mass-assignment
    protected $fillable = [
        'order_id',
        'delivery_person_id',
        'status',
        'tracking_number',
        'delivered_at',
    ];

    // Champs de type date/heure
    protected $dates = [
        'delivered_at',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'delivered_at' => 'datetime',
    ];

    /**
     * Livraison appartient à une commande
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Livraison appartient à un livreur
     */
    public function deliveryPerson()
    {
        return $this->belongsTo(User::class, 'delivery_person_id');
    }
}
