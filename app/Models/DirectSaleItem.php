<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DirectSaleItem extends Model {
    use HasFactory;

    protected $fillable = ['direct_sale_id', 'product_id', 'quantity', 'unit_price', 'total_price'];

    public function sale() {
        return $this->belongsTo(DirectSale::class, 'direct_sale_id');
    }

    public function product() {
        return $this->belongsTo(Product::class);
    }
    
}