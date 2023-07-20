<?php

namespace App\DTA;

use App\DTA\Converter\AddressDtaConverter;
use App\DTA\Converter\CouponDtaConverter;
use App\DTA\Converter\UserDtaConverter;
use App\Entity\Address;
use App\Entity\Coupon;
use App\Entity\User;
use DateTimeImmutable;
use Exception;

class OrderDTA
{
    public int $id;
    public string $reference;
    public Address $shipping_address;
    public Address $billing_address;
    public User $user;
    public Coupon $coupon;
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

        $shipping_address_dta = new AddressDTA([
            'id' => $data['shipping_address_id'],
            'name' => $data['shipping_address_name'],
            'address_line1' => $data['shipping_address_address_line1'],
            'address_line2' => $data['shipping_address_address_line2'],
            'city' => $data['shipping_address_city'],
            'postal_code' => $data['shipping_address_postal_code'],
            'country_id' => $data['shipping_address_country_id'],
            'country_name' => $data['shipping_address_country_name'],
            'country_code' => $data['shipping_address_country_code'],
            'country_created_at' => $data['shipping_address_country_created_at'],
            'country_updated_at' => $data['shipping_address_country_updated_at'],
            'created_at' => $data['shipping_address_created_at'],
            'updated_at' => $data['shipping_address_updated_at'],
        ]);
        $shipping_address_dta_converter = new AddressDtaConverter();
        $this->shipping_address = $shipping_address_dta_converter->toAddress($shipping_address_dta);

        $billing_address_dta = new AddressDTA([
            'id' => $data['billing_address_id'],
            'name' => $data['billing_address_name'],
            'address_line1' => $data['billing_address_address_line1'],
            'address_line2' => $data['billing_address_address_line2'],
            'city' => $data['billing_address_city'],
            'postal_code' => $data['billing_address_postal_code'],
            'country_id' => $data['billing_address_country_id'],
            'country_name' => $data['billing_address_country_name'],
            'country_code' => $data['billing_address_country_code'],
            'country_created_at' => $data['billing_address_country_created_at'],
            'country_updated_at' => $data['billing_address_country_updated_at'],
            'created_at' => $data['billing_address_created_at'],
            'updated_at' => $data['billing_address_updated_at'],
        ]);
        $billing_address_dta_converter = new AddressDtaConverter();
        $this->billing_address = $billing_address_dta_converter->toAddress($billing_address_dta);

        $user_dta = new UserDTA([
            'id' => $data['user_id'],
            'lastname' => $data['user_lastname'],
            'firstname' => $data['user_firstname'],
            'email' => $data['user_email'],
            'phone_number' => $data['user_phone_number'],
            'password' => $data['user_password'],
            'roles' => $data['user_roles'],
            'created_at' => $data['user_created_at'],
            'updated_at' => $data['user_updated_at'],
        ]);
        $user_dta_converter = new UserDtaConverter();
        $this->user = $user_dta_converter->toUser($user_dta);

        $coupon_dta = new CouponDTA([
            'id' => $data['coupon_id'],
            'name' => $data['coupon_name'],
            'code' => $data['coupon_code'],
            'percent' => $data['coupon_percent'],
            'duration' => $data['coupon_duration'],
            'duration_in_month' => $data['coupon_duration_in_month'],
            'valid' => $data['coupon_valid'],
            'created_at' => $data['coupon_created_at'],
            'updated_at' => $data['coupon_updated_at'],
        ]);
        $coupon_dta_converter = new CouponDtaConverter();
        $this->coupon = $coupon_dta_converter->toCoupon($coupon_dta);

        $this->total = $data['total'];
        $this->status = $data['status'];
        $this->created_at = new DateTimeImmutable($data['created_at']);
        $this->updated_at = new DateTimeImmutable($data['updated_at']);
    }
}