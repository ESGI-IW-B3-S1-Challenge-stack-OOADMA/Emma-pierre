<?php

namespace App\Controller;

use App\Routing\Attribute\Route;

class AboutController extends AbstractController
{
  #[Route("/a-propos", name: "app_about")]
  public function about()
  {
    return $this->render('_about.html.twig');
  }
}
