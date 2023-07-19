<?php

namespace App\Controller;

use App\Repository\JewelryCategoryRepository;
use App\Repository\ProductCategoryRepository;
use App\Repository\ProductRepository;
use App\Routing\Attribute\Route;
use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;

class ProductController extends AbstractController
{
  #[Route("/produits", name: "app_products_index")]
  public function index(ProductRepository $productRepository, ProductCategoryRepository $productCategoryRepository, JewelryCategoryRepository $jewelryCategoryRepository)
  {
    $_REQUEST['page'] ??= 1;
    $productCategoryId = $_REQUEST['productCategory'] ?? 0;
    $jewelryCategoryId  = $_REQUEST['jewelryCategory'] ?? 0;
    $products = $productRepository->findActivatedByFilters($productCategoryId, $jewelryCategoryId);

    $productCategories = $productCategoryRepository->findAll();
    $jewelryCategories = $jewelryCategoryRepository->findAll();

    $adapter = new ArrayAdapter($products);
    $productsPaginated = new Pagerfanta($adapter);
    $productsPaginated->setMaxPerPage(16);
    $productsPaginated->setCurrentPage($_REQUEST['page']);
    
    return $this->render('product/_index.html.twig' , [
      'products' => $productsPaginated,
      'productCategories' => $productCategories,
      'jewelryCategories' => $jewelryCategories,
    ]);
  }

  #[Route("/produit/{id}", name: "app_product_show")]
  public function show($id)
  {
    return $this->render('product/_show.html.twig');
  }
}
