<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Provider;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use ProtoneMedia\LaravelCrossEloquentSearch\Search;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->query('value');
        // $products = Product::where('name', 'like', $q.'%')->get();
        $results = Search::add(Product::class, 'name')
    ->add(ProductCategory::class, 'name')
    ->add(Client::class, ['name', 'email'])
    ->add(Provider::class, ['name', 'email'])
    ->orderByDesc('updated_at')
    ->beginWithWildcard()
    ->get($q);
    $suppliers = collect();
    $categories = collect();
    $products = collect();
    $clients = collect();
    foreach ($results as $result) {
        if($result->getTable() == "product_categories")
        {
            $categories->push($result);
        }
        if($result->getTable() == "products")
        {
            $products->push($result);
        }
        if($result->getTable() == "clients")
        {
            $clients->push($result);
        }
        if($result->getTable() == "providers")
        {
            $suppliers->push($result);
        }
    }
    $newResult = collect(['Categories'=>$categories, 'Products'=> $products, 'Clients'=>$clients, 'Suppliers'=>$suppliers]);
    return $newResult;
    }
}
