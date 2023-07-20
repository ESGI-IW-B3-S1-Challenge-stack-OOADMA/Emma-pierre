<?php

namespace App\Controller;

use App\Entity\Address;
use App\Enum\OrderStatus;
use App\Repository\AddressRepository;
use App\Repository\CountryRepository;
use App\Repository\OrderRepository;
use App\Routing\Attribute\Route;

class StripeController
{
    #[Route('/stripe/success', name: 'app_stripe_success')]
    public function success()
    {
    }

    #[Route('/stripe/cancel', name: 'app_stripe_cancel')]
    public function cancel()
    {

    }

    #[Route('/stripe/webhook', name: 'app_stripe_webhook', httpMethod: ['POST'])]
    public function webhook(OrderRepository $orderRepository, CountryRepository $countryRepository, AddressRepository $addressRepository)
    {
        // This is your Stripe CLI webhook secret for testing your endpoint locally.
        $endpoint_secret = 'whsec_983d6a69bce35541d558aca6945769825231a05cee6229e1770e544dd9bc59e2';
        $payload = @file_get_contents('php://input');
        $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
        $event = null;
        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload, $sig_header, $endpoint_secret
            );
        } catch (\UnexpectedValueException $e) {
            // Invalid payload
            http_response_code(400);
            exit();
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            // Invalid signature
            http_response_code(400);
            exit();
        }

        // Handle the event
        switch ($event->type) {
            case 'checkout.session.completed':
                $checkoutSessionCompleted = $event->data->object;

                $order = $orderRepository->findOneByReference($checkoutSessionCompleted->metadata->order);
                $order->setStatus(OrderStatus::paid->value);

                $shipping_data = $checkoutSessionCompleted->shipping;
                $shipping_address_data = $shipping_data->address;
                $shippingAddress = new Address();
                $shippingAddress->setName($shipping_data->name);
                $shippingAddress->setCity($shipping_address_data->city);
                $shippingAddress->setAddressLine1($shipping_address_data->line1);
                $shippingAddress->setAddressLine2($shipping_address_data->line2);
                $shippingAddress->setPostalCode($shipping_address_data->postal_code);
                $shippingAddress->setCreatedAt(new \DateTimeImmutable());
                $shippingAddress->setUpdatedAt(new \DateTimeImmutable());
                $shippingAddress->setCountry($countryRepository->findOneByCode($shipping_address_data->country));
                $shippingAddress->setId($addressRepository->add($shippingAddress));
                $order->setShippingAddress($shippingAddress);
                $orderRepository->update($order);

                break;
            default:
                echo 'Received unknown event type ' . $event->type;
        }

        http_response_code(200);
    }
}