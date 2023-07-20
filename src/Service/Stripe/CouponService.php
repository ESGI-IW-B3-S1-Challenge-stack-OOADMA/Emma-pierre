<?php

namespace App\Service\Stripe;

use App\Entity\Coupon;
use Stripe\Coupon as StripeCoupon;
use Stripe\StripeClient;

class CouponService
{
    private StripeClient $stripeClient;

    public function __construct(string $stripe_secret_key)
    {
        $this->stripeClient = new StripeClient($stripe_secret_key);
    }

    /**
     * createCoupon
     *
     * @param Coupon $coupon
     * @return StripeCoupon
     */
    public function createCoupon(Coupon $coupon)
    {
        return $this->stripeClient->coupons->create([
            'amount_off' => $coupon->getAmountOff() * 100,
            'currency' => 'EUR',
            'duration' => $coupon->getDuration(),
            'name' => $coupon->getName(),
            'redeem_by' => $coupon->getRedeemBy()->getTimestamp(),/** @phpstan-ignore-line */
        ]);
    }

    /**
     * updateCoupon
     *
     * @param Coupon $coupon
     * @return void
     */
    public function updateCoupon(Coupon $coupon)
    {
        if ($coupon->getStripeId()) {
            $this->stripeClient->coupons->update($coupon->getStripeId(), [
                'name' => $coupon->getName(),
            ]);
        }
    }

    /**
     * deleteCoupon
     *
     * @param Coupon $coupon
     * @return void
     */
    public function deleteCoupon(Coupon $coupon)
    {
        if ($coupon->getStripeId()) {
            $this->stripeClient->coupons->delete($coupon->getStripeId());
        }
    }
}