<?php

namespace App\DTA\Converter;

use App\DTA\CountryDTA;
use App\Entity\Country;

class CountryDtaConverter
{
    public function toCountry(CountryDTA $countryDTA): Country
    {
        $country = new Country();
        $country->setId($countryDTA->id);
        $country->setName($countryDTA->name);
        $country->setCode($countryDTA->code);
        $country->setCreatedAt($countryDTA->created_at);
        $country->setUpdatedAt($countryDTA->updated_at);
        return $country;
    }
}