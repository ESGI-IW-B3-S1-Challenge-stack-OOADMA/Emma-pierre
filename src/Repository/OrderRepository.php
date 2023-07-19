<?php

namespace App\Repository;

use App\DTA\Converter\OrderDtaConverter;
use App\DTA\OrderDTA;
use App\Entity\Order;
use Exception;

class OrderRepository extends AbstractRepository
{
    public function add(Order $order): int
    {
        $statement = $this->pdo->prepare('INSERT INTO `order` (`reference`, `shipping_address_id`, `billing_address_id`, `user_id`, `coupon_id`, `total`, `status`) VALUES (?, ?, ?, ?, ?, ?, ?)');
        $statement->execute([$order->getReference(), $order->getShippingAddress()->getId(), $order->getBillingAddress()->getId(), $order->getUser()->getId(), $order->getCoupon()->getId(), $order->getTotal(), $order->getStatus()]);
        return $this->pdo->lastInsertId();
    }

    /**
     * @throws Exception
     */
    public function findOne(int $id): Order | null
    {
        $statement = $this->pdo->prepare('SELECT * FROM `order` WHERE id = ?');
        $statement->execute([$id]);
        $data = $statement->fetch(\PDO::FETCH_ASSOC);
        if ($data === false) {
            return null;
        }

        $orderDta = new OrderDTA($data);
        $orderDtaConverter = new OrderDtaConverter();
        return $orderDtaConverter->toOrder($orderDta);
    }

    /**
     * @return array<Order>
     * @throws Exception
     */
    public function findAll(): array
    {
        $sql = '
            SELECT o.reference, o.total, o.status, o.created_at, concat(a1.address_line1, " ", a1.postal_code, a1.city, c1.name) AS shipping_address, concat(a2.address_line1, " ", a2.postal_code, a2.city, c2.name) AS billing_address
            FROM `order` o
            INNER JOIN address a1 ON o.shipping_address_id = a1.id
            INNER JOIN address a2 ON o.billing_address_id = a2.id
            JOIN country c1 ON a1.country = c1.id
            JOIN country c2 ON a2.country = c2.id
            ORDER BY o.id DESC
        ';
        $statement = $this->pdo->prepare($sql);
        $statement->execute();
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);

        if ($data === false) {
            return [];
        }

        foreach ($data as $order) {
            $orderDta = new OrderDTA($order);
            $orderDtaConverter = new OrderDtaConverter();
            $orders[] = $orderDtaConverter->toOrder($orderDta);
        }

        return $orders;
    }
}