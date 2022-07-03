<?php

namespace App\Http\Controllers;

use App\Product;
use League\Flysystem\Util;
use Illuminate\Http\Request;
use App\Http\Requests\ProductRequest;
use Intervention\Image\Facades\Image;
use Illuminate\Database\Query\Builder;
use App\Http\Resources\ProductResource;
use App\Http\Resources\ProductShowResource;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
        $product = Product::query();
        if ($request->has('category')) {
            $product->where('category_id', $request->category)->get();
        }
        if ($request->has('brand')) {
            $product->where('brand_id', $request->brand)->get();
        }
        
        return ProductResource::collection($product->with('category', 'brand')->paginate(3));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductRequest $request)
    {
        if ($request->hasFile('image')) {
            $file = $request->image;
            $extension = $file->extension();
            $filename = time().'.'.$extension;
            $file->move('images', $filename); 

            $resizedImage = Image::make(public_path('images/'.$filename))
                                   ->fit(400, 400)
                                   ->save(public_path('images\thumbnail_'.$filename));
        }else {
            $filename = 'default.png';
        }

       Product::create([
        'name' => $request->name,
        'slug' => $request->slug,
        'description' => $request->description,
        'price' => $request->price,
        'image' => $filename,
        'category_id' => $request->category_id,
        'brand_id' => $request->brand_id,

       ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        
        return new ProductShowResource($product->load('category', 'brand'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        //
    }
}
