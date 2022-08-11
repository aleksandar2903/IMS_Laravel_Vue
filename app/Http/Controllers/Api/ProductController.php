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
use Illuminate\Support\Str;
use Image;

class ProductController extends Controller
{

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        $helper = new Helper();
        $req = $helper->requestBuilder($request);
        $query = $helper->queryBuilder($req['query'], $req['categories'], $req['brands'], $req['priceMax'], $req['priceMin']);

        $productQuery = $query['products'];

        return $productQuery->with('image')->orderBy($req['sortBy'], $req['order'])->paginate(15);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function filter(Request $request)
    {
        $helper = new Helper();
        $req = $helper->requestBuilder($request);

        $query = $helper->queryBuilder($req['query'], $req['categories'], $req['brands'], $req['priceMax'], $req['priceMin']);

        $max_product_price = round((clone $query['products'])->max('price'));
        $min_product_price = round((clone $query['products'])->min('price'));
        $total_records = round((clone $query['products'])->count('id'));

        return ["categories" => $query['categories']->get(), "brands" => $query['brands']->get(), "max_product_price" => $max_product_price, "min_product_price" => $min_product_price, "total_records" => $total_records];
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
        $product->load('image', 'subcategory_with_category', 'brand', 'images', 'specification_attributes', 'reviews');
        $product->similarProducts;
        $product->similarProduct;
        $product->popularBrands;

        return $product;
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
