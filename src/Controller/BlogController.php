<?php

namespace App\Controller;

use App\Routing\Attribute\Route;

class BlogController extends AbstractController
{
  #[Route("/blog", name: "app_blog")]
  public function blog()
  {
    return $this->render('_blog.html.twig');
  }
}
