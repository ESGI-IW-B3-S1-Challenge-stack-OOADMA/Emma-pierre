<?php

namespace App\Repository;

use App\DTA\Converter\OrderDtaConverter;
use App\DTA\Converter\OrderItemDtaConverter;
use App\DTA\OrderDTA;
use App\DTA\OrderItemDTA;
use App\Entity\Order;
use App\Entity\OrderItem;
use Exception;

class OrderItemRepository extends AbstractRepository
{
    public function add(OrderItem $orderItem): int
    {
        $statement = $this->pdo->prepare('INSERT INTO `order_item` (`order_id`, `product_id`, `quantity`, `created_at`, `updated_at`) VALUES (?, ?, ?, ?, ?)');
        $statement->execute([$orderItem->getOrder()->getId(), $orderItem->getProduct()->getId(), $orderItem->getQuantity(), $orderItem->getCreatedAt()->format('Y-m-d H:i:s'), $orderItem->getUpdatedAt()->format('Y-m-d H:i:s')]);
        return $this->pdo->lastInsertId();
    }

    /**
     * @throws Exception
     */
    public function findOne(int $id): Order|null
    {
        $sql = '
            SELECT o.id id, o.reference reference, sa.id shipping_address_id, sa.name shipping_address_name, sa.address_line1 shipping_address_address_line1,
                   sa.address_line2 shipping_address_address_line2, sa.city shipping_address_city, sa.postal_code shipping_address_postal_code,
                   sac.id shipping_address_country_id, sac.name shipping_address_country_name, sac.code shipping_address_country_code,
                   sac.created_at shipping_address_country_created_at, sac.updated_at shipping_address_country_updated_at,
                   sa.created_at shipping_address_created_at, sa.updated_at shipping_address_updated_at, ba.id billing_address_id,
                   ba.name billing_address_name, ba.address_line1 billing_address_address_line1, ba.address_line2 billing_address_address_line2,
                   ba.city billing_address_city, ba.postal_code billing_address_postal_code, bac.id billing_address_country_id,
                   bac.name billing_address_country_name, bac.code billing_address_country_code, bac.created_at billing_address_country_created_at,
                   bac.updated_at billing_address_country_updated_at, ba.created_at billing_address_created_at, ba.updated_at billing_address_updated_at,
                   u.id user_id, u.lastname user_lastname, u.firstname user_firstname, u.email user_email, u.phone_number user_phone_number,
                   u.password user_password, u.roles user_roles, u.created_at user_created_at, u.updated_at user_updated_at,
                   c.id coupon_id, c.name coupon_name, c.code coupon_code, c.percent coupon_percent, c.duration coupon_duration,
                   c.duration_in_months coupon_duration_in_month, c.valid coupon_valid, c.created_at coupon_created_at,
                   c.updated_at coupon_updated_at, o.total total, o.status status, o.created_at created_at, o.updated_at updated_at
            FROM `order` o
            INNER JOIN address sa ON o.shipping_address_id = sa.id
            INNER JOIN address ba ON o.billing_address_id = ba.id
            JOIN country sac ON sa.country = sac.id
            JOIN country bac ON ba.country = bac.id
            INNER JOIN `user` u ON o.user_id = u.id
            JOIN coupon c ON o.coupon_id = c.id
            WHERE o.id = ?
        ';
        $statement = $this->pdo->prepare($sql);
        $statement->execute([$id]);
        $data = $statement->fetch(\PDO::FETCH_ASSOC);
        if ($data === false) {
            return null;
        }

        $orderDta = new OrderDTA($data);
        $orderDtaConverter = new OrderDtaConverter();
        return $orderDtaConverter->toOrder($orderDta);
    }


    public function findByOrder(Order $order){
        $sql = 'SELECT * FROM order_item WHERE order_id = ?';
        $statement = $this->pdo->prepare($sql);
        $statement->execute([$order->getId()]);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        if ($data === false) {
            return null;
        }

        $orderItems = [];
        foreach ($data as $datum) {
            $orderItemDta = new OrderItemDTA($datum);
            $orderItemDtaConverter = new OrderItemDtaConverter();
            $orderItem = $orderItemDtaConverter->toOrderItem($orderItemDta);

            $orderItem->setOrder($order);

            $productRepository = new ProductRepository($this->pdo);
            $product = $productRepository->find($datum['product_id']);
            $orderItem->setProduct($product);
            $orderItems[] = $orderItem;
        }
        return $orderItems;
    }
}