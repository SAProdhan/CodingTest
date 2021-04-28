<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ProductVariantPrice;
use App\Models\Variant;

class ProductFilterController extends Controller
{
    public function filter(Request $request)
    {

        $products = Product::with('images', 'ProductVariantPrices', 'ProductVariants')
        ->when($request->title, function($query)use($request){
            return $query->where('title', 'like', '%'.$request->title.'%');
        })
        ->when($request->variant, function($query) use($request){
            return $query->whereHas('ProductVariantPrices', function($query) use($request){
                $query->where('product_variant_one',$request->variant)
                    ->orWhere('product_variant_two', $request->variant)
                    ->orWhere('product_variant_three', $request->variant);
            });
        })
        ->when($request->price_from <= $request->price_to, function($query) use($request){
            return $query->whereHas('ProductVariantPrices', function($query) use($request){
                $query->whereBetween('price', [$request->price_from, $request->price_to]);
            });
        })
        ->when($request->date, function($query) use($request){
            return $query->whereDate('created_at', $request->date);
        })
        
        ->paginate(2);
        

        $variants = ProductVariant::addSelect(['title' => Variant::select('title')
            ->whereColumn('id', 'product_variants.variant_id')
            ->limit(1)
        ])->get()->groupBy('title');

        return view('products.index', compact('products', 'variants'));
    }
}
