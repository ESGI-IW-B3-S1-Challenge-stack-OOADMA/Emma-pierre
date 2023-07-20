<?php

namespace App\Service\Stripe;

use App\Entity\Product;
use Stripe\Exception\ApiErrorException;
use Stripe\Price;
use Stripe\StripeClient;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class PriceService
{
    private StripeClient $stripeClient;
    private ProductService $productService;

    public function __construct(string $stripe_secret_key, ProductService $productService)
    {
        /** @var string */
        $this->stripeClient = new StripeClient($stripe_secret_key);
        $this->productService = $productService;
    }

    /**
     * @throws ApiErrorException
     * Summary of createPrice
     * @param \App\Entity\Product $product
     * @param int $quantity
     * @return \Stripe\Price
     */
    public function createPrice(Product $product): Price
    {
        return $this->stripeClient->prices->create([
            'product' => $this->productService->retrieveProductOrCreate($product)->id, /** @phpstan-ignore-line */
            'unit_amount' => $product->getPrice(),
            'currency' => 'eur',
            'tax_behavior' => 'exclusive',
        ]);
    }
}