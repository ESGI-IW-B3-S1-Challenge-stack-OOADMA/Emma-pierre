<?php

namespace App\Controller;

use App\HttpFoundationWish\Request;
use App\Repository\FavoriteRepository;
use App\Repository\JewelryCategoryRepository;
use App\Repository\ProductCategoryRepository;
use App\Repository\ProductRepository;
use App\Routing\Attribute\Route;
use Exception;
use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class ProductController extends AbstractController
{
    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     * @throws Exception
     */
    #[Route("/produits", name: "app_products_index")]
  public function index(
      Request $request,
      ProductRepository $productRepository,
      ProductCategoryRepository $productCategoryRepository,
      JewelryCategoryRepository $jewelryCategoryRepository,
      FavoriteRepository $favoriteRepository
  )
  {
    $request = $request->query;
    $page = $request->get('page') ?? 1;
    $productCategoryId = $request->get('productCategory') ?? 0;
    $jewelryCategoryId  = $request->get('jewelryCategory') ?? 0;
    $products = $productRepository->findActivatedByFilters($productCategoryId, $jewelryCategoryId);

    $productCategories = $productCategoryRepository->findAll();
    $jewelryCategories = $jewelryCategoryRepository->findAll();

    $adapter = new ArrayAdapter($products);
    $productsPaginated = new Pagerfanta($adapter);
    $productsPaginated->setMaxPerPage(16);
    $productsPaginated->setCurrentPage($page);

    if ($this->getSession()->has('user_id') && $this->getUser()) {
      $favorites = $favoriteRepository->findAllByUser($this->getUser()->getId());
    } else {
      $favorites = [];
    }
    
    return $this->render('product/_index.html.twig' , [
      'products' => $productsPaginated,
      'productCategories' => $productCategories,
      'jewelryCategories' => $jewelryCategories,
      'favorites' => $favorites,
    ]);
  }

  #[Route("/produit/{id}", name: "app_product_show")]
  public function show(ProductRepository $productRepository, $id)
  {
    $product = $productRepository->find($id);
    $sameProductCategoryProducts = $productRepository->findAllByCategory($product->getProductCategory()->getId());

    return $this->render('product/_show.html.twig', [
      'product' => $product,
      'sameProductCategoryProducts' => $sameProductCategoryProducts,
    ]);
  }
}
