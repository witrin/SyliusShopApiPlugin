<?php

declare(strict_types=1);

namespace spec\Sylius\ShopApiPlugin\Command;

use Sylius\ShopApiPlugin\Command\ChoosePaymentMethod;
use PhpSpec\ObjectBehavior;

final class ChoosePaymentMethodSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith('ORDERTOKEN', 'CASH_ON_DELIVERY_METHOD');
    }

    function it_has_order_token()
    {
        $this->orderToken()->shouldReturn('ORDERTOKEN');
    }

    function it_has_payment_method_defined()
    {
        $this->paymentMethod()->shouldReturn('CASH_ON_DELIVERY_METHOD');
    }
}
