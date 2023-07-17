<?php

namespace App\Controller;

use App\Entity\User;
use App\Routing\Attribute\Route;

class IndexController extends AbstractController
{
  #[Route("/", name: "homepage")]
  public function home(): string
  {
    return $this->render('index.html.twig');
  }
}
