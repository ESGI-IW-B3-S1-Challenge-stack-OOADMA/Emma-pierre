<?php

namespace App\Entity;

use DateTimeImmutable;

class Address
{
    private int $id;
    private string $name;
    private string $address_line1;
    private string $address_line2;
    private string $city;
    private string $postal_code;
    private Country $country;
    private DateTimeImmutable $created_at;
    private DateTimeImmutable $updated_at;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getAddressLine1(): string
    {
        return $this->address_line1;
    }

    /**
     * @param string $address_line1
     */
    public function setAddressLine1(string $address_line1): void
    {
        $this->address_line1 = $address_line1;
    }

    /**
     * @return string
     */
    public function getAddressLine2(): string
    {
        return $this->address_line2;
    }

    /**
     * @param string $address_line2
     */
    public function setAddressLine2(string $address_line2): void
    {
        $this->address_line2 = $address_line2;
    }

    /**
     * @return string
     */
    public function getCity(): string
    {
        return $this->city;
    }

    /**
     * @param string $city
     */
    public function setCity(string $city): void
    {
        $this->city = $city;
    }

    /**
     * @return string
     */
    public function getPostalCode(): string
    {
        return $this->postal_code;
    }

    /**
     * @param string $postal_code
     */
    public function setPostalCode(string $postal_code): void
    {
        $this->postal_code = $postal_code;
    }

    /**
     * @return Country
     */
    public function getCountry(): Country
    {
        return $this->country;
    }

    /**
     * @param Country $country
     */
    public function setCountry(Country $country): void
    {
        $this->country = $country;
    }

    /**
     * @return DateTimeImmutable
     */
    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->created_at;
    }

    /**
     * @param DateTimeImmutable $created_at
     */
    public function setCreatedAt(DateTimeImmutable $created_at): void
    {
        $this->created_at = $created_at;
    }

    /**
     * @return DateTimeImmutable
     */
    public function getUpdatedAt(): DateTimeImmutable
    {
        return $this->updated_at;
    }

    /**
     * @param DateTimeImmutable $updated_at
     */
    public function setUpdatedAt(DateTimeImmutable $updated_at): void
    {
        $this->updated_at = $updated_at;
    }
}