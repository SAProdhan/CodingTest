<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\ProductImage;
use App\Models\ProductVariantPrice;
use App\Models\ProductVariant;

class Product extends Model
{
    protected $fillable = [
        'title', 'sku', 'description'
    ];

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }
    public function ProductVariantPrices()
    {
        return $this->hasMany(ProductVariantPrice::class);
    }
    public function ProductVariants()
    {
        return $this->hasMany(ProductVariant::class);
    }
    

}
