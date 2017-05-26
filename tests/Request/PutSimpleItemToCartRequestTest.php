<?php

namespace Tests\Sylius\ShopApiPlugin\Request;

use Sylius\ShopApiPlugin\Command\PutSimpleItemToCart;
use Sylius\ShopApiPlugin\Request\PutSimpleItemToCartRequest;
use Symfony\Component\HttpFoundation\Request;

final class PutSimpleItemToCartRequestTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function it_creates_put_simple_item_to_cart_command()
    {
        $putSimpleItemToCartRequest = new PutSimpleItemToCartRequest(new Request([], [
            'productCode' => 'HACKTOBERFEST_TSHIRT_CODE',
            'quantity' => 4,
        ], ['token' => 'ORDERTOKEN']));

        $this->assertEquals($putSimpleItemToCartRequest->getCommand(), new PutSimpleItemToCart(
            'ORDERTOKEN',
            'HACKTOBERFEST_TSHIRT_CODE',
            4
        ));
    }
}
