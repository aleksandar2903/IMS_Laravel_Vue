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

use function PHPSTORM_META\type;

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
        $selectedCategorieIds = $helper->stringToArray($request->categories);
        $selectedBrandIds = $helper->stringToArray($request->brands);
        $req = $helper->requestBuilder($request, $selectedCategorieIds, $selectedBrandIds);

        $query = $helper->queryBuilder($req['query'], $selectedCategorieIds, $selectedBrandIds, $req['priceMax'], $req['priceMin']);

        $productQuery = $query['products'];

        $paginattedProducts = $productQuery->with('image')->orderBy($req['sortBy'], $req['order'])->paginate(15);

        $this->favouriteCheck($paginattedProducts);

        return $paginattedProducts;
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
        $selectedCategorieIds = $helper->stringToArray($request->categories);
        $selectedBrandIds = $helper->stringToArray($request->brands);
        $req = $helper->requestBuilder($request, $selectedCategorieIds, $selectedBrandIds);

        $query = $helper->queryBuilder($req['query'], $selectedCategorieIds, $selectedBrandIds, $req['priceMax'], $req['priceMin']);

        $max_product_price = round((clone $query['products'])->max('price'));
        $min_product_price = round((clone $query['products'])->min('price'));
        $total_records = round((clone $query['products'])->count('id'));

        $activeFilters = [];
        $selectedCategories = [];
        $selectedBrands = [];

        $mappedCategories = $query['categories']->get();
        foreach ($mappedCategories as $category) {
            if (in_array($category->id, $selectedCategorieIds)) {
                $category->is_selected = true;
                array_push($selectedCategories, $category);
            } else {
                $category->is_selected = false;
            }
        };

        $mappedBrands = $query['brands']->get();
        foreach ($mappedBrands as $brand) {
            if (in_array($brand->id, $selectedBrandIds)) {
                $brand->is_selected = true;
                array_push($selectedBrands, $brand);
            } else {
                $brand->is_selected = false;
            }
        };

        $activeFilters = [
            'categories' => $selectedCategories,
            'brands' => $selectedBrands,
        ];

        $sort = $req['order'] == 'DESC' ? $req['sortBy'] . 'DESC' : $req['sortBy'];

        return [
            "categories" => $mappedCategories, "brands" => $mappedBrands,
            "activeFilters" => $activeFilters, "max_product_price" => $max_product_price, "min_product_price" => $min_product_price, "total_records" => $total_records,
            "keyword" => $req['query'],
            "sort" => $sort,
            'price' => [
                'min' => $req['priceMin'],
                'max' => $req['priceMax'],
            ]
        ];
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function popular()
    {
        $paginattedProducts = Product::withCount('solds')->with('image')->orderBy('solds_count', 'DESC')->paginate(10);

        $this->favouriteCheck($paginattedProducts);

        return $paginattedProducts;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function newest()
    {
        $paginattedProducts = Product::with('image')->orderBy('created_at', 'DESC')->paginate(10);

        $this->favouriteCheck($paginattedProducts);

        return $paginattedProducts;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function gaming()
    {
        $paginattedProducts = Product::withCount(['category' => function ($q) {
            $q->where('product_category_id', '=', 1);
        }])->having('category_count', '>', 0)->with('image')->paginate(10);

        $this->favouriteCheck($paginattedProducts);

        return $paginattedProducts;
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
        $product->load('image', 'subcategory_with_category', 'brand', 'images', 'specification_attributes', 'publishedReviews');
        $product->similarProducts;
        $product->similarProduct;
        $product->popularBrands;

        foreach ($product->images as $key => $image) {
            if ($image->id == $product->image->id) {
                $temp = $product->images[0];
                $product->images[0] = $image;
                $product->images[$key] = $temp;
                break;
            }
        }

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

    private function favouriteCheck($req)
    {
        $user = Auth::guard('sanctum')->user();
        if ($user == null) {
            return;
        }
        $favourites = Auth::guard('sanctum')->user()->favourites;

        if ($favourites == null) {
            return;
        }

        $req->withQueryString()
            ->map(function ($product) use ($favourites) {
                $product->favourite = $favourites->contains('product_id', $product->id);
                return $product;
            });
    }
}
