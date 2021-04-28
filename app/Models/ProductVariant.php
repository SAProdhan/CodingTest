<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Product;
use App\Models\Variant;

class ProductVariant extends Model
{
    protected $fillable = [
        'variant', 'variant_id', 'product_id'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function varianttable()
    {
        return $this->belongsTo(Variant::class, 'variant_id');
    }

    

}
