<?php

namespace App\Controller;

use App\Entity\Customer;
use App\HttpFoundationWish\Request;
use App\Repository\UserRepository;
use App\Routing\Attribute\Route;

class UserProfileController extends AbstractController
{
    #[Route('/user-profile', name: 'app_user_profile', httpMethod: ['GET', 'POST'])]
    public function profile(Request $request, UserRepository $customerRepository)
    {
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
}