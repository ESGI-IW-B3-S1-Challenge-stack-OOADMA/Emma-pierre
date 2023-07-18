<?php

namespace App\Controller;

use App\Routing\Attribute\Route;

class DashboardController extends AbstractController
{
    #[Route('/admin/dashboard', name: 'app_admin_dashboard')]
    public function dashboard(): string
    {
        if (!$this->getSession()->has('user_id')) {
            return $this->redirectToRoute('/login');
        }

        return $this->render('admin/dashboard.html.twig', [
            'user' => $this->getUser()
        ]);
    }
}
