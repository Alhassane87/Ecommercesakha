<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use App\Models\Payment as PaymentModel;
use App\Services\Payment\PaymentManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function show()
    {
        if (Auth::check()) {
            $cart = Auth::user()->cart()->with('items.product')->first();
        } elseif (session('cart_id')) {
            $cart = \App\Models\Cart::with('items.product')->find(session('cart_id'));
        } else {
            $cart = null;
        }

        return view('checkout.checkout', compact('cart'));
    }

    public function process(Request $request, PaymentManager $payments)
    {
        $request->validate([
            'payment_method' => 'required|in:cash',
            'shipping_address' => 'required|array',
            'customer_email' => 'nullable|email',
            'customer_phone' => 'required|string',
        ]);

        $user = Auth::user();
        $paymentMethod = 'cash';

        if ($user) {
            $cart = $user->cart()->with('items')->first();
        } elseif (session('cart_id')) {
            $cart = Cart::with('items')->find(session('cart_id'));
        } else {
            $cart = null;
        }

        if (! $cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Votre panier est vide.');
        }

        $orderTotal = (float) $cart->items->sum(fn($item) => (float) $item->unit_price * (int) $item->qty);

        // Build a minimal order (no inventory lock for this skeleton)
        $order = new Order();
        $order->user_id = $user ? $user->id : null;
        $order->shipping_address = $request->shipping_address;
        $order->status = 'received';
        $order->total = $orderTotal;
        // store guest contact if provided
        $order->customer_email = $request->input('customer_email') ?: ($user?->email ?? null);
        $order->customer_phone = $request->input('customer_phone') ?: null;
        $order->save();

        if ($cart && $cart->items->count()) {
            foreach ($cart->items as $cartItem) {
                $order->items()->create([
                    'product_id' => $cartItem->product_id,
                    'product_variation_id' => $cartItem->product_variation_id,
                    'selected_attributes' => $cartItem->selected_attributes,
                    'qty' => $cartItem->qty,
                    'unit_price' => $cartItem->unit_price,
                ]);
            }

            // Vider le panier après création de la commande
            $cart->items()->delete();
            if (! $user) {
                $cart->delete();
                session()->forget('cart_id');
            }
        }

        // Ensure a random public token exists and set an expiration (30 days)
        if (! $order->public_token) {
            $order->public_token = Str::upper(Str::random(20));
            $order->public_token_expires_at = now()->addDays(30);
            $order->save();
        }

        // Prepare success/cancel URLs for providers that need redirects (Stripe Checkout, Wave, Orange)
        $successUrl = route('orders.show', ['order' => $order->id, 'token' => $order->public_token]);
        $cancelUrl = route('checkout.show');

        // Create a payment record (attempt)
        $payment = PaymentModel::create([
            'order_id' => $order->id,
            'provider' => $paymentMethod,
            'amount' => $order->total,
            'currency' => config('payments.stripe.currency', env('STRIPE_CURRENCY', 'eur')),
            'status' => 'initiated',
        ]);

        // Call payment driver
        $result = $payments->driver($paymentMethod)->pay([
            'amount' => $order->total,
            'order_id' => $order->id,
            'success_url' => $successUrl,
            'cancel_url' => $cancelUrl,
        ]);

        // Update order and payment with result
        $order->payment_provider = $paymentMethod;
        $order->payment_reference = $result['reference'] ?? $payment->reference ?? null;
        $order->status = ($result['status'] ?? 'pending') === 'paid' ? 'processing' : 'pending';
        $order->save();

        $payment->reference = $result['reference'] ?? $payment->reference;
        $payment->status = $result['status'] ?? ($payment->status ?? 'pending');
        $payment->response = $result;
        $payment->save();

        // If provider returned a redirect (hosted checkout), redirect the user there
        if (! empty($result['redirect_url'])) {
            return redirect()->away($result['redirect_url']);
        }

        // Send email notification to customer with public tracking link if email available
        if ($order->customer_email) {
            try {
                \Illuminate\Support\Facades\Notification::route('mail', $order->customer_email)
                    ->notify(new \App\Notifications\OrderPlacedNotification($order));
            } catch (\Throwable $e) {
                logger()->error('Failed to send OrderPlacedNotification: '.$e->getMessage());
            }
        }

        return redirect()
            ->route('orders.show', ['order' => $order->id, 'token' => $order->public_token])
            ->with('status', 'Commande enregistree. Paiement a la livraison confirme.');
    }
}
