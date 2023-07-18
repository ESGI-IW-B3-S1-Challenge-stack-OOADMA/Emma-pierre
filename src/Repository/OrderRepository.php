<?php

namespace App\Repository;

use App\Entity\Order;

class OrderRepository extends AbstractRepository
{
    public function add(Order $order): int
    {
        $statement = $this->pdo->prepare('INSERT INTO `order` (`reference`, `shipping_address_id`, `billing_address_id`, `user_id`, `coupon_id`, `total`, `status`) VALUES (?, ?, ?, ?, ?, ?, ?)');
        $statement->execute([$order->getReference(), $order->getShippingAddressId(), $order->getBillingAddressId(), $order->getUserId(), $order->getCouponId(), $order->getTotal(), $order->getStatus()]);
        return $this->pdo->lastInsertId();
    }
}