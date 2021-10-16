<?php

namespace App\Http\Controllers;

use App\Models\PaymentMethod;
use App\Models\Receipt;
use App\Models\Provider;
use App\Models\Product;

use Carbon\Carbon;
use App\Models\ReceivedProduct;
use App\Models\Transaction;
use App\Models\User;
use App\Notifications\StockAlert;
use Illuminate\Http\Request;

class ReceiptController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  Receipt  $model
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $purchases = Receipt::latest()->get();

        return view('purchases.index', compact('purchases'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $providers = Provider::all();

        return view('purchases.create', compact('providers'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Receipt  $purchase
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Receipt $purchase)
    {
        $purchase = $purchase->create($request->all());

        return redirect()
            ->route('purchases.show', $purchase)
            ->withStatus(__('Purchase successfully registered, you can start adding the products belonging to it.'));
    }

    /**
     * Display the specified resource.
     *
     * @param  Receipt  $purchase
     * @return \Illuminate\Http\Response
     */
    public function show(Receipt $purchase)
    {
        $products = Product::all();
        $payment_methods = PaymentMethod::all();
        return view('purchases.show', compact('purchase', 'products', 'payment_methods'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Receipt  $purchase
     * @return \Illuminate\Http\Response
     */
    public function destroy(Receipt $purchase)
    {
        $purchase->delete();

        return redirect()
            ->route('purchases.index')
            ->withStatus(__('Purchase removed successfully.'));
    }

    /**
     * Finalize the Receipt for stop adding products.
     *
     * @param  Receipt  $purchase
     * @return \Illuminate\Http\Response
     */
    public function finalize(Receipt $purchase)
    {
        $transaction = Transaction::class;
        if ($purchase->products->sum('total_amount') > $purchase->transactions->sum('amount')) return back()->withError(__("The purchase cannot be finalized. The bill has not been paid."));
        if ($purchase->products->sum('total_amount') <= $purchase->transactions->sum('amount')) {
            if (($purchase->transactions->sum('amount') - $purchase->products->sum('total_amount')) > 0) {
                $paymentMethod = $purchase->transactions()->latest()->first();
                $purchase->transactions()->create([
                    'user_id' => $purchase->user_id,
                    'provider_id' => $purchase->provider_id,
                    'title' => __('Purchase')." ID: '$purchase->id'",
                    'type' => "income",
                    'payment_method_id' => $paymentMethod->payment_method_id,
                    'amount' => $purchase->transactions->sum('amount') - $purchase->products->sum('total_amount')
                ]);
            }
            $purchase->paid = $purchase->transactions->where('type', 'expense')->sum('amount');
            $purchase->status = __('Paid');
            $purchase->due = ($purchase->paid - $purchase->products->sum('total_amount'));
        }
        $purchase->finalized_at = Carbon::now()->toDateTimeString();
        $purchase->save();

        foreach ($purchase->products as $receivedproduct) {
            $receivedproduct->product->stock += $receivedproduct->stock;
            $receivedproduct->product->stock_defective += $receivedproduct->stock_defective;
            $receivedproduct->product->save();
        }
        $users = User::all();
        foreach ($purchase->products as $sold_product) {
            $sold_product->product->stock -= $sold_product->qty;
            $sold_product->product->save();
            if ($sold_product->product->stock > 10) {
                foreach ($users as $user) {
                    foreach ($user->unreadNotifications as $notification) {
                        if ($notification->data['product']['id'] == $sold_product->product->id) {
                            $notification->markAsRead();
                        }
                    }
                }
            }
        }

        return back()->withStatus(__('Purchase successfully completed.'));
    }

    /**
     * Add product on Receipt.
     *
     * @param  Receipt  $purchase
     * @return \Illuminate\Http\Response
     */
    public function addproduct(Receipt $purchase)
    {
        $products = Product::all();

        return view('purchases.addproduct', compact('purchase', 'products'));
    }

    /**
     * Add product on Receipt.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Receipt  $purchase
     * @return \Illuminate\Http\Response
     */
    public function storeproduct(Request $request, Receipt $purchase, ReceivedProduct $receivedproduct)
    {
        $existent = $purchase->products()->where('product_id', $request->get('product_id'))->get();
        if ($existent->count()) {
            return redirect()
                ->route('purchases.show', ['purchase' => $purchase])
                ->withError(__('Product is already registered.'));
        }
        $request->merge(['total_amount' => $request->get('price') * $request->get('stock')]);
        $receivedproduct->create($request->all());

        return redirect()
            ->route('purchases.show', $purchase)
            ->withStatus(__('Product successfully added.'));
    }

    /**
     * Editor product on Receipt.
     *
     * @param  Receipt  $purchase
     * @return \Illuminate\Http\Response
     */
    public function editproduct(Receipt $purchase, ReceivedProduct $receivedproduct)
    {
        $products = Product::all();

        return view('purchases.editproduct', compact('purchase', 'receivedproduct', 'products'));
    }

    /**
     * Update product on Receipt.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Receipt  $purchase
     * @return \Illuminate\Http\Response
     */
    public function updateproduct(Request $request, Receipt $purchase, ReceivedProduct $receivedproduct)
    {
        $request->merge(['total_amount' => $request->get('price') * $request->get('stock')]);
        $receivedproduct->update($request->all());

        return redirect()
            ->route('purchases.show', $purchase)
            ->withStatus(__('Product edited successfully.'));
    }

    /**
     * Add product on Receipt.
     *
     * @param  Receipt  $purchase
     * @return \Illuminate\Http\Response
     */
    public function destroyproduct(Receipt $purchase, ReceivedProduct $receivedproduct)
    {
        $receivedproduct->delete();

        return redirect()
            ->route('purchases.show', $purchase)
            ->withStatus(__('Product removed successfully.'));
    }
    public function storetransaction(Request $request, Receipt $purchase)
    {
        if ($purchase->finalized_at == null && $purchase->products()->count() > 0) {
            $request->merge(['title' =>  __('Expense').' | '.__('Purchase').' ID: '.$purchase->id]);
            $request->merge(['type' => 'expense']);

            $purchase->transactions()->create($request->all());

            return redirect()
                ->route('purchases.show', compact('purchase'))
                ->withStatus(__('Transaction successfully registered.'));
        }
        return redirect()
            ->route('purchases.show', compact('purchase'))
            ->withError(__('Add some products.'));
    }

    public function destroytransaction(Receipt $purchase, Transaction $transaction)
    {
        $purchase->transactions->find($transaction)->delete();

        return back()->withStatus(__('Transaction removed successfully.'));
    }
}
