<?php

namespace App\DTA\Converter;

use App\DTA\OrderItemDTA;
use App\Entity\OrderItem;

class OrderItemDtaConverter
{
    public function toOrderItem(OrderItemDTA $orderItemDTA)
    {
        $orderItem = new OrderItem();
        $orderItem->setId($orderItemDTA->id);
        $orderItem->setCreatedAt($orderItemDTA->created_at);
        $orderItem->setUpdatedAt($orderItemDTA->updated_at);
        $orderItem->setQuantity($orderItemDTA->quantity);
        return $orderItem;
    }
}