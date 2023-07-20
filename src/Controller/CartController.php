<?php

namespace App\Controller;

use App\Routing\Attribute\Route;

class CartController extends AbstractController
{
    #[Route('/panier', name: "app_cart")]
    public function cart(){
        return $this->render('_panier.html.twig');
    }
}