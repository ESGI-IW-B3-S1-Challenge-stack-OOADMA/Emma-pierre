<?php

namespace App\DTA\Converter;

use App\DTA\AttributeDta;
use App\DTA\AttributeGroupDta;
use App\Entity\AttributeGroup;

class AttributeGroupDtaConverter
{
    public function toAttributeGroup(AttributeGroupDta $attributeGroupDta): AttributeGroup
    {
        $attributeGroup = new AttributeGroup();
        $attributeGroup->setId($attributeGroupDta->id);
        $attributeGroup->setName($attributeGroupDta->name);
        $attributeGroup->setAttributeType($attributeGroupDta->attribute_type);
        $attributeGroup->setCreatedAt($attributeGroupDta->created_at);
        $attributeGroup->setUpdatedAt($attributeGroupDta->updated_at);
        return $attributeGroup;
    }
}