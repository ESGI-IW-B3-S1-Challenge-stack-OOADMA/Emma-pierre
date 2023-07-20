<?php

namespace App\Service\Stripe;


use App\Entity\Order;
use App\Entity\User;
use Stripe\Exception\ApiErrorException;
use Stripe\StripeClient;

class CheckoutService
{
    private StripeClient $stripeClient;
    private ProductService $productService;
    private PriceService $priceService;
    private CustomerService $customerService;
    private AdminUrlGenerator $adminUrlGenerator;
    private TaxService $taxService;
    private string $stripe_secret_key;

    public function __construct(string $stripe_secret_key, ProductService $productService, PriceService $priceService, CustomerService $customerService, TaxService $taxService)
    {
        $this->stripeClient = new StripeClient($stripe_secret_key);
        $this->stripe_secret_key = $stripe_secret_key;
        $this->productService = $productService;
        $this->priceService = $priceService;
        $this->customerService = $customerService;
        $this->taxService = $taxService;
    }

    /**
     * @throws ApiErrorException
     */
    public function createCheckout(Order $order, User $user): \Stripe\Checkout\Session
    {
        $orderItems = $order->getOrderItems();

        $lineItems = [];
        foreach ($orderItems as $orderItem) {
            $product = $orderItem->getProduct();
            $quantity = $orderItem->getQuantity();
            $lineItems[] = [
                'price' => $this->productService->retrieveProductOrCreate($product)->default_price !== $product->getPrice() ? $this->priceService->createPrice($product)->id : ($this->productService->retrieveProductOrCreate($product))->default_price->id,
                'quantity' => $quantity,
                'tax_rates' => [$this->taxService->retrieveTaxRate()]
            ];
        }


        /** @phpstan-ignore-next-line */

        try {
            return $this->stripeClient->checkout->sessions->create([
                'customer' => ($this->customerService->retrieveCustomerOrCreate($user))->id,
                'mode' => 'payment',
                'payment_method_types' => ['card'],
                "line_items" => $lineItems,
                'metadata' => [
                    'order' => $order->getReference(),
                ],
                'shipping_address_collection' => [
                    'allowed_countries' => ['FR'],
                ],
                'billing_address_collection' => 'required',
                'shipping_options' => [
                    [
                        'shipping_rate_data' => [
                            'type' => 'fixed_amount',
                            'fixed_amount' => [
                                'amount' => 0,
                                'currency' => 'eur',
                            ],
                            'display_name' => 'Livraison gratuite',
                            // Delivers between 5-7 business days
                            /*'delivery_estimate' => [
                                'minimum' => [
                                    'unit' => 'business_day',
                                    'value' => 5,
                                ],
                                'maximum' => [
                                    'unit' => 'business_day',
                                    'value' => 7,
                                ],
                            ]*/
                        ]
                    ],
                    [
                        'shipping_rate_data' => [
                            'type' => 'fixed_amount',
                            'fixed_amount' => [
                                'amount' => 2500 * 1.2,
                                'currency' => 'eur',
                            ],
                            'display_name' => 'Traitement express',
                            'delivery_estimate' => [
                                'minimum' => [
                                    'unit' => 'business_day',
                                    'value' => 1,
                                ],
                                'maximum' => [
                                    'unit' => 'business_day',
                                    'value' => 1,
                                ],
                            ]
                        ]
                    ]
                ],
                "allow_promotion_codes" => true,
                'success_url' => (empty($_SERVER['HTTPS']) ? 'http' : 'https') . '://' . $_SERVER['HTTP_HOST'] . '/stripe/success',
                'cancel_url' => (empty($_SERVER['HTTPS']) ? 'http' : 'https') . '://' . $_SERVER['HTTP_HOST'] . '/stripe/cancel',
            ]);
        } catch (ApiErrorException $e) {
            throw new \Exception($e->getMessage());
        }
    }
}
