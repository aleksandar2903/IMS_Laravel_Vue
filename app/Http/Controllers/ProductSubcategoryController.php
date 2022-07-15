<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductSubcategory;
use Illuminate\Http\Request;

class ProductSubcategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ProductSubcategory $model)
    {
        $subcategories = ProductSubcategory::latest('updated_at')->get();
        $categories = ProductCategory::latest('name')->get();

        return view('products.subcategories.index', compact('subcategories','categories'));
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
    public function store(Request $request, ProductSubcategory $category)
    {
        $request->validate(['name'=>'required|min:3|unique:product_subcategories,name,product_category_id,']);
        $category->create($request->all());
        return redirect('/products/subcategories')
            ->withStatus(__('Subcategory successfully created.'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(ProductSubcategory $category)
    {
        return view('products.subcategories.show', [
            'category' => $category,
            'products' => Product::where('product_subcategory_id', $category->id)->latest()->get()
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(ProductSubcategory $category)
    {
        return view('products.subcategories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ProductSubcategory $category)
    {
        $request->validate(['name'=>'required|min:3|unique:product_subcategories,name,product_category_id']);

        $data = $request->all();

        $category->update($data);

        return redirect('/products/subcategories')
            ->withStatus(__('Subcategory successfully updated.'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProductSubcategory $category)
    {
        $category->delete();

        return redirect('/products/subcategories')
            ->withStatus(__('Subcategory successfully deleted.'));
    }
}
