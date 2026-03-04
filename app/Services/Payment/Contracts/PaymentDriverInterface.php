<?php

namespace App\Services\Payment\Contracts;

interface PaymentDriverInterface
{
    /**
     * Process a payment.
     * Return array with keys: status (paid|pending|failed), reference
     */
    public function pay(array $data): array;
}
