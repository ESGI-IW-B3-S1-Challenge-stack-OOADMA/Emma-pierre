<?php

namespace App\DTA\Converter;

use App\DTA\FavoriteDTA;
use App\Entity\Favorite;

class FavoriteDtaConverter
{
    public function toFavorite(FavoriteDTA $favoriteDTA): Favorite
    {
        $favorite = new Favorite();
        $favorite->setId($favoriteDTA->id);
        $favorite->setUser($favoriteDTA->user);
        $favorite->setProduct($favoriteDTA->product);
        $favorite->setCreatedAt($favoriteDTA->created_at);
        $favorite->setUpdatedAt($favoriteDTA->updated_at);
        return $favorite;
    }
}