<?php

namespace App\Repository;

use App\DTA\Converter\ProductCategoryDtaConverter;
use App\DTA\Converter\ProductDtaConverter;
use App\DTA\ProductCategoryDTA;
use App\DTA\ProductDTA;
use App\Entity\ProductCategory;

class ProductCategoryRepository extends AbstractRepository
{

    public function findById(int $id): ?ProductCategory
    {
        $sql = '
            SELECT
                *
            FROM
                `product_category`
            WHERE
                id = :id
        ';
        $statement = $this->pdo->prepare($sql);
        $statement->execute(['id' => $id]);
        $data = $statement->fetch(\PDO::FETCH_ASSOC);
        if ($data === false) {
            return null;
        }

        $productCategoryDta = new ProductCategoryDTA($data);
        $productCategoryDtaConverter = new ProductCategoryDtaConverter();
        $productCategory = $productCategoryDtaConverter->toProductCategory($productCategoryDta);

        return $productCategory;
    }
}
