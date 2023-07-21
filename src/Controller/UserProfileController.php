<?php

namespace App\Controller;

use App\Entity\Customer;
use App\HttpFoundationWish\Request;
use App\Repository\OrderRepository;
use App\Repository\UserRepository;
use App\Routing\Attribute\Route;

class UserProfileController extends AbstractController
{
    #[Route('/user-profile', name: 'app_user_profile', httpMethod: ['GET', 'POST'])]
    public function profile(Request $request, UserRepository $customerRepository)
    {
        if (!$this->getUser()) {
            $this->redirectToRoute('/login');
        }
        $request = $request->request;
        if ($request->has('profile')) {
            $customer = $this->getUser();
            $customer->setLastname($request->all('profile')['lastname']);
            $customer->setFirstname($request->all('profile')['firstname']);
            $customer->setEmail($request->all('profile')['email']);
            $customer->setPhoneNumber($request->all('profile')['phone_number']);
            $password = password_hash($request->all('profile')['password'], PASSWORD_DEFAULT);
            $customer->setPassword($password);
            $customerRepository->edit($customer);
        }

        return $this->render('user/_profile.html.twig', [
            'user' => $this->getUser(),
            'isAdmin' => $this->isAdmin(),
        ]);
    }

    #[Route('/user-profile/my-orders', name: "app_user_profile_orders")]
    public function orders(OrderRepository $orderRepository){
        if (!$this->getUser()) {
            $this->redirectToRoute('/login');
        }

        $orders = $orderRepository->findAllByUser($this->getUser());


        return $this->render('user/order/_list.html.twig', [
            'orders' => $orders
        ]);
    }

    #[Route('/user-profile/my-orders/{id}', name: "app_user_profile_orders")]
    public function order_detail(OrderRepository $orderRepository, $id){
        if (!$this->getUser()) {
            $this->redirectToRoute('/login');
        }

        $order = $orderRepository->find($id);


        return $this->render('user/order/_detail.html.twig', [
            'order' => $order
        ]);
    }
}