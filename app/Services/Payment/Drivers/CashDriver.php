<?php

namespace App\Services\Payment\Drivers;

use App\Services\Payment\Contracts\PaymentDriverInterface;

class CashDriver implements PaymentDriverInterface
{
    public function pay(array $data): array
    {
        // Cash / Liquide payment: mark as pending until manual confirmation
        return [
            'status' => 'pending',
            'reference' => 'CASH-' . uniqid(),
        ];
    }
}
