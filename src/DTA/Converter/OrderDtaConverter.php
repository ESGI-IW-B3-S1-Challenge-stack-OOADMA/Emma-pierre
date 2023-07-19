<?php

namespace App\DTA\Converter;

use App\DTA\OrderDTA;
use App\Entity\Order;

class OrderDtaConverter
{
    public function toOrder(OrderDTA $orderDTA): Order
    {
        $order = new Order();
        $order->setId($orderDTA->id);
        $order->setReference($orderDTA->reference);
        $order->setShippingAddress($orderDTA->shipping_address);
        $order->setBillingAddress($orderDTA->billing_address);
        $order->setUser($orderDTA->user);
        $order->setCoupon($orderDTA->coupon);
        $order->setTotal($orderDTA->total);
        $order->setStatus($orderDTA->status);
        $order->setCreatedAt($orderDTA->created_at);
        $order->setUpdatedAt($orderDTA->updated_at);
        return $order;
    }
}