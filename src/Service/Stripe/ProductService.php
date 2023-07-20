<?php

namespace App\Service\Stripe;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Stripe\Exception\ApiErrorException;
use Stripe\Exception\InvalidRequestException;
use Stripe\Product as StripeProduct;
use Stripe\StripeClient;

class ProductService
{
    private StripeClient $stripeClient;
    private ProductRepository $productRepository;

    public function __construct(string $stripe_secret_key, ProductRepository $productRepository)
    {
        $this->stripeClient = new StripeClient($stripe_secret_key);
        $this->productRepository = $productRepository;
    }

    /**
     * @return StripeProduct|bool
     * @throws ApiErrorException
     */
    public function productExists(Product $product)
    {
        try {
            $stripeProduct = $this->stripeClient->products->retrieve($product->getStripeId());
        } catch (InvalidRequestException $e) {
            if ($e->getStripeCode() === 'resource_missing') {
                return false;
            }
            throw $e;
        }

        return $stripeProduct;
    }

    /**
     * @throws ApiErrorException
     */
    public function createProduct(Product $product): \Stripe\Product
    {
        return $this->stripeClient->products->create([
            'name' => $product->getName(),
            'shippable' => false,
            'default_price_data' => [
                'currency' => 'eur',
                'unit_amount' => $product->getPrice(),
            ],
        ]);
    }

    /**
     * Summary of retrieveProductOrCreate
     * @param \App\Entity\Product $product
     * @param int $quantity
     * @return StripeProduct|bool
     */
    public function retrieveProductOrCreate(Product $product)
    {
        $stripeProduct = $this->productExists($product);
        if ($stripeProduct) {
            return $stripeProduct;
        }

        $stripeProduct = $this->createProduct($product);


        $product->setStripeId($stripeProduct->id);
        $this->productRepository->add($product);

        return $stripeProduct;
    }
}
