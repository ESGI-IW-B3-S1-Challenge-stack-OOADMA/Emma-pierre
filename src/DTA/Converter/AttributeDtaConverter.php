<?php

namespace App\DTA\Converter;

use App\DTA\AttributeDta;
use App\DTA\AttributeGroupDta;
use App\Entity\Attribute;
use App\Entity\AttributeGroup;

class AttributeDtaConverter
{
    public function toAttribute(AttributeDta $attributeDta): Attribute
    {
        $attribute = new Attribute();
        $attribute->setId($attributeDta->id);
        $attribute->setData($attributeDta->data);
        $attributeDtaConverter = new AttributeGroupDtaConverter();
        $attribute->setAttributeGroup($attributeDtaConverter->toAttributeGroup($attributeDta->attributeGroupDta));
        $attribute->setCreatedAt($attributeDta->created_at);
        $attribute->setUpdatedAt($attributeDta->updated_at);
        return $attribute;
    }
}