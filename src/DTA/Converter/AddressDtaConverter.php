<?php

namespace App\DTA\Converter;

use App\DTA\AddressDTA;
use App\Entity\Address;

class AddressDtaConverter
{
    public function toAddress(AddressDTA $addressDTA): Address
    {
        $address = new Address();
        $address->setId($addressDTA->id);
        $address->setName($addressDTA->name);
        $address->setAddressLine1($addressDTA->address_line1);
        $address->setAddressLine2($addressDTA->address_line2);
        $address->setCity($addressDTA->city);
        $address->setPostalCode($addressDTA->postal_code);
        $address->setCreatedAt($addressDTA->created_at);
        $address->setUpdatedAt($addressDTA->updated_at);
        return $address;
    }
}