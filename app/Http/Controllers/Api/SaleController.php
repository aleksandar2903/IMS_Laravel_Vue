<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Sale;
use App\Models\Cart;
use App\Models\Product;
use Carbon\Carbon;
use App\Models\SoldProduct;
use App\Models\Transaction;
use App\Models\PaymentMethod;
use App\Models\User;
use App\Notifications\StockAlert;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

class SaleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $client = Auth::user()->client;
        return Sale::where('client_id', $client->id)->with('shipping_address')->with(['products' => function ($q) {
            $q->with(['product' => function ($query) {
                $query->with('image');
            }]);
        }])->latest()->get();
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
    public function store(Request $request)
    {
        $user = Auth::user();
        $client = Client::firstOrCreate([
            'name' => $user->name,
            'email' => $user->email,
            'document_id' => Carbon::now()->timestamp,
            'document_type' => 'v'
        ]);

        $cart = Cart::where('user_id', Auth::id())->with('product')->get();

        $client->save();

        $sale = Sale::create([
            'client_id' => $client->id,
            'status' => 'Unpaid',
            'paid' => 0,
            'due' => 0,
        ]);

        error_log($cart);

        foreach ($cart as $key => $cart) {
            $sale->products()->create([
                'product_id' => $cart->product['id'],
                'price' => $cart->product['price'],
                'qty' => $cart['quantity'],
                'total_amount' => $cart['quantity'] * $cart->product['price'],
            ]);
        }

        $sale->shipping_address()->create([
            "name" => $request->shipping_address['name'],
            "city" => $request->shipping_address['city'],
            "address" => $request->shipping_address['address'],
            "zip" => $request->shipping_address['zip'],
            "phone" => $request->shipping_address['phone'],
            "email" => $request->shipping_address['email']
        ]);

        $user->carts()->delete();

        return ['success' => __('Sale successfully registered.')];
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Sale $sale)
    {
        return $sale->with('products');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Sale $sale)
    {
        //
    }

    public function storetransaction(Request $request, Sale $sale, Transaction $transaction)
    {
        if ($sale->finalized_at == null && $sale->products()->count() > 0) {
            $request->merge(['title' => __('Income') . ' | ' . __('Sale') . ' ID: ' . $sale->id]);
            $request->merge(['type' => 'income']);

            $transaction->create($request->all());

            return redirect()
                ->route('sales.show', compact('sale'))
                ->withStatus(__('Transaction successfully registered.'));
        }
        return redirect()
            ->route('sales.show', compact('sale'))
            ->withError(__('Add some products'));
    }
}
