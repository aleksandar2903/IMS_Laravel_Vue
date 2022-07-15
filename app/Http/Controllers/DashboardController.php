<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $current_date = Carbon::now();
        $lastweek_date = Carbon::now()->subDays(7);

        function total($lastweek, $currentdate, $db)
        {
        $thisWeek = $db::select('*', DB::raw('DATE(finalized_at) as date'))
        ->whereBetween(DB::raw('DATE(created_at)'), [$lastweek, $currentdate])->latest()->get()->groupBy('date');

        return $thisWeek;
        }
        $totalPurchases = total( $lastweek_date, $current_date, '\App\Models\Receipt');
        $totalSales = total( $lastweek_date, $current_date, '\App\Models\Sale');
        $thisWeekSales = collect();
        $thisWeekPurchases = collect();
        foreach ($totalSales as $date => $sale) {
        $thisWeekSales->push(['date'=> Carbon::parse($date)->format('D'), 'totalAmount'=>($sale->sum('paid') - $sale->sum('due'))]);
    }
    foreach ($totalPurchases as $date => $purchase) {
        $thisWeekPurchases->push(['date'=> Carbon::parse($date)->format('D'), 'totalAmount'=>($purchase->sum('paid') - $purchase->sum('due'))]);
    }
        $products = Product::select('products.name','products.description','products.image_id',DB::raw('SUM(sold_products.qty) as total_sold'))
        ->join('sold_products', 'products.id', '=', 'sold_products.product_id')
        ->join('sales', 'sold_products.sale_id', '=', 'sales.id')
        ->where('finalized_at', '!=', null)
        ->groupBy('products.id','products.name','products.description','products.image_id')
        ->orderBy('total_sold','desc')
        ->take(5)
        ->get();

        $categories = ProductCategory::select('product_categories.id','product_categories.name',DB::raw('SUM(sold_products.total_amount) as total'))
        ->join('product_subcategories', 'product_categories.id', '=', 'product_subcategories.product_category_id')
        ->join('products', 'product_subcategories.id', '=', 'products.product_subcategory_id')
        ->join('sold_products', 'products.id', '=', 'sold_products.product_id')
        ->join('sales', 'sold_products.sale_id', '=', 'sales.id')
        ->where('finalized_at', '!=', null)
        ->groupBy('product_categories.id','product_categories.name')
        ->orderBy('total','desc')
        ->take(5)
        ->get();

        $sales = Sale::latest()->take(10)->get();

    return view('dashboard',compact('thisWeekPurchases', 'thisWeekSales','products','categories','sales'));
    }
}
