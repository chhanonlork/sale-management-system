<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockTransaction extends Model {
    protected $fillable = ['product_id', 'user_id', 'supplier_id', 'type', 'qty', 'date'];

    public function product() { return $this->belongsTo(Product::class); }
    public function user() { return $this->belongsTo(User::class); }
    public function supplier() { return $this->belongsTo(Supplier::class); }
}