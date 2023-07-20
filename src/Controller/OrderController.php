<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\OrderItem;
use App\Enum\OrderStatus;
use App\HttpFoundationWish\Request;
use App\Repository\OrderRepository;
use App\Repository\ProductRepository;
use App\Routing\Attribute\Route;
use App\Service\Stripe\CheckoutService;
use App\Session\SessionManager;

class OrderController extends AbstractController
{
    #[Route('/panier', name: "app_cart")]
    public function cart(OrderRepository $orderRepository, ProductRepository $productRepository, SessionManager $sessionManager, CheckoutService $checkoutService){
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

            $orderItem1 = new OrderItem();
            $orderItem1->setOrder($order);
            $orderItem1->setProduct($productRepository->find(1));
            $orderItem1->setQuantity(2);
            $orderItem1->setCreatedAt(new \DateTimeImmutable());
            $orderItem1->setUpdatedAt(new \DateTimeImmutable());

            $order->setOrderItems([$orderItem1]);
            $total = 0;
            foreach ($order->getOrderItems() as $orderItem) {
                $total = $total + ($orderItem->getQuantity() * $orderItem->getProduct()->getPrice());
            }

            $order->setTotal($total);

            $checkout = $checkoutService->createCheckout($order, $this->getUser());
            $order->setStripeId($checkout->id);
            $order->setId($orderRepository->add($order));

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