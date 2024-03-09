<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Sale;
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
        $sales = Sale::latest()->get();

        return view('sales.index', compact('sales'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $clients = Client::all();

        return view('sales.create', compact('clients'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $existent = Sale::where('client_id', $request->get('client_id'))->where('finalized_at', null)->get();
        if ($existent->count()) {
            return redirect()->route('sales.show', $existent->first());
        }

        $sale = Sale::create($request->all());

        return redirect()->route('sales.show', ['sale' => $sale->id])->withStatus(__('Sale successfully registered, you can start adding the products belonging to it.'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Sale $sale)
    {
        $products = Product::all();
        $payment_methods = PaymentMethod::all();
        return view('sales.show', ['sale' => $sale, 'products' => $products, 'payment_methods' => $payment_methods]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Sale $sale)
    {
        $sale->delete();

        return redirect()
            ->route('sales.index')
            ->withStatus(__('Sale record removed successfully.'));
    }

    public function finalize(Sale $sale)
    {
        $transaction = Transaction::class;
        if ($sale->products->sum('total_amount') > $sale->transactions->sum('amount')) return back()->withError(__("The sale cannot be finalized. The bill has not been paid."));
        if ($sale->products->sum('total_amount') <= $sale->transactions->sum('amount')) {
            if (($sale->transactions->sum('amount') - $sale->products->sum('total_amount')) > 0) {
                $paymentMethod = $sale->transactions()->latest()->first();
                $transaction = new Transaction([
                    'user_id' => $sale->user_id,
                    'client_id' => $sale->client_id,
                    'title' => __('Sale') . " ID: '$sale->id'",
                    'type' => "expense",
                    'payment_method_id' => $paymentMethod->payment_method_id,
                    'amount' => $sale->transactions->sum('amount') - $sale->products->sum('total_amount'),
                ]);
                $sale->transactions()->save($transaction);
            }
            $sale->paid = $sale->transactions->where('type', 'income')->sum('amount');
            $sale->status = __('Paid');
            $sale->due = $sale->transactions->sum('amount') - $sale->products->sum('total_amount');
        }
        foreach ($sale->products as $sold_product) {
            $product_name = $sold_product->product->name;
            $product_stock = $sold_product->product->stock;
            if ($sold_product->qty > $product_stock) return back()->withError(__("The product ':name' does not have enough stock. Only has :stock units.", ['name' => $product_name, 'stock' => $product_stock]));
        }
        $users = User::all();
        foreach ($sale->products as $sold_product) {
            $sold_product->product->stock -= $sold_product->qty;
            $sold_product->product->save();
            if ($sold_product->product->stock < 10) {
                foreach ($users as $user) {
                    foreach ($user->unreadNotifications as $notification) {
                        if (isset($notification->data['product']['id']) && $notification->data['product']['id'] == $sold_product->product->id) {
                            $notification->markAsRead();
                        }
                    }
                    $user->notify(new StockAlert($sold_product->product->load('image')));
                }
            }
        }

        $sale->finalized_at = Carbon::now()->toDateTimeString();
        $sale->save();
        $sale->client->save();

        return back()->withStatus(__('Sale successfully completed.'));
    }

    public function addproduct(Sale $sale)
    {
        $products = Product::all();

        return view('sales.addproduct', compact('sale', 'products'));
    }

    public function storeproduct(Request $request, Sale $sale, SoldProduct $soldProduct)
    {
        $existent = $sale->products()->where('product_id', $request->get('product_id'))->get();
        $product = Product::findorFail($request->get('product_id'));
        if ($existent->count()) {
            return redirect()
                ->route('sales.show', ['sale' => $sale])
                ->withError(__('Product is already registered.'));
        }
        if ($request->get('qty') > $product->stock) {
            return back()->withError(__("The product ':name' does not have enough stock. Only has :stock units.", ['name' => $product->name, 'stock' => $product->stock]));
        }
        $request->merge(['total_amount' => $request->get('price') * $request->get('qty')]);

        $soldProduct->create($request->all());

        return redirect()
            ->route('sales.show', ['sale' => $sale])
            ->withStatus(__('Product successfully added.'));
    }

    public function editproduct(Sale $sale, SoldProduct $soldproduct)
    {
        $products = Product::all();

        return view('sales.editproduct', compact('sale', 'soldproduct', 'products'));
    }

    public function updateproduct(Request $request, Sale $sale, SoldProduct $soldproduct)
    {
        $request->merge(['total_amount' => $soldproduct->price * $request->get('qty')]);

        $soldproduct->update($request->all());

        return redirect()->route('sales.show', $sale)->withStatus(__('Product updated successfully.'));
    }

    public function destroyproduct(Sale $sale, SoldProduct $soldproduct)
    {
        $soldproduct->delete();

        return back()->withStatus(__('Product removed successfully.'));
    }

    public function addtransaction(Sale $sale)
    {
        $payment_methods = PaymentMethod::all();

        return view('sales.addtransaction', compact('sale', 'payment_methods'));
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

    public function edittransaction(Sale $sale, Transaction $transaction)
    {
        $payment_methods = PaymentMethod::all();

        return view('sales.edittransaction', compact('sale', 'transaction', 'payment_methods'));
    }

    public function updatetransaction(Request $request, Sale $sale, Transaction $transaction)
    {
        switch ($request->get('type')) {
            case 'income':
                $request->merge(['title' => 'Payment Received from Sale ID: ' . $request->get('sale_id')]);
                break;

            case 'expense':
                $request->merge(['title' => 'Sale Return Payment ID: ' . $request->get('sale_id')]);

                if ($request->get('amount') > 0) {
                    $request->merge(['amount' => (float) $request->get('amount') * (-1)]);
                }
                break;
        }
        $transaction->update($request->all());

        return redirect()
            ->route('sales.show', compact('sale'))
            ->withStatus('Transaction modified successfully.');
    }

    public function destroytransaction(Sale $sale, Transaction $transaction)
    {
        $sale->transactions->find($transaction)->delete();

        return back()->withStatus(__('Transaction removed successfully.'));
    }

    public function unreadNotifications()
    {
        $unreadNotification = Auth::user()->unreadNotifications;

        return $unreadNotification;
    }

    public function changeDeliveryStatus(Request $request, Sale $sale)
    {
        $sale->delivery_status = $request->delivery_status;
        $sale->save();

        return back()->withStatus(__('Delivery status has been changed successfully.'));
    }
}
