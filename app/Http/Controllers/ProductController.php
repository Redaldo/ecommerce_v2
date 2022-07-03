<?php

namespace App\Http\Controllers;

use App\Product;
use League\Flysystem\Util;
use Illuminate\Http\Request;
use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductResource;
use App\Http\Resources\ProductShowResource;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        return ProductResource::collection(Product::with('category', 'brand')->paginate(3));
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
