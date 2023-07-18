<?php

namespace App\DTA;

class ProductCategoryDTA
{
    public int $id;
    public string $name;
    public \DateTimeImmutable $created_at;
    public \DateTimeImmutable $updated_at;

    public function __construct(array $data)
    {
        $this->id = $data['id'];
        $this->name = $data['name'];
        $this->created_at = new \DateTimeImmutable($data['created_at']);
        $this->updated_at = new \DateTimeImmutable($data['updated_at']);
    }
}