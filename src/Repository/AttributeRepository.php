<?php

namespace App\Repository;

use App\DTA\AttributeDta;
use App\DTA\Converter\AttributeDtaConverter;
use App\Entity\Attribute;
use App\Entity\AttributeGroup;

class AttributeRepository extends AbstractRepository
{
    public function add(Attribute $attribute)
    {
        $statement = $this->pdo->prepare('INSERT INTO `attribute` (`data`, `attribute_group_id`) VALUES (?, ?)');
        $statement->execute([$attribute->getData(), $attribute->getAttributeGroup()->getId()]);
        return $this->pdo->lastInsertId();
    }

    public function update(Attribute $attribute)
    {
        $statement = $this->pdo->prepare('UPDATE `attribute` SET `data` = ? WHERE `id` = ?');
        $statement->execute([$attribute->getData(), $attribute->getId()]);
    }

    public function findByAttributeGroup(AttributeGroup $attributeGroup): ?array
    {
        $sql = 'SELECT a.id AS id, 
a.data as data, 
a.created_at as created_at, 
a.updated_at as updated_at, 
ag.id AS attribute_group_id,
ag.name AS attribute_group_name,
ag.attribute_type AS attribute_group_attribute_type,
ag.created_at AS attribute_group_created_at,
ag.updated_at AS attribute_group_updated_at
FROM attribute a
JOIN attribute_group ag ON a.attribute_group_id = ag.id
WHERE a.attribute_group_id = ?';
        $statement = $this->pdo->prepare($sql);
        $statement->execute([$attributeGroup->getId()]);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        if ($data === false) {
            return null;
        }

        $attributes = [];
        foreach ($data as $attribute) {
            $attributeDta = new AttributeDta($attribute);
            $attributeDtaConverter = new AttributeDtaConverter();
            $attributes[] = $attributeDtaConverter->toAttribute($attributeDta);
        }

        return $attributes;
    }

    public function findOneByDataAndAttributeGroup(string $data, AttributeGroup $attributeGroup)
    {
        $sql = 'SELECT a.id AS id, 
a.data as data, 
a.created_at as created_at, 
a.updated_at as updated_at, 
ag.id AS attribute_group_id,
ag.name AS attribute_group_name,
ag.attribute_type AS attribute_group_attribute_type,
ag.created_at AS attribute_group_created_at,
ag.updated_at AS attribute_group_updated_at
FROM attribute a
JOIN attribute_group ag ON a.attribute_group_id = ag.id
WHERE a.attribute_group_id = ? AND a.data = ? LIMIT 1';
        $statement = $this->pdo->prepare($sql);
        $statement->execute([$attributeGroup->getId(), $data]);
        $data = $statement->fetch(\PDO::FETCH_ASSOC);
        if ($data === false) {
            return null;
        }

        $attributeDta = new AttributeDta($data);
        $attributeDtaConverter = new AttributeDtaConverter();

        return $attributeDtaConverter->toAttribute($attributeDta);
    }

    public function find(int $id)
    {
        $sql = 'SELECT a.id AS id, 
a.data as data, 
a.created_at as created_at, 
a.updated_at as updated_at, 
ag.id AS attribute_group_id,
ag.name AS attribute_group_name,
ag.attribute_type AS attribute_group_attribute_type,
ag.created_at AS attribute_group_created_at,
ag.updated_at AS attribute_group_updated_at
FROM attribute a
JOIN attribute_group ag ON a.attribute_group_id = ag.id
WHERE a.id = ?';
        $statement = $this->pdo->prepare($sql);
        $statement->execute([$id]);
        $data = $statement->fetch(\PDO::FETCH_ASSOC);
        if ($data === false) {
            return null;
        }

        $attributeGroupDta = new AttributeDta($data);
        $attributeGroupDtaConverter = new AttributeDtaConverter();

        return $attributeGroupDtaConverter->toAttribute($attributeGroupDta);
    }

    public function delete(int $id)
    {
        $sql = 'DELETE FROM attribute WHERE id = ?';
        $statement = $this->pdo->prepare($sql);
        $statement->execute([$id]);
    }
}