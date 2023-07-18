<?php

namespace App\Controller;

use App\Routing\Attribute\Route;

class AboutController extends AbstractController
{
  #[Route("/a-propros", name: "app_about")]
  public function index()
  {
    return $this->render('_about.html.twig');
  }
}
