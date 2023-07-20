<?php

namespace App\Controller;

use App\Routing\Attribute\Route;

class OrderController extends AbstractController
{
    #[Route('/panier', name: "app_cart")]
    public function cart(){
        return $this->render('_panier.html.twig');
    }

    #[Route('/livraison', name:"app_shipping")]
    public function shipping(){
        return $this->render('_livraison.html.twig');
    }

    #[Route('/paiement', name:"app_checkout")] // Elle sert surement à rien, tout dépend si on fait le formulaire custom
    public function checkout(){
        return $this->render('_paiement.html.twig');
    }
}