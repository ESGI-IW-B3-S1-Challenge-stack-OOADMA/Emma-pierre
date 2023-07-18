<?php

namespace App\DTA\Converter;

use App\DTA\UserDTA;
use App\Entity\Admin;
use App\Entity\Customer;

class UserDtaConverter
{
    public function toUser(UserDTA $userDTA)
    {
        if (in_array('customer', $userDTA->roles)) {
            $user = new Customer();
        } else {
            $user = new Admin();
        }
        $user->setId($userDTA->id);
        $user->setPhoneNumber($userDTA->phone_number);
        $user->setEmail($userDTA->email);
        $user->setLastname($userDTA->lastname);
        $user->setFirstname($userDTA->firstname);
        $user->setRoles($userDTA->roles);
        $user->setPassword($userDTA->password);
        $user->setCreatedAt($userDTA->created_at);
        $user->setUpdatedAt($userDTA->updated_at);
        return $user;
    }
}