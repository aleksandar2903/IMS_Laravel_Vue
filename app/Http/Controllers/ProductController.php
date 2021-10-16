<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Notifications\StockAlert;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Collection;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::orderBy('updated_at', 'desc')->get();

        return view('products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = ProductCategory::all();

        return view('products.create', compact('categories'));
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
            'product_category_id' => 'required',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ], [], ['stock_defective' => 'defective stock']);

        $imageName = time() . '.' . $request->image->extension();

        $request->image->move(public_path('/storage/images/'), $imageName);
        $data = $request->all();
        $data['image'] = $imageName;
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
        $categories = ProductCategory::all();

        return view('products.edit', compact('product', 'categories'));
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
            'product_category_id' => 'required',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ], [], ['stock_defective' => 'defective stock']);
        $data = $request->all();
        if ($request->image) {
            File::delete(public_path('/storage/images/' . $product->image));
            $imageName = time() . '.' . $request->image->extension();

            $request->image->move(public_path('/storage/images/'), $imageName);
            $data['image'] = $imageName;
        }

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
}
