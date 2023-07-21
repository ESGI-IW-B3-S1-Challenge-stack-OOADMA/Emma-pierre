<?php

namespace App\Repository;

use App\DTA\Converter\OrderDtaConverter;
use App\DTA\OrderDTA;
use App\Entity\Order;
use App\Entity\User;
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
    public function findAllByUser(User $user): array
    {
        $sql = '
            SELECT *
            FROM `order`
            WHERE user_id = ?
            ORDER BY id DESC
        ';
        $statement = $this->pdo->prepare($sql);
        $statement->execute([$user->getId()]);
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
}