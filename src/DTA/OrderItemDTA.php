<?php

namespace App\DTA;

use App\DTA\Converter\AddressDtaConverter;
use App\DTA\Converter\CouponDtaConverter;
use App\DTA\Converter\OrderDtaConverter;
use App\DTA\Converter\UserDtaConverter;
use App\Entity\Address;
use App\Entity\Coupon;
use App\Entity\Order;
use App\Entity\Product;
use App\Entity\User;
use DateTimeImmutable;
use Exception;

class OrderItemDTA
{
    public int $id;

    public Order $order;

    public Product $product;

    public int $quantity;

    public \DateTimeImmutable $created_at;
    public \DateTimeImmutable $updated_at;

    /**
     * @throws Exception
     */
    public function __construct(array $data)
    {
        $this->id = $data['id'];
        $this->quantity = $data['quantity'];
        $this->created_at = new DateTimeImmutable($data['created_at']);
        $this->updated_at = new DateTimeImmutable($data['updated_at']);
    }
}