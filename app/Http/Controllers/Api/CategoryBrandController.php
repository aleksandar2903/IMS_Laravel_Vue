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

class CategoryBrandController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  App\ProductCategory  $model
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request, ProductCategory $category)
    {
        $helper = new Helper();
        $req = $helper->requestBuilder($request);
        $query = $helper->queryBuilder($req['query'], $req['categories'], $req['brands'], $req['priceMax'], $req['priceMin'], $category->id);

        return $query['products']->with('image')->orderBy($req['sortBy'], $req['order'])->paginate(15);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  App\ProductCategory  $model
     * @return \Illuminate\Http\Response
     */
    public function filter(Request $request, ProductCategory $category)
    {
        $helper = new Helper();
        $req = $helper->requestBuilder($request);

        $query = $helper->queryBuilder($req['query'], $req['categories'], $req['brands'], $req['priceMax'], $req['priceMin'], $category->id);

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
    public function categories()
    {
        $categories = ProductCategory::all();

        return $categories;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function brands()
    {
        $brands = Brand::all();

        return $brands;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  App\ProductCategory  $model
     * @return \Illuminate\Http\Response
     */
    public function subcategories(ProductCategory $category)
    {
        return $category->subcategories;
    }
}
