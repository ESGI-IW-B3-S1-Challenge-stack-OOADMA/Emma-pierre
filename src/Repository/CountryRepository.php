<?php

namespace App\Repository;

use App\DTA\Converter\CountryDtaConverter;
use App\DTA\CountryDTA;

class CountryRepository extends AbstractRepository
{
    public function find(int $id){
        $statement = $this->pdo->prepare('SELECT * FROM country WHERE id = ?');
        $statement->execute([$id]);
        $data = $statement->fetch(\PDO::FETCH_ASSOC);
        if ($data === false) {
            return null;
        }

        $countryDta = new CountryDTA($data);
        $countryDtaConverter = new CountryDtaConverter();

        return $countryDtaConverter->toCountry($countryDta);
    }

    public function findOneByCode(string $code){
        $statement = $this->pdo->prepare('SELECT * FROM country WHERE code = ?');
        $statement->execute([$code]);
        $data = $statement->fetch(\PDO::FETCH_ASSOC);
        if ($data === false) {
            return null;
        }
        $countryDta = new CountryDTA($data);
        $countryDtaConverter = new CountryDtaConverter();

        return $countryDtaConverter->toCountry($countryDta);
    }
}