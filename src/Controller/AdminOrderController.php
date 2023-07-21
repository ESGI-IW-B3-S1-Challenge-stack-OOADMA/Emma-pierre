<?php

namespace App\Controller;

use App\Repository\OrderRepository;
use App\Routing\Attribute\Route;

class AdminOrderController extends AbstractController
{
    #[Route('/admin/orders', name: "app_admin_orders")]
    public function list(OrderRepository $orderRepository)
    {
        $orders = $orderRepository->findAll();

        return $this->render('admin/order/_list.html.twig', [
            'orders' => $orders
        ]);
    }

    #[Route('/admin/orders/detail/{id}', name: "app_admin_orders_detail")]
    public function detail(OrderRepository $orderRepository, $id)
    {
        $order = $orderRepository->find($id);

        return $this->render('admin/order/_detail.html.twig', [
            'order' => $order
        ]);
    }
}