<?php

declare(strict_types=1);

namespace Sylius\ShopApiPlugin\Command;

final class ChoosePaymentMethod
{
    /** @var string */
    private $paymentMethod;

    /** @var string */
    private $orderToken;

    public function __construct(string $orderToken, string $paymentMethod)
    {
        $this->orderToken = $orderToken;
        $this->paymentMethod = $paymentMethod;
    }

    public function orderToken(): string
    {
        return $this->orderToken;
    }

    public function paymentMethod(): string
    {
        return $this->paymentMethod;
    }
}
