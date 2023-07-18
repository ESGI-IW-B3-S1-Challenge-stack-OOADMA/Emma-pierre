<?php

namespace App\Entity;

use DateTimeImmutable;

class Order
{
    protected int $id;
    protected string $reference;
    protected int $shipping_address_id;
    protected int $billing_address_id;
    protected int $user_id;
    protected int $coupon_id;
    protected int $total;
    protected string $status;
    protected DateTimeImmutable $created_at;
    protected DateTimeImmutable $updated_at;

    public function __construct()
    {
        $this->created_at = new DateTimeImmutable('now');
        $this->updated_at = new DateTimeImmutable('now');
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param string $reference
     */
    public function setReference(string $reference): void
    {
        $this->reference = $reference;
    }

    /**
     * @return string
     */
    public function getReference(): string
    {
        return $this->reference;
    }

    /**
     * @param int $id
     */
    public function setShippingAddressId(int $id): void
    {
        $this->shipping_address_id = $id;
    }

    /**
     * @return int
     */
    public function getShippingAddressId(): int
    {
        return $this->shipping_address_id;
    }

    /**
     * @param int $id
     */
    public function setBillingAddressId(int $id): void
    {
        $this->billing_address_id = $id;
    }

    /**
     * @return int
     */
    public function getBillingAddressId(): int
    {
        return $this->billing_address_id;
    }

    /**
     * @param int $id
     */
    public function setUserId(int $id): void
    {
        $this->user_id = $id;
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->user_id;
    }

    /**
     * @param int $id
     */
    public function setCouponId(int $id): void
    {
        $this->coupon_id = $id;
    }

    /**
     * @return int
     */
    public function getCouponId(): int
    {
        return $this->coupon_id;
    }

    /**
     * @param int $total
     */
    public function setTotal(int $total): void
    {
        $this->total = $total;
    }

    /**
     * @return int
     */
    public function getTotal(): int
    {
        return $this->total;
    }

    /**
     * @param int $status
     */
    public function setStatus(int $status): void
    {
        $this->status = $status;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @return DateTimeImmutable
     */
    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->created_at;
    }

    /**
     * @param DateTimeImmutable $created_at
     */
    public function setCreatedAt(DateTimeImmutable $created_at): void
    {
        $this->created_at = $created_at;
    }

    /**
     * @return DateTimeImmutable
     */
    public function getUpdatedAt(): DateTimeImmutable
    {
        return $this->updated_at;
    }

    /**
     * @param DateTimeImmutable $updated_at
     */
    public function setUpdatedAt(DateTimeImmutable $updated_at): void
    {
        $this->updated_at = $updated_at;
    }
}