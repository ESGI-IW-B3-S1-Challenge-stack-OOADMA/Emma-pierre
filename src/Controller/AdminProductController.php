<?php

namespace App\Controller;

use App\Routing\Attribute\Route;
use App\Repository\ProductRepository;
use App\Repository\JewelryCategoryRepository;
use App\Repository\ProductCategoryRepository;
use App\Repository\ProductImageRepository;
use App\Repository\AttributeGroupRepository;
use App\Repository\AttributeRepository;
use App\Repository\AttributeProductRepository;
use App\HttpFoundationWish\Request;
use App\Entity\Product;
use App\Entity\ProductImage;
use App\Service\FileUploader;
use App\Service\Stripe\ProductService;

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
    public function create(FileUploader $fileUploader, Request $request, ProductRepository $productRepository, JewelryCategoryRepository $jewelryCategoryRepository, ProductCategoryRepository $productCategoryRepository, ProductImageRepository $productImageRepository, AttributeGroupRepository $attributeGroupRepository, AttributeRepository $attributeRepository, AttributeProductRepository $attributeProductRepository, ProductService $productService)
    {   
        $attributeGroups = $attributeGroupRepository->findAllWithoutAttribute();
        $jewelryCategories = $jewelryCategoryRepository->findAll();
        $productCategories = $productCategoryRepository->findAll();	
        $attributeGroupsWithAttributes = [];
        foreach($attributeGroups as $attributeGroup){
            $attributes = $attributeRepository->findByAttributeGroup($attributeGroup);
            $attributeGroupsWithAttributes[] = [
                'attributeGroup' => $attributeGroup,
                'attributes' => $attributes
            ];
        }

        if ($request->request->has('product_create')) {
            $datas = $request->request->all('product_create');
            $files = $request->files->all('product_create');

            $formatPrice = '/^\d+(\.\d{2})?$/';
            if(preg_match($formatPrice, $datas['price']) == 0){
                return $this->render('admin/product/new.html.twig', [
                    'display_errors' => true,
                    'jewelryCategories' => $jewelryCategories,
                    'productCategories' => $productCategories,
                    'attributeGroupsWithAttributes' => $attributeGroupsWithAttributes
                ]);
            }

            //Création du nouveau produit
            $product = new Product();
            $product
            ->setName($datas['name'])
            ->setDescription($datas['description'])
            ->setPrice($datas['price']*100)
            ->setAvailable($datas['available']);
            $jewelryCategory = $jewelryCategoryRepository->findById($datas['type']);
            $product->setJewelryCategory($jewelryCategory);
            $productCategory = $productCategoryRepository->findById($datas['category']);
            $product->setProductCategory($productCategory);
            $product->setStripeId($productService->createProduct($product));

            $idProduct = $productRepository->add($product);

            //Ajout de l'image
            if(!empty($files['name']['img'])){
                $pathImg = $fileUploader->uploadFile($files['name']['img'], $files['tmp_name']['img'], $files['size']['img'], $idProduct);
                $img = new ProductImage();
                $img->setPath($pathImg)
                ->setIdProduct($idProduct);
                $idProductImg = $productImageRepository->add($img);
            }

            //Ajout des attributs
            if(!empty($datas['attributes'])){
                foreach($datas['attributes'] as $idAttribute){
                    $attributeProductRepository->add($idAttribute, $idProduct);
                }
            }
            
            return $this->redirectToRoute('/admin/product');
        }

        return $this->render('admin/product/new.html.twig',[
            'jewelryCategories' => $jewelryCategories,
            'productCategories' => $productCategories,
            'attributeGroupsWithAttributes' => $attributeGroupsWithAttributes
        ]);
    }

    #[Route('/admin/product/edit/{id}', name: 'app_admin_product_edit', httpMethod: ['GET', 'POST'])]
    public function edit(FileUploader $fileUploader, Request $request, ProductRepository $productRepository, JewelryCategoryRepository $jewelryCategoryRepository, ProductCategoryRepository $productCategoryRepository, ProductImageRepository $productImageRepository, AttributeGroupRepository $attributeGroupRepository, AttributeRepository $attributeRepository, AttributeProductRepository $attributeProductRepository, $id)
    {
        $productExist = $productRepository->find($id);
        $attributeGroups = $attributeGroupRepository->findAllWithoutAttribute();
        $attributesIdsExists = $attributeProductRepository->getIdAttributeByIdProduct($productExist->getId());
        $jewelryCategories = $jewelryCategoryRepository->findAll();
        $productCategories = $productCategoryRepository->findAll();	
        $attributeGroupsWithAttributes = [];
        foreach($attributeGroups as $attributeGroup){
            $attributes = $attributeRepository->findByAttributeGroup($attributeGroup);
            $attributeGroupsWithAttributes[] = [
                'attributeGroup' => $attributeGroup,
                'attributes' => $attributes
            ]; 
        }

        if(empty($productExist)){
            throw new \Exception('Product not found');
        }

        if ($request->request->has('product_edit')) {
            $productEdit = $request->request->all('product_edit');
            $files = $request->files->all('product_edit');

            $formatPrice = '/^\d+(\.\d{2})?$/';
            if(preg_match($formatPrice, $productEdit['price']) == 0){
                return $this->render('admin/product/new.html.twig', [
                    'display_errors' => true,
                    'product' => $productExist,
                    'jewelryCategories' => $jewelryCategories,
                    'productCategories' => $productCategories,
                    'attributeGroupsWithAttributes' => $attributeGroupsWithAttributes,
                    'idsAttributesExists' => $attributesIdsExists
                ]);
            }

            //Gestion des attributs
            $newIdsAttributes = $productEdit['attributes'];
            $oldIdsAttributes = $attributesIdsExists;

            $idsAttributesToDelete = array_diff($oldIdsAttributes, $newIdsAttributes);
            $idsAttributesToAdd = array_diff($newIdsAttributes, $oldIdsAttributes);

            foreach($idsAttributesToDelete as $idAttributeToDelete){
                $attributeProductRepository->delete($idAttributeToDelete, $productExist->getId());
            }
            foreach($idsAttributesToAdd as $idAttributeToAdd){
                $attributeProductRepository->add($idAttributeToAdd, $productExist->getId());
            }

            //Gestion du produit
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

            //Gestion des images
            if(!empty($files['name']['img'])){
                if(!empty($productExist->getImage())){
                    unlink("/public".$productExist->getImage()->getPath());
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

        return $this->render('admin/product/edit.html.twig', [
            'product' => $productExist,
            'jewelryCategories' => $jewelryCategories,
            'productCategories' => $productCategories,
            'attributeGroupsWithAttributes' => $attributeGroupsWithAttributes,
            'idsAttributesExists' => $attributesIdsExists
        ]);
    }

    #[Route('/admin/product/delete/{id}', name: 'app_admin_product_delete')]
    public function delete(Request $request, ProductRepository $productRepository, $id){
        $productExist = $productRepository->find($id);

        if(empty($productExist)){
            throw new \Exception('Product not found');
        }

        if(!empty($productExist->getImage())){
            unlink("/public".$productExist->getImage()->getPath());
        }

        $productRepository->deleteOneById($id);
        return $this->redirectToRoute('/admin/product');
    }

}