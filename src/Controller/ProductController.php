<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use App\Routing\Attribute\Route;
use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;

class ProductController extends AbstractController
{
  #[Route("/produits", name: "app_products_index")]
  public function index(ProductRepository $productRepository)
  {
    $products = $productRepository->findAllActivated();

    $adapter = new ArrayAdapter($products);
    $productsPaginated = new Pagerfanta($adapter);
    $productsPaginated->setMaxPerPage(16);
    
    return $this->render('product/_index.html.twig' , [
      'products' => $productsPaginated
    ]);
  }

  #[Route("/produit/{id}", name: "app_product_show")]
  public function show($id)
  {
    return $this->render('product/_show.html.twig');
  }
}
