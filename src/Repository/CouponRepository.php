<?php

namespace App\Repository;

use App\DTA\Converter\CouponDtaConverter;
use App\DTA\CouponDTA;
use Exception;

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

    /**
     * @throws Exception
     */
    public function findByCode(string $code)
    {
        $sql = '
            SELECT *
            FROM `coupon`
            WHERE code = ?
        ';
        $statement = $this->pdo->prepare($sql);
        $statement->execute([$code]);
        $data = $statement->fetch(\PDO::FETCH_ASSOC);

        if ($data === false) {
            return false;
        }

        $couponDta = new CouponDTA($data);
        $couponDtaConverter = new CouponDtaConverter();
        return $couponDtaConverter->toCoupon($couponDta);
    }
}