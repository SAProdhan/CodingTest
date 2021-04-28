<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ProductVariantPrice;
use App\Models\Variant;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function index()
    {
        $products = Product::with('images', 'ProductVariantPrices', 'ProductVariants')->paginate(2);
        

        $variants = ProductVariant::addSelect(['title' => Variant::select('title')
            ->whereColumn('id', 'product_variants.variant_id')
            ->limit(1)
        ])->get()->groupBy('title');

        return view('products.index', compact('products', 'variants'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function create()
    {
        $variants = Variant::all();
        return view('products.create', compact('variants'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // // echo('<pre>');
        // print_r($request->all());
        $product = new Product();
        $product->title = $request->title;
        $product->sku = $request->sku;
        $product->description = $request->description;
        $status = $product->save();

        if(!$status)
        {
            return $status;
        }

        $product_variants_prices = [];
        $product_variants = [];
        $product_images = [];

        foreach($request->product_variant as $pv)
        {
            foreach($pv['tags'] as $tag)
            {
                array_push($product_variants, Array(
                    'variant'=> $tag,
                    'variant_id'=>$pv['option'],
                    'product_id'=>$product->id,
                    'created_at'=> now(),
                    'updated_at'=> now()
                ));
            }
        }

        
        $status = ProductVariant::insert($product_variants);

        if(!$status)
        {
            return $status;
        }

        // foreach($request->product_image)
        // {
        //     array_push($product_images, Array(
        //         'product_id'=>$product->id,
        //         'file_path'=>
        //     ))
        // }

        foreach($request->product_variant_prices as $pvprice)
        {
            $titles = explode("/",$pvprice['title']);
            $product_variant_one = '';
            $product_variant_two = '';
            $product_variant_three = '';
            foreach($titles as $index=>$title)
            {
                if($title)
                {
                    if($index==0)
                    {
                        $product_variant_one=ProductVariant::where('variant',$title)->where('product_id', $product->id)->first()->id;
                    }
                    elseif($index==1)
                    {
                        $product_variant_two=ProductVariant::where('variant',$title)->where('product_id', $product->id)->first()->id;
                    }
                    elseif($index==2)
                    {
                        $product_variant_three=ProductVariant::where('variant',$title)->where('product_id', $product->id)->first()->id;
                    }
                }
            }

            array_push($product_variants_prices, Array(
                'product_variant_one'=>$product_variant_one,
                'product_variant_two'=>$product_variant_two,
                'product_variant_three'=>$product_variant_three,
                'price'=>$pvprice['price'],
                'stock'=>$pvprice['stock'],
                'product_id'=>$product->id,
                'created_at'=> now(),
                'updated_at'=> now()
            ));
        }


        
        $status = ProductVariantPrice::insert($product_variants_prices);

        if(!$status)
        {
            return $status;
        }

        return true;
    }


    /**
     * Display the specified resource.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function show($product)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        $variants = Variant::all();
        return view('products.edit', compact('variants', 'product'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        //
    }
}
