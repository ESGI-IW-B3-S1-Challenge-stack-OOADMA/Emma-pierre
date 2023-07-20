<?php

namespace App\DTA;

use App\DTA\Converter\JewelryCategoryDtaConverter;
use App\DTA\Converter\ProductCategoryDtaConverter;
use App\DTA\Converter\ProductImageDtaConverter;
use App\Entity\JewelryCategory;
use App\Entity\ProductCategory;
use App\Repository\JewelryCategoryRepository;
use App\Repository\ProductCategoryRepository;
use App\Entity\ProductImage;	

class ProductDTA
{
    public int $id;
    public string $name;
    public string $description;
    public ProductCategory $product_category;
    public JewelryCategory $jewelry_category;
    public int $price;
    public bool $available;
    public \DateTimeImmutable $created_at;
    public \DateTimeImmutable $updated_at;
    public ?ProductImage $product_image;

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
        if(!empty($data['product_image_id'])){
            $productImageDTA = new ProductImageDTA([
                'id' => $data['product_image_id'], 
                'path' => $data['product_image_path'], 
                'created_at' => $data['product_image_created_at'], 
                'updated_at' => $data['product_image_updated_at']
            ]);
            $productImageDtaConverter = new ProductImageDtaConverter();
            $this->product_image = $productImageDtaConverter->toProductImage($productImageDTA);
        }else{
            $this->product_image = null;
        }
        
        $this->price = $data['price'];
        $this->available = $data['available'];
        $this->created_at = new \DateTimeImmutable($data['created_at']);
        $this->updated_at = new \DateTimeImmutable($data['updated_at']);
        $this->stripe_id = $data['stripe_id'];
    }
}