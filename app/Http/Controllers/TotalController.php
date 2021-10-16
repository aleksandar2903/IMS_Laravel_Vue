<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use PhpParser\ErrorHandler\Collecting;
use Symfony\Component\VarDumper\Cloner\Data;
use Illuminate\Database\Eloquent\Collection;
use App\Models\Purchase;
use App\Models\Sale;
use App\Models\SoldProduct;
use Illuminate\Support\Facades\DB;

class TotalController extends Controller
{
    public function index()
    {
        $products = Product::get()->count();
        $invoices = Sale::where('finalized_at', '!=', null)->get()->count();
        $customers = Client::get()->count();
        $current_date = Carbon::now();
        $lastweek_date = $current_date->subDays(7)->format('Y-m-d');
        $two_weeks_ago = $current_date->subDays(7)->format('Y-m-d');
        $current_date = Carbon::now()->format('Y-m-d');

        function total($twoweeksago, $lastweek, $currentdate, $db)
        {
            $thisWeek = $db::select('*', DB::raw('DATE(created_at) as date'))
                ->whereBetween(DB::raw('DATE(created_at)'), [$lastweek, $currentdate])->latest()->get();

            $lastWeek = $db::select('*', DB::raw('DATE(created_at) as date'))
                ->whereBetween(DB::raw('DATE(created_at)'), [$twoweeksago, $lastweek])->latest()->get();

            return collect(['thisWeek' => $thisWeek, 'lastWeek' => $lastWeek]);
        }

        $totalProducts = total($two_weeks_ago, $lastweek_date, $current_date, '\App\Models\SoldProduct');
        $totalPurchases = total($two_weeks_ago, $lastweek_date, $current_date, '\App\Models\Purchase');
        $totalCustomers = total($two_weeks_ago, $lastweek_date, $current_date, '\App\Models\Client');
        $totalInvoices = total($two_weeks_ago, $lastweek_date, $current_date, '\App\Models\Sale');

        function totalTrafficPercent($total, $select)
        {
            if ($total['thisWeek']->pluck($select)->Sum() != 0 && $total['lastWeek']->pluck($select)->Sum() != 0) {
                $totalPercentage = ($total['thisWeek']->pluck($select)->Sum() - $total['lastWeek']->pluck($select)->Sum()) / $total['lastWeek']->pluck($select)->Sum() * 100;
                return number_format($totalPercentage);
            } else if ($total['thisWeek']->pluck($select)->Sum() != 0 && $total['lastWeek']->pluck($select)->Sum() == 0) {
                return 100;
            } else if ($total['thisWeek']->pluck($select)->Sum() == 0 && $total['lastWeek']->pluck($select)->Sum() != 0) {
                return -100;
            }
            return 0;
        }

        $totaltrafficSaleAmountPercent = totalTrafficPercent($totalProducts, 'total_amount');
        $totaltrafficPurchaseAmountPercent = totalTrafficPercent($totalPurchases, 'total_amount');
        $totaltrafficSaleProductsPercent = totalTrafficPercent($totalProducts, 'qty');
        $totaltrafficSaleCustomersPercent = totalTrafficPercent($totalCustomers, 'id');
        $totaltrafficSaleInvoicesPercent = totalTrafficPercent($totalInvoices, 'id');

        $totalTraffic = collect(['thisWeek' => $totalProducts['thisWeek']->pluck('total_amount')->Sum() + $totalPurchases['thisWeek']->pluck('total_amount')->Sum(), 'lastWeek' => $totalProducts['lastWeek']->pluck('total_amount')->Sum() + $totalPurchases['lastWeek']->pluck('total_amount')->Sum()]);
        $totalTrafficPercent = ($totaltrafficSaleAmountPercent + $totaltrafficPurchaseAmountPercent) / 2;
        $totalPurchases = collect([
            'totalPurchasesForThisWeek' => $totalPurchases['thisWeek'],
            'totalPurchasesForLastWeek' => $totalPurchases['lastWeek'],
            'totalPurchasesPercent' => $totaltrafficPurchaseAmountPercent,
            'totalTrafficForThisWeek' => $totalTraffic['thisWeek'],
            'totalTrafficForLastWeek' => $totalTraffic['lastWeek'],
            'totalPercetage' => $totalTrafficPercent,
            'totalProductsThisWeek' => $totalProducts['thisWeek'],
            'totalProductsLastWeek' => $totalProducts['lastWeek'],

            'totalProductsPercent' => $totaltrafficSaleProductsPercent,
            'totalInvoicesThisWeek' => $totalInvoices['thisWeek'],
            'totalInvoicesLastWeek' => $totalInvoices['lastWeek'],
            'totalInvoicesPercent' => $totaltrafficSaleInvoicesPercent,

            'totalCustomersThisWeek' => $totalCustomers['thisWeek'],
            'totalCustomersLastWeek' => $totalCustomers['lastWeek'],
            'totalCustomersPercent' => $totaltrafficSaleCustomersPercent
        ]);
        dd($totalPurchases);
    }
}
