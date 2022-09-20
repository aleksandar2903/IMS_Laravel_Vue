<?php

namespace App\Http\Controllers\Api;

use App\Models\Brand;
use App\Models\Product;
use App\Models\ProductSubcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class Helper
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  String  $request
     */
    public static function stringToArray(?string $request)
    {
        $array = [];
        if (!empty($request)) {
            $array = explode(',', $request);
        }

        return $array;
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Array $arrayCategories
     * @param  Array $arrayBrands
     */
    public static function requestBuilder(Request $request)
    {
        $query_string = "";
        $sortBy = "name";
        $order = "ASC";
        $priceMin = 0.0;
        $priceMax = 99999999.9;

        if ($request->has('sortBy') && $request->sortBy != null) {
            switch (Str::lower($request->sortBy)) {
                case 'price':
                    $sortBy = "price";
                    break;
                case 'pricedesc':
                    $sortBy = "price";
                    $order = "DESC";
                    break;
                case 'namedesc':
                    $order = "DESC";
                    break;
            }
        }

        if ($request->has('query') && $request->query('query') != null) {
            $query_string = $request->query('query');
        }

        if ($request->has('priceMin') && $request->query('priceMin') != null && is_numeric($request->query('priceMin')) && $request->has('priceMax') && $request->query('priceMax') != null && is_numeric($request->query('priceMax')) && $request->query('priceMax') > 0) {
            $priceMin = $request->query('priceMin');
            $priceMax = $request->query('priceMax');
        }

        return [
            'query' => $query_string,
            'sortBy' => $sortBy,
            'order' => $order,
            'priceMin' => $priceMin,
            'priceMax' => $priceMax,
        ];
    }
    public static function queryBuilder($query_string, array $arrayCategories, array $arrayBrands, $priceMax, $priceMin, $category = 0)
    {

        $products = Product::whereBetween('price', [$priceMin, $priceMax])->where(function ($query) use ($query_string) {
            $query->where('name', 'LIKE', '%' . $query_string . '%')
                ->orWhere('description', 'LIKE', '%' . $query_string . '%');
        });

        if ($category > 0) {
            $products = $products->whereHas('category', function ($query) use ($category) {
                $query->where('product_subcategories.product_category_id', $category);
            });
        }

        if (count($arrayBrands) > 0) {
            $products = $products->whereIn('product_brand_id', $arrayBrands);
        }

        if (count($arrayCategories) > 0) {
            $products = $products->whereIn('product_subcategory_id', $arrayCategories);
        }

        $brands = Brand::withCount(['products' => function ($query) use ($query_string, $priceMin, $priceMax, $arrayCategories, $category) {
            $query->whereBetween('products.price', [$priceMin, $priceMax]);
            if ($category > 0) {
                $query->whereHas('category', function ($query) use ($category) {
                    $query->where('product_subcategories.product_category_id', $category);
                });
            }
            if (count($arrayCategories) > 0) {
                $query->whereIn('product_subcategory_id', $arrayCategories);
            }
            $query->where(function ($q) use ($query_string) {
                $q->where('products.name', 'LIKE', '%' . $query_string . '%')->orWhere('description', 'LIKE', '%' . $query_string . '%');
            });
        }])->having('products_count', '>', 0);

        $categories = ProductSubcategory::withCount(['products' => function ($query) use ($query_string, $priceMin, $priceMax, $arrayBrands) {
            $query->whereBetween('products.price', [$priceMin, $priceMax]);
            if (count($arrayBrands) > 0) {
                $query->whereIn('product_brand_id', $arrayBrands);
            }
            $query->where(function ($q) use ($query_string) {
                $q->where('products.name', 'LIKE', '%' . $query_string . '%')->orWhere('description', 'LIKE', '%' . $query_string . '%');
            });
        }])->having('products_count', '>', 0);

        if ($category > 0) {
            $categories = $categories->where('product_category_id', $category);
        }

        return ["products" => $products, "categories" => $categories, 'brands' => $brands];
    }
}
