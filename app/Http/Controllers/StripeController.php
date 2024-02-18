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

            $client = Client::where('email', Auth::user()->email)->first();

            $sale = Sale::where('stripe_session_id', $sessionId)->with('products')->first();

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
                'client_id' => $client->id,
                'payment_method_id' => 2
            ]);

            $client->total_paid += $request->total_amount;
            $client->total_purchases += $request->total_amount;
            $client->last_purchase = Carbon::now();

            $user = Auth::user();

            $stripe_sessions = $request->session()->get((string) $user->id);

            $new_stripe_sessions = [];

            foreach ($stripe_sessions as $session) {
               if ($session != $sessionId) {
                array_push($new_stripe_sessions, $session);
               }
            }

            $request->session()->put((string) $user->id, $new_stripe_sessions);

            return view('checkout.success');
        } catch (\Exception $e) {
            throw new NotFoundHttpException();
        }

    }

    public function cancel(Request $request)
    {
        $user = Auth::user();

        $stripe_sessions = $request->session()->get((string) $user->id);

        $new_stripe_sessions = [];

        foreach ($stripe_sessions as $session) {
           if ($session != $request->get('session_id')) {
            array_push($new_stripe_sessions, $session);
           }
        }

        $request->session()->put((string) $user->id, $new_stripe_sessions);

        return view('checkout.cancel');
    }

    public function webhook()
    {
        // This is your Stripe CLI webhook secret for testing your endpoint locally.
        $endpoint_secret = env('STRIPE_WEBHOOK_KEY');

        $payload = @file_get_contents('php://input');
        $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
        $event = null;


        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload, $sig_header, $endpoint_secret
            );
        } catch (\UnexpectedValueException $e) {
            // Invalid payload
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            // Invalid signature
        }

        error_log($event);


// Handle the event
        switch ($event->type) {
            case 'checkout.session.completed':
                $session = $event->data->object;

            // ... handle other event types
            default:
                return response('Received unknown event type ' . $event->type, 400);
        }

        error_log($event);

        return response($event);
    }
}
