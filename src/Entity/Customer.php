<?php

namespace App\Entity;

class Customer extends User
{
    protected array $roles = ['customer'];
}
