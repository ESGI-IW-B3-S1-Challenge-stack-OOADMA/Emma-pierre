<?php

namespace App\Entity;

class Address
{
    private int $id;
    private string $name;
    private string $address_line1;
    private string $address_line2;
    private string $city;
    private string $postal_code;
    private Country $country;
    private \DateTimeImmutable $created_at;
    private \DateTimeImmutable $updated_at;
}