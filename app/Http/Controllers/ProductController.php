<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductImage;
use App\Models\ProductSpecificationAttribute;
use App\Models\ProductSpecificationAttributeValue;
use App\Models\ProductSubcategory;
use App\Models\Transaction;
use App\Notifications\StockAlert;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Image;
use Mockery\Undefined;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::withCount(['solds' => function ($sold) {
            $sold->select(DB::raw('SUM(qty) as solds'));
        }])->orderBy('created_at', 'DESC')->get();

        return view('products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $brands = Brand::all();
        $categories = ProductSubcategory::all();

        return view('products.create', compact('categories', 'brands'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  App\Http\Requests\ProductRequest  $request
     * @param  App\Product  $model
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|min:3',
            'description' => 'required|min:3',
            'stock' => 'required|numeric',
            'stock_defective' => "required|numeric",
            'price' => 'required|numeric',
            'product_subcategory_id' => 'required',
        ], [], ['stock_defective' => 'defective stock']);

        $data = $request->all();
        $product->create($data);

        return redirect('/products')
            ->withStatus(__('Product successfully registered.'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        $solds = $product->solds()->latest()->limit(25)->get();

        $receiveds = $product->receiveds()->latest()->limit(25)->get();

        // dd($product);

        return view('products.show', compact('product', 'solds', 'receiveds'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        $brands = Brand::all();
        $categories = ProductSubcategory::all();
        $attributes = ProductSpecificationAttribute::whereNotIn('id', $product->attributes->pluck('attribute_id'))->get();

        return view('products.edit', compact('product', 'categories', 'brands', 'attributes'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|min:3',
            'description' => 'min:20',
            'stock' => 'required|numeric|min:0',
            'stock_defective' => "required|numeric|min:0",
            'price' => 'required|numeric|min:0',
            'product_subcategory_id' => 'required',
            'image_id' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ], [], ['stock_defective' => 'defective stock']);
        $data = $request->all();

        $product->update($data);

        return redirect()
            ->route('products.index')
            ->withStatus(__('Product updated successfully.'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        if ($product->image) {
            File::delete(public_path('/storage/images/' . $product->image));
        }
        $product->delete();

        return redirect()
            ->route('products.index')
            ->withStatus(__('Product removed successfully.'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function selectImage(Request $request, Product $product)
    {
        $product->update(['image_id' => $request->input('image_id')]);

        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function addSpecificationAttribute(Request $request, Product $product)
    {
        $product->attributes()->create([
            'attribute_id' => $request->input('attribute_id'),
            'value' => $request->input('value'),
        ]);

        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function removeSpecificationAttribute(ProductSpecificationAttributeValue $attribute)
    {
        $attribute->delete();

        return back();
    }
}
