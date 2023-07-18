<?php

namespace App\Repository;

use App\DTA\Converter\JewelryCategoryDtaConverter;
use App\DTA\JewelryCategoryDTA;
use App\Entity\JewelryCategory;

class JewelryCategoryRepository extends AbstractRepository
{

    public function findById(int $id): ?JewelryCategory
    {
        $sql = '
            SELECT
                *
            FROM
                `jewelry_category`
            WHERE
                id = :id
        ';
        $statement = $this->pdo->prepare($sql);
        $statement->execute(['id' => $id]);
        $data = $statement->fetch(\PDO::FETCH_ASSOC);
        if ($data === false) {
            return null;
        }

        $jewelryCategoryDta = new JewelryCategoryDTA($data);
        $jewelryCategoryDtaConverter = new JewelryCategoryDtaConverter();
        $jewelryCategory = $jewelryCategoryDtaConverter->toJewelryCategory($jewelryCategoryDta);

        return $jewelryCategory;
    }
}
