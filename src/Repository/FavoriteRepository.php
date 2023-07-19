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

    /**
     * @return array<Favorite>
     * @throws Exception
     */
    public function findAllByUser(int $user_id): array
    {
        $sql = '
            SELECT f.created_at, p.name
            FROM favorite f 
            INNER JOIN `user` u ON f.user_id = u.id
            INNER JOIN product p ON f.product_id = p.id
            ORDER BY f.created_at
            WHERE f.user_id = ?;
        ';
        $statement = $this->pdo->prepare($sql);
        $statement->execute([$user_id]);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);

        if ($data === false) {
            return [];
        }

        foreach ($data as $favorite) {
            $favoriteDta = new FavoriteDTA($favorite);
            $favoriteDtaConverter = new FavoriteDtaConverter();
            $favorites = $favoriteDtaConverter->toFavorite($favoriteDta);
        }

        return $favorites;
    }
}