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
        $order->setTotal($orderDTA->total);
        $order->setStatus($orderDTA->status);
        $order->setCreatedAt($orderDTA->created_at);
        $order->setUpdatedAt($orderDTA->updated_at);
        $order->setStripeId($orderDTA->stripe_id);
        return $order;
    }
}