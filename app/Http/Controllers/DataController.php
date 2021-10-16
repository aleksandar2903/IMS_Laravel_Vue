<?php

namespace App\Http\Controllers;

use App\Models\Product;
use DateTime;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use PhpParser\ErrorHandler\Collecting;
use Symfony\Component\VarDumper\Cloner\Data;
use Illuminate\Database\Eloquent\Collection;
use App\Models\Purchase;
use Illuminate\Support\Facades\DB;

class DataController extends Controller
{
    public function index(Request $query)
    {
        $current_timestamp = $query->query();
        // $request = Http::get('https://api.covid19api.com/total/country/serbia')->collect();
        function total($lastweek, $currentdate, $relation, $id)
        {
             $record = Product::findOrFail($id)->$relation();
             $thisWeek = $record->select('*', DB::raw('DATE(created_at) as date'))
                ->whereBetween(DB::raw('DATE(created_at)'), [$lastweek, $currentdate])->latest()->get()->groupBy('date');

            return $thisWeek;
        }
        $totalSales = total(Carbon::parse($current_timestamp[0])->format('y-m-d'), Carbon::parse($current_timestamp[1])->format('y-m-d'), 'solds', $current_timestamp[2]);
        $totalPurchases = total(Carbon::parse($current_timestamp[0])->format('y-m-d'), Carbon::parse($current_timestamp[1])->format('y-m-d'), 'receiveds', $current_timestamp[2]);
        $thisWeekSales = collect();
        $thisWeekPurchases = collect();
        foreach ($totalSales as $date => $sale) {
            $thisWeekSales->push(['date' => Carbon::parse($date)->format('d-M'), 'totalAmount' => $sale->sum('total_amount')]);
        }
        foreach ($totalPurchases as $date => $sale) {
            $thisWeekPurchases->push(['date' => Carbon::parse($date)->format('d-M'), 'totalAmount' => $sale->sum('total_amount')]);
        }
        $total = collect(['sales'=>$thisWeekSales,'purchases'=>$thisWeekPurchases]);
        return json_encode($total);
    }
}
