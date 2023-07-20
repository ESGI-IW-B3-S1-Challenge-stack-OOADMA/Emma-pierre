<?php

namespace App\Service\Stripe;

use Stripe\Exception\ApiErrorException;
use Stripe\StripeClient;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class TaxService
{
    private StripeClient $stripeClient;

    public function __construct(string $stripe_secret_key)
    {
        $this->stripeClient = new StripeClient($stripe_secret_key);
    }

    /**
     * @throws ApiErrorException
     */
    public function retrieveTaxRate(): \Stripe\TaxRate
    {
        $taxRates = $this->stripeClient->taxRates->all();
        if (empty($taxRates['data'])) {
            return $this->createTax();
        }

        return $taxRates->data[0];
    }

    /**
     * @throws ApiErrorException
     */
    private function createTax(): \Stripe\TaxRate
    {
        return $this->stripeClient->taxRates->create([
            'display_name' => 'TVA 20%',
            'description' => 'TVA 20%',
            'country' => 'FR',
            'inclusive' => false,
            'percentage' => 20,
        ]);
    }
}