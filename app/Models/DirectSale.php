<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DirectSale extends Model {
    use HasFactory;

    protected $fillable = ['employee_id', 'total', 'payment_method'];

    public function items() {
        return $this->hasMany(DirectSaleItem::class);
    }

    public function employee() {
        return $this->belongsTo(User::class, 'employee_id');
    }
}