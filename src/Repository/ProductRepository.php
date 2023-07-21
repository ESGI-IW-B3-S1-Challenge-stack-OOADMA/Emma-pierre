<?php

namespace App\Repository;

use App\DTA\Converter\ProductDtaConverter;
use App\DTA\ProductDTA;
use App\Entity\Product;

class ProductRepository extends AbstractRepository
{

    /**
     * Récupère tout les produits
     * Si on veut récupérer que ceux activer on passe le paramètre à true
     */
    public function findAll(bool $available = false): ?array
    {

        $sql = '
            SELECT
                p.*,
                pc.id as product_category_id,
                pc.`name` product_category_name,
                pc.created_at product_category_created_at,
                pc.updated_at product_category_updated_at,
                jc.id as jewelry_category_id,
                jc.`name` jewelry_category_name,
                jc.created_at jewelry_category_created_at,
                jc.updated_at jewelry_category_updated_at
            FROM
                `product` p
                JOIN product_category pc on pc.id = p.product_category_id
                JOIN jewelry_category jc on jc.id = p.jewelry_category_id
        ';

        if ($available) {
            $sql .= ' WHERE p.available = 1';
        }
        $statement = $this->pdo->prepare($sql);

        $statement->execute();
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        if ($data === false) {
            return null;
        }

        $products = [];
        foreach ($data as $product) {
            $productDta = new ProductDTA($product);
            $productDtaConverter = new ProductDtaConverter();
            $products[] = $productDtaConverter->toProduct($productDta);
        }

        return $products;
    }

    public function find(int $id): ?Product
    {
        $sql = '
            SELECT
                p.*,
                pc.id as product_category_id,
                pc.`name` product_category_name,
                pc.created_at product_category_created_at,
                pc.updated_at product_category_updated_at,
                jc.id as jewelry_category_id,
                jc.`name` jewelry_category_name,
                jc.created_at jewelry_category_created_at,
                jc.updated_at jewelry_category_updated_at,
                pi.id product_image_id,
                pi.path product_image_path,
                pi.created_at product_image_created_at,
                pi.updated_at product_image_updated_at
            FROM
                `product` p
                JOIN product_category pc on pc.id = p.product_category_id
                JOIN jewelry_category jc on jc.id = p.jewelry_category_id
                LEFT JOIN product_image pi on pi.product_id = p.id
            WHERE
                p.id = ?
        ';
        $statement = $this->pdo->prepare($sql);

        $statement->execute([$id]);
        $data = $statement->fetch(\PDO::FETCH_ASSOC);
        if ($data === false) {
            return null;
        }

        $productDta = new ProductDTA($data);

        $productDtaConverter = new ProductDtaConverter();

        return $productDtaConverter->toProduct($productDta);
    }

    public function findMultipleById(array $ids): ?array
    {
        if (empty($ids)) {
            return [];
        }
        $sql = '
            SELECT
                p.*,
                pc.id as product_category_id,
                pc.`name` product_category_name,
                pc.created_at product_category_created_at,
                pc.updated_at product_category_updated_at,
                jc.id as jewelry_category_id,
                jc.`name` jewelry_category_name,
                jc.created_at jewelry_category_created_at,
                jc.updated_at jewelry_category_updated_at,
                pi.id product_image_id,
                pi.path product_image_path,
                pi.created_at product_image_created_at,
                pi.updated_at product_image_updated_at
            FROM
                `product` p
                JOIN product_category pc on pc.id = p.product_category_id
                JOIN jewelry_category jc on jc.id = p.jewelry_category_id
                LEFT JOIN product_image pi on pi.product_id = p.id
            WHERE
                p.id in (' . implode(',', $ids) . ')
        ';
        $statement = $this->pdo->prepare($sql);

        $statement->execute(
        // ['ids' => implode(',', array_map('intval', $ids))]
        // On a commenté cette ligne car elle ne fonctionne pas quand on veut transformer un tableau de int
        );
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        if ($data === false) {
            return null;
        }


        $products = [];

        foreach ($data as $product) {
            $productDta = new ProductDTA($product);
            $productDtaConverter = new ProductDtaConverter();
            $products[] = $productDtaConverter->toProduct($productDta);
        }

        return $products;
    }

    public function findActivatedByFilters(int $productCategoryId, int $jewelryCategoryId): ?array
    {

        $sql = '
            SELECT
                p.*,
                pc.id as product_category_id,
                pc.`name` product_category_name,
                pc.created_at product_category_created_at,
                pc.updated_at product_category_updated_at,
                jc.id as jewelry_category_id,
                jc.`name` jewelry_category_name,
                jc.created_at jewelry_category_created_at,
                jc.updated_at jewelry_category_updated_at,
                pi.id product_image_id,
                pi.path product_image_path,
                pi.created_at product_image_created_at,
                pi.updated_at product_image_updated_at
            FROM
                `product` p
                JOIN product_category pc on pc.id = p.product_category_id
                JOIN jewelry_category jc on jc.id = p.jewelry_category_id
                LEFT JOIN product_image pi on pi.product_id = p.id
            WHERE 
                p.available = 1
        ';

        $sql .= $productCategoryId ? ' AND p.product_category_id = :product_category_id' : "";
        $sql .= $jewelryCategoryId ? ' AND p.jewelry_category_id = :jewelry_category_id' : "";

        $statement = $this->pdo->prepare($sql);

        $params = [];
        if ($productCategoryId) {
            $params['product_category_id'] = $productCategoryId;
        }
        if ($jewelryCategoryId) {
            $params['jewelry_category_id'] = $jewelryCategoryId;
        }

        $statement->execute($params);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        if ($data === false) {
            return null;
        }

        $products = [];

        foreach ($data as $product) {
            $productDta = new ProductDTA($product);
            $productDtaConverter = new ProductDtaConverter();
            $products[] = $productDtaConverter->toProduct($productDta);
        }

        return $products;
    }


    public function findAllByCategory(int $productCategoryId): ?array
    {
        $sql = '
            SELECT
                p.*,
                pc.id as product_category_id,
                pc.`name` product_category_name,
                pc.created_at product_category_created_at,
                pc.updated_at product_category_updated_at,
                jc.id as jewelry_category_id,
                jc.`name` jewelry_category_name,
                jc.created_at jewelry_category_created_at,
                jc.updated_at jewelry_category_updated_at,
                pi.id product_image_id,
                pi.path product_image_path,
                pi.created_at product_image_created_at,
                pi.updated_at product_image_updated_at
            FROM
                `product` p
                JOIN product_category pc on pc.id = p.product_category_id
                JOIN jewelry_category jc on jc.id = p.jewelry_category_id
                LEFT JOIN product_image pi on pi.product_id = p.id
            WHERE
                p.available = 1
                AND p.product_category_id = :product_category_id
        ';

        $statement = $this->pdo->prepare($sql);

        $statement->execute([
            'product_category_id' => $productCategoryId
        ]);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        if ($data === false) {
            return null;
        }

        foreach ($data as $product) {
            $productDta = new ProductDTA($product);
            $productDtaConverter = new ProductDtaConverter();
            $products[] = $productDtaConverter->toProduct($productDta);
        }

        return $products;
    }

    public function add(Product $product): int
    {
        $statement = $this->pdo->prepare('INSERT INTO `product` (`name`, `description`, `product_category_id`, `jewelry_category_id`, `price`, `available`, `stripe_id`) VALUES (?, ?, ?, ?, ?, ?, ?)');
        $statement->execute([$product->getName(), $product->getDescription(), $product->getProductCategory()->getId(), $product->getJewelryCategory()->getId(), $product->getPrice(), $product->getAvailable() ? 1 : 0, $product->getStripeId()]);
        return $this->pdo->lastInsertId();
    }

    public function edit(Product $product)
    {
        $statement = $this->pdo->prepare('UPDATE `product` SET `name` = ?, `description` = ?, `product_category_id` = ?, `jewelry_category_id` = ?, `price` = ?, `available`= ?, `updated_at` = ? WHERE `id` = ?');
        $statement->execute([$product->getName(), $product->getDescription(), $product->getProductCategory()->getId(), $product->getJewelryCategory()->getId(), $product->getPrice()*100, $product->getAvailable(), $product->getUpdatedAt()->format('Y-m-d H:i:s'), $product->getId()]);
    }

    public function deleteOneById(int $id)
    {
        $statement = $this->pdo->prepare('DELETE FROM `product` WHERE id = ?');
        $statement->execute([$id]);
    }
}
