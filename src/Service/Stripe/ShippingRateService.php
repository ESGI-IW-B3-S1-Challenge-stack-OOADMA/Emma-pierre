<?php

namespace App\Service\Stripe;

use Stripe\Checkout\Session;
use Stripe\StripeClient;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class ShippingRateService
{
    private StripeClient $stripeClient;
    
    public function __construct(string $stripe_secret_key)
    {
        $this->stripeClient = new StripeClient($stripe_secret_key);
    }

    public function retrieveShippingRate(Session $session): \Stripe\ShippingRate
    {
        return $this->stripeClient->shippingRates->retrieve($session->shipping_rate); /** @phpstan-ignore-line */
    }
}