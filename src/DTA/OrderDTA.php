<?php

namespace App\DTA;

use DateTimeImmutable;
use Exception;

class OrderDTA
{
    public int $id;
    public string $reference;
    public int $shippingAddressId;
    public int $billingAddressId;
    public int $userId;
    public int $couponId;
    public int $total;
    public string $status;
    public DateTimeImmutable $created_at;
    public DateTimeImmutable $updated_at;

    /**
     * @throws Exception
     */
    public function __construct(array $data)
    {
        $this->id = $data['id'];
        $this->reference = $data['reference'];
        $this->shippingAddressId = $data['shippingAddressId'];
        $this->billingAddressId = $data['billingAddressId'];
        $this->userId = $data['userId'];
        $this->couponId = $data['couponId'];
        $this->total = $data['total'];
        $this->status = $data['status'];
        $this->created_at = new DateTimeImmutable($data['created_at']);
        $this->updated_at = new DateTimeImmutable($data['updated_at']);
    }
}