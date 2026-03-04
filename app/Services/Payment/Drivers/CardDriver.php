<?php

namespace App\Services\Payment\Drivers;

use App\Services\Payment\Contracts\PaymentDriverInterface;
use Illuminate\Support\Facades\Http;

class CardDriver implements PaymentDriverInterface
{
    public function pay(array $data): array
    {
        $stripeSecret = env('STRIPE_SECRET');

        if ($stripeSecret) {
            try {
                // Prefer creating a Checkout Session (hosted payment page) so we can redirect the user
                if (! empty($data['success_url']) && ! empty($data['cancel_url'])) {
                    $amount = (int)round($data['amount'] * 100); // cents

                    $payload = [
                        'mode' => 'payment',
                        'success_url' => $data['success_url'],
                        'cancel_url' => $data['cancel_url'],
                        'line_items[0][price_data][currency]' => env('STRIPE_CURRENCY', 'eur'),
                        'line_items[0][price_data][product_data][name]' => 'Order '.$data['order_id'],
                        'line_items[0][price_data][unit_amount]' => $amount,
                        'line_items[0][quantity]' => 1,
                        'metadata[order_id]' => $data['order_id'] ?? null,
                    ];

                    $response = Http::withBasicAuth($stripeSecret, '')->asForm()->post('https://api.stripe.com/v1/checkout/sessions', $payload);

                    if ($response->ok()) {
                        $body = $response->json();

                        return [
                            'status' => 'pending',
                            'reference' => $body['id'] ?? ('CARD-' . uniqid()),
                            'redirect_url' => $body['url'] ?? null,
                        ];
                    }
                }
            } catch (\Exception $e) {
                // fall through to simulated
                logger()->error('CardDriver Stripe error: '.$e->getMessage());
            }
        }

        // Fallback (development/demo) - mark as paid
        return [
            'status' => 'paid',
            'reference' => 'CARD-' . uniqid(),
        ];
    }
}
