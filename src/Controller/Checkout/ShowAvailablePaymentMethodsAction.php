<?php

declare(strict_types=1);

namespace Sylius\ShopApiPlugin\Controller\Checkout;

use FOS\RestBundle\View\View;
use FOS\RestBundle\View\ViewHandlerInterface;
use Sylius\Component\Core\Factory\PaymentMethodFactoryInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\PaymentInterface;
use Sylius\Component\Core\Model\PaymentMethodInterface;
use Sylius\Component\Core\Model\ShipmentInterface;
use Sylius\Component\Core\Model\ShippingMethodInterface;
use Sylius\Component\Core\Repository\OrderRepositoryInterface;
use Sylius\Component\Payment\Resolver\PaymentMethodsResolverInterface;
use Sylius\Component\Registry\ServiceRegistryInterface;
use Sylius\Component\Shipping\Calculator\CalculatorInterface;
use Sylius\Component\Shipping\Resolver\ShippingMethodsResolverInterface;
use Sylius\ShopApiPlugin\Factory\PaymentMethodViewFactoryInterface;
use Sylius\ShopApiPlugin\Model\PaymentStates;
use Sylius\ShopApiPlugin\View\PaymentMethodView;
use Sylius\ShopApiPlugin\View\ShipmentMethodView;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class ShowAvailablePaymentMethodsAction
{
    /** @var OrderRepositoryInterface */
    private $cartRepository;

    /** @var ViewHandlerInterface */
    private $viewHandler;

    /** @var PaymentMethodsResolverInterface */
    private $paymentMethodsResolver;

    /** @var PaymentMethodViewFactoryInterface */
    private $paymentMethodViewFactory;

    public function __construct(
        OrderRepositoryInterface $cartRepository,
        ViewHandlerInterface $viewHandler,
        PaymentMethodsResolverInterface $paymentMethodResolver,
        PaymentMethodViewFactoryInterface $paymentMethodViewFactory
    ) {
        $this->cartRepository = $cartRepository;
        $this->viewHandler = $viewHandler;
        $this->paymentMethodsResolver = $paymentMethodResolver;
        $this->paymentMethodViewFactory = $paymentMethodViewFactory;
    }

    public function __invoke(Request $request): Response
    {
        /** @var OrderInterface $cart */
        $cart = $this->cartRepository->findOneBy(['tokenValue' => $request->attributes->get('token')]);

        return $this->viewHandler->handle(View::create($this->getPaymentMethods(
            $cart->getLastPayment(PaymentStates::PAYMENT_CART),
            $cart->getLocaleCode()
        )));
    }

    /**
     * @param PaymentInterface $payment
     * @param string $locale
     *
     * @return PaymentMethodInterface[]
     */
    private function getPaymentMethods(PaymentInterface $payment, string $locale): array
    {
        $rawPaymentMethods = [];

        /** @var PaymentMethodInterface $paymentMethod */
        foreach ($this->paymentMethodsResolver->getSupportedMethods($payment) as $paymentMethod) {
            $rawPaymentMethods['methods'][$paymentMethod->getCode()] = $this->paymentMethodViewFactory->create($paymentMethod, $locale);
        }

        return $rawPaymentMethods;
    }
}
