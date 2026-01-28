<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleDetail extends Model
{
    use HasFactory;

    // ✅ បន្ថែមឈ្មោះ Column ឱ្យគ្រប់
    protected $fillable = [
        'sale_id',
        'product_id',
        'qty',        // ត្រូវប្រាកដថាមានពាក្យនេះ
        'price',
        'subtotal',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}