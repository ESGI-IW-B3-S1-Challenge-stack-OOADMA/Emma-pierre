<?php

namespace App\DTA\Converter;

use App\DTA\CouponDTA;
use App\Entity\Coupon;

class CouponDtaConverter
{
    public function toCoupon(CouponDTA $couponDTA): Coupon
    {
        $coupon = new Coupon();
        $coupon->setId($couponDTA->id);
        $coupon->setName($couponDTA->name);
        $coupon->setCode($couponDTA->code);
        $coupon->setPercent($couponDTA->percent);
        $coupon->setDuration($couponDTA->duration);
        $coupon->setDurationInMonth($couponDTA->duration_in_months);
        $coupon->setValid($couponDTA->valid);
        $coupon->setCreatedAt($couponDTA->created_at);
        $coupon->setUpdatedAt($couponDTA->updated_at);
        return $coupon;
    }
}