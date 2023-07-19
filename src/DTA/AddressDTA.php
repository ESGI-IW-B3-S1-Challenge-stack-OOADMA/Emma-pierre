<?php

namespace App\DTA;

use App\DTA\Converter\CountryDtaConverter;
use App\Entity\Country;
use DateTimeImmutable;

class AddressDTA
{
    public int $id;
    public string $name;
    public string $address_line1;
    public string $address_line2;
    public string $city;
    public string $postal_code;
    public Country $country;
    public DateTimeImmutable $created_at;
    public DateTimeImmutable $updated_at;

    public function __construct(array $data)
    {
        $this->id = $data['id'];
        $this->name = $data['name'];
        $this->address_line1 = $data['address_line1'];
        $this->address_line2 = $data['address_line2'];
        $this->city = $data['$city'];
        $this->postal_code = $data['postal_code'];

        $country_dta = new CountryDTA([
            'id' => $data['country_id'],
            'name' => $data['country_name'],
            'code' => $data['country_code'],
            'created_at' => $data['country_created_at'],
            'updated_at' => $data['country_updated_at']
        ]);
        $country_dta_converter = new CountryDtaConverter();
        $this->country = $country_dta_converter->toCountry($country_dta);

        $this->created_at = $data['created_at'];
        $this->updated_at = $data['updated_at'];
    }
}