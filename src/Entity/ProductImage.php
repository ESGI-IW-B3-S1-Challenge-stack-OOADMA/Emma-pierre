<?php

namespace App\Entity;

class ProductImage
{
    private int $id;
    private int $id_product;
    private string $path;
    private \DateTimeImmutable $created_at;
    private \DateTimeImmutable $updated_at;
    private int $position;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getIdProduct(): int
    {
        return $this->id_product;
    }

    public function setIdProduct(int $id_product): self
    {
        $this->id_product = $id_product;
        return $this;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function setPath(string $path): self
    {
        $this->path = $path;
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