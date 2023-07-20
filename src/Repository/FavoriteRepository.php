<?php

namespace App\Repository;

use App\DTA\Converter\FavoriteDtaConverter;
use App\DTA\FavoriteDTA;
use App\Entity\Favorite;
use Exception;

class FavoriteRepository extends AbstractRepository
{
    public function add(Favorite $favorite): int
    {
        $statement = $this->pdo->prepare('INSERT INTO `favorite` (user_id, product_id) VALUES (?, ?)');
        $statement->execute([$favorite->getUser()->getId(), $favorite->getProduct()->getId()]);
        return $this->pdo->lastInsertId();
    }

    public function remove(Favorite $favorite): void
    {
        $statement = $this->pdo->prepare('DELETE FROM `favorite` WHERE id = ?');
        $statement->execute([$favorite->getId()]);
    }

    /**
     * @throws Exception
     */
    public function findOneById(int $id): ?Favorite
    {
        $sql = '
            SELECT
                f.id id, u.id user_id, u.lastname user_lastname, u.firstname user_firstname, u.email user_email,
                u.phone_number user_phone_number, u.password user_password, u.roles user_roles, u.created_at user_created_at,
                u.updated_at user_updated_at, p.id product_id, p.name product_name, p.description product_description, p.stripe_id product_stripe_id,
                pc.id product_product_category_id, pc.name product_product_category_name, pc.created_at product_product_category_created_at,
                pc.updated_at product_product_category_updated_at, jc.id product_jewelry_category_id, jc.name product_jewelry_category_name,
                jc.created_at product_jewelry_category_created_at, jc.updated_at product_jewelry_category_updated_at,
                p.price product_price, p.available product_available, p.created_at product_created_at, p.updated_at product_updated_at,
                f.created_at product_created_at, p.updated_at product_updated_at, f.created_at created_at, f.updated_at updated_at
            FROM favorite f 
            INNER JOIN `user` u ON f.user_id = u.id
            INNER JOIN product p ON f.product_id = p.id
            INNER JOIN product_category pc ON p.product_category_id = pc.id
            INNER JOIN jewelry_category jc ON p.jewelry_category_id = jc.id
            WHERE f.id = ?;
        ';
        $statement = $this->pdo->prepare($sql);
        $statement->execute([$id]);
        $data = $statement->fetch(\PDO::FETCH_ASSOC);

        if ($data === false) {
            return null;
        }

        $favoriteDta = new FavoriteDTA($data);
        $favoriteDtaConverter = new FavoriteDtaConverter();
        return $favoriteDtaConverter->toFavorite($favoriteDta);
    }

    /**
     * @return array<Favorite>
     * @throws Exception
     */
    public function findAllByUser(int $user_id): array
    {
        $sql = '
            SELECT
                f.id id, u.id user_id, u.lastname user_lastname, u.firstname user_firstname, u.email user_email,
                u.phone_number user_phone_number, u.password user_password, u.roles user_roles, u.created_at user_created_at,
                u.updated_at user_updated_at, p.id product_id, p.name product_name, p.description product_description, p.stripe_id product_stripe_id,
                pc.id product_product_category_id, pc.name product_product_category_name, pc.created_at product_product_category_created_at,
                pc.updated_at product_product_category_updated_at, jc.id product_jewelry_category_id, jc.name product_jewelry_category_name,
                jc.created_at product_jewelry_category_created_at, jc.updated_at product_jewelry_category_updated_at,
                p.price product_price, p.available product_available, p.created_at product_created_at, p.updated_at product_updated_at,
                f.created_at product_created_at, p.updated_at product_updated_at, f.created_at created_at, f.updated_at updated_at
            FROM favorite f 
            INNER JOIN `user` u ON f.user_id = u.id
            INNER JOIN product p ON f.product_id = p.id
            INNER JOIN product_category pc ON p.product_category_id = pc.id
            INNER JOIN jewelry_category jc ON p.jewelry_category_id = jc.id
            WHERE f.user_id = ?
            ORDER BY f.created_at;
        ';
        $statement = $this->pdo->prepare($sql);
        $statement->execute([$user_id]);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);

        if ($data === false) {
            return [];
        }

        $favorites = [];

        foreach ($data as $favorite) {
            $favoriteDta = new FavoriteDTA($favorite);
            $favoriteDtaConverter = new FavoriteDtaConverter();
            $favorites[] = $favoriteDtaConverter->toFavorite($favoriteDta);
        }

        return $favorites;
    }
}