<?php

namespace App\Repository;

use App\DTA\Converter\ProductImageDtaConverter;
use App\DTA\ProductImageDTA;
use App\Entity\ProductImage;

class ProductImageRepository extends AbstractRepository
{

    public function findByIdProduct(int $id_product): ?ProductImage
    {
        $sql = '
            SELECT
                *
            FROM
                `product_image`
            WHERE
                product_id = :id
        ';
        $statement = $this->pdo->prepare($sql);
        $statement->execute(['id' => $id]);
        $data = $statement->fetch(\PDO::FETCH_ASSOC);
        if ($data === false) {
            return null;
        }

        $productImageDta = new ProductImageDTA($data);
        $productImageDtaConverter = new ProductImageDtaConverter();
        $productImage = $productImageDtaConverter->toProductImage($productImage);

        return $productImage;
    }

    public function add(ProductImage $product_image):int{
        $statement = $this->pdo->prepare('INSERT INTO `product_image` (`product_id`, `path`) VALUES (?, ?)');
        $statement->execute([$product_image->getIdProduct(), $product_image->getPath()]);
        return $this->pdo->lastInsertId();
    }

    public function delete(int $id){
        $statement = $this->pdo->prepare('DELETE FROM `product_image` WHERE `id` = ?');
        $statement->execute([$id]);
    }
}