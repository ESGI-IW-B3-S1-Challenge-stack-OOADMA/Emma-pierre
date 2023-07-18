<?php

namespace App\Entity;

class Product
{
    private int $id;
    private string $name;
    private string $description;
    private ProductCategory $product_category;
    private JewelryCategory $jewelry_category;
    private int $price;
    private int $available;
    private \DateTimeImmutable $created_at;
    private \DateTimeImmutable $updated_at;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }
    
    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;
        return $this;
    }

    public function getProductCategory(): ProductCategory
    {
        return $this->product_category;
    }

    public function setProductCategory(ProductCategory $product_category): self
    {
        $this->product_category = $product_category;
        return $this;
    }

    public function getJewelryCategory(): JewelryCategory
    {
        return $this->jewelry_category;
    }

    public function setJewelryCategory(JewelryCategory $jewelry_category): self
    {
        $this->jewelry_category = $jewelry_category;
        return $this;
    }

    public function getPrice(): int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;
        return $this;
    }

    public function getAvailable(): int
    {
        return $this->available;
    }

    public function setAvailable(int $available): self
    {
        $this->available = $available;
        return $this;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): self
    {
        $this->created_at = $created_at;
        return $this;
    }

    public function getUpdatedAt(): \DateTimeImmutable
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTimeImmutable $updated_at): self
    {
        $this->updated_at = $updated_at;
        return $this;
    }
}