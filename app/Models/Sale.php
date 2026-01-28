<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model {
    protected $fillable = ['user_id', 'customer_id', 'invoice_number', 'total_amount', 'discount', 'tax', 'final_total', 'payment_type'];

    public function saleDetails() { return $this->hasMany(SaleDetail::class); }
    public function user() { return $this->belongsTo(User::class); }
    public function customer() { return $this->belongsTo(Customer::class); }
}