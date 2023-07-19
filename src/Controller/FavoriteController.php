<?php

namespace App\Controller;

use App\Repository\FavoriteRepository;
use App\Routing\Attribute\Route;
use Exception;

class FavoriteController extends AbstractController
{
    /**
     * @throws Exception
     */
    #[Route('/favorites', name: 'favorites_show')]
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
}