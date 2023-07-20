<?php

namespace App\DTA\Converter;

use App\DTA\ProductImageDTA;
use App\Entity\ProductImage;

class ProductImageDtaConverter
{
    public function toProductImage(ProductImageDTA $productImageDTA)
    {
        $productImg = new ProductImage();
        $productImg->setId($productImageDTA->id);
        $productImg->setPath($productImageDTA->path);
        //$productImg->setPosition($productImageDTA->position);
        $productImg->setCreatedAt($productImageDTA->created_at);
        $productImg->setUpdatedAt($productImageDTA->updated_at);
        return $productImg;
    }
}