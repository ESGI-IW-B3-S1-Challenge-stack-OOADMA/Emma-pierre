<?php

namespace App\Repository;


use App\DTA\AddressDTA;
use App\DTA\Converter\AddressDtaConverter;
use App\Entity\Address;

class AddressRepository extends AbstractRepository
{
    public function find(int $id)
    {
        $statement = $this->pdo->query('SELECT * FROM address WHERE id = ?');
        $statement->execute([$id]);
        $data = $statement->fetch(\PDO::FETCH_ASSOC);
        if ($data === false) {
            return null;
        }

        $addressDta = new AddressDTA([$data]);
        $addressDtaConverter = new AddressDtaConverter();

        $address = $addressDtaConverter->toAddress($addressDta);
        $countryRepository = new CountryRepository($this->pdo);
        $address->setCountry($countryRepository->find($data['country_id']));

        return $address;
    }

    public function add(Address $address)
    {
        $statement = $this->pdo->prepare('INSERT INTO `address` (name, address_line1, address_line2, city, postal_code, country_id, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
        $statement->execute([
            $address->getName(),
            $address->getAddressLine1(),
            $address->getAddressLine2(),
            $address->getCity(),
            $address->getPostalCode(),
            $address->getCountry()?->getId(),
            $address->getCreatedAt()->format('Y-m-d H:i:s'),
            $address->getUpdatedAt()->format('Y-m-d H:i:s'),
        ]);

        return $this->pdo->lastInsertId();
    }
}