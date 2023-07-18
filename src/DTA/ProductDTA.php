<?php

namespace App\DTA;

use App\DTA\Converter\JewelryCategoryDtaConverter;
use App\DTA\Converter\ProductCategoryDtaConverter;
use App\Entity\JewelryCategory;
use App\Entity\ProductCategory;
use App\Repository\JewelryCategoryRepository;
use App\Repository\ProductCategoryRepository;

class ProductDTA
{
    public int $id;
    public string $name;
    public string $description;
    public ProductCategory $product_category;
    public JewelryCategory $jewelry_category;
    public int $price;
    public int $available;
    public \DateTimeImmutable $created_at;
    public \DateTimeImmutable $updated_at;

    public function __construct(
        array $data
    )
    {
        $this->id = $data['id'];
        $this->name = $data['name'];
        $this->description = $data['description'];
        $productCategoryDTA = new ProductCategoryDTA([
            'id' => $data['product_category_id'], 
            'name' => $data['product_category_name'], 
            'created_at' => $data['product_category_created_at'], 
            'updated_at' => $data['product_category_updated_at']
        ]);
        $productCategoryDtaConverter = new ProductCategoryDtaConverter();
        $this->product_category = $productCategoryDtaConverter->toProductCategory($productCategoryDTA);
        $jewelryCategoryDTA = new JewelryCategoryDTA([
            'id' => $data['jewelry_category_id'], 
            'name' => $data['jewelry_category_name'], 
            'created_at' => $data['jewelry_category_created_at'], 
            'updated_at' => $data['jewelry_category_updated_at']
        ]);
        $jewelryCategoryDtaConverter = new JewelryCategoryDtaConverter();
        $this->jewelry_category = $jewelryCategoryDtaConverter->toJewelryCategory($jewelryCategoryDTA);
        $this->price = $data['price'];
        $this->available = $data['available'];
        $this->created_at = new \DateTimeImmutable($data['created_at']);
        $this->updated_at = new \DateTimeImmutable($data['updated_at']);
    }
}