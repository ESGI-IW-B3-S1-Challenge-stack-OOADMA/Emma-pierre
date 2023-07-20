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
        $statement = $this->pdo->prepare('INSERT INTO `order` (`reference`, `shipping_address_id`, `billing_address_id`, `user_id`, `coupon_id`, `total`, `status`, `stripe_id`) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
        $statement->execute([
            $order->getReference(),
            $order->getShippingAddress()?->getId(),
            $order->getBillingAddress()?->getId(),
            $order->getUser()->getId(),
            $order->getCoupon()?->getId(),
            $order->getTotal(),
            $order->getStatus(),
            $order->getStripeId()
        ]);
        return $this->pdo->lastInsertId();
    }

    public function update(Order $order){
        $statement = $this->pdo->prepare('UPDATE `order` SET reference = ?, shipping_address_id = ?, billing_address_id = ?, user_id = ?, coupon_id = ?, total = ?, status = ?, stripe_id = ? WHERE id = ?');
        $statement->execute([
            $order->getReference(),
            $order->getShippingAddress()?->getId(),
            $order->getBillingAddress()?->getId(),
            $order->getUser()->getId(),
            $order->getCoupon()?->getId(),
            $order->getTotal(),
            $order->getStatus(),
            $order->getStripeId(),
            $order->getId()
        ]);
    }

    /**
     * @throws Exception
     */
    public function find(int $id): Order|null
    {
        $sql = 'SELECT * FROM `order` WHERE id = ?';
        $statement = $this->pdo->prepare($sql);
        $statement->execute([$id]);
        $data = $statement->fetch(\PDO::FETCH_ASSOC);
        if ($data === false) {
            return null;
        }

        $orderDta = new OrderDTA($data);
        $orderDtaConverter = new OrderDtaConverter();
        $order = $orderDtaConverter->toOrder($orderDta);

        $addressRepository = new AddressRepository($this->pdo);
        if ($data['shipping_address_id']) {
            $order->setShippingAddress($addressRepository->find($data['shipping_address_id']));
        }

        if ($data['billing_address_id']) {
            $order->setBillingAddress($addressRepository->find($data['billing_address_id']));
        }

        $userRepo = new UserRepository($this->pdo);
        if ($data['user_id']) {
            $order->setUser($userRepo->find($data['user_id']));
        }

        $couponRepository = new CouponRepository($this->pdo);
        if ($data['coupon_id']) {
            $order->setCoupon($couponRepository->find($data['coupon_id']));
        }

        $orderItemRepository = new OrderItemRepository($this->pdo);
        $orderItems = $orderItemRepository->findByOrder($order);
        $order->setOrderItems($orderItems);

        return $order;
    }

    public function findOneByReference(string $reference): Order|null
    {
        $sql = 'SELECT * FROM `order` WHERE reference = ?';
        $statement = $this->pdo->prepare($sql);
        $statement->execute([$reference]);
        $data = $statement->fetch(\PDO::FETCH_ASSOC);
        if ($data === false) {
            return null;
        }

        $orderDta = new OrderDTA($data);
        $orderDtaConverter = new OrderDtaConverter();
        $order = $orderDtaConverter->toOrder($orderDta);

        $addressRepository = new AddressRepository($this->pdo);
        if ($data['shipping_address_id']) {
            $order->setShippingAddress($addressRepository->find($data['shipping_address_id']));
        }

        if ($data['billing_address_id']) {
            $order->setBillingAddress($addressRepository->find($data['billing_address_id']));
        }

        $userRepo = new UserRepository($this->pdo);
        if ($data['user_id']) {
            $order->setUser($userRepo->find($data['user_id']));
        }

        $couponRepository = new CouponRepository($this->pdo);
        if ($data['coupon_id']) {
            $order->setCoupon($couponRepository->find($data['coupon_id']));
        }

        return $order;
    }

    /**
     * @return array<Order>
     * @throws Exception
     */
    public function findAll(): array
    {
        $sql = '
            SELECT *
            FROM `order` 
            ORDER BY id DESC
        ';
        $statement = $this->pdo->prepare($sql);
        $statement->execute();
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);

        if ($data === false) {
            return [];
        }

        $orders = [];

        foreach ($data as $datum) {
            $orderDta = new OrderDTA($datum);
            $orderDtaConverter = new OrderDtaConverter();
            $order = $orderDtaConverter->toOrder($orderDta);

            $addressRepository = new AddressRepository($this->pdo);
            if ($datum['shipping_address_id']) {
                $order->setShippingAddress($addressRepository->find($datum['shipping_address_id']));
            }

            if ($datum['billing_address_id']) {
                $order->setBillingAddress($addressRepository->find($datum['billing_address_id']));
            }

            $userRepo = new UserRepository($this->pdo);
            if ($datum['user_id']) {
                $order->setUser($userRepo->find($datum['user_id']));
            }

            $couponRepository = new CouponRepository($this->pdo);
            if ($datum['coupon_id']) {
                $order->setCoupon($couponRepository->find($datum['coupon_id']));
            }
            $orders[] = $order;
        }


        return $orders;
    }

    /**
     * @return array<Order>
     * @throws Exception
     */
    public function findAllByUser(int $user_id): array
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
            ORDER BY o.id DESC
            WHERE o.user_id = ?
        ';
        $statement = $this->pdo->prepare($sql);
        $statement->execute([$user_id]);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);

        if ($data === false) {
            return [];
        }

        $orders = [];

        foreach ($data as $order) {
            $orderDta = new OrderDTA($order);
            $orderDtaConverter = new OrderDtaConverter();
            $orders[] = $orderDtaConverter->toOrder($orderDta);
        }

        return $orders;
    }
}