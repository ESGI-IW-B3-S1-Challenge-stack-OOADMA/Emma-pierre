<?php

namespace App\DTA\Converter;

use App\DTA\ProductDTA;
use App\Entity\Product;

class ProductDtaConverter
{
    public function toProduct(ProductDTA $productDTA)
    {
        $product = new Product();
        $product->setId($productDTA->id);
        $product->setName($productDTA->name);
        $product->setDescription($productDTA->description);
        $product->setProductCategoryId($productDTA->product_category_id);
        $product->setJewelryCategoryId($productDTA->jewelry_category_id);
        $product->setPrice($productDTA->price);
        $product->setAvailable($productDTA->available);
        $product->setCreatedAt($productDTA->created_at);
        $product->setUpdatedAt($productDTA->updated_at);
        return $product;
    }
}