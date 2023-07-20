<?php

namespace App\Repository;

use App\DTA\Converter\CouponDtaConverter;
use App\DTA\CouponDTA;

class CouponRepository extends AbstractRepository
{
    public function find(int $id){
        $statement = $this->pdo->query('SELECT * FROM coupon WHERE id = ?');
        $statement->execute([$id]);
        $data = $statement->fetch(\PDO::FETCH_ASSOC);
        if ($data === false) {
            return null;
        }

        $couponDta = new CouponDTA([$data]);
        $couponDtaConverter = new CouponDtaConverter();

        $coupon = $couponDtaConverter->toCoupon($couponDta);

        return $coupon;
    }
}