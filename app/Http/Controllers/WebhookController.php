<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    public function stripe(Request $request)
    {
        $payloadRaw = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $webhookSecret = env('STRIPE_WEBHOOK_SECRET');

        $event = null;

        if ($webhookSecret && class_exists('\\Stripe\\Webhook')) {
            try {
                $event = \Stripe\Webhook::constructEvent($payloadRaw, $sigHeader, $webhookSecret);
            } catch (\UnexpectedValueException $e) {
                // Invalid payload
                Log::warning('Stripe webhook invalid payload: '.$e->getMessage());
                return response()->json(['ok' => false], 400);
            } catch (\Stripe\Exception\SignatureVerificationException $e) {
                // Invalid signature
                Log::warning('Stripe webhook invalid signature: '.$e->getMessage());
                return response()->json(['ok' => false], 400);
            }
        } else {
            // Fallback : try decode json body
            try {
                $event = json_decode($payloadRaw, false);
            } catch (\Throwable $e) {
                Log::warning('Stripe webhook decode failed: '.$e->getMessage());
                return response()->json(['ok' => false], 400);
            }
        }

        // Extract relevant object and reference
        $object = $event->data->object ?? null;
        $reference = null;

        if ($object) {
            // PaymentIntent or Checkout Session or Charge
            $reference = $object->id ?? ($object->payment_intent ?? ($object->charges->data[0]->id ?? null));
            // metadata.order_id if provided
            $metaOrderId = $object->metadata->order_id ?? null;
        }

        // If we have a metadata order id, prefer it
        if (! empty($metaOrderId)) {
            $order = Order::find($metaOrderId);
        } else {
            $order = $reference ? Order::where('payment_reference', $reference)->first() : null;
        }

        if ($order) {
            // Map some Stripe event types to our status
            $eventType = $event->type ?? null;
            if (in_array($eventType, ['payment_intent.succeeded','checkout.session.completed','charge.succeeded'])) {
                $order->status = 'processing';
            } elseif (in_array($eventType, ['payment_intent.payment_failed','charge.failed'])) {
                $order->status = 'failed';
            }
            $order->save();

            // Update payment record if exists
            if ($reference) {
                $payment = Payment::where('reference', $reference)->first();
                if ($payment) {
                    $payment->status = $order->status === 'processing' ? 'paid' : ($order->status ?? $payment->status);
                    $payment->response = is_array($event) || is_object($event) ? json_decode(json_encode($event), true) : null;
                    $payment->save();
                }
            }
        } else {
            Log::info('Stripe webhook received but no order found for reference: '.($reference ?? 'n/a').' metaOrderId: '.($metaOrderId ?? 'n/a'));
        }

        return response()->json(['ok' => true]);
    }

    public function generic(Request $request, $provider)
    {
        $reference = $request->input('reference');

        if ($reference) {
            $order = Order::where('payment_reference', $reference)->first();
            if ($order) {
                $order->status = $request->input('status', 'processing');
                $order->save();
            }
        }

        return response()->json(['ok' => true]);
    }
}
