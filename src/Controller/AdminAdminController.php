<?php

namespace App\Controller;

use App\Routing\Attribute\Route;
use App\Repository\UserRepository;
use App\HttpFoundationWish\Request;

class AdminAdminController extends AbstractController
{
    #[Route("/admin/administrator", name: "app_admin_admins_list")]
    public function list(UserRepository $adminRepository)
    {
        $admins = $adminRepository->getAllByRole('admin');
        return $this->render('admin/administrator/list.html.twig', [
        'admins' => $admins
        ]);
    }

    #[Route('/admin/dashboard/admin/create', name: 'app_admin_admin_create', httpMethod: ['GET', 'POST'])]
    public function create(Request $request, UserRepository $adminRepository)
    {
        $request = $request->request;
        
        if ($request->has('admin_create')) {
            $email = $request->all('admin_create')['email'];
            $admin = $adminRepository->findOneByEmail($email);
            if ($admin){
                return $this->render('admin/administrator/new.html.twig', [
                    'display_errors' => true
                ]);
            }
            $id = $adminRepository->saveAdmin($request->all('admin_create'), 'create');
            return $this->redirectToRoute('/admin/administrator');
        }

        return $this->render('admin/administrator/new.html.twig');
    }

    #[Route('/admin/dashboard/admin/edit/{id}', name: 'app_admin_admin_edit', httpMethod: ['GET', 'POST'])]
    public function edit(Request $request, UserRepository $adminRepository, $id)
    {
        $request = $request->request;
        $adminExist = $adminRepository->find($id);

        if(empty($adminExist)){
            throw new \Exception('Admin not found');
        }

        if ($request->has('admin_edit')) {
            $email = $request->all('admin_edit')['email'];
            $admin = $adminRepository->findOneByEmail($email);
            if (!empty($admin) && $email != $adminExist->getEmail()){
                return $this->render('admin/administrator/edit.html.twig', [
                    'display_errors' => true
                ]);
            }
            $adminEdit = $request->all('admin_edit');
            $adminEdit['id'] = $adminExist->getId();
          
            $adminRepository->saveAdmin($adminEdit, 'edit');
            return $this->redirectToRoute('/admin/administrator');
        }

        return $this->render('admin/administrator/edit.html.twig', [
            'admin' => $adminExist
        ]);
    }

    #[Route('/admin/dashboard/admin/delete/{id}', name: 'app_admin_admin_delete')]
    public function delete(Request $request, UserRepository $adminRepository, $id)
    {
        $adminExist = $adminRepository->find($id);

        if(empty($adminExist)){
            throw new \Exception('Admin not found');
        }

        $adminRepository->deleteOneByID($id);
        return $this->redirectToRoute('/admin/administrator');
    }

}