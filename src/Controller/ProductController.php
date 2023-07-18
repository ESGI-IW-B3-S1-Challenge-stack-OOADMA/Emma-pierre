<?php

namespace App\Controller;

use App\Routing\Attribute\Route;

class ProductController extends AbstractController
{
  #[Route("/produits", name: "app_products_index")]
  public function index()
  {
    return $this->render('product/_index.html.twig');
  }

  #[Route("/produit/{id}", name: "app_product_show")]
  public function show($id)
  {
    return $this->render('product/_show.html.twig');
  }
}
