<?php

namespace App\Controller;

use App\HttpFoundationWish\Request;
use App\Repository\UserRepository;
use App\Routing\Attribute\Route;
use App\Session\SessionManager;

class LoginController extends AbstractController
{
    #[Route('/login', name: 'app_login', httpMethod: ['GET', 'POST'])]
    public function new(Request $request, UserRepository $customerRepository, SessionManager $sessionManager)
    {
        $request = $request->request;
        if ($request->has('login')) {
            $email = $request->all('login')['email'];
            $password = $request->all('login')['password'];
            $customer = $customerRepository->findOneByEmail($email);

            if ($customer && password_verify($password, $customer->getPassword())) {
                $sessionManager->set('user_id', $customer->getId());
                return $this->redirectToRoute('/admin/dashboard');
            }
            return $this->render('user/_login.html.twig', [
                'display_errors' => true
            ]);
        }
        return $this->render('user/_login.html.twig');
    }
}
