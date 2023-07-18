<?php

namespace App\DTA\Converter;

use App\DTA\ProductCategoryDTA;
use App\Entity\ProductCategory;

class ProductCategoryDtaConverter
{
    public function toProductCategory(ProductCategoryDTA $productCategoryDTA)
    {
        $productCategory = new ProductCategory();
        $productCategory->setId($productCategoryDTA->id);
        $productCategory->setName($productCategoryDTA->name);
        $productCategory->setCreatedAt($productCategoryDTA->created_at);
        $productCategory->setUpdatedAt($productCategoryDTA->updated_at);
        return $productCategory;
    }
}