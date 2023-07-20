<?php

namespace App\Repository;

use App\DTA\AttributeDta;
use App\DTA\Converter\AttributeDtaConverter;
use App\Entity\Attribute;
use App\Entity\AttributeGroup;

class AttributeProductRepository extends AbstractRepository
{
    public function add(int $idAttribute, int $idProduct)
    {
        $statement = $this->pdo->prepare('INSERT INTO `attribute_product` (`attribute_id`, `product_id`) VALUES (?, ?)');
        $statement->execute([$idAttribute, $idProduct]);
    }

    public function delete(int $idAttribute, int $idProduct){
        $statement = $this->pdo->prepare('DELETE FROM `attribute_product` WHERE `attribute_id` = ? AND `product_id` = ?');
        $statement->execute([$idAttribute, $idProduct]);
    }

    public function getIdAttributeByIdProduct(int $idProduct){
        $sql = '
            SELECT 
                ap.attribute_id as id
            FROM
                attribute_product ap
            WHERE
                ap.product_id = ?
        ';
        $statement = $this->pdo->prepare($sql);
        $statement->execute([$idProduct]);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);

        $idAttributes = [];
        foreach($data as $attributes){
            $idAttributes[] = $attributes['id'];
        }

        return $idAttributes;
    }
}