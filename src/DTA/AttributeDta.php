<?php

namespace App\DTA;

use App\Entity\AttributeGroup;

class AttributeDta
{
    public int $id;
    public string $data;
    public AttributeGroupDta $attributeGroupDta;
    public \DateTimeImmutable $created_at;
    public \DateTimeImmutable $updated_at;

    public function __construct(array $data)
    {
        $this->id = $data['id'];
        $this->data = $data['data'];
        $this->attributeGroupDta = new AttributeGroupDta([
            'id' => $data['attribute_group_id'],
            'name' => $data['attribute_group_name'],
            'attribute_type' => $data['attribute_group_attribute_type'],
            'created_at' => $data['attribute_group_created_at'],
            'updated_at' => $data['attribute_group_updated_at'],
        ]);
        $this->created_at = new  \DateTimeImmutable($data['created_at']);
        $this->updated_at = new  \DateTimeImmutable($data['updated_at']);
    }
}