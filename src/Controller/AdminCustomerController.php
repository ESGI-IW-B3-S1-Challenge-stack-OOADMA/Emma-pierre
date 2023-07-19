<?php

namespace App\Controller;

use App\Routing\Attribute\Route;
use App\Repository\UserRepository;
use App\HttpFoundationWish\Request;

class AdminCustomerController extends AbstractController
{
    #[Route("/admin/dashboard/customer", name: "app_admin_customers_list")]
    public function list(UserRepository $customerRepository)
    {
        $customers = $customerRepository->getAllByRole('customer');
        return $this->render('admin/customer/list.html.twig', [
        'customers' => $customers
        ]);
    }

    #[Route('/admin/dashboard/customer/create', name: 'app_admin_customer_create', httpMethod: ['GET', 'POST'])]
    public function create(Request $request, UserRepository $customerRepository)
    {
        $request = $request->request;
        
        if ($request->has('customer_create')) {
            $email = $request->all('customer_create')['email'];
            $customer = $customerRepository->findOneByEmail($email);
            if ($customer){
                return $this->render('admin/customer/new.html.twig', [
                    'display_errors' => true
                ]);
            }
            $id = $customerRepository->saveCustomer($request->all('customer_create'), 'create');
            return $this->redirectToRoute('/admin/dashboard/customer');
        }

        return $this->render('admin/customer/new.html.twig');
    }

    #[Route('/admin/dashboard/customer/edit/{id}', name: 'app_admin_customer_edit', httpMethod: ['GET', 'POST'])]
    public function edit(Request $request, UserRepository $customerRepository, $id)
    {
        $request = $request->request;
        $customerExist = $customerRepository->find($id);

        if(empty($customerExist)){
            throw new \Exception('Customer not found');
        }

        if ($request->has('customer_edit')) {
            $email = $request->all('customer_edit')['email'];
            $customer = $customerRepository->findOneByEmail($email);
            if (!empty($customer) && $email != $customerExist->getEmail()){
                return $this->render('admin/customer/edit.html.twig', [
                    'display_errors' => true
                ]);
            }
            $customerEdit = $request->all('customer_edit');
            $customerEdit['id'] = $customerExist->getId();
            
            $id = $customerRepository->saveCustomer($customerEdit, 'edit');
            return $this->redirectToRoute('/admin/dashboard/customer');
        }

        return $this->render('admin/customer/edit.html.twig', [
            'customer' => $customerExist
        ]);
    }

    #[Route('/admin/dashboard/customer/delete/{id}', name: 'app_admin_customer_delete')]
    public function delete(Request $request, UserRepository $customerRepository, $id){
        $customerExist = $customerRepository->find($id);

        if(empty($customerExist)){
            throw new \Exception('Customer not found');
        }

        $customerRepository->deleteOneById($id);
        return $this->redirectToRoute('/admin/dashboard/customer');
    }

}