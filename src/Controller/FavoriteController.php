<?php

namespace App\Controller;

use App\Entity\Favorite;
use App\Repository\FavoriteRepository;
use App\Repository\ProductRepository;
use App\Routing\Attribute\Route;
use Exception;

class FavoriteController extends AbstractController
{
    /**
     * @throws Exception
     */
    #[Route('/favoris', name: 'favorites_show')]
    public function listByUser(FavoriteRepository $favoriteRepository)
    {
        if (!$this->getSession()->has('user_id')) {
            return $this->redirectToRoute('/login');
        }

        $favorites = $favoriteRepository->findAllByUser($this->getUser()->getId());
        return $this->render('_favoris.html.twig', [
            'favorites' => $favorites
        ]);
    }

    #[Route('/favoris/add/{product_id}', name: 'add_favorite')]
    public function add(FavoriteRepository $favoriteRepository, ProductRepository $productRepository, $product_id)
    {
        if (!$this->getSession()->has('user_id')) {
            return $this->redirectToRoute('/login');
        }

        $favorite = new Favorite();
        $favorite->setUser($this->getUser());
        $favorite->setProduct($productRepository->find($product_id));
        $favoriteRepository->add($favorite);
        return json_encode('favorite added');
    }
}