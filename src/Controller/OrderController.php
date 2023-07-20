<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\OrderItem;
use App\Enum\OrderStatus;
use App\HttpFoundationWish\Request;
use App\Repository\OrderItemRepository;
use App\Repository\OrderRepository;
use App\Repository\ProductRepository;
use App\Routing\Attribute\Route;
use App\Service\Stripe\CheckoutService;
use App\Session\SessionManager;

class OrderController extends AbstractController
{
    #[Route('/panier', name: "app_cart")]
    public function cart(ProductRepository $productRepository){
        if(!$this->getUser()) {
            $this->redirectToRoute('/login');
        }
        $cart = $this->getSession()->has('cart') ? $this->getSession()->get('cart') : [];
        $products = $productRepository->findMultipleById(array_keys($cart));
        return $this->render('_panier.html.twig', [
            'cart' => $cart,
            'products' => $products
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
        return json_encode($cart[$id]); 
    }

    #[Route('/panier/decrement', name: "app_cart_decrement" , httpMethod: ['POST'])]
    public function decrementFromCart(Request $request){    
        $cart = $this->getSession()->has('cart') ? $this->getSession()->get('cart') : [];
        $id = $request->request->get('id');
        if(isset($cart[$id])){
            if($cart[$id] > 1){
                $cart[$id]--;
            }
            else {
                unset($cart[$id]);
            }
        }
        $this->getSession()->set('cart', $cart);
        return json_encode($cart[$id]);
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
    
    #[Route('/order/new', name: "app_order_new")]
    public function newOrder(OrderRepository $orderRepository, ProductRepository $productRepository, SessionManager $sessionManager, CheckoutService $checkoutService, OrderItemRepository $orderItemRepository){
        if (!$this->getSession()->has('user_id')) {
            return $this->redirectToRoute('/login');
        }
        $sessionManager->set('panier', true);
        if ($sessionManager->has('panier')){
            $order = new Order();
            $order->setReference(uniqid());
            $order->setUser($this->getUser());
            $order->setStatus(OrderStatus::unpaid->value);
            $order->setCreatedAt(new \DateTimeImmutable());
            $order->setUpdatedAt(new \DateTimeImmutable());

            $cart = $this->getSession()->has('cart') ? $this->getSession()->get('cart') : [];
            $orderItems = [];
            $total = 0;
            foreach ($cart as $id => $quantity) {
                $orderItem = new OrderItem();
                $orderItem->setOrder($order);
                $orderItem->setProduct($productRepository->find($id));
                $orderItem->setQuantity($quantity);
                $orderItem->setCreatedAt(new \DateTimeImmutable());
                $orderItem->setUpdatedAt(new \DateTimeImmutable());
                
                $orderItems[] = $orderItem;
                $total = $total + ($orderItem->getQuantity() * $orderItem->getProduct()->getPrice());
                
            }
            $order->setOrderItems($orderItems);

            $order->setTotal($total);

            $checkout = $checkoutService->createCheckout($order, $this->getUser());
            $order->setStripeId($checkout->id);
            $order->setId($orderRepository->add($order));
            
            foreach ($orderItems as $orderItem) {
                $orderItemRepository->add($orderItem);
            }

            // Maintenant qu'on à L'id faut ajouter les order items

            return $this->redirectToRoute($checkout->url);
        }
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