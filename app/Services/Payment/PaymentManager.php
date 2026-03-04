<?php

namespace App\Services\Payment;

use App\Services\Payment\Contracts\PaymentDriverInterface;

class PaymentManager
{
    protected $drivers = [];

    public function __construct()
    {
        // register available drivers
        $this->drivers = [
            'wave' => Drivers\WaveDriver::class,
            'orange_money' => Drivers\OrangeMoneyDriver::class,
            'card' => Drivers\CardDriver::class,
            'cash' => Drivers\CashDriver::class,
        ];
    }

    public function driver(string $name): PaymentDriverInterface
    {
        $name = strtolower($name);

        if (! isset($this->drivers[$name])) {
            throw new \InvalidArgumentException("Payment driver [{$name}] is not defined.");
        }

        $class = $this->drivers[$name];

        return new $class();
    }
}
