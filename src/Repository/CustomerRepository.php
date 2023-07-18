<?php

namespace App\Repository;

use App\Entity\Customer;

class CustomerRepository extends AbstractRepository
{
    public function add(Customer $customer): void
    {
        $statement = $this->pdo->prepare('INSERT INTO `user` (`address_id`, `lastname`, `firstname`, `email`, `phone_number`, `password`, `roles`) VALUES (?, ?, ?, ?, ?, ?, ?)');
        $statement->execute([null, $customer->getLastname(), $customer->getFirstname(), $customer->getEmail(), $customer->getPhoneNumber(), $customer->getPassword(), json_encode($customer->getRoles())]);
    }
}
