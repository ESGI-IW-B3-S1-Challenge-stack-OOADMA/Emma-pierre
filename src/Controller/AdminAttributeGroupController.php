<?php

namespace App\Controller;

use App\Entity\Attribute;
use App\Entity\AttributeGroup;
use App\HttpFoundationWish\Request;
use App\Repository\AttributeGroupRepository;
use App\Repository\AttributeRepository;
use App\Routing\Attribute\Route;

class AdminAttributeGroupController extends AbstractController
{
    #[Route('/admin/attributes-group', name: 'app_admin_attributes_group')]
    public function index(AttributeGroupRepository $attributeGroupRepository)
    {
        $this->adminRoute();
        $attributeGroups = $attributeGroupRepository->findAllWithoutAttribute();
        return $this->render('admin/attribute_group/index.html.twig', [
            'attributeGroups' => $attributeGroups
        ]);
    }

    #[Route('/admin/attributes-group/{id}/detail', name: 'app_admin_attributes_group_detail')]
    public function detail(AttributeGroupRepository $attributeGroupRepository, AttributeRepository $attributeRepository, $id)
    {
        $this->adminRoute();
        $attributeGroup = $attributeGroupRepository->find(intval($id));
        $attributes = $attributeRepository->findByAttributeGroup($attributeGroup);
        return $this->render('admin/attribute_group/detail.html.twig', [
            'attributes' => $attributes,
            'attributeGroup' => $attributeGroup
        ]);
    }

    #[Route('/admin/attributes-group/new', name: 'app_admin_attributes_group_new', httpMethod: ['GET', 'POST'])]
    public function new(AttributeGroupRepository $attributeGroupRepository, Request $request)
    {
        $this->adminRoute();
        $request = $request->request;

        if ($request->has('attribute_group')) {
            $attributeGroupData = $request->all('attribute_group');
            $attributeGroup = $attributeGroupRepository->findOneByName($attributeGroupData['name']);
            if ($attributeGroup) {
                return $this->render('admin/attribute_group/new.html.twig', [
                    'display_errors' => true
                ]);
            }
            $attributeGroup = new AttributeGroup();
            $attributeGroup->setName($attributeGroupData['name']);
            $attributeGroup->setAttributeType($attributeGroupData['attribute_type']);
            $attributeGroup->setCreatedAt(new \DateTimeImmutable());
            $attributeGroup->setUpdatedAt(new \DateTimeImmutable());
            $attributeGroupId = $attributeGroupRepository->add($attributeGroup);
            return $this->redirectToRoute('/admin/attributes-group/' . $attributeGroupId . '/detail');
        }

        return $this->render('admin/attribute_group/new.html.twig', [
            'display_errors' => false
        ]);
    }

    #[Route('/admin/attributes-group/{id}/edit', name: 'app_admin_attributes_group_edit', httpMethod: ['GET', 'POST'])]
    public function edit(AttributeGroupRepository $attributeGroupRepository, AttributeRepository $attributeRepository, Request $request, $id)
    {
        $this->adminRoute();
        $request = $request->request;

        $attributeGroup = $attributeGroupRepository->find(intval($id));

        if ($request->has('attribute_group')) {
            $attributeGroupData = $request->all('attribute_group');

            $attributeGroupAlreadyExist = $attributeGroupRepository->findOneByName($attributeGroupData['name']);
            if ($attributeGroupAlreadyExist && $attributeGroupAlreadyExist->getId() !== $attributeGroup->getId()) {
                return $this->render('admin/attribute_group/edit.html.twig', [
                    'attributeGroup' => $attributeGroup,
                    'display_errors' => true
                ]);
            }

            $attributeGroup->setName($attributeGroupData['name']);
            $attributeGroup->setAttributeType($attributeGroupData['attribute_type']);
            $attributeGroup->setUpdatedAt(new \DateTimeImmutable('now'));
            $attributeGroupRepository->update($attributeGroup);

            return $this->redirectToRoute('/admin/attributes-group');
        }

        return $this->render('admin/attribute_group/edit.html.twig', [
            'attributeGroup' => $attributeGroup,
            'display_errors' => false
        ]);
    }

    #[Route('/admin/attributes-group/{id}/attribute/new', name: 'app_admin_attributes_group_attribute_new', httpMethod: ['GET', 'POST'])]
    public function attribute_new(AttributeGroupRepository $attributeGroupRepository, AttributeRepository $attributeRepository, Request $request, $id)
    {
        $this->adminRoute();
        $request = $request->request;
        $attributeGroup = $attributeGroupRepository->find($id);

        if ($request->has('attribute')) {
            $attributeData = $request->all('attribute');

            $attribute = $attributeRepository->findOneByDataAndAttributeGroup($attributeData['data'], $attributeGroup);

            if ($attribute) {
                return $this->render('admin/attribute/new.html.twig', [
                    'attributeGroup' => $attributeGroup,
                    'display_errors' => true
                ]);
            }

            $attribute = new Attribute();
            $attribute->setData($attributeData['data']);
            $attribute->setAttributeGroup($attributeGroup);
            $attribute->setCreatedAt(new \DateTimeImmutable('now'));
            $attribute->setUpdatedAt(new \DateTimeImmutable('now'));
            $attributeRepository->add($attribute);
            return $this->redirectToRoute('/admin/attributes-group/' . $attributeGroup->getId() . '/detail');
        }

        return $this->render('admin/attribute/new.html.twig', [
            'attributeGroup' => $attributeGroup,
            'display_errors' => false
        ]);
    }

    #[Route('/admin/attributes-group/attribute/{id}/edit', name: 'app_admin_attributes_group_attribute_edit', httpMethod: ['GET', 'POST'])]
    public function attribute_edit(AttributeRepository $attributeRepository, Request $request, $id)
    {
        $this->adminRoute();
        $request = $request->request;
        $attribute = $attributeRepository->find($id);
        $attributeGroup = $attribute->getAttributeGroup();

        if ($request->has('attribute')) {
            $attributeData = $request->all('attribute');

            $attributeAlreadyExist = $attributeRepository->findOneByDataAndAttributeGroup($attributeData['data'], $attributeGroup);

            if ($attributeAlreadyExist && $attributeAlreadyExist->getId() !== $attribute->getId()) {
                return $this->render('admin/attribute/edit.html.twig', [
                    'attributeGroup' => $attributeGroup,
                    'attribute' => $attribute,
                    'display_errors' => true
                ]);
            }

            $attribute->setData($attributeData['data']);
            $attributeRepository->update($attribute);
            return $this->redirectToRoute('/admin/attributes-group/' . $attributeGroup->getId() . '/detail');
        }

        return $this->render('admin/attribute/edit.html.twig', [
            'attributeGroup' => $attributeGroup,
            'attribute' => $attribute,
            'display_errors' => false
        ]);
    }

    #[Route('/admin/attributes-group/attribute/{id}/delete', name: 'app_admin_attributes_group_attribute_delete')]
    public function attribute_delete(AttributeRepository $attributeRepository, $id)
    {
        $this->adminRoute();
        $attribute = $attributeRepository->find($id);
        $attributeGroupId = $attribute->getAttributeGroup()->getId();
        $attributeRepository->delete(intval($id));
        return $this->redirectToRoute('/admin/attributes-group/' . $attributeGroupId . '/detail');
    }
}