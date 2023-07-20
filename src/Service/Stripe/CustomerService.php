<?php

namespace App\Service\Stripe;

use App\Entity\User;
use Stripe\Customer;
use Stripe\Exception\ApiErrorException;
use Stripe\StripeClient;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class CustomerService
{
    private StripeClient $stripeClient;

    public function __construct(string $stripe_secret_key)
    {
        $this->stripeClient = new StripeClient($stripe_secret_key);
    }

    /**
     * @return Customer|bool
     * @throws ApiErrorException
     */
    public function customerExists(User $user)
    {
        /** @var array<Customer> $customer */
        $customer = $this->stripeClient->customers->search([
            'query' => sprintf('email: \'%s\'', $user->getEmail()),
        ])['data'];
        if (!empty($customer)) {
            return $customer[0];
        }
        return false;
    }

    /**
     * @throws ApiErrorException
     */
    private function createCustomer(User $user): Customer
    {
        return $this->stripeClient->customers->create([
            'email' => $user->getEmail(),
            'name' => $user->getFirstName() . ' ' . $user->getLastName(),
        ]);
    }

    /**
     * @param \App\Entity\User $user
     * @return Customer
     * @throws ApiErrorException
     * Summary of retrieveCustomerOrCreate
     */
    public function retrieveCustomerOrCreate(User $user): Customer
    {
        $customer = $this->customerExists($user);
        if ($customer) {
            /** @var Customer $customer */
            return $customer;
        }
        return $this->createCustomer($user);
    }
}
