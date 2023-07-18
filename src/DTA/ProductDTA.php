<?php

namespace App\DTA;


class ProductDTA
{
    public int $id;
    public string $name;
    public string $description;
    public int $product_category_id;
    public int $jewelry_category_id;
    public int $price;
    public int $available;
    public \DateTimeImmutable $created_at;
    public \DateTimeImmutable $updated_at;

    public function __construct(array $data)
    {
        $this->id = $data['id'];
        $this->name = $data['name'];
        $this->description = $data['description'];
        $this->product_category_id = $data['product_category_id'];
        $this->jewelry_category_id = $data['jewelry_category_id'];
        $this->price = $data['price'];
        $this->available = $data['available'];
        $this->created_at = new \DateTimeImmutable($data['created_at']);
        $this->updated_at = new \DateTimeImmutable($data['updated_at']);
    }
}