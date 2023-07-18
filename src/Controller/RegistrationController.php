<?php

namespace App\Controller;

use App\Entity\Customer;
use App\HttpFoundationWish\Request;
use App\Repository\UserRepository;
use App\Routing\Attribute\Route;

class RegistrationController extends AbstractController
{
    #[Route('/registration', name: 'app_registration', httpMethod: ['GET', 'POST'])]
    public function registration(Request $request, UserRepository $customerRepository)
    {
        $request = $request->request;

        if ($request->has('registration')) {
            $email = $request->all('registration')['email'];
            $customer = $customerRepository->findOneByEmail($email);
            if ($customer){
                return $this->render('user/_registration.html.twig', [
                    'display_errors' => true
                ]);
            }

            $customer = new Customer();
            $customer->setLastname($request->all('registration')['lastname']);
            $customer->setFirstname($request->all('registration')['firstname']);
            $customer->setEmail($email);
            $customer->setPhoneNumber($request->all('registration')['phone_number']);
            $password = password_hash($request->all('registration')['password'], PASSWORD_DEFAULT);
            $customer->setPassword($password);
            $customerRepository->add($customer);
            return $this->redirectToRoute('/admin/dashboard');
        }

        return $this->render('user/_registration.html.twig');
    }
}
