<?php

namespace App\Controller;

use App\HttpFoundationWish\Request;
use App\Routing\Attribute\Route;

class OrderController extends AbstractController
{
    #[Route('/panier', name: "app_cart")]
    public function cart(){
        $cart = $this->getSession()->has('cart') ? $this->getSession()->get('cart') : [];
        return $this->render('_panier.html.twig', [
            'cart' => $cart
        ]);
    }

    #[Route('/panier/add', name: "app_cart_add" , httpMethod: ['POST'])]
    public function addToCart(Request $request){    
        $cart = $this->getSession()->has('cart') ? $this->getSession()->get('cart') : [];
        $id  = $request->request->get('id');
        if(isset($cart[$id])){
            $cart[$id]++;
        }
        else {
            $cart[$id] = 1;
        }
        $this->getSession()->set('cart', $cart);
        return json_encode($cart); 
    }

    #[Route('/panier/decrement', name: "app_cart_decrement" , httpMethod: ['POST'])]
    public function decrementFromCart(Request $request){    
        $cart = $this->getSession()->has('cart') ? $this->getSession()->get('cart') : [];
        if(isset($cart[$request->request->get('id')])){
            if($cart[$request->request->get('id')] > 1){
                $cart[$request->request->get('id')]--;
            }
            else {
                unset($cart[$request->request->get('id')]);
            }
        }
        $cart = $this->getSession()->set('cart', $cart);
        return json_encode($cart);
    }

    #[Route('/panier/remove', name: "app_cart_remove" , httpMethod: ['POST'])]
    public function removeFromCart(Request $request){    
        $cart = $this->getSession()->has('cart') ? $this->getSession()->get('cart') : [];
        if(isset($cart[$request->request->get('id')])){
                unset($cart[$request->request->get('id')]);
        }
        $this->getSession()->set('cart', $cart);
        return json_encode($cart);
    }

    #[Route('/panier/delete', name: "app_cart_delete" , httpMethod: ['GET'])]
    public function deleteCart(){    
        $this->getSession()->remove('cart');
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