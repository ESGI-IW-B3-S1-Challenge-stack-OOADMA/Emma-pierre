<?php

namespace App\Repository;

use App\DTA\AttributeGroupDta;
use App\DTA\Converter\AttributeGroupDtaConverter;
use App\Entity\AttributeGroup;

class AttributeGroupRepository extends AbstractRepository
{
    public function findAllWithoutAttribute(): ?array
    {
        $sql = 'SELECT * FROM attribute_group';
        $statement = $this->pdo->prepare($sql);
        $statement->execute();
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        if ($data === false) {
            return null;
        }

        $attributeGroups = [];
        foreach ($data as $attributeGroup) {
            $attributeGroupDta = new AttributeGroupDta($attributeGroup);
            $attributeGroupDtaConverter = new AttributeGroupDtaConverter();
            $attributeGroups[] = $attributeGroupDtaConverter->toAttributeGroup($attributeGroupDta);
        }

        return $attributeGroups;
    }

    public function find(int $id)
    {
        $sql = 'SELECT * FROM attribute_group WHERE id = ?';
        $statement = $this->pdo->prepare($sql);
        $statement->execute([$id]);
        $data = $statement->fetch(\PDO::FETCH_ASSOC);
        if ($data === false) {
            return null;
        }

        $attributeGroupDta = new AttributeGroupDta($data);
        $attributeGroupDtaConverter = new AttributeGroupDtaConverter();

        return $attributeGroupDtaConverter->toAttributeGroup($attributeGroupDta);
    }

    public function findOneByName(string $name)
    {
        $sql = 'SELECT * FROM attribute_group WHERE name = ?';
        $statement = $this->pdo->prepare($sql);
        $statement->execute([$name]);
        $data = $statement->fetch(\PDO::FETCH_ASSOC);
        if ($data === false) {
            return null;
        }

        $attributeGroupDta = new AttributeGroupDta($data);
        $attributeGroupDtaConverter = new AttributeGroupDtaConverter();

        return $attributeGroupDtaConverter->toAttributeGroup($attributeGroupDta);
    }

    public function add(AttributeGroup $attributeGroup)
    {
        $statement = $this->pdo->prepare('INSERT INTO `attribute_group` (`name`, `attribute_type`, `created_at`, `updated_at`) VALUES (?, ?, ?, ?)');
        $statement->execute([$attributeGroup->getName(), $attributeGroup->getAttributeType(), $attributeGroup->getCreatedAt()->format('Y-m-d H:i:s'), $attributeGroup->getUpdatedAt()->format('Y-m-d H:i:s')]);
        return $this->pdo->lastInsertId();
    }

    public function update(AttributeGroup $attributeGroup): void
    {
        $sql = 'UPDATE `attribute_group` SET name = ?, attribute_type = ?, updated_at = ? WHERE id = ?';
        $statement = $this->pdo->prepare($sql);
        $statement->execute([$attributeGroup->getName(), $attributeGroup->getAttributeType(), $attributeGroup->getUpdatedAt()->format('Y-m-d H:i:s'), $attributeGroup->getId()]);
    }

    public function delete(int $id)
    {
        $sql = 'DELETE FROM attribute_group WHERE id = ?';
        $statement = $this->pdo->prepare($sql);
        $statement->execute([$id]);
    }
}