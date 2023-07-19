<?php

namespace App\DTA;

class AttributeGroupDta
{
    public int $id;
    public string $name;
    public string $attribute_type;
    public \DateTimeImmutable $created_at;
    public \DateTimeImmutable $updated_at;

    public function __construct(array $data)
    {
        $this->id = $data['id'];
        $this->name = $data['name'];
        $this->attribute_type = $data['attribute_type'];
        $this->created_at = new  \DateTimeImmutable($data['created_at']);
        $this->updated_at = new  \DateTimeImmutable($data['updated_at']);
    }
}