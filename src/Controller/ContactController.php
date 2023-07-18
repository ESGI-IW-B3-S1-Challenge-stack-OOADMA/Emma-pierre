<?php

namespace App\Controller;

use App\Routing\Attribute\Route;

class ContactController extends AbstractController
{
  #[Route("/contact", name: "app_contact")]
  public function contact()
  {
    return $this->render('_contact.html.twig');
  }

  #[Route("/devis", name: "page_devis")]
  public function devis()
  {
    echo "Page de devis";
  }
}
