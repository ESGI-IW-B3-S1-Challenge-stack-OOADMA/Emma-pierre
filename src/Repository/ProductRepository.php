<?php

namespace App\Repository;

use App\DTA\Converter\ProductDtaConverter;
use App\DTA\ProductDTA;


class ProductRepository extends AbstractRepository
{
    public function findAllActivated(): ?array
    {
        $statement = $this->pdo->prepare('SELECT * FROM `product` p WHERE p.available = 1');
        $statement->execute();
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
