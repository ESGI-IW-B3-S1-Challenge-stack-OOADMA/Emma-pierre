<?php

namespace App\Entity;

class Admin extends User
{
    protected array $roles = ['admin'];
}
