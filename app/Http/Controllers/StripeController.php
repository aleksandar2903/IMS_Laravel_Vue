<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Client;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;

class StripeController extends Controller
{
    public function checkout(Request $request) {
        \Stripe\Stripe::setApiKey(env("STRIPE_SECRET_KEY"));

        $sale = Sale::where('id', $request->sale_id)->with(['products' => function ($q) {
            return $q->with('product');
        }])->first();

        if ($sale == null) {
            return;
        }

        $lineItems = [];
        $totalPrice = 0;

        foreach ($sale->products as $product) {
            $totalPrice += $product->product->price;
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'rsd',
                    'product_data' => [
                        'name' => $product->product->name,
                        'images' => ['https://gmedia.playstation.com/is/image/SIEPDC/ps5-product-thumbnail-01-en-14sep21?$facebook$']
                    ],
                    'unit_amount' => $product->price * 100,
                ],
                'quantity' => $product->qty,
            ];
        }

        header('Content-Type: application/json');

        $YOUR_DOMAIN = 'http://localhost:80';

        $checkout_session = \Stripe\Checkout\Session::create([
          'line_items' => $lineItems,
          'mode' => 'payment',
          'success_url' => route('checkout.success', [], true) . "?session_id={CHECKOUT_SESSION_ID}",
          'cancel_url' => route('checkout.cancel', [], true) . "?session_id={CHECKOUT_SESSION_ID}",
          'expires_at'=> Carbon::now()->addMinute(30)->timestamp
        ]);

        $sale->stripe_session_id = $checkout_session->id;
        $sale->save();

        $user = Auth::user();

        if ($request->session()->has((string) $user->id)) {
            $request->session()->push((string) $user->id, $checkout_session->id);
        } else {
            $request->session()->put((string) $user->id, [$checkout_session->id]);
        }

        header("HTTP/1.1 303 See Other");
        header("Location: " . $checkout_session->url);

        return redirect($checkout_session->url);
    }

    public function success(Request $request)
    {
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET_KEY'));
        $sessionId = $request->get('session_id');

        try {
            $session = \Stripe\Checkout\Session::retrieve($sessionId);

            if (!$session) {
                throw new NotFoundHttpException;
            }

            $sale = Sale::where('stripe_session_id', $sessionId)->with(['products', 'client'])->first();

            if ($sale == null) {
                return;
            }

            $total_amount = 0;

            foreach ($sale->products as $product) {
                $total_amount += $product->total_amount;
            }

            $sale->transactions()->create([
                'title' => __('Income') . ' | ' . __('Sale') . ' ID: ' . $sale->id,
                'type' => 'income',
                'amount' => $total_amount,
                'client_id' => $sale->client->id,
                'payment_method_id' => 2
            ]);

            $sale->paid = $total_amount;
            $sale->status = 'Paid';
            $sale->client->total_paid += $total_amount;
            $sale->client->total_purchases += $total_amount;
            $sale->client->last_purchase = Carbon::now();

            $sale->client->save();
            $sale->save();

            return view('checkout.success');
        } catch (\Exception $e) {
            error_log($e);
        }
    }

    public function cancel(Request $request)
    {
        return view('checkout.cancel');
    }

    public function webhook()
    {
// //         // This is your Stripe CLI webhook secret for testing your endpoint locally.
//         $endpoint_secret = env('STRIPE_WEBHOOK_SECRET');

//         $payload = @file_get_contents('php://input');
//         $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
//         $event = null;

//         try {
//             $event = \Stripe\Webhook::constructEvent(
//                 $payload, $sig_header, $endpoint_secret
//             );
//         } catch (\UnexpectedValueException $e) {
//             // Invalid payload
//             return response('', 400);
//         } catch (\Stripe\Exception\SignatureVerificationException $e) {
//             // Invalid signature
//             return response('', 400);
//         }

// // Handle the event
//         switch ($event->type) {
//             case 'checkout.session.completed':
//                 $session = $event->data->object;

//                 $sale = Sale::where('stripe_session_id', $sessionId)->with('products')->first();

//                 if ($sale == null) {
//                     return;
//                 }

//                 $total_amount = 0;

//                 foreach ($sale->products as $product) {
//                     $total_amount += $product->total_amount;
//                 }

//                 $sale->transactions()->create([
//                     'title' => __('Income') . ' | ' . __('Sale') . ' ID: ' . $sale->id,
//                     'type' => 'income',
//                     'amount' => $total_amount,
//                     'client_id' => $client->id,
//                     'payment_method_id' => 2
//                 ]);

//                 $client->total_paid += $request->total_amount;
//                 $client->total_purchases += $request->total_amount;
//                 $client->last_purchase = Carbon::now();

//             // ... handle other event types
//             default:
//                 echo 'Received unknown event type ' . $event->type;
//         }

//         return response('');
    }
}
