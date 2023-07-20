<?php

namespace App\Entity;

use DateTimeImmutable;

class Order
{
    private int $id;
    private string $reference;
    private ?Address $shipping_address = null;
    private ?Address $billing_address = null;
    private User $user;
    private ?Coupon $coupon = null;
    private int $total;
    private string $status;

    /**
     * @var array<OrderItem>
     */
    private array $orderItems;
    private DateTimeImmutable $created_at;
    private DateTimeImmutable $updated_at;

    private string $stripe_id;

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
     * @param ?Address $address
     */
    public function setShippingAddress(?Address $address): void
    {
        $this->shipping_address = $address;
    }

    /**
     * @return ?Address
     */
    public function getShippingAddress(): ?Address
    {
        return $this->shipping_address;
    }

    /**
     * @param ?Address $address
     */
    public function setBillingAddress(?Address $address): void
    {
        $this->billing_address = $address;
    }

    /**
     * @return ?Address
     */
    public function getBillingAddress(): ?Address
    {
        return $this->billing_address;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param ?Coupon $coupon
     */
    public function setCoupon(?Coupon $coupon): void
    {
        $this->coupon = $coupon;
    }

    /**
     * @return ?Coupon
     */
    public function getCoupon(): ?Coupon
    {
        return $this->coupon;
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
     * @param string $status
     */
    public function setStatus(string $status): void
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

    /**
     * @return array
     */
    public function getOrderItems(): array
    {
        return $this->orderItems;
    }

    /**
     * @param array $orderItems
     */
    public function setOrderItems(array $orderItems): void
    {
        $this->orderItems = $orderItems;
    }

    /**
     * @return string
     */
    public function getStripeId(): string
    {
        return $this->stripe_id;
    }

    /**
     * @param string $stripe_id
     */
    public function setStripeId(string $stripe_id): void
    {
        $this->stripe_id = $stripe_id;
    }
}