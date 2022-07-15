<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductSpecificationAttribute;
use Illuminate\Http\Request;

class ProductSpecificationAttributeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ProductSpecificationAttribute $model)
    {
        $specifications = ProductSpecificationAttribute::latest('updated_at')->get();

        return view('products.specifications.index', compact('specifications'));
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
    public function store(Request $request, ProductSpecificationAttribute $specification)
    {
        $request->validate(['name'=>'required|min:3|unique:product_specification_attributes,name']);
        $specification->create($request->all());
        return redirect('/products/specifications')
            ->withStatus(__('Specification successfully created.'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(ProductSpecificationAttribute $specification)
    {
        return view('products.specifications.show', [
            'specification' => $specification,
            'products' => Product::with([
                    'attributes' => function($query) use ($specification) {
                        $query->where('attribute_id', $specification->id);
                    }
            ])->withCount([
                'attributes' => function($query) use ($specification) {
                    $query->where('attribute_id', $specification->id);
                }
        ])->having('attributes_count','>',0)->latest()->get()
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(ProductSpecificationAttribute $specification)
    {
        return view('products.specifications.edit', compact('specification'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ProductSpecificationAttribute $specification)
    {
        $request->validate(['name'=>'required|min:3|unique:product_specification_attributes,name']);

        $data = $request->all();

        $specification->update($data);

        return redirect('/products/specifications')
            ->withStatus(__('Specification successfully updated.'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProductSpecificationAttribute $specification)
    {
        $specification->delete();

        return redirect('/products/specifications')
            ->withStatus(__('Specification successfully deleted.'));
    }
}
