<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
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

class ProductController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  App\Http\Requests\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        $arrayCategories = [];
        if ($request->has('categories') && $request->categories != null) {
            $arrayCategories = explode(',', $request->categories);
        }

        $arrayBrands = [];
        if ($request->has('brands') && $request->brands != null) {
            $arrayBrands = explode(',', $request->brands);
        }

        $query_string = "";
        $sortBy = "name";
        $order = "ASC";
        $priceMin = 0.0;
        $priceMax = 99999999.9;

        if ($request->has('sortBy') && $request->sortBy != null) {
            switch ($request->sortBy) {
                case 'price':
                    $sortBy = "price";
                    break;
                case 'priceDesc':
                    $sortBy = "price";
                    $order = "DESC";
                    break;
                case 'nameDesc':
                    $order = "DESC";
                    break;
            }
        }

        // DB::statement("SET SQL_MODE=''");

        if ($request->has('query') && $request->query('query') != null) {
            $query_string = $request->query('query');
        }

        if ($request->has('priceMin') && $request->query('priceMin') != null && is_numeric($request->query('priceMin')) && $request->has('priceMax') && $request->query('priceMax') != null && is_numeric($request->query('priceMax'))) {
            $priceMin = $request->query('priceMin');
            $priceMax = $request->query('priceMax');
        }

        $products = Product::where('name', 'LIKE', '%' . $query_string . '%')
            ->whereBetween('price', [$priceMin, $priceMax]);

        $brands = Brand::withCount(['products' => function ($q) use ($query_string, $priceMin, $priceMax) {
            $q->where('products.name', 'LIKE', '%' . $query_string . '%')->whereBetween('products.price', [$priceMin, $priceMax]);
        }])->having('products_count', '>', 0);
        $categories = ProductCategory::withCount(['subProducts' => function ($q) use ($query_string, $priceMin, $priceMax) {
            $q->where('products.name', 'LIKE', '%' . $query_string . '%')->whereBetween('products.price', [$priceMin, $priceMax]);
        }])->with(['subcategories' => function ($query) use ($query_string, $priceMin, $priceMax) {
            $query->withCount(['products' => function ($q) use ($query_string, $priceMin, $priceMax) {
                $q->where('name', 'LIKE', '%' . $query_string . '%')->whereBetween('price', [$priceMin, $priceMax]);
            }])->having('products_count', '>', 0);
        }])->having('sub_products_count', '>', 0);

        if (count($arrayCategories) > 0) {
            $products = $products->where(function ($query) use ($arrayCategories) {
                $query->withCount(['category' => function ($q) use ($arrayCategories) {
                    $q->whereIn('product_category_id', $arrayCategories);
                }])->having('sub_categories_count', '>', 0);
            });
        }

        if (count($arrayBrands) > 0) {
            $products = $products->whereIn('product_brand_id', $arrayBrands);
        }

        return ["products" => $products->with('image')->orderBy($sortBy, $order)->paginate(15), "categories" => $categories->get(), 'brands' => $brands->get()];
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function popular()
    {
        return Product::withCount('solds')->with('image')->orderBy('solds_count', 'DESC')->paginate(10);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function newest()
    {
        return Product::with('image')->orderBy('created_at', 'DESC')->paginate(10);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function gaming()
    {
        return Product::withCount(['category' => function ($q) {
            $q->where('product_category_id', '=', 1);
        }])->having('category_count', '>', 0)->with('image')->paginate(10);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Display a listing of the resource.
     ** @param  App\Http\Requests\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function autocomplete(Request $request)
    {
        $query_string = $request->query('query');
        $query_explode = explode(' ', $query_string);
        $brands = Brand::where('name', 'LIKE', $query_string . '%')->select('name')->get(10);

        return $brands;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
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
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        return $product->load('image', 'subcategory_with_category', 'brand', 'images', 'specification_attributes');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
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
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
    }
}
