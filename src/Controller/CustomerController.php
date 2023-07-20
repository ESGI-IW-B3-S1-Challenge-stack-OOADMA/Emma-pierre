<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Repository\UserRepository;
use App\Routing\Attribute\Route;
use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;
use PDO;

class CustomerController extends AbstractController
{
    #[Route('/customer/new', name: 'app_customer_new')]
    public function new(UserRepository $customerRepository): string
    {


        // Si le nom passe les contrôles de validation, alors création de l'utilisateur
        $customer = new Customer();
        $customer->setLastname('Rome');
        $customer->setFirstname('Mathis');
        $customer->setEmail('mathis.rome@icloud.com');
        $customer->setPhoneNumber('0658418678');
        $password = password_hash('password', PASSWORD_DEFAULT);
        $customer->setPassword($password);
        $customerRepository->add($customer);

        return $this->render('user/new.html.twig', [
                'customer' => $customer
            ]
        );
    }

    #[Route('/customer/page/{idPage}', name: 'app_customer_list')]
    public function list(\PDO $pdo, $idPage = 1): string
    {
        $statement = $pdo->query('SELECT * FROM user');
        $statement->execute();

        $customers = $statement->fetchAll(PDO::FETCH_CLASS, Customer::class);

        $adapter = new ArrayAdapter($customers);
        $pagerfanta = new PagerFanta($adapter);

        $pagerfanta->setMaxPerPage(2);

        $pagerfanta->setCurrentPage($idPage);

        $url = $_SERVER['REQUEST_URI'];

        $previousPage = $idPage - 1 > 0 ? $idPage - 1 : 1;
        $explodeUrl = explode('/', $url);
        $explodeUrl[count($explodeUrl) - 1] = $previousPage;

        $urlPreviousPage = implode('/', $explodeUrl);

        $nextPage = $idPage + 1 < $pagerfanta->getNbPages() ? $idPage + 1 : $pagerfanta->getNbPages();
        $explodeUrl[count($explodeUrl) - 1] = $nextPage;
        $urlNextPage = implode('/', $explodeUrl);

        return $this->render('user/index.html.twig', [
                'clients' => $pagerfanta,
                'urlPreviousPage' => $urlPreviousPage,
                'urlNextPage' => $urlNextPage,
            ]
        );
    }

    #[Route('/user/{idUser}', name: 'user_show')]
    public function show($idUser): string
    {
        $client = $this->em->getRepository(Customer::class)->find(1);

        return $this->render(
            'user/show.html.twig',
            ['client' => $client]
        );
    }

    #[Route('/user/name/{name}', name: 'user_show')]
    public function showByName($name): string
    {
        $clients = $this->em->getRepository(Customer::class)->findByNameInOrder($name);

        return $this->render(
            'user/show.html.twig',
            ['clients' => $clients]
        );
    }
}
