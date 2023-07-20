<?php

namespace App\Controller;

use App\Routing\Attribute\Route;
use App\Repository\ProductRepository;
use App\Repository\JewelryCategoryRepository;
use App\Repository\ProductCategoryRepository;
use App\Repository\ProductImageRepository;
use App\HttpFoundationWish\Request;
use App\Entity\Product;
use App\Entity\ProductImage;
use App\Service\FileUploader;

class AdminProductController extends AbstractController
{
    #[Route("/admin/product", name: "app_admin_products_list")]
    public function list(ProductRepository $productRepository)
    {
        $products = $productRepository->findAll();
        return $this->render('admin/product/list.html.twig', [
            'products' => $products
        ]);
    }

    #[Route('/admin/product/create', name: 'app_admin_product_create', httpMethod: ['GET', 'POST'])]
    public function create(FileUploader $fileUploader, Request $request, ProductRepository $productRepository, JewelryCategoryRepository $jewelryCategoryRepository, ProductCategoryRepository $productCategoryRepository, ProductImageRepository $productImageRepository)
    {   
        if ($request->request->has('product_create')) {
            $datas = $request->request->all('product_create');
            $files = $request->files->all('product_create');
            
            $product = new Product();
            $product
            ->setName($datas['name'])
            ->setDescription($datas['description'])
            ->setPrice($datas['price'])
            ->setAvailable($datas['available']);
            $jewelryCategory = $jewelryCategoryRepository->findById($datas['type']);
            $product->setJewelryCategory($jewelryCategory);
            $productCategory = $productCategoryRepository->findById($datas['category']);
            $product->setProductCategory($productCategory);

            $id = $productRepository->add($product);

            $pathImg = $fileUploader->uploadFile($files['name']['img'], $files['tmp_name']['img'], $files['size']['img'], $id);
            $img = new ProductImage();
            $img->setPath($pathImg)
            ->setIdProduct($id);
            $id = $productImageRepository->add($img);

            return $this->redirectToRoute('/admin/product');
        }

        $jewelryCategories = $jewelryCategoryRepository->findAll();
        $productCategories = $productCategoryRepository->findAll();	

        return $this->render('admin/product/new.html.twig',[
            'jewelryCategories' => $jewelryCategories,
            'productCategories' => $productCategories
        
        ]);
    }

    #[Route('/admin/product/edit/{id}', name: 'app_admin_product_edit', httpMethod: ['GET', 'POST'])]
    public function edit(FileUploader $fileUploader, Request $request, ProductRepository $productRepository, JewelryCategoryRepository $jewelryCategoryRepository, ProductCategoryRepository $productCategoryRepository, ProductImageRepository $productImageRepository, $id)
    {
        $productExist = $productRepository->find($id);
        
        if(empty($productExist)){
            throw new \Exception('Product not found');
        }

        if ($request->request->has('product_edit')) {
            $productEdit = $request->request->all('product_edit');
            $files = $request->files->all('product_edit');

            $product = new Product();
            $product
            ->setId($productExist->getId())
            ->setName($productEdit['name'])
            ->setDescription($productEdit['description'])
            ->setPrice($productEdit['price'])
            ->setAvailable($productEdit['available'])
            ->setUpdatedAt(new \DateTimeImmutable('now'));
            if($productEdit['type'] != $productExist->getJewelryCategory()->getId()){
                $jewelryCategory = $jewelryCategoryRepository->findById($productEdit['type']);
                $product->setJewelryCategory($jewelryCategory);
            }else{
                $product->setJewelryCategory($productExist->getJewelryCategory());
            }
            if($productEdit['category'] != $productExist->getProductCategory()->getId()){
                $productCategory = $productCategoryRepository->findById($productEdit['category']);
                $product->setProductCategory($productCategory);
            }else{
                $product->setProductCategory($productExist->getProductCategory());
            }

            if(!empty($files['name']['img'])){
                if(!empty($productExist->getImage())){
                    unlink($productExist->getImage()->getPath());
                    $productImageRepository->delete($productExist->getImage()->getId());
                }
                $pathImg = $fileUploader->uploadFile($files['name']['img'], $files['tmp_name']['img'], $files['size']['img'], $productExist->getId());
                $img = new ProductImage();
                $img->setPath($pathImg)
                ->setIdProduct($productExist->getId());
                $id = $productImageRepository->add($img);
            }

            $productRepository->edit($product);

            return $this->redirectToRoute('/admin/product');
        }

        $jewelryCategories = $jewelryCategoryRepository->findAll();
        $productCategories = $productCategoryRepository->findAll();	
       
        return $this->render('admin/product/edit.html.twig', [
            'product' => $productExist,
            'jewelryCategories' => $jewelryCategories,
            'productCategories' => $productCategories
        ]);
    }

    #[Route('/admin/product/delete/{id}', name: 'app_admin_product_delete')]
    public function delete(Request $request, ProductRepository $productRepository, $id){
        $productExist = $productRepository->find($id);

        if(empty($productExist)){
            throw new \Exception('Product not found');
        }

        if(!empty($productExist->getImage())){
            unlink($productExist->getImage()->getPath());
        }
        //AJOUTER CASCADE product_image !!!
        $productRepository->deleteOneById($id);
        return $this->redirectToRoute('/admin/product');
    }

}