<?php

namespace App\Entity;

class Attribute
{
    private int $id;
    private string $data;
    private AttributeGroup $attributeGroup;
    private \DateTimeImmutable $created_at;
    private \DateTimeImmutable $updated_at;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getData(): string
    {
        return $this->data;
    }

    /**
     * @param string $data
     */
    public function setData(string $data): void
    {
        $this->data = $data;
    }

    /**
     * @return AttributeGroup
     */
    public function getAttributeGroup(): AttributeGroup
    {
        return $this->attributeGroup;
    }

    /**
     * @param AttributeGroup $attributeGroup
     */
    public function setAttributeGroup(AttributeGroup $attributeGroup): void
    {
        $this->attributeGroup = $attributeGroup;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->created_at;
    }

    /**
     * @param \DateTimeImmutable $created_at
     */
    public function setCreatedAt(\DateTimeImmutable $created_at): void
    {
        $this->created_at = $created_at;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getUpdatedAt(): \DateTimeImmutable
    {
        return $this->updated_at;
    }

    /**
     * @param \DateTimeImmutable $updated_at
     */
    public function setUpdatedAt(\DateTimeImmutable $updated_at): void
    {
        $this->updated_at = $updated_at;
    }
}