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
    public ?string $address_line2 = null;
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

        $this->created_at = new \DateTimeImmutable($data['created_at']);
        $this->updated_at = new \DateTimeImmutable($data['updated_at']);
    }
}