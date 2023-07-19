<?php

namespace App\Repository;

use App\DTA\Converter\ProductDtaConverter;
use App\DTA\ProductDTA;


class ProductRepository extends AbstractRepository
{
    public function findActivatedByFilters(int $productCategoryId, int $jewelryCategoryId): ?array
    {
        $sql = '
            SELECT
                p.*,
                pc.id as product_category_id,
                pc.`name` product_category_name,
                pc.created_at product_category_created_at,
                pc.updated_at product_category_updated_at,
                jc.id as jewelry_category_id,
                jc.`name` jewelry_category_name,
                jc.created_at jewelry_category_created_at,
                jc.updated_at jewelry_category_updated_at
            FROM
                `product` p
                JOIN product_category pc on pc.id = p.product_category_id
                JOIN jewelry_category jc on jc.id = p.jewelry_category_id
            WHERE
                p.available = 1
        ';

        $sql .= $productCategoryId ? ' AND p.product_category_id = :product_category_id' : "";
        $sql .= $jewelryCategoryId ? ' AND p.jewelry_category_id = :jewelry_category_id' : "";
        
        $statement = $this->pdo->prepare($sql);

        $params = [];
        if($productCategoryId){
            $params['product_category_id'] = $productCategoryId;
        }
        if($jewelryCategoryId){
            $params['jewelry_category_id'] = $jewelryCategoryId;
        }

        $statement->execute($params);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        if ($data === false) {
            return null;
        }

        foreach ($data as $product) {
            $productDta = new ProductDTA($product);
            $productDtaConverter = new ProductDtaConverter();
            $products[] = $productDtaConverter->toProduct($productDta);
        }

        return $products;
    }
}
