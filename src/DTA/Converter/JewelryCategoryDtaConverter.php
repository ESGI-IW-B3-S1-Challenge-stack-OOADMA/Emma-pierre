<?php

namespace App\DTA\Converter;

use App\DTA\JewelryCategoryDTA;
use App\Entity\JewelryCategory;

class JewelryCategoryDtaConverter
{
    public function toJewelryCategory(JewelryCategoryDTA $jewelryCategoryDTA)
    {
        $jewelryCategory = new JewelryCategory();
        $jewelryCategory->setId($jewelryCategoryDTA->id);
        $jewelryCategory->setName($jewelryCategoryDTA->name);
        $jewelryCategory->setCreatedAt($jewelryCategoryDTA->created_at);
        $jewelryCategory->setUpdatedAt($jewelryCategoryDTA->updated_at);
        return $jewelryCategory;
    }
}