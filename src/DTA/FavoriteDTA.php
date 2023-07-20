<?php

namespace App\DTA;

use App\DTA\Converter\ProductDtaConverter;
use App\DTA\Converter\UserDtaConverter;
use App\Entity\Product;
use App\Entity\User;
use DateTimeImmutable;
use Exception;

class FavoriteDTA
{
    public int $id;
    public User $user;
    public Product $product;
    public DateTimeImmutable $created_at;
    public DateTimeImmutable $updated_at;

    /**
     * @throws Exception
     */
    public function __construct(array $data)
    {
        $this->id = $data['id'];

        $userDta = new UserDTA([
            'id' => $data['user_id'],
            'lastname' => $data['user_lastname'],
            'firstname' => $data['user_firstname'],
            'email' => $data['user_email'],
            'phone_number' => $data['user_phone_number'],
            'password' => $data['user_password'],
            'roles' => $data['user_roles'],
            'created_at' => $data['user_created_at'],
            'updated_at' => $data['user_updated_at'],
        ]);
        $userDtaConverter = new UserDtaConverter();
        $this->user = $userDtaConverter->toUser($userDta);

        $productDta = new ProductDTA([
            'id' => $data['product_id'],
            'name' => $data['product_name'],
            'description' => $data['product_description'],
            'product_category_id' => $data['product_product_category_id'],
            'product_category_name' => $data['product_product_category_name'],
            'product_category_created_at' => $data['product_product_category_created_at'],
            'product_category_updated_at' => $data['product_product_category_updated_at'],
            'jewelry_category_id' => $data['product_jewelry_category_id'],
            'jewelry_category_name' => $data['product_jewelry_category_name'],
            'jewelry_category_created_at' => $data['product_jewelry_category_created_at'],
            'jewelry_category_updated_at' => $data['product_jewelry_category_updated_at'],
            'price' => $data['product_price'],
            'available' => $data['product_available'],
            'created_at' => $data['product_created_at'],
            'updated_at' => $data['product_updated_at'],
            'stripe_id' => $data['product_stripe_id']
        ]);
        $productDtaConverter = new ProductDtaConverter();
        $this->product = $productDtaConverter->toProduct($productDta);

        $this->created_at = new DateTimeImmutable($data['created_at']);
        $this->updated_at = new DateTimeImmutable($data['updated_at']);
    }
}