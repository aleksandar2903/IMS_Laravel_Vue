<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Brand $model)
    {
        $brands = Brand::latest('updated_at')->get();

        return view('products.brands.index', compact('brands'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Brand $brand)
    {
        $request->validate(['name'=>'required|min:2|unique:brands,name',
        'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048']);

        $data = $request->all();

        $imageName = time() . '.' . $request->image->extension();

        $request->image->move(public_path('/storage/images/'),$imageName);
        $data['image'] = $imageName;

        $brand->create($data);
        return redirect('/products/brands')
            ->withStatus(__('Brand successfully created.'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Brand $brand)
    {
        return view('products.brands.show', [
            'brand' => $brand,
            'products' => Product::where('product_brand_id', $brand->id)->latest()->get()
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Brand $brand)
    {
        return view('products.brands.edit', compact('brand'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Brand $brand)
    {
        $request->validate(['name'=>'required|min:2|unique:brands,name',
        'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048']);

        $data = $request->all();
        if ($request->image) {
            File::delete(public_path('/storage/images/' . $request->image));
            $imageName = time() . '.' . $request->image->extension();

            $request->image->move(public_path('/storage/images/'), $imageName);
            $data['image'] = $imageName;
        }

        $brand->update($data);

        return redirect('/products/brands')
            ->withStatus(__('Brand successfully updated.'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Brand $brand)
    {
        if ($brand->image) {
            File::delete(public_path('/storage/images/' . $brand->image));
        }
        $brand->delete();

        return redirect('/products/brands')
            ->withStatus(__('Brand successfully deleted.'));
    }
}
