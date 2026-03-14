<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    protected $fillable = [
        'product_id',
        'path',
    ];

    // Ensure no guarded attributes for static analysis and to prevent mass-assignment warnings
    protected $guarded = [];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
